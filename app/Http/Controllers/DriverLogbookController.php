<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DriverChecklist;
use App\Models\OdometerReading;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class DriverLogbookController extends Controller
{
    /**
     * Display the driver logbook page
     */
    public function index()
    {
        return view('driver-logbook');
    }

    /**
     * Save driver checklist
     */
    public function saveChecklist(Request $request)
    {
        try {
            $validated = $request->validate([
                'check_type' => 'required|in:start_of_day,end_of_day',
                'odometer_reading' => 'required|integer|min:0',
                'checklist_items' => 'required|array',
                'notes' => 'nullable|string',
            ]);

            DriverChecklist::create([
                'user_id' => auth()->id(),
                'check_type' => $validated['check_type'],
                'odometer_reading' => $validated['odometer_reading'],
                'checklist_items' => $validated['checklist_items'],
                'notes' => $validated['notes'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Checklist saved successfully'
            ], 201);
        } catch (ValidationException $e) {
            Log::error('Validation Error:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Save Checklist Error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save checklist: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save odometer reading
     */
    public function saveOdometer(Request $request)
    {
        try {
            $validated = $request->validate([
                'date' => 'required|date',
                'starting_odometer' => 'required|integer|min:0',
                'ending_odometer' => 'required|integer|min:0',
                'log_type' => 'required|in:travel,maintenance',
                'purpose' => 'nullable|string|max:255',
                'maintenance_cost' => 'nullable|numeric|min:0',
                'description' => 'nullable|string',
            ]);

            // Calculate distance traveled
            $distanceTraveled = $validated['ending_odometer'] - $validated['starting_odometer'];

            // Ensure distance is not negative
            if ($distanceTraveled < 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ending odometer must be greater than or equal to starting odometer'
                ], 422);
            }

            OdometerReading::create([
                'user_id' => auth()->id(),
                'date' => $validated['date'],
                'starting_odometer' => $validated['starting_odometer'],
                'ending_odometer' => $validated['ending_odometer'],
                'distance_traveled' => $distanceTraveled,
                'log_type' => $validated['log_type'],
                'purpose' => $validated['purpose'],
                'maintenance_cost' => $validated['maintenance_cost'],
                'description' => $validated['description'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Odometer reading saved successfully'
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Save Odometer Error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save odometer reading: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all driver logs for authenticated user
     */
    public function getLogs(Request $request)
    {
        try {
            $filter = $request->query('filter', 'all');
            $userId = auth()->id();
            $logs = [];

            if ($filter === 'all' || $filter === 'checklist') {
                $checklists = DriverChecklist::where('user_id', $userId)
                    ->latest()
                    ->get()
                    ->map(function ($checklist) {
                        return [
                            'id' => 'checklist_' . $checklist->id,
                            'type' => 'checklist',
                            'details' => ucfirst(str_replace('_', ' ', $checklist->check_type)) . ' - ' . count($checklist->checklist_items) . ' items checked',
                            'created_at' => $checklist->created_at,
                        ];
                    });
                $logs = array_merge($logs, $checklists->toArray());
            }

            if ($filter === 'all' || $filter === 'odometer') {
                $odometers = OdometerReading::where('user_id', $userId)
                    ->latest()
                    ->get()
                    ->map(function ($odometer) {
                        return [
                            'id' => 'odometer_' . $odometer->id,
                            'type' => 'odometer',
                            'details' => ucfirst($odometer->log_type) . ' - ' . $odometer->distance_traveled . ' km - ' . $odometer->purpose,
                            'created_at' => $odometer->created_at,
                        ];
                    });
                $logs = array_merge($logs, $odometers->toArray());
            }

            // Sort by created_at descending
            usort($logs, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });

            return response()->json($logs);
        } catch (\Exception $e) {
            Log::error('Get Logs Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch logs'
            ], 500);
        }
    }

    /**
     * Delete a driver log
     */
    public function deleteLog($id)
    {
        try {
            $userId = auth()->id();

            // Determine if it's a checklist or odometer log
            if (str_starts_with($id, 'checklist_')) {
                $logId = str_replace('checklist_', '', $id);
                $log = DriverChecklist::where('user_id', $userId)->findOrFail($logId);
            } else {
                $logId = str_replace('odometer_', '', $id);
                $log = OdometerReading::where('user_id', $userId)->findOrFail($logId);
            }

            $log->delete();

            return response()->json([
                'success' => true,
                'message' => 'Log deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Delete Log Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete log'
            ], 500);
        }
    }
}