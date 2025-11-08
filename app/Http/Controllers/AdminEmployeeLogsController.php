<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DriverChecklist;
use App\Models\OdometerReading;
use App\Models\DriverReminder;
use App\Models\AdminLogReminder;
use App\Models\OptimalPath;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdminEmployeeLogsController extends Controller
{
    /**
     * Display employee logs dashboard
     */
    public function index()
    {
        return view('admin.employee-logs');
    }

    /**
     * Get all employees with their log status
     */
    public function getEmployeeLogsStatus(Request $request)
    {
        try {
            $date = $request->query('date', now()->toDateString());
            $employees = User::where('role', 'driver')
                ->orWhere('role', 'employee')
                ->get();

            $employeeStatus = $employees->map(function ($employee) use ($date) {
                $checklist = DriverChecklist::where('user_id', $employee->id)
                    ->whereDate('created_at', $date)
                    ->latest()
                    ->first();

                $odometer = OdometerReading::where('user_id', $employee->id)
                    ->whereDate('date', $date)
                    ->first();

                $reminder = DriverReminder::where('user_id', $employee->id)
                    ->where('reminder_date', $date)
                    ->first();

                return [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'checklist_status' => $checklist ? 'completed' : 'pending',
                    'checklist_time' => $checklist ? $checklist->created_at->format('H:i') : null,
                    'odometer_status' => $odometer ? 'completed' : 'pending',
                    'odometer_distance' => $odometer ? $odometer->distance_traveled : null,
                    'overall_status' => ($checklist && $odometer) ? 'complete' : 'incomplete',
                    'status_percentage' => ($checklist ? 50 : 0) + ($odometer ? 50 : 0),
                ];
            });

            return response()->json($employeeStatus);
        } catch (\Exception $e) {
            Log::error('Get Employee Logs Status Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch employee logs status'
            ], 500);
        }
    }

    /**
     * Get detailed logs for a specific employee
     */
    public function getEmployeeDetailedLogs($employeeId, Request $request)
    {
        try {
            $startDate = $request->query('start_date', now()->subDays(7)->toDateString());
            $endDate = $request->query('end_date', now()->toDateString());

            $checklists = DriverChecklist::where('user_id', $employeeId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->latest()
                ->get()
                ->map(function ($checklist) {
                    return [
                        'id' => 'checklist_' . $checklist->id,
                        'type' => 'checklist',
                        'date' => $checklist->created_at->toDateString(),
                        'time' => $checklist->created_at->format('H:i'),
                        'check_type' => $checklist->check_type,
                        'odometer' => $checklist->odometer_reading,
                        'items_checked' => count($checklist->checklist_items),
                        'notes' => $checklist->notes,
                    ];
                });

            $odometers = OdometerReading::where('user_id', $employeeId)
                ->whereBetween('date', [$startDate, $endDate])
                ->latest()
                ->get()
                ->map(function ($odometer) {
                    return [
                        'id' => 'odometer_' . $odometer->id,
                        'type' => 'odometer',
                        'date' => $odometer->date->toDateString(),
                        'log_type' => $odometer->log_type,
                        'distance' => $odometer->distance_traveled,
                        'purpose' => $odometer->purpose,
                        'cost' => $odometer->maintenance_cost,
                        'description' => $odometer->description,
                    ];
                });

            $logs = array_merge($checklists->toArray(), $odometers->toArray());
            usort($logs, function ($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });

            return response()->json($logs);
        } catch (\Exception $e) {
            Log::error('Get Employee Detailed Logs Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch employee logs'
            ], 500);
        }
    }

    /**
     * Send reminder to employee for missing logs
     */
    public function sendEmployeeReminder(Request $request)
    {
        try {
            $validated = $request->validate([
                'employee_id' => 'required|exists:users,id',
                'reminder_type' => 'required|in:missing_checklist,missing_odometer,overdue_logs',
                'message' => 'nullable|string',
            ]);

            $employee = User::findOrFail($validated['employee_id']);
            $date = now()->toDateString();

            // Create admin log reminder
            $reminder = AdminLogReminder::create([
                'admin_id' => auth()->id(),
                'employee_id' => $validated['employee_id'],
                'reminder_date' => $date,
                'reminder_type' => $validated['reminder_type'],
                'sent' => true,
                'sent_at' => now(),
                'notes' => $validated['message'],
            ]);

            // You can add email/notification sending here
            // Mail::send(new EmployeeLogReminderMail($employee, $validated['reminder_type']));

            return response()->json([
                'success' => true,
                'message' => 'Reminder sent to ' . $employee->name,
                'reminder' => $reminder
            ]);
        } catch (\Exception $e) {
            Log::error('Send Reminder Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reminder: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all reminders sent by admin
     */
    public function getAdminReminders(Request $request)
    {
        try {
            $startDate = $request->query('start_date', now()->subDays(30)->toDateString());
            $endDate = $request->query('end_date', now()->toDateString());

            $reminders = AdminLogReminder::where('admin_id', auth()->id())
                ->whereBetween('reminder_date', [$startDate, $endDate])
                ->with('employee')
                ->latest()
                ->get()
                ->map(function ($reminder) {
                    return [
                        'id' => $reminder->id,
                        'employee_name' => $reminder->employee->name,
                        'employee_id' => $reminder->employee_id,
                        'reminder_type' => $reminder->reminder_type,
                        'date' => $reminder->reminder_date->format('M d, Y'),
                        'sent' => $reminder->sent,
                        'sent_at' => $reminder->sent_at?->format('H:i'),
                        'acknowledged' => $reminder->acknowledged,
                        'acknowledged_at' => $reminder->acknowledged_at?->format('M d, H:i'),
                        'notes' => $reminder->notes,
                    ];
                });

            return response()->json($reminders);
        } catch (\Exception $e) {
            Log::error('Get Admin Reminders Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch reminders'
            ], 500);
        }
    }

    /**
     * Get reminders received by employee (from employee side)
     */
    public function getReceivedReminders()
    {
        try {
            $reminders = AdminLogReminder::where('employee_id', auth()->id())
                ->where('sent', true)
                ->with('admin')
                ->latest()
                ->limit(10)
                ->get()
                ->map(function ($reminder) {
                    return [
                        'id' => $reminder->id,
                        'admin_name' => $reminder->admin->name,
                        'reminder_type' => $reminder->reminder_type,
                        'type_label' => $this->getReminderLabel($reminder->reminder_type),
                        'date' => $reminder->sent_at->format('M d, H:i'),
                        'message' => $reminder->notes,
                        'acknowledged' => $reminder->acknowledged,
                    ];
                });

            return response()->json($reminders);
        } catch (\Exception $e) {
            Log::error('Get Received Reminders Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch reminders'
            ], 500);
        }
    }

    /**
     * Acknowledge reminder as employee
     */
    public function acknowledgeReminder($id)
    {
        try {
            $reminder = AdminLogReminder::where('employee_id', auth()->id())
                ->findOrFail($id);

            $reminder->markAsAcknowledged();

            return response()->json([
                'success' => true,
                'message' => 'Reminder acknowledged'
            ]);
        } catch (\Exception $e) {
            Log::error('Acknowledge Reminder Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to acknowledge reminder'
            ], 500);
        }
    }

    /**
     * Get reminder statistics for admin dashboard
     */
    public function getReminderStatistics(Request $request)
    {
        try {
            $date = $request->query('date', now()->toDateString());
            $adminId = auth()->id();

            $stats = [
                'total_reminders_sent' => AdminLogReminder::where('admin_id', $adminId)
                    ->where('reminder_date', $date)
                    ->count(),
                'acknowledged_reminders' => AdminLogReminder::where('admin_id', $adminId)
                    ->where('reminder_date', $date)
                    ->where('acknowledged', true)
                    ->count(),
                'pending_reminders' => AdminLogReminder::where('admin_id', $adminId)
                    ->where('reminder_date', $date)
                    ->where('acknowledged', false)
                    ->count(),
                'missing_checklists' => AdminLogReminder::where('admin_id', $adminId)
                    ->where('reminder_date', $date)
                    ->where('reminder_type', 'missing_checklist')
                    ->count(),
                'missing_odometers' => AdminLogReminder::where('admin_id', $adminId)
                    ->where('reminder_date', $date)
                    ->where('reminder_type', 'missing_odometer')
                    ->count(),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('Get Reminder Statistics Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics'
            ], 500);
        }
    }

    /**
     * Get auto-generated list of employees who haven't logged today
     */
    public function getEmployeesWithMissingLogs(Request $request)
    {
        try {
            $date = $request->query('date', now()->toDateString());
            $employees = User::where('role', 'driver')
                ->orWhere('role', 'employee')
                ->get();

            $missingLogs = $employees->filter(function ($employee) use ($date) {
                $hasChecklist = DriverChecklist::where('user_id', $employee->id)
                    ->whereDate('created_at', $date)
                    ->exists();

                $hasOdometer = OdometerReading::where('user_id', $employee->id)
                    ->whereDate('date', $date)
                    ->exists();

                return !$hasChecklist || !$hasOdometer;
            })->map(function ($employee) use ($date) {
                $hasChecklist = DriverChecklist::where('user_id', $employee->id)
                    ->whereDate('created_at', $date)
                    ->exists();

                $hasOdometer = OdometerReading::where('user_id', $employee->id)
                    ->whereDate('date', $date)
                    ->exists();

                return [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'missing_checklist' => !$hasChecklist,
                    'missing_odometer' => !$hasOdometer,
                ];
            })->values();

            return response()->json($missingLogs);
        } catch (\Exception $e) {
            Log::error('Get Missing Logs Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch missing logs'
            ], 500);
        }
    }

    /**
     * Get all routes visited by an employee
     */
    public function getEmployeeRoutes($employeeId, Request $request)
    {
        try {
            $startDate = $request->query('start_date', now()->subDays(30)->toDateString());
            $endDate = $request->query('end_date', now()->toDateString());

            Log::info('getEmployeeRoutes', [
                'employeeId' => $employeeId,
                'startDate' => $startDate,
                'endDate' => $endDate
            ]);

            // Get all routes for this employee
            $routes = OptimalPath::where('user_id', $employeeId)
                ->get();

            Log::info('Total routes found: ' . $routes->count());

            // Filter by date in PHP instead of SQL
            $filteredRoutes = $routes->filter(function ($route) use ($startDate, $endDate) {
                $routeDate = $route->created_at->toDateString();
                return $routeDate >= $startDate && $routeDate <= $endDate;
            })->values();

            Log::info('Filtered routes: ' . $filteredRoutes->count());

            // Map the routes
            $formattedRoutes = $filteredRoutes->map(function ($route) {
                try {
                    // Parse locations
                    $locations = [];
                    if ($route->locations) {
                        $locations = is_string($route->locations)
                            ? json_decode($route->locations, true)
                            : $route->locations;
                        $locations = $locations ?? [];
                    }

                    // Routes and odometer readings are not directly linked
                    // So we skip the odometer data for now
                    $totalDistance = 0;

                    // Calculate duration
                    $duration = null;
                    if ($route->started_at && $route->completed_at) {
                        $minutes = $route->completed_at->diffInMinutes($route->started_at);
                        $duration = $minutes . ' mins';
                    }

                    return [
                        'id' => $route->id,
                        'name' => 'Route #' . $route->id,
                        'date_created' => $route->created_at->format('M d, Y H:i'),
                        'status' => $route->status ?? 'unknown',
                        'optimize_type' => $route->optimize_type ?? 'N/A',
                        'total_weight' => $route->total_weight ?? '-',
                        'stops_count' => count($locations),
                        'location_count' => count($locations),
                        'locations' => $locations,
                        'distance' => $totalDistance,
                        'total_distance' => $totalDistance,
                        'duration' => $duration ?? '-',
                        'notes' => $route->route_notes ?? null,
                    ];
                } catch (\Exception $e) {
                    Log::error('Error mapping route ' . $route->id . ': ' . $e->getMessage());
                    return null;
                }
            })->filter(function ($route) {
                return $route !== null;
            })->values();

            Log::info('Formatted routes: ' . $formattedRoutes->count());

            return response()->json($formattedRoutes);
        } catch (\Exception $e) {
            Log::error('Get Employee Routes Error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch employee routes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get routes statistics for employee
     */

    public function getEmployeeRouteStatistics($employeeId, Request $request)
    {
        try {
            Log::info('=== getEmployeeRouteStatistics START ===', ['employeeId' => $employeeId]);

            // First, check if employee exists
            $employee = User::find($employeeId);
            if (!$employee) {
                Log::warning('Employee not found', ['employeeId' => $employeeId]);
                return response()->json([
                    'total_routes_created' => 0,
                    'total_routes_completed' => 0,
                    'total_routes_in_progress' => 0,
                    'total_locations_visited' => 0,
                    'total_distance_traveled' => 0,
                    'average_route_duration' => 0,
                    'most_used_optimization' => 'N/A',
                ]);
            }

            Log::info('Employee found', ['name' => $employee->name]);

            // Get ALL routes for this employee (no date filtering)
            $allRoutes = OptimalPath::where('user_id', $employeeId)
                ->get();

            Log::info('Total routes found', ['count' => $allRoutes->count()]);

            if ($allRoutes->count() > 0) {
                $allRoutes->each(function ($route) {
                    Log::info('Route details', [
                        'id' => $route->id,
                        'user_id' => $route->user_id,
                        'status' => $route->status,
                        'optimize_type' => $route->optimize_type,
                        'created_at' => $route->created_at,
                    ]);
                });
            }

            // Calculate statistics
            $totalRoutes = $allRoutes->count();
            $completedRoutes = $allRoutes->where('status', 'completed')->count();
            $inProgressRoutes = $allRoutes->where('status', 'in_progress')->count();

            // Count total locations from all routes
            $totalLocations = $allRoutes->sum(function ($route) {
                $locations = is_string($route->locations)
                    ? json_decode($route->locations, true)
                    : $route->locations;
                return is_array($locations) ? count($locations) : 0;
            });

            // Get most used optimization type
            $optimizationCounts = $allRoutes->groupBy('optimize_type')
                ->map->count()
                ->sortDesc();

            $mostUsedOptimization = $optimizationCounts->keys()->first() ?? 'N/A';

            $stats = [
                'total_routes_created' => $totalRoutes,
                'total_routes_completed' => $completedRoutes,
                'total_routes_in_progress' => $inProgressRoutes,
                'total_locations_visited' => $totalLocations,
                'total_distance_traveled' => 0,
                'average_route_duration' => 0,
                'most_used_optimization' => $mostUsedOptimization,
            ];

            Log::info('Final statistics', $stats);
            Log::info('=== getEmployeeRouteStatistics END ===');

            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('Get Route Statistics Error', [
                'message' => $e->getMessage(),
                'exception' => (string)$e,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'total_routes_created' => 0,
                'total_routes_completed' => 0,
                'total_routes_in_progress' => 0,
                'total_locations_visited' => 0,
                'total_distance_traveled' => 0,
                'average_route_duration' => 0,
                'most_used_optimization' => 'N/A',
            ]);
        }
    }
    /**
     * Get single route details with all information
     */
    public function getRouteDetails($routeId)
    {
        try {
            $route = OptimalPath::findOrFail($routeId);

            // Parse locations
            $locations = [];
            if ($route->locations) {
                $locations = is_string($route->locations)
                    ? json_decode($route->locations, true)
                    : $route->locations;
                $locations = $locations ?? [];
            }

            return response()->json([
                'id' => $route->id,
                'name' => 'Route #' . $route->id,
                'employee' => $route->user->name ?? 'Unknown',
                'date_created' => $route->created_at->format('M d, Y H:i'),
                'date_started' => $route->started_at?->format('M d, Y H:i'),
                'date_completed' => $route->completed_at?->format('M d, Y H:i'),
                'status' => $route->status ?? 'unknown',
                'optimize_type' => $route->optimize_type ?? 'N/A',
                'total_weight' => $route->total_weight ?? '-',
                'locations' => $locations,
                'location_count' => count($locations),
                'optimal_path' => $route->optimal_path ?? '-',
                'stops' => array_map(function ($location, $index) {
                    if (is_array($location)) {
                        return [
                            'address' => $location['name'] ?? $location['address'] ?? 'Location ' . ($index + 1),
                            'latitude' => $location['lat'] ?? 0,
                            'longitude' => $location['lng'] ?? 0,
                        ];
                    } else {
                        return [
                            'address' => $location,
                            'latitude' => 0,
                            'longitude' => 0,
                        ];
                    }
                }, $locations, array_keys($locations)),
                'duration' => ($route->started_at && $route->completed_at)
                    ? $route->completed_at->diffInMinutes($route->started_at) . ' mins'
                    : null,
                'total_distance' => 0,
                'total_locations_logged' => count($locations),
                'notes' => $route->route_notes ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error('Get Route Details Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch route details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get reminder label
     */
    private function getReminderLabel($type)
    {
        return match ($type) {
            'missing_checklist' => 'Missing Daily Checklist',
            'missing_odometer' => 'Missing Odometer Reading',
            'overdue_logs' => 'Overdue Logs',
            default => 'Log Reminder',
        };
    }
}