@extends('layouts.app')

@section('title', 'Detail Departemen - Sistem Absensi Archemi')
@section('page-title', 'Detail Departemen')

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Department Info Card -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Informasi Departemen</h5>
                @if($department->is_active)
                    <span class="badge bg-success">Aktif</span>
                @else
                    <span class="badge bg-secondary">Tidak Aktif</span>
                @endif
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                         style="width: 100px; height: 100px;">
                        <i class="bi bi-building display-4 text-white"></i>
                    </div>
                    <h4 class="card-title">{{ $department->name }}</h4>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Kepala Departemen</label>
                    <div class="fw-bold">{{ $department->head_of_department ?? 'Belum ditentukan' }}</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Deskripsi</label>
                    <div class="text-muted">{{ $department->description ?? 'Tidak ada deskripsi' }}</div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label text-muted">Jumlah Karyawan</label>
                    <div class="fw-bold fs-4 text-primary">
                        <i class="bi bi-people"></i> {{ $department->employees->count() }} orang
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('departments.edit', $department) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit Departemen
                    </a>
                    <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Department Stats -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Statistik Departemen</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-success">{{ $department->employees->where('status', 'active')->count() }}</h4>
                        <small class="text-muted">Karyawan Aktif</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning">{{ $department->employees->where('status', '!=', 'active')->count() }}</h4>
                        <small class="text-muted">Tidak Aktif</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-12">
                        <h4 class="text-info">
                            @if($department->employees->count() > 0)
                                {{ number_format($department->employees->avg('salary'), 0, ',', '.') }}
                            @else
                                0
                            @endif
                        </h4>
                        <small class="text-muted">Rata-rata Gaji</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <!-- Employee List -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Karyawan</h5>
                <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah Karyawan
                </a>
            </div>
            <div class="card-body">
                @if($department->employees->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>ID Karyawan</th>
                                    <th>Posisi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($department->employees as $employee)
                                <tr>
                                    <td>
                                        @if($employee->profile_photo)
                                            <img src="{{ Storage::url($employee->profile_photo) }}" 
                                                 class="rounded-circle" width="40" height="40" alt="Profile">
                                        @else
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-person text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $employee->name }}</div>
                                        <small class="text-muted">{{ $employee->email }}</small>
                                    </td>
                                    <td>{{ $employee->employee_id }}</td>
                                    <td>{{ $employee->position }}</td>
                                    <td>
                                        @if($employee->status == 'active')
                                            <span class="badge bg-success">Aktif</span>
                                        @elseif($employee->status == 'inactive')
                                            <span class="badge bg-warning">Tidak Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Terminated</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('employees.show', $employee) }}" 
                                               class="btn btn-sm btn-outline-info" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('employees.edit', $employee) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-people display-1 text-muted"></i>
                        <h5 class="text-muted">Belum ada karyawan</h5>
                        <p class="text-muted">Departemen ini belum memiliki karyawan.</p>
                        <a href="{{ route('employees.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Karyawan Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Recent Activities -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Aktivitas Terbaru</h5>
            </div>
            <div class="card-body">
                @php
                    $recentAttendances = \App\Models\Attendance::whereHas('employee', function($query) use ($department) {
                        $query->where('department_id', $department->id);
                    })->with('employee')->latest()->limit(5)->get();
                @endphp
                
                @if($recentAttendances->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentAttendances as $attendance)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                @if($attendance->employee && $attendance->employee->profile_photo)
                                    <img src="{{ Storage::url($attendance->employee->profile_photo) }}" 
                                         class="rounded-circle me-3" width="32" height="32" alt="Profile">
                                @else
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 32px; height: 32px;">
                                        <i class="bi bi-person text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold">{{ $attendance->employee->name ?? 'Unknown Employee' }}</div>
                                    <small class="text-muted">
                                        {{ $attendance->date->format('d/m/Y') }} - 
                                        {{ $attendance->clock_in ? 'Masuk ' . $attendance->clock_in : 'Belum masuk' }}
                                    </small>
                                </div>
                            </div>
                            <div>
                                @switch($attendance->status)
                                    @case('present')
                                        <span class="badge bg-success">Hadir</span>
                                        @break
                                    @case('late')
                                        <span class="badge bg-warning">Terlambat</span>
                                        @break
                                    @case('absent')
                                        <span class="badge bg-danger">Tidak Hadir</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($attendance->status) }}</span>
                                @endswitch
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('attendances.index', ['department' => $department->id]) }}" class="btn btn-outline-primary btn-sm">
                            Lihat Semua Absensi
                        </a>
                    </div>
                @else
                    <p class="text-muted text-center">Belum ada aktivitas absensi</p>
                @endif
            </div>
        </div>
        
        <!-- Department Timeline -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Informasi Tambahan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Dibuat</label>
                        <div class="fw-bold">{{ $department->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Terakhir Diupdate</label>
                        <div class="fw-bold">{{ $department->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
                
                @if($department->employees->count() > 0)
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Karyawan Terbaru</label>
                        <div class="fw-bold">{{ $department->employees->sortByDesc('created_at')->first()->name }}</div>
                        <small class="text-muted">{{ $department->employees->sortByDesc('created_at')->first()->created_at->format('d/m/Y') }}</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Karyawan Terlama</label>
                        <div class="fw-bold">{{ $department->employees->sortBy('hire_date')->first()->name }}</div>
                        <small class="text-muted">Bergabung {{ $department->employees->sortBy('hire_date')->first()->hire_date->format('d/m/Y') }}</small>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
