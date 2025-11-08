<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');

            // Notification Settings
            $table->boolean('email_notifications')->default(true);
            $table->boolean('end_of_day_reminder')->default(true);
            $table->boolean('missing_log_notification')->default(true);
            $table->boolean('route_completion_notification')->default(true);

            // Display Preferences
            $table->enum('theme', ['light', 'dark', 'system'])->default('system');
            $table->string('language')->default('en');
            $table->string('timezone')->default('UTC');
            $table->boolean('24_hour_format')->default(true);

            // Privacy Settings
            $table->boolean('show_location_history')->default(true);
            $table->boolean('show_distance_traveled')->default(true);
            $table->boolean('allow_admin_view_logs')->default(true);

            // Default Preferences
            $table->enum('default_optimization', ['duration', 'distance'])->default('distance');
            $table->boolean('auto_start_route')->default(false);
            $table->boolean('auto_complete_route')->default(false);

            // Vehicle Preferences
            $table->string('vehicle_number')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->text('vehicle_notes')->nullable();

            // Reminders & Alerts
            $table->integer('reminder_time_hours_before')->default(2);
            $table->boolean('daily_summary_email')->default(false);
            $table->string('preferred_reminder_time')->default('08:00');

            $table->timestamps();
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};