<?php

namespace Tests\Feature\Api;

use App\Models\OptimalPath;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DriverRouteStopResolutionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A route with no ordered_stops - only the original locations, as saved
     * before optimisation ever produced ordered_stops for it.
     */
    private function makeRouteWithLocationsOnly(User $driver, array $locations): OptimalPath
    {
        return OptimalPath::create([
            'user_id' => $driver->id,
            'employee_id' => $driver->id,
            'optimal_path' => '1. Stop A<br>2. Stop B',
            'total_weight' => '10 kilometers.',
            'optimize_type' => 'distance',
            'status' => 'in_progress',
            'locations' => $locations,
            'ordered_stops' => null,
        ]);
    }

    public function test_today_route_falls_back_to_locations_when_ordered_stops_is_empty(): void
    {
        $driver = User::factory()->create();
        Sanctum::actingAs($driver);

        $locations = [
            ['address' => 'Stop A', 'lat' => -27.1, 'lng' => 153.1],
            ['address' => 'Stop B', 'lat' => -27.2, 'lng' => 153.2],
        ];
        $this->makeRouteWithLocationsOnly($driver, $locations);

        $response = $this->getJson('/api/driver/today-route');

        $response->assertOk()->assertJson([
            'stops' => $locations,
        ]);
    }

    public function test_updating_first_stop_materialises_ordered_stops_without_touching_locations(): void
    {
        $driver = User::factory()->create();
        Sanctum::actingAs($driver);

        $locations = [
            ['address' => 'Stop A', 'lat' => -27.1, 'lng' => 153.1],
            ['address' => 'Stop B', 'lat' => -27.2, 'lng' => 153.2],
        ];
        $route = $this->makeRouteWithLocationsOnly($driver, $locations);

        $response = $this->postJson("/api/driver/route/{$route->id}/stop/0/status", [
            'status' => 'arrived',
        ]);

        $response->assertOk()->assertJson(['message' => 'Stop status updated']);

        $route->refresh();

        $this->assertNotEmpty($route->ordered_stops);
        $this->assertSame('Stop A', $route->ordered_stops[0]['address']);
        $this->assertSame('arrived', $route->ordered_stops[0]['status']);
        $this->assertArrayHasKey('arrived_at', $route->ordered_stops[0]);

        // locations must be left exactly as it was.
        $this->assertSame($locations, $route->locations);
    }

    public function test_second_update_reads_the_materialised_ordered_stops(): void
    {
        $driver = User::factory()->create();
        Sanctum::actingAs($driver);

        $locations = [
            ['address' => 'Stop A', 'lat' => -27.1, 'lng' => 153.1],
            ['address' => 'Stop B', 'lat' => -27.2, 'lng' => 153.2],
        ];
        $route = $this->makeRouteWithLocationsOnly($driver, $locations);

        $this->postJson("/api/driver/route/{$route->id}/stop/0/status", [
            'status' => 'delivered',
        ])->assertOk();

        $this->postJson("/api/driver/route/{$route->id}/stop/1/status", [
            'status' => 'arrived',
        ])->assertOk();

        $route->refresh();

        $this->assertSame('delivered', $route->ordered_stops[0]['status']);
        $this->assertArrayHasKey('delivered_at', $route->ordered_stops[0]);
        $this->assertSame('arrived', $route->ordered_stops[1]['status']);
        $this->assertArrayHasKey('arrived_at', $route->ordered_stops[1]);

        $this->assertSame($locations, $route->locations);
    }

    public function test_out_of_range_index_still_returns_not_found(): void
    {
        $driver = User::factory()->create();
        Sanctum::actingAs($driver);

        $locations = [
            ['address' => 'Stop A', 'lat' => -27.1, 'lng' => 153.1],
        ];
        $route = $this->makeRouteWithLocationsOnly($driver, $locations);

        $response = $this->postJson("/api/driver/route/{$route->id}/stop/5/status", [
            'status' => 'arrived',
        ]);

        $response->assertNotFound()->assertJson(['message' => 'Stop not found']);

        // Nothing should have been materialised on a failed lookup.
        $route->refresh();
        $this->assertNull($route->ordered_stops);
    }
}
