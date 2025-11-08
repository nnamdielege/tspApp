<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Show employee management page
     */
    public function employeeManagement()
    {
        return view('admin.employees');
    }

    /**
     * Show employee logs review page
     */
    public function employeeLogs()
    {
        return view('admin.employee-logs');
    }

    /**
     * Show employee routes page
     */
    public function employeeRoutes($employee)
    {
        return view('admin.employee-routes', ['employee_id' => $employee]);
    }
}