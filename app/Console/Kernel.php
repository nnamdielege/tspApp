<?php

namespace App\Console;

use App\Jobs\CreateDailyReminders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Create daily reminders at 6:00 AM
        $schedule->job(new CreateDailyReminders)
            ->dailyAt('06:00')
            ->timezone('Australia/Brisbane'); // Change to your timezone

        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}