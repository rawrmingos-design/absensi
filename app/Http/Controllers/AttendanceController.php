<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        // Only admin and HR can create/edit attendance manually
        $this->middleware('role:admin,hr')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Attendance::with('employee.department');
        
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('date', $request->date);
        } else {
            $query->whereDate('date', Carbon::today());
        }
        
        if ($request->has('employee') && $request->employee != '') {
            $query->where('employee_id', $request->employee);
        }
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $attendances = $query->orderBy('clock_in', 'desc')->paginate(15);
        $employees = Employee::active()->get();
        
        return view('attendances.index', compact('attendances', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::active()->get();
        return view('attendances.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i|after:clock_in',
            'break_start' => 'nullable|date_format:H:i',
            'break_end' => 'nullable|date_format:H:i|after:break_start',
            'status' => 'required|in:present,absent,late,half_day,sick,permission',
            'notes' => 'nullable|string',
            'location' => 'nullable|string'
        ]);
        
        // Check if attendance already exists for this employee and date
        $existingAttendance = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', $request->date)
            ->first();
            
        if ($existingAttendance) {
            return back()->withErrors(['employee_id' => 'Absensi untuk karyawan ini pada tanggal tersebut sudah ada.']);
        }
        
        $data = $request->all();
        
        // Calculate total hours if both clock_in and clock_out are provided
        if ($request->clock_in && $request->clock_out) {
            $clockIn = Carbon::parse($request->clock_in);
            $clockOut = Carbon::parse($request->clock_out);
            $totalMinutes = $clockOut->diffInMinutes($clockIn);
            
            // Subtract break time if provided
            if ($request->break_start && $request->break_end) {
                $breakStart = Carbon::parse($request->break_start);
                $breakEnd = Carbon::parse($request->break_end);
                $breakMinutes = $breakEnd->diffInMinutes($breakStart);
                $totalMinutes -= $breakMinutes;
            }
            
            $data['total_hours'] = round($totalMinutes / 60, 2);
        }
        
        Attendance::create($data);
        
        return redirect()->route('attendances.index')
            ->with('success', 'Data absensi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $attendance = Attendance::with('employee.department')->find($id);
        
        if (!$attendance) {
            return redirect()->route('attendances.index')
                ->with('error', 'Data absensi tidak ditemukan.');
        }
        return view('attendances.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $attendance = Attendance::with('employee.department')->find($id);
        
        if (!$attendance) {
            return redirect()->route('attendances.index')
                ->with('error', 'Data absensi tidak ditemukan.');
        }
        
        $employees = Employee::active()->get();
        return view('attendances.edit', compact('attendance', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $attendance = Attendance::find($id);
        
        if (!$attendance) {
            return redirect()->route('attendances.index')
                ->with('error', 'Data absensi tidak ditemukan.');
        }
        
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i|after:clock_in',
            'break_start' => 'nullable|date_format:H:i',
            'break_end' => 'nullable|date_format:H:i|after:break_start',
            'status' => 'required|in:present,absent,late,half_day,sick,permission',
            'notes' => 'nullable|string',
            'location' => 'nullable|string'
        ]);
        
        // Check if attendance already exists for this employee and date (excluding current record)
        $existingAttendance = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', $request->date)
            ->where('id', '!=', $attendance->id)
            ->first();
            
        if ($existingAttendance) {
            return back()->withErrors(['employee_id' => 'Absensi untuk karyawan ini pada tanggal tersebut sudah ada.']);
        }
        
        $data = $request->all();
        
        // Calculate total hours if both clock_in and clock_out are provided
        if ($request->clock_in && $request->clock_out) {
            $clockIn = Carbon::parse($request->clock_in);
            $clockOut = Carbon::parse($request->clock_out);
            $totalMinutes = $clockOut->diffInMinutes($clockIn);
            
            // Subtract break time if provided
            if ($request->break_start && $request->break_end) {
                $breakStart = Carbon::parse($request->break_start);
                $breakEnd = Carbon::parse($request->break_end);
                $breakMinutes = $breakEnd->diffInMinutes($breakStart);
                $totalMinutes -= $breakMinutes;
            }
            
            $data['total_hours'] = round($totalMinutes / 60, 2);
        }
        
        $attendance->update($data);
        
        return redirect()->route('attendances.index')
            ->with('success', 'Data absensi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $attendance = Attendance::find($id);
        
        if (!$attendance) {
            return redirect()->route('attendances.index')
                ->with('error', 'Data absensi tidak ditemukan.');
        }
        
        $attendance->delete();
        
        return redirect()->route('attendances.index')
            ->with('success', 'Data absensi berhasil dihapus.');
    }

    /**
     * Clock in for employee
     */
    public function clockIn(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'location' => 'nullable|string'
        ]);
        
        $today = Carbon::today();
        $now = Carbon::now();
        
        // Check if already clocked in today
        $existingAttendance = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', $today)
            ->first();
            
        if ($existingAttendance && $existingAttendance->clock_in) {
            return response()->json(['error' => 'Sudah melakukan clock in hari ini.'], 400);
        }
        
        $status = 'present';
        if ($now->format('H:i') > '08:00') {
            $status = 'late';
        }
        
        if ($existingAttendance) {
            $existingAttendance->update([
                'clock_in' => $now->format('H:i'),
                'status' => $status,
                'location' => $request->location
            ]);
        } else {
            Attendance::create([
                'employee_id' => $request->employee_id,
                'date' => $today,
                'clock_in' => $now->format('H:i'),
                'status' => $status,
                'location' => $request->location
            ]);
        }
        
        return response()->json(['success' => 'Clock in berhasil.']);
    }

    /**
     * Clock out for employee
     */
    public function clockOut(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id'
        ]);
        
        $today = Carbon::today();
        $now = Carbon::now();
        
        $attendance = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('date', $today)
            ->first();
            
        if (!$attendance || !$attendance->clock_in) {
            return response()->json(['error' => 'Belum melakukan clock in hari ini.'], 400);
        }
        
        if ($attendance->clock_out) {
            return response()->json(['error' => 'Sudah melakukan clock out hari ini.'], 400);
        }
        
        // Calculate total hours
        $clockIn = Carbon::parse($attendance->clock_in);
        $clockOut = $now;
        $totalMinutes = $clockOut->diffInMinutes($clockIn);
        
        // Subtract break time if exists
        if ($attendance->break_start && $attendance->break_end) {
            $breakStart = Carbon::parse($attendance->break_start);
            $breakEnd = Carbon::parse($attendance->break_end);
            $breakMinutes = $breakEnd->diffInMinutes($breakStart);
            $totalMinutes -= $breakMinutes;
        }
        
        $attendance->update([
            'clock_out' => $now->format('H:i'),
            'total_hours' => round($totalMinutes / 60, 2)
        ]);
        
        return response()->json(['success' => 'Clock out berhasil.']);
    }
}
