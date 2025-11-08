<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AdminLogReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminEmployeeManagementController extends Controller
{
    /**
     * Get all employees with suspension status
     */
    public function getAllEmployees(Request $request)
    {
        try {
            $status = $request->query('status', 'all'); // all, active, suspended

            $query = User::whereIn('role', ['driver', 'employee']);

            if ($status === 'active') {
                $query->where('is_suspended', false);
            } elseif ($status === 'suspended') {
                $query->where('is_suspended', true);
            }

            $employees = $query->with('suspendedByAdmin')
                ->latest()
                ->get()
                ->map(function ($employee) {
                    return [
                        'id' => $employee->id,
                        'name' => $employee->name,
                        'email' => $employee->email,
                        'role' => $employee->role,
                        'is_suspended' => $employee->is_suspended,
                        'suspended_at' => $employee->suspended_at?->format('M d, Y H:i'),
                        'suspended_by' => $employee->suspendedByAdmin?->name,
                        'suspension_reason' => $employee->suspension_reason,
                        'unsuspended_at' => $employee->unsuspended_at?->format('M d, Y H:i'),
                        'created_at' => $employee->created_at->format('M d, Y'),
                    ];
                });

            return response()->json($employees);
        } catch (\Exception $e) {
            Log::error('Get All Employees Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch employees'
            ], 500);
        }
    }

    /**
     * Suspend an employee
     */
    public function suspendEmployee(Request $request)
    {
        try {
            $validated = $request->validate([
                'employee_id' => 'required|exists:users,id',
                'reason' => 'required|string|max:500',
            ]);

            $employee = User::findOrFail($validated['employee_id']);

            // Check if already suspended
            if ($employee->is_suspended) {
                return response()->json([
                    'success' => false,
                    'message' => 'This employee is already suspended'
                ], 422);
            }

            // Suspend the employee
            $employee->suspend($validated['reason']);

            // Log the action
            Log::info('Employee Suspended', [
                'admin_id' => auth()->id(),
                'employee_id' => $employee->id,
                'reason' => $validated['reason'],
            ]);

            // You can send notification email here
            // Mail::send(new EmployeeSuspendedMail($employee, $validated['reason']));

            return response()->json([
                'success' => true,
                'message' => $employee->name . ' has been suspended',
                'employee' => [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'is_suspended' => $employee->is_suspended,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Suspend Employee Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to suspend employee: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unsuspend an employee
     */
    public function unsuspendEmployee(Request $request)
    {
        try {
            $validated = $request->validate([
                'employee_id' => 'required|exists:users,id',
            ]);

            $employee = User::findOrFail($validated['employee_id']);

            // Check if not suspended
            if (!$employee->is_suspended) {
                return response()->json([
                    'success' => false,
                    'message' => 'This employee is not suspended'
                ], 422);
            }

            // Unsuspend the employee
            $employee->unsuspend();

            // Log the action
            Log::info('Employee Unsuspended', [
                'admin_id' => auth()->id(),
                'employee_id' => $employee->id,
            ]);

            // You can send notification email here
            // Mail::send(new EmployeeUnsuspendedMail($employee));

            return response()->json([
                'success' => true,
                'message' => $employee->name . ' has been reinstated',
                'employee' => [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'is_suspended' => $employee->is_suspended,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Unsuspend Employee Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to unsuspend employee: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get suspension history for employee
     */
    public function getEmployeeSuspensionHistory($employeeId)
    {
        try {
            $employee = User::findOrFail($employeeId);

            $suspensionHistory = [
                'is_currently_suspended' => $employee->is_suspended,
                'suspension_start' => $employee->suspended_at?->format('M d, Y H:i'),
                'suspension_reason' => $employee->suspension_reason,
                'suspended_by' => $employee->suspendedByAdmin?->name,
                'suspension_duration' => $employee->suspended_at && $employee->unsuspended_at
                    ? $employee->unsuspended_at->diffInDays($employee->suspended_at) . ' days'
                    : ($employee->suspended_at ? 'Still suspended' : 'N/A'),
                'unsuspended_at' => $employee->unsuspended_at?->format('M d, Y H:i'),
            ];

            return response()->json($suspensionHistory);
        } catch (\Exception $e) {
            Log::error('Get Suspension History Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch suspension history'
            ], 500);
        }
    }

    /**
     * Get suspension statistics
     */
    public function getSuspensionStatistics()
    {
        try {
            $totalEmployees = User::whereIn('role', ['driver', 'employee'])->count();
            $suspendedCount = User::whereIn('role', ['driver', 'employee'])
                ->where('is_suspended', true)
                ->count();

            $stats = [
                'total_employees' => $totalEmployees,
                'active_employees' => $totalEmployees - $suspendedCount,
                'suspended_employees' => $suspendedCount,
                'suspension_percentage' => $totalEmployees > 0
                    ? round(($suspendedCount / $totalEmployees) * 100, 2)
                    : 0,
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('Get Suspension Statistics Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics'
            ], 500);
        }
    }
}