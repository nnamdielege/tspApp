<?php

namespace App\Http\Controllers;

use App\CustomClasses\TSPSolver;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ShortestPathController extends Controller
{
    public function deriveTSP(Request $request)
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'locations' => 'required|array',
                'locations.*' => 'required|string',
                'optimize' => 'required|in:duration,distance'
            ]);

            // If validation fails, return an error response
            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()->first() ?: 'Invalid input data.'
                ], 422); // Unprocessable Entity
            }

            // If validation passes, retrieve the data
            $locations = $request->input('locations');
            $optimize = $request->input('optimize');

            // Clear the cache if request data is different from the previous request
            $this->clearCacheIfRequestChanged($locations, $optimize);

            // Set the points array based on the provided locations
            $points = $this->calculateDistances($locations, $optimize);

            // Find the optimal route using the TSPSolver
            $tour = TSPSolver::nearestNeighbour($points);

            // Remove the starting point (to avoid duplication)
            if (!empty($tour)) {
                array_pop($tour);
            }

            // Format the tour with location names
            $formattedTour = $this->formatTourWithNames($tour, $locations);

            // Calculate the total weight of the path
            $totalWeight = $this->calculateTotalWeight($tour, $locations, $optimize);

            // Return the formatted tour as JSON
            return response()->json([
                'optimalPath' => $formattedTour,
                'totalWeight' => $totalWeight,
                'locations' => $locations,
            ]);
        } catch (Exception $e) {
            // Log the exception for debugging
            Log::error('Error in deriveTSP:', ['error' => $e->getMessage()]);

            // Return error response on exception
            return response()->json([
                'error' => 'An error occurred: ' . $e->getMessage()
            ], 500); // Internal Server Error status code
        }
    }

    private function clearCacheIfRequestChanged(array $locations, string $optimize)
    {
        $cacheKey = 'tsp_request_data';
        $previousRequestData = Cache::get($cacheKey);

        // Generate a unique identifier for the current request data
        $currentRequestDataIdentifier = md5(serialize([$locations, $optimize]));

        if ($previousRequestData !== $currentRequestDataIdentifier) {
            // Clear the cache if the current request data is different from the previous request
            Cache::flush();
            // Store the current request data identifier in the cache
            Cache::put($cacheKey, $currentRequestDataIdentifier);
        }
    }

    private function calculateTotalWeight(array $tour, array $locations, string $optimize): int|string
    {
        try {
            $totalWeight = 0;

            // Calculate the total weight by summing up distances or durations
            for ($i = 0; $i < count($tour) - 1; $i++) {
                $origin = $locations[$tour[$i]];
                $destination = $locations[$tour[$i + 1]];
                $weight = $this->getDistance($origin, $destination, $optimize);

                // Add the weight to the total
                $totalWeight += $weight;
            }

            if ($optimize === 'duration') {
                // Duration optimization
                $seconds = intval($totalWeight);
                // Calculate hours, minutes, and seconds
                $hours = floor($seconds / 3600);
                $minutes = floor(($seconds % 3600) / 60);
                $seconds = $seconds % 60;

                // Format the time
                $totalWeight = "$hours Hours, $minutes Minutes, $seconds Seconds";
            }

            if ($optimize === 'distance') {
                // Distance optimization
                $meters = intval($totalWeight);
                // Convert meters to kilometers
                $kilometers = $meters / 1000;

                // Format the distance
                $totalWeight = "$kilometers kilometers.";
            }

            return $totalWeight;
        } catch (Exception $e) {
            // Throw exception on error
            throw new Exception('Error calculating total weight: ' . $e->getMessage());
        }
    }


    private function calculateDistances(array $locations, string $optimize): array
    {
        // Initialize an empty array to store distances
        $points = [];

        // Get distances between each pair of locations
        foreach ($locations as $origin) {
            // Initialize inner array for distances from this origin
            $distancesFromOrigin = [];

            foreach ($locations as $destination) {
                if ($origin === $destination) {
                    // Distance from a point to itself is 0
                    $distance = 0;
                } else {
                    // Calculate the distance or duration between the origin and destination
                    $distance = $this->getDistance($origin, $destination, $optimize);
                }
                // Store the distance in the array
                $distancesFromOrigin[] = $distance;
            }

            // Push the inner array into the main array
            $points[] = $distancesFromOrigin;
        }

        // Log the $points array
        Log::info('Distances between locations:', $points);

        return $points;
    }

    private function getDistance(string $origin, string $destination, string $optimize): int|string
    {
        // Get the distance matrix data from the Google Distance Matrix API
        $response = $this->getDistanceMatrix($origin, $destination);

        // Extract the distance or duration based on the optimization parameter
        $valueKey = ($optimize === 'distance') ? 'distance' : 'duration';

        $weight = $response['rows'][0]['elements'][0][$valueKey]['value'];

        return $weight;
    }


    private function getDistanceMatrix(string $origin, string $destination): array
    {
        // Your Google API key
        $apiKey = config('services.google.api_key');

        // Generate a unique cache key based on origin and destination
        $cacheKey = 'distance_' . md5($origin . '_' . $destination);

        // Check if the data is cached
        if (cache()->has($cacheKey)) {
            // Retrieve data from cache
            return cache()->get($cacheKey);
        }

        // Make API request to Google Distance Matrix API
        $client = new Client();
        $response = $client->request('GET', 'https://maps.googleapis.com/maps/api/distancematrix/json', [
            'query' => [
                'origins' => $origin,
                'destinations' => $destination,
                'key' => $apiKey,
            ],
        ]);

        // Process response
        $responseData = json_decode($response->getBody(), true);

        // Log the responseData
        Log::info('Distance matrix API response:', $responseData);

        // Cache the data for future use
        cache()->put($cacheKey, $responseData, now()->addSeconds(5)); // Adjust the cache duration as needed

        return $responseData;
    }

    // private function formatTourWithNames(array $tour, array $pointNames): string
    // {
    //     // Map tour indices to point names
    //     $namedTour = array_map(function ($index) use ($pointNames) {
    //         return $pointNames[$index];
    //     }, $tour);

    //     // Format the tour for output
    //     $formattedTour = implode(" ➔ ", $namedTour);

    //     return $formattedTour;
    // }
    private function formatTourWithNames(array $tour, array $pointNames): string
    {
        // Initialize an empty string to store formatted tour
        $formattedTour = '';

        // Initialize a counter for numbering the locations
        $counter = 1;

        // Iterate through the tour indices
        foreach ($tour as $index) {
            // Append the numbered point name to the formatted tour string
            $formattedTour .= $counter . ". " . $pointNames[$index] . "<br>";
            // Increment the counter
            $counter++;
        }

        return $formattedTour;
    }
}