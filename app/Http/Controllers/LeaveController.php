<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Leave::with('employee.department');
        
        // Filter by employee if regular employee
        if (auth()->user()->isEmployee() && auth()->user()->employee_id) {
            $query->where('employee_id', auth()->user()->employee_id);
        }
        
        if ($request->has('employee') && $request->employee != '') {
            $query->where('employee_id', $request->employee);
        }
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }
        
        if ($request->has('year') && $request->year != '') {
            $query->whereYear('start_date', $request->year);
        }
        
        $leaves = $query->orderBy('created_at', 'desc')->paginate(15);
        $employees = Employee::active()->get();
        
        return view('leaves.index', compact('leaves', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::active()->get();
        return view('leaves.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:annual,sick,maternity,paternity,emergency,unpaid',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);
        
        $data = $request->all();
        
        // Calculate total days
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $data['total_days'] = $startDate->diffInDays($endDate) + 1;
        
        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('leave_attachments', 'public');
        }
        
        Leave::create($data);
        
        return redirect()->route('leaves.index')
            ->with('success', 'Pengajuan cuti berhasil disubmit.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $leave = Leave::with('employee.department', 'approvedBy')->find($id);
        
        if (!$leave) {
            return redirect()->route('leaves.index')
                ->with('error', 'Data pengajuan cuti tidak ditemukan.');
        }
        
        return view('leaves.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $leave = Leave::with('employee.department')->find($id);
        
        if (!$leave) {
            return redirect()->route('leaves.index')
                ->with('error', 'Data pengajuan cuti tidak ditemukan.');
        }
        
        // Only allow editing if status is pending
        if ($leave->status !== 'pending') {
            return redirect()->route('leaves.index')
                ->with('error', 'Hanya pengajuan cuti dengan status pending yang dapat diedit.');
        }
        
        $employees = Employee::active()->get();
        return view('leaves.edit', compact('leave', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $leave = Leave::find($id);
        
        if (!$leave) {
            return redirect()->route('leaves.index')
                ->with('error', 'Data pengajuan cuti tidak ditemukan.');
        }
        
        // Only allow updating if status is pending
        if ($leave->status !== 'pending') {
            return redirect()->route('leaves.index')
                ->with('error', 'Hanya pengajuan cuti dengan status pending yang dapat diedit.');
        }
        
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:annual,sick,maternity,paternity,emergency,unpaid',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);
        
        $data = $request->all();
        
        // Calculate total days
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $data['total_days'] = $startDate->diffInDays($endDate) + 1;
        
        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            // Delete old attachment
            if ($leave->attachment) {
                Storage::disk('public')->delete($leave->attachment);
            }
            $data['attachment'] = $request->file('attachment')->store('leave_attachments', 'public');
        }
        
        $leave->update($data);
        
        return redirect()->route('leaves.index')
            ->with('success', 'Pengajuan cuti berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $leave = Leave::find($id);
        
        if (!$leave) {
            return redirect()->route('leaves.index')
                ->with('error', 'Data pengajuan cuti tidak ditemukan.');
        }
        
        // Only allow deletion if status is pending
        if ($leave->status !== 'pending') {
            return redirect()->route('leaves.index')
                ->with('error', 'Hanya pengajuan cuti dengan status pending yang dapat dihapus.');
        }
        
        // Delete attachment
        if ($leave->attachment) {
            Storage::disk('public')->delete($leave->attachment);
        }
        
        $leave->delete();
        
        return redirect()->route('leaves.index')
            ->with('success', 'Pengajuan cuti berhasil dihapus.');
    }

    /**
     * Approve leave request
     */
    public function approve(Request $request, $id)
    {
        $leave = Leave::find($id);
        
        if (!$leave) {
            return redirect()->route('leaves.index')
                ->with('error', 'Data pengajuan cuti tidak ditemukan.');
        }
        
        if (!auth()->user()->canApproveLeaves()) {
            return redirect()->route('leaves.index')
                ->with('error', 'Anda tidak memiliki akses untuk menyetujui cuti.');
        }
        
        $request->validate([
            'admin_notes' => 'nullable|string'
        ]);
        
        $leave->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'approved_at' => Carbon::now(),
            'approved_by' => auth()->id()
        ]);
        
        return redirect()->route('leaves.index')
            ->with('success', 'Pengajuan cuti berhasil disetujui.');
    }

    /**
     * Reject leave request
     */
    public function reject(Request $request, $id)
    {
        $leave = Leave::find($id);
        
        if (!$leave) {
            return redirect()->route('leaves.index')
                ->with('error', 'Data pengajuan cuti tidak ditemukan.');
        }
        
        if (!auth()->user()->canApproveLeaves()) {
            return redirect()->route('leaves.index')
                ->with('error', 'Anda tidak memiliki akses untuk menolak cuti.');
        }
        
        $request->validate([
            'admin_notes' => 'required|string'
        ]);
        
        $leave->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'approved_at' => Carbon::now(),
            'approved_by' => auth()->id()
        ]);
        
        return redirect()->route('leaves.index')
            ->with('success', 'Pengajuan cuti berhasil ditolak.');
    }
}
