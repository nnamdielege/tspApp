<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DriverLocationTest extends TestCase
{
    use RefreshDatabase;

    public function test_consecutive_location_updates_leave_exactly_one_row_per_driver(): void
    {
        $driver = User::factory()->create();
        Sanctum::actingAs($driver);

        $first = $this->postJson('/api/driver/location', [
            'lat' => -27.4698,
            'lng' => 153.0251,
        ]);
        $first->assertOk()->assertJson(['message' => 'Location saved']);

        $second = $this->postJson('/api/driver/location', [
            'lat' => -27.5000,
            'lng' => 153.1000,
        ]);
        $second->assertOk()->assertJson(['message' => 'Location saved']);

        $this->assertDatabaseCount('driver_locations', 1);
        $this->assertDatabaseHas('driver_locations', [
            'driver_id' => $driver->id,
            'lat' => -27.5000,
            'lng' => 153.1000,
        ]);
    }
}
