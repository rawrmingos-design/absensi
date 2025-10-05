@extends('layouts.app')

@section('title', 'Detail Absensi - Sistem Absensi Archemi')
@section('page-title', 'Detail Absensi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Absensi</h5>
                <div>
                    @if(auth()->user()->isAdmin() || auth()->user()->isHR())
                    <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    @endif
                    <a href="{{ route('attendances.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Employee Info -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="d-flex align-items-center mb-3">
                            @if($attendance->employee && $attendance->employee->profile_photo)
                                <img src="{{ Storage::url($attendance->employee->profile_photo) }}" 
                                     class="rounded-circle me-3" width="60" height="60" alt="Profile">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 60px; height: 60px;">
                                    <i class="bi bi-person text-white"></i>
                                </div>
                            @endif
                            <div>
                                <h4 class="mb-1">{{ $attendance->employee->name ?? 'Unknown Employee' }}</h4>
                                <p class="text-muted mb-0">{{ $attendance->employee->employee_id ?? 'N/A' }} - {{ $attendance->employee->position ?? 'N/A' }}</p>
                                <small class="text-muted">{{ $attendance->employee->department->name ?? 'No Department' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Attendance Details -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Tanggal</label>
                        <div class="fw-bold fs-5">{{ $attendance->date->format('d F Y') }}</div>
                        <small class="text-muted">{{ $attendance->date->format('l') }}</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Status Kehadiran</label>
                        <div>
                            @switch($attendance->status)
                                @case('present')
                                    <span class="badge bg-success fs-6">Hadir</span>
                                    @break
                                @case('absent')
                                    <span class="badge bg-danger fs-6">Tidak Hadir</span>
                                    @break
                                @case('late')
                                    <span class="badge bg-warning fs-6">Terlambat</span>
                                    @break
                                @case('half_day')
                                    <span class="badge bg-info fs-6">Setengah Hari</span>
                                    @break
                                @case('sick')
                                    <span class="badge bg-secondary fs-6">Sakit</span>
                                    @break
                                @case('permission')
                                    <span class="badge bg-primary fs-6">Izin</span>
                                    @break
                                @default
                                    <span class="badge bg-light text-dark fs-6">{{ ucfirst($attendance->status) }}</span>
                            @endswitch
                        </div>
                    </div>
                </div>
                
                <!-- Time Details -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Jam Masuk</label>
                        <div class="fw-bold fs-5">
                            @if($attendance->clock_in)
                                {{ $attendance->clock_in }}
                                @if($attendance->is_late)
                                    <small class="text-danger">(Terlambat)</small>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Jam Keluar</label>
                        <div class="fw-bold fs-5">
                            @if($attendance->clock_out)
                                {{ $attendance->clock_out }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Break Time -->
                @if($attendance->break_start || $attendance->break_end)
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Mulai Istirahat</label>
                        <div class="fw-bold">{{ $attendance->break_start ?? '-' }}</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Selesai Istirahat</label>
                        <div class="fw-bold">{{ $attendance->break_end ?? '-' }}</div>
                    </div>
                </div>
                @endif
                
                <!-- Working Hours -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Total Jam Kerja</label>
                        <div class="fw-bold fs-5">
                            @if($attendance->total_working_hours > 0)
                                {{ $attendance->total_working_hours }} jam
                                @if($attendance->total_working_hours >= 8)
                                    <small class="text-success">(Full Time)</small>
                                @elseif($attendance->total_working_hours >= 4)
                                    <small class="text-warning">(Part Time)</small>
                                @else
                                    <small class="text-danger">(Under Time)</small>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Lokasi</label>
                        <div class="fw-bold">{{ $attendance->location ?? '-' }}</div>
                    </div>
                </div>
                
                <!-- Notes -->
                @if($attendance->notes)
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label text-muted">Catatan</label>
                        <div class="p-3 bg-light rounded">
                            {{ $attendance->notes }}
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Timestamps -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Dibuat</label>
                        <div class="fw-bold">{{ $attendance->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Terakhir Diupdate</label>
                        <div class="fw-bold">{{ $attendance->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Working Hours Calculation Card -->
        @if($attendance->clock_in && $attendance->clock_out)
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Rincian Jam Kerja</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="border-end">
                            <h4 class="text-primary">{{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}</h4>
                            <small class="text-muted">Jam Masuk</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-end">
                            <h4 class="text-danger">{{ \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') }}</h4>
                            <small class="text-muted">Jam Keluar</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-end">
                            <h4 class="text-warning">
                                @if($attendance->break_start && $attendance->break_end)
                                    {{ \Carbon\Carbon::parse($attendance->break_end)->diffInMinutes(\Carbon\Carbon::parse($attendance->break_start)) }} mnt
                                @else
                                    0 mnt
                                @endif
                            </h4>
                            <small class="text-muted">Istirahat</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h4 class="text-success">{{ $attendance->total_working_hours }} jam</h4>
                        <small class="text-muted">Total Kerja</small>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
