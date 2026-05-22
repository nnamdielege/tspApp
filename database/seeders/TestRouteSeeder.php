<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\DeliveryRoute;
use App\Models\RouteStop;
use Illuminate\Support\Facades\Hash;

class TestRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        $driver = User::firstOrCreate(
            ['email' => 'driver@test.com'],
            [
                'name' => 'Test Driver',
                'password' => Hash::make('password'),
            ]
        );

        $route = DeliveryRoute::create([
            'driver_id' => $driver->id,
            'route_date' => today(),
            'status' => 'pending',
        ]);

        $stops = [
            ['Bean In Cafe', 'Canungra QLD', -28.0189, 153.1640],
            ['Brisbane City Customer', 'Brisbane City QLD', -27.4698, 153.0251],
            ['Mount Warren Park Customer', 'Mount Warren Park QLD', -27.7292, 153.2135],
        ];

        foreach ($stops as $index => $stop) {
            RouteStop::create([
                'delivery_route_id' => $route->id,
                'customer_name' => $stop[0],
                'address' => $stop[1],
                'lat' => $stop[2],
                'lng' => $stop[3],
                'sequence_order' => $index + 1,
            ]);
        }
    }
}
