@extends('layouts.app')

@section('title', 'Dashboard - Sistem Absensi Archemi')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Karyawan
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalEmployees }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Hadir Hari Ini
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayPresentEmployees }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Tidak Hadir
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayAbsentEmployees }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-x-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Cuti Pending
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingLeaves }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-calendar-x fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-lightning"></i> Quick Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @if(auth()->user()->employee_id)
                    <div class="col-md-6 mb-3">
                        <button class="btn btn-success btn-block" onclick="clockIn()">
                            <i class="bi bi-clock"></i> Clock In
                        </button>
                    </div>
                    <div class="col-md-6 mb-3">
                        <button class="btn btn-danger btn-block" onclick="clockOut()">
                            <i class="bi bi-clock-history"></i> Clock Out
                        </button>
                    </div>
                    @endif
                    
                    @if(auth()->user()->isAdmin() || auth()->user()->isHR())
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('employees.create') }}" class="btn btn-primary btn-block">
                            <i class="bi bi-person-plus"></i> Tambah Karyawan
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('attendances.create') }}" class="btn btn-info btn-block">
                            <i class="bi bi-plus-circle"></i> Input Absensi
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-activity"></i> Aktivitas Terbaru
                </h6>
            </div>
            <div class="card-body">
                @if($recentAttendances->count() > 0)
                    @foreach($recentAttendances->take(5) as $attendance)
                    <div class="d-flex align-items-center mb-3">
                        <div class="mr-3">
                            <div class="icon-circle bg-success">
                                <i class="bi bi-check text-white"></i>
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500">{{ $attendance->created_at->format('H:i') }}</div>
                            <span class="font-weight-bold">{{ $attendance->employee->name }}</span> clock in
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted">Belum ada aktivitas hari ini.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Pending Leave Requests -->
    @if(auth()->user()->canApproveLeaves() && $recentLeaves->count() > 0)
    <div class="col-lg-12 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-calendar-check"></i> Pengajuan Cuti Pending
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Karyawan</th>
                                <th>Jenis Cuti</th>
                                <th>Tanggal</th>
                                <th>Durasi</th>
                                <th>Alasan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentLeaves as $leave)
                            <tr>
                                <td>{{ $leave->employee->name }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $leave->type_label }}</span>
                                </td>
                                <td>{{ $leave->start_date->format('d/m/Y') }} - {{ $leave->end_date->format('d/m/Y') }}</td>
                                <td>{{ $leave->total_days }} hari</td>
                                <td>{{ Str::limit($leave->reason, 50) }}</td>
                                <td>
                                    <a href="{{ route('leaves.show', $leave) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.border-left-primary { border-left: 0.25rem solid #4e73df !important; }
.border-left-success { border-left: 0.25rem solid #1cc88a !important; }
.border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
.border-left-info { border-left: 0.25rem solid #36b9cc !important; }
.icon-circle { display: inline-flex; align-items: center; justify-content: center; width: 2rem; height: 2rem; border-radius: 100%; }
.btn-block { width: 100%; }
</style>

<script>
function clockIn() {
    if (!{{ auth()->user()->employee_id ?? 0 }}) {
        alert('Anda tidak memiliki data karyawan yang terkait.');
        return;
    }
    
    if (confirm('Apakah Anda yakin ingin clock in?')) {
        fetch('{{ route("attendance.clock-in") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                employee_id: {{ auth()->user()->employee_id ?? 0 }},
                location: 'Dashboard'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
                location.reload();
            } else {
                alert(data.error || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan sistem');
        });
    }
}

function clockOut() {
    if (!{{ auth()->user()->employee_id ?? 0 }}) {
        alert('Anda tidak memiliki data karyawan yang terkait.');
        return;
    }
    
    if (confirm('Apakah Anda yakin ingin clock out?')) {
        fetch('{{ route("attendance.clock-out") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                employee_id: {{ auth()->user()->employee_id ?? 0 }}
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
                location.reload();
            } else {
                alert(data.error || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan sistem');
        });
    }
}
</script>
@endsection
