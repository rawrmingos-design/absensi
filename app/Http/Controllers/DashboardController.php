<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Attendance;
use App\Models\Leave;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // Statistics
        $totalEmployees = Employee::active()->count();
        $totalDepartments = Department::where('is_active', true)->count();
        $todayAttendance = Attendance::today()->count();
        $pendingLeaves = Leave::pending()->count();
        
        // Today's attendance
        $todayPresentEmployees = Attendance::today()->present()->count();
        $todayAbsentEmployees = $totalEmployees - $todayPresentEmployees;
        
        // Recent activities
        $recentAttendances = Attendance::with('employee')
            ->today()
            ->orderBy('clock_in', 'desc')
            ->limit(10)
            ->get();
            
        $recentLeaves = Leave::with('employee')
            ->pending()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Monthly stats
        $monthlyAttendance = Attendance::thisMonth()->count();
        $monthlyLeaves = Leave::whereMonth('start_date', Carbon::now()->month)
            ->whereYear('start_date', Carbon::now()->year)
            ->count();
        
        return view('dashboard', compact(
            'totalEmployees',
            'totalDepartments', 
            'todayAttendance',
            'pendingLeaves',
            'todayPresentEmployees',
            'todayAbsentEmployees',
            'recentAttendances',
            'recentLeaves',
            'monthlyAttendance',
            'monthlyLeaves'
        ));
    }
}
