<?php

namespace App\Http\Controllers;

use App\Models\OptimalPath;
use App\Models\User;
use Illuminate\Http\Request;

class AdminRouteAssignmentController extends Controller
{
    public function index()
    {
        $drivers = User::orderBy('name')->get();

        $routes = OptimalPath::with(['user', 'employee'])
            ->latest()
            ->get();

        return view('admin.assign-saved-route', compact('routes', 'drivers'));
    }

    public function assign(Request $request, OptimalPath $route)
    {
        $data = $request->validate([
            'employee_id' => ['required', 'exists:users,id'],
        ]);

        $route->update([
            'employee_id' => $data['employee_id'],
            'status' => 'planned',
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.assign-saved-route')
            ->with('success', 'Optimal route assigned to driver successfully.');
    }
}
