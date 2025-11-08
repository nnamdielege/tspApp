<?php

namespace App\Http\Controllers;

use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserSettingsController extends Controller
{
    /**
     * Display user settings page
     */
    public function index()
    {
        $settings = auth()->user()->getOrCreateSettings();
        return view('settings.index', compact('settings'));
    }

    /**
     * Get user settings as JSON
     */
    public function getSettings()
    {
        try {
            $settings = auth()->user()->getOrCreateSettings();

            return response()->json([
                'success' => true,
                'settings' => $settings
            ]);
        } catch (\Exception $e) {
            Log::error('Get Settings Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch settings'
            ], 500);
        }
    }

    /**
     * Update notification settings
     */
    public function updateNotifications(Request $request)
    {
        try {
            $validated = $request->validate([
                'email_notifications' => 'boolean',
                'end_of_day_reminder' => 'boolean',
                'missing_log_notification' => 'boolean',
                'route_completion_notification' => 'boolean',
                'daily_summary_email' => 'boolean',
                'preferred_reminder_time' => 'date_format:H:i',
                'reminder_time_hours_before' => 'integer|min:1|max:24',
            ]);

            $settings = auth()->user()->getOrCreateSettings();
            $settings->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Notification settings updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Update Notifications Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update display preferences
     */
    public function updateDisplay(Request $request)
    {
        try {
            $validated = $request->validate([
                'theme' => 'required|in:light,dark,system',
                'language' => 'required|string|max:10',
                'timezone' => 'required|timezone',
                '24_hour_format' => 'boolean',
            ]);

            $settings = auth()->user()->getOrCreateSettings();
            $settings->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Display preferences updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Update Display Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update preferences: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update privacy settings
     */
    public function updatePrivacy(Request $request)
    {
        try {
            $validated = $request->validate([
                'show_location_history' => 'boolean',
                'show_distance_traveled' => 'boolean',
                'allow_admin_view_logs' => 'boolean',
            ]);

            $settings = auth()->user()->getOrCreateSettings();
            $settings->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Privacy settings updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Update Privacy Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update privacy settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update preferences
     */
    public function updatePreferences(Request $request)
    {
        try {
            $validated = $request->validate([
                'default_optimization' => 'required|in:duration,distance',
                'auto_start_route' => 'boolean',
                'auto_complete_route' => 'boolean',
            ]);

            $settings = auth()->user()->getOrCreateSettings();
            $settings->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Preferences updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Update Preferences Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update preferences: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update vehicle information
     */
    public function updateVehicle(Request $request)
    {
        try {
            $validated = $request->validate([
                'vehicle_number' => 'nullable|string|max:50',
                'vehicle_model' => 'nullable|string|max:100',
                'vehicle_notes' => 'nullable|string|max:1000',
            ]);

            $settings = auth()->user()->getOrCreateSettings();
            $settings->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle information updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Update Vehicle Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update vehicle information: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset all settings to defaults
     */
    public function resetSettings(Request $request)
    {
        try {
            $settings = auth()->user()->getOrCreateSettings();
            $settings->update([
                'email_notifications' => true,
                'end_of_day_reminder' => true,
                'missing_log_notification' => true,
                'route_completion_notification' => true,
                'theme' => 'system',
                'language' => 'en',
                'timezone' => 'UTC',
                '24_hour_format' => true,
                'show_location_history' => true,
                'show_distance_traveled' => true,
                'allow_admin_view_logs' => true,
                'default_optimization' => 'distance',
                'auto_start_route' => false,
                'auto_complete_route' => false,
                'reminder_time_hours_before' => 2,
                'daily_summary_email' => false,
                'preferred_reminder_time' => '08:00',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'All settings reset to defaults'
            ]);
        } catch (\Exception $e) {
            Log::error('Reset Settings Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available timezones
     */
    public function getTimezones()
    {
        $timezones = timezone_identifiers_list();
        return response()->json($timezones);
    }
}