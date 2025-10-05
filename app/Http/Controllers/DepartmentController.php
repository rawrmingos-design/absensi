<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,hr');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Department::withCount('employees');
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('head_of_department', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active');
        }
        
        $departments = $query->paginate(10);
        
        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string',
            'head_of_department' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);
        
        Department::create($request->all());
        
        return redirect()->route('departments.index')
            ->with('success', 'Departemen berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $department = Department::with('employees')->find($id);
        
        if (!$department) {
            return redirect()->route('departments.index')
                ->with('error', 'Data departemen tidak ditemukan.');
        }
        
        return view('departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $department = Department::find($id);
        
        if (!$department) {
            return redirect()->route('departments.index')
                ->with('error', 'Data departemen tidak ditemukan.');
        }
        
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $department = Department::find($id);
        
        if (!$department) {
            return redirect()->route('departments.index')
                ->with('error', 'Data departemen tidak ditemukan.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
            'head_of_department' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);
        
        $department->update($request->all());
        
        return redirect()->route('departments.index')
            ->with('success', 'Departemen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $department = Department::find($id);
        
        if (!$department) {
            return redirect()->route('departments.index')
                ->with('error', 'Data departemen tidak ditemukan.');
        }
        
        if ($department->employees()->count() > 0) {
            return redirect()->route('departments.index')
                ->with('error', 'Tidak dapat menghapus departemen yang masih memiliki karyawan.');
        }
        
        $department->delete();
        
        return redirect()->route('departments.index')
            ->with('success', 'Departemen berhasil dihapus.');
    }
}
