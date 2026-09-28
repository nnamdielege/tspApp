<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DriverLocation;
use App\Models\OptimalPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverRouteController extends Controller
{
    public function todayRoute(Request $request)
    {
        $route = OptimalPath::where('employee_id', $request->user()->id)
            ->whereIn('status', ['planned', 'in_progress'])
            ->orderByDesc('updated_at')
            ->first();

        if (!$route) {
            return response()->json([
                'message' => 'No route assigned to this driver.',
                'driver_id' => $request->user()->id,
            ], 404);
        }

        return response()->json([
            'id' => $route->id,
            'route_date' => optional($route->created_at)->toDateString(),
            'status' => $route->status,
            'total_weight' => $route->total_weight,
            'optimize_type' => $route->optimize_type,
            'optimal_path' => $route->optimal_path,
            'stops' => $route->resolvedStops(),
            'updated_at' => $route->updated_at,
        ]);
    }

    public function storeLocation(Request $request)
    {
        $data = $request->validate([
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
        ]);

        DriverLocation::updateOrCreate(
            ['driver_id' => $request->user()->id],
            [
                'lat' => $data['lat'],
                'lng' => $data['lng'],
            ]
        );

        return response()->json([
            'message' => 'Location saved',
        ]);
    }

    public function startRoute(Request $request, OptimalPath $route)
    {
        if ($route->employee_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorised'], 403);
        }

        $route->markAsStarted();

        return response()->json([
            'message' => 'Route started',
            'route' => $route,
        ]);
    }

    public function completeRoute(Request $request, OptimalPath $route)
    {
        if ($route->employee_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorised'], 403);
        }

        $route->markAsCompleted();

        return response()->json([
            'message' => 'Route completed',
            'route' => $route,
        ]);
    }

    public function updateStopStatus(Request $request, OptimalPath $route, $index)
    {
        if ($route->employee_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorised'], 403);
        }

        $data = $request->validate([
            'status' => ['required', 'in:pending,arrived,delivered,failed'],
        ]);

        $stops = DB::transaction(function () use ($route, $index, $data) {
            $locked = OptimalPath::whereKey($route->getKey())->lockForUpdate()->firstOrFail();

            // Materialise ordered_stops from the original locations the first
            // time a stop is updated, so later reads/writes always work off
            // ordered_stops. locations itself is never modified.
            $stops = !empty($locked->ordered_stops) ? $locked->ordered_stops : $locked->resolvedStops();

            if (!isset($stops[$index])) {
                return null;
            }

            $stops[$index]['status'] = $data['status'];

            if ($data['status'] === 'arrived') {
                $stops[$index]['arrived_at'] = now()->toDateTimeString();
            }

            if ($data['status'] === 'delivered') {
                $stops[$index]['delivered_at'] = now()->toDateTimeString();
            }

            if ($data['status'] === 'failed') {
                $stops[$index]['failed_at'] = now()->toDateTimeString();
            }

            $locked->update([
                'ordered_stops' => $stops,
            ]);

            return $stops;
        });

        if ($stops === null) {
            return response()->json(['message' => 'Stop not found'], 404);
        }

        return response()->json([
            'message' => 'Stop status updated',
            'stops' => $stops,
        ]);
    }
}
