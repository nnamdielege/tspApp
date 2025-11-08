<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\DriverReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateDailyReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get all drivers (assuming users with driver role)
        $drivers = User::all();

        foreach ($drivers as $driver) {
            $today = now()->toDateString();

            // Check if start of day reminder already exists
            $startReminder = DriverReminder::where('user_id', $driver->id)
                ->where('reminder_date', $today)
                ->where('reminder_type', 'start_of_day')
                ->first();

            if (!$startReminder) {
                DriverReminder::create([
                    'user_id' => $driver->id,
                    'reminder_type' => 'start_of_day',
                    'reminder_date' => $today,
                    'is_completed' => false,
                    'notes' => 'Complete your daily vehicle checklist',
                ]);
            }

            // Check if end of day reminder already exists
            $endReminder = DriverReminder::where('user_id', $driver->id)
                ->where('reminder_date', $today)
                ->where('reminder_type', 'end_of_day')
                ->first();

            if (!$endReminder) {
                DriverReminder::create([
                    'user_id' => $driver->id,
                    'reminder_type' => 'end_of_day',
                    'reminder_date' => $today,
                    'is_completed' => false,
                    'notes' => 'Log your end of day odometer reading',
                ]);
            }
        }
    }
}