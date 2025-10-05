@extends('layouts.app')

@section('title', 'Detail Karyawan - Sistem Absensi Archemi')
@section('page-title', 'Detail Karyawan')

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Profile Card -->
        <div class="card">
            <div class="card-body text-center">
                @if($employee->profile_photo)
                    <img src="{{ Storage::url($employee->profile_photo) }}" 
                         class="rounded-circle mb-3" width="150" height="150" alt="Profile Photo">
                @else
                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                         style="width: 150px; height: 150px;">
                        <i class="bi bi-person display-4 text-white"></i>
                    </div>
                @endif
                
                <h4 class="card-title">{{ $employee->name }}</h4>
                <p class="text-muted">{{ $employee->position }}</p>
                
                <div class="mb-3">
                    @if($employee->status == 'active')
                        <span class="badge bg-success fs-6">Aktif</span>
                    @elseif($employee->status == 'inactive')
                        <span class="badge bg-warning fs-6">Tidak Aktif</span>
                    @else
                        <span class="badge bg-danger fs-6">Terminated</span>
                    @endif
                </div>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit Data
                    </a>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Statistik Cepat</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary">{{ $employee->attendances()->thisMonth()->count() }}</h4>
                        <small class="text-muted">Absensi Bulan Ini</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ $employee->leaves()->thisYear()->approved()->count() }}</h4>
                        <small class="text-muted">Cuti Disetujui</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <!-- Personal Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Personal</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">ID Karyawan</label>
                        <div class="fw-bold">{{ $employee->employee_id }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Email</label>
                        <div class="fw-bold">{{ $employee->email }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Nomor Telepon</label>
                        <div class="fw-bold">{{ $employee->phone ?? '-' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Tanggal Lahir</label>
                        <div class="fw-bold">
                            {{ $employee->birth_date ? $employee->birth_date->format('d/m/Y') : '-' }}
                            @if($employee->birth_date)
                                <small class="text-muted">({{ $employee->age }} tahun)</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Jenis Kelamin</label>
                        <div class="fw-bold">
                            @if($employee->gender == 'male')
                                Laki-laki
                            @elseif($employee->gender == 'female')
                                Perempuan
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Departemen</label>
                        <div class="fw-bold">
                            <span class="badge bg-info">{{ $employee->department->name }}</span>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label text-muted">Alamat</label>
                        <div class="fw-bold">{{ $employee->address ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Employment Information -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Informasi Kepegawaian</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Tanggal Bergabung</label>
                        <div class="fw-bold">
                            {{ $employee->hire_date ? $employee->hire_date->format('d/m/Y') : 'Tidak tersedia' }}
                            @if($employee->hire_date)
                                <small class="text-muted">({{ $employee->working_years }} tahun)</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Gaji</label>
                        <div class="fw-bold">
                            @if($employee->salary)
                                Rp {{ number_format($employee->salary, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Attendance -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Absensi Terbaru</h5>
                <a href="{{ route('attendances.index', ['employee' => $employee->id]) }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($employee->attendances()->latest()->limit(5)->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Masuk</th>
                                    <th>Keluar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee->attendances()->latest()->limit(5)->get() as $attendance)
                                <tr>
                                    <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                    <td>{{ $attendance->clock_in ?? '-' }}</td>
                                    <td>{{ $attendance->clock_out ?? '-' }}</td>
                                    <td>
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
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">Belum ada data absensi</p>
                @endif
            </div>
        </div>
        
        <!-- Recent Leaves -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Riwayat Cuti Terbaru</h5>
                <a href="{{ route('leaves.index', ['employee' => $employee->id]) }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($employee->leaves()->latest()->limit(5)->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Jenis</th>
                                    <th>Tanggal</th>
                                    <th>Durasi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee->leaves()->latest()->limit(5)->get() as $leave)
                                <tr>
                                    <td><span class="badge bg-info">{{ $leave->type_label }}</span></td>
                                    <td>{{ $leave->start_date->format('d/m/Y') }} - {{ $leave->end_date->format('d/m/Y') }}</td>
                                    <td>{{ $leave->total_days }} hari</td>
                                    <td>
                                        @switch($leave->status)
                                            @case('pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @break
                                            @case('approved')
                                                <span class="badge bg-success">Disetujui</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">Belum ada pengajuan cuti</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
