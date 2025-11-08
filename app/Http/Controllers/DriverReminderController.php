<?php

namespace App\Http\Controllers;

use App\Models\DriverReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DriverReminderController extends Controller
{
    /**
     * Get today's reminders for the authenticated driver
     */
    public function getTodayReminders()
    {
        try {
            $reminders = DriverReminder::where('user_id', auth()->id())
                ->where('reminder_date', now()->toDateString())
                ->orderBy('reminder_type')
                ->get()
                ->map(function ($reminder) {
                    return [
                        'id' => $reminder->id,
                        'type' => $reminder->reminder_type,
                        'is_completed' => $reminder->is_completed,
                        'notes' => $reminder->notes,
                        'completed_at' => $reminder->completed_at,
                        'type_label' => $this->getReminderLabel($reminder->reminder_type),
                        'icon' => $this->getReminderIcon($reminder->reminder_type),
                    ];
                });

            return response()->json($reminders);
        } catch (\Exception $e) {
            Log::error('Get Reminders Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch reminders'
            ], 500);
        }
    }

    /**
     * Get all pending reminders
     */
    public function getPendingReminders()
    {
        try {
            $reminders = DriverReminder::where('user_id', auth()->id())
                ->where('is_completed', false)
                ->where('reminder_date', '<=', now()->toDateString())
                ->orderBy('reminder_date')
                ->orderBy('reminder_type')
                ->get()
                ->map(function ($reminder) {
                    return [
                        'id' => $reminder->id,
                        'type' => $reminder->reminder_type,
                        'date' => $reminder->reminder_date->format('M d, Y'),
                        'notes' => $reminder->notes,
                        'type_label' => $this->getReminderLabel($reminder->reminder_type),
                        'icon' => $this->getReminderIcon($reminder->reminder_type),
                    ];
                });

            return response()->json($reminders);
        } catch (\Exception $e) {
            Log::error('Get Pending Reminders Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch pending reminders'
            ], 500);
        }
    }

    /**
     * Mark a reminder as completed
     */
    public function markAsCompleted(Request $request, $id)
    {
        try {
            $reminder = DriverReminder::where('user_id', auth()->id())
                ->findOrFail($id);

            $reminder->markAsCompleted();

            return response()->json([
                'success' => true,
                'message' => 'Reminder marked as completed'
            ]);
        } catch (\Exception $e) {
            Log::error('Mark Reminder Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update reminder'
            ], 500);
        }
    }

    public function markEndOfDayComplete(Request $request)
    {
        try {
            $userId = auth()->id();
            $today = now()->toDateString();

            // Mark end of day reminder as completed
            $reminder = DriverReminder::where('user_id', $userId)
                ->where('reminder_date', $today)
                ->where('reminder_type', 'end_of_day')
                ->firstOrFail();

            $reminder->markAsCompleted();

            // Create tomorrow's reminders if they don't exist
            $tomorrow = now()->addDay()->toDateString();

            $startReminderExists = DriverReminder::where('user_id', $userId)
                ->where('reminder_date', $tomorrow)
                ->where('reminder_type', 'start_of_day')
                ->exists();

            if (!$startReminderExists) {
                DriverReminder::create([
                    'user_id' => $userId,
                    'reminder_type' => 'start_of_day',
                    'reminder_date' => $tomorrow,
                    'is_completed' => false,
                    'notes' => 'Complete your daily vehicle checklist',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'End of day reminder completed. See you tomorrow!'
            ]);
        } catch (\Exception $e) {
            Log::error('Mark End of Day Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete end of day reminder'
            ], 500);
        }
    }

    /**
     * Get reminder statistics
     */
    public function getStatistics()
    {
        try {
            $userId = auth()->id();
            $today = now()->toDateString();

            $stats = [
                'total_today' => DriverReminder::where('user_id', $userId)
                    ->where('reminder_date', $today)
                    ->count(),
                'completed_today' => DriverReminder::where('user_id', $userId)
                    ->where('reminder_date', $today)
                    ->where('is_completed', true)
                    ->count(),
                'pending_today' => DriverReminder::where('user_id', $userId)
                    ->where('reminder_date', $today)
                    ->where('is_completed', false)
                    ->count(),
                'pending_overdue' => DriverReminder::where('user_id', $userId)
                    ->where('reminder_date', '<', $today)
                    ->where('is_completed', false)
                    ->count(),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('Get Statistics Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics'
            ], 500);
        }
    }

    /**
     * Get reminder label
     */
    private function getReminderLabel($type)
    {
        return match ($type) {
            'start_of_day' => 'Start of Day Checklist',
            'end_of_day' => 'End of Day Odometer',
            'odometer_at_location' => 'Odometer at Location',
            default => 'Reminder',
        };
    }

    /**
     * Get reminder icon
     */
    private function getReminderIcon($type)
    {
        return match ($type) {
            'start_of_day' => '🌅',
            'end_of_day' => '🌙',
            'odometer_at_location' => '📍',
            default => '📋',
        };
    }
}