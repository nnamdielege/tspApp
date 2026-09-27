<?php

namespace App\Http\Controllers;

use App\Models\DriverLocation;

class DriverLocationController extends Controller
{
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