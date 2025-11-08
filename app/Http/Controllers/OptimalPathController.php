<?php

namespace App\Http\Controllers;

use App\Models\OptimalPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OptimalPathController extends Controller
{
    /**
     * Display the optimal path page
     */
    public function index()
    {
        return view('optimal-path');
    }

    /**
     * Save the optimized route to database
     */
    public function saveRoute(Request $request)
    {
        try {
            Log::info('Save Route Request:', $request->all());

            $validated = $request->validate([
                'optimalPath' => 'required|string',
                'totalWeight' => 'required|string',
                'optimize' => 'required|in:duration,distance',
                'locations' => 'required|array',
            ]);

            Log::info('Validated data:', $validated);

            $route = OptimalPath::create([
                'user_id' => auth()->id(),
                'optimal_path' => $validated['optimalPath'],
                'total_weight' => $validated['totalWeight'],
                'optimize_type' => $validated['optimize'],
                'locations' => json_encode($validated['locations']),
            ]);

            Log::info('Route created:', $route->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Route saved successfully',
                'route' => $route
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Save Route Error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save route: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all saved routes for the authenticated user
     */
    public function getUserRoutes()
    {
        try {
            $routes = OptimalPath::where('user_id', auth()->id())
                ->latest()
                ->get();

            return response()->json($routes);
        } catch (\Exception $e) {
            Log::error('Get User Routes Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch routes'
            ], 500);
        }
    }

    /**
     * Get a specific saved route
     */
    public function show($id)
    {
        try {
            $route = OptimalPath::where('user_id', auth()->id())
                ->findOrFail($id);

            return response()->json($route);
        } catch (\Exception $e) {
            Log::error('Show Route Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Route not found'
            ], 404);
        }
    }

    /**
     * Delete a saved route
     */
    public function delete($id)
    {
        try {
            $route = OptimalPath::where('user_id', auth()->id())
                ->findOrFail($id);

            $route->delete();

            return response()->json([
                'success' => true,
                'message' => 'Route deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Delete Route Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete route'
            ], 500);
        }
    }
}