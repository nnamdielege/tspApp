<?php

namespace App\Http\Controllers;

use App\Models\DriverLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverLocationController extends Controller
{
    /**
     * Update driver's current location
     */
    public function updateLocation(Request $request)
    {
        $validated = $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $location = DriverLocation::updateOrCreate(
            ['driver_id' => auth()->id()],
            [
                'lat' => $validated['lat'],
                'lng' => $validated['lng'],
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
            'location' => $location,
        ]);
    }

    /**
     * Get all live driver locations
     */
    public function liveLocations()
    {
        $latestLocationIds = DriverLocation::selectRaw('MAX(id) as id')
            ->groupBy('driver_id')
            ->pluck('id');

        return DriverLocation::with('driver')
            ->whereIn('id', $latestLocationIds)
            ->get()
            ->map(function ($location) {
                return [
                    'driver_id' => $location->driver_id,
                    'driver_name' => $location->driver?->name ?? 'Unknown Driver',
                    'lat' => (float) $location->lat,
                    'lng' => (float) $location->lng,
                    'updated_at' => optional($location->updated_at)->toDateTimeString(),
                ];
            })
            ->values();
    }
}