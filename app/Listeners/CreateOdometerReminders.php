<?php

namespace App\Listeners;

use App\Events\RouteOptimized;
use App\Models\DriverReminder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateOdometerReminders
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RouteOptimized $event): void
    {
        // Get the number of locations in the route
        $locationsCount = count(json_decode($event->route->locations, true));

        // Create an odometer reminder for each location (except the first)
        for ($i = 1; $i < $locationsCount; $i++) {
            DriverReminder::create([
                'user_id' => $event->route->user_id,
                'optimal_path_id' => $event->route->id,
                'reminder_type' => 'odometer_at_location',
                'reminder_date' => now()->toDateString(),
                'is_completed' => false,
                'notes' => 'Log odometer reading at Location ' . ($i + 1),
            ]);
        }
    }
}