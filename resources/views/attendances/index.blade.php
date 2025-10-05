@extends('layouts.app')

@section('title', 'Data Absensi - Sistem Absensi Archemi')
@section('page-title', 'Data Absensi')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        @if(auth()->user()->isAdmin() || auth()->user()->isHR())
        <a href="{{ route('attendances.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Input Absensi Manual
        </a>
        @endif
    </div>
    <div class="col-md-6">
        <div class="d-flex gap-2">
            @if(auth()->user()->employee_id)
            <button class="btn btn-success" onclick="clockIn()">
                <i class="bi bi-clock"></i> Clock In
            </button>
            <button class="btn btn-danger" onclick="clockOut()">
                <i class="bi bi-clock-history"></i> Clock Out
            </button>
            @endif
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="date" class="form-control" value="{{ request('date', date('Y-m-d')) }}">
            </div>
            @if(auth()->user()->isAdmin() || auth()->user()->isHR())
            <div class="col-md-3">
                <label class="form-label">Karyawan</label>
                <select name="employee" class="form-select">
                    <option value="">Semua Karyawan</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Hadir</option>
                    <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Tidak Hadir</option>
                    <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Terlambat</option>
                    <option value="sick" {{ request('status') == 'sick' ? 'selected' : '' }}>Sakit</option>
                    <option value="permission" {{ request('status') == 'permission' ? 'selected' : '' }}>Izin</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('attendances.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Attendance List -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Data Absensi - {{ request('date', date('d/m/Y')) }} ({{ $attendances->total() }})</h5>
    </div>
    <div class="card-body">
        @if($attendances->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Departemen</th>
                            <th>Tanggal</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Total Jam</th>
                            <th>Status</th>
                            <th>Lokasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($attendance->employee && $attendance->employee->profile_photo)
                                        <img src="{{ Storage::url($attendance->employee->profile_photo) }}" 
                                             class="rounded-circle me-2" width="32" height="32" alt="Profile">
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                             style="width: 32px; height: 32px;">
                                            <i class="bi bi-person text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $attendance->employee->name ?? 'Unknown Employee' }}</div>
                                        <small class="text-muted">{{ $attendance->employee->employee_id ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $attendance->employee->department->name ?? 'No Department' }}</span>
                            </td>
                            <td>{{ $attendance->date->format('d/m/Y') }}</td>
                            <td>
                                @if($attendance->clock_in)
                                    <span class="badge bg-success">{{ $attendance->clock_in }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->clock_out)
                                    <span class="badge bg-danger">{{ $attendance->clock_out }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->total_working_hours > 0)
                                    {{ $attendance->total_working_hours }} jam
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @switch($attendance->status)
                                    @case('present')
                                        <span class="badge bg-success">Hadir</span>
                                        @break
                                    @case('absent')
                                        <span class="badge bg-danger">Tidak Hadir</span>
                                        @break
                                    @case('late')
                                        <span class="badge bg-warning">Terlambat</span>
                                        @break
                                    @case('sick')
                                        <span class="badge bg-info">Sakit</span>
                                        @break
                                    @case('permission')
                                        <span class="badge bg-secondary">Izin</span>
                                        @break
                                    @default
                                        <span class="badge bg-light text-dark">{{ ucfirst($attendance->status) }}</span>
                                @endswitch
                            </td>
                            <td>{{ $attendance->location ?? '-' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('attendances.show', $attendance) }}" 
                                       class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(auth()->user()->isAdmin() || auth()->user()->isHR())
                                    <a href="{{ route('attendances.edit', $attendance) }}" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('attendances.destroy', $attendance) }}" 
                                          method="POST" class="d-inline" 
                                          onsubmit="return confirm('Yakin ingin menghapus data absensi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $attendances->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="bi bi-clock display-1 text-muted"></i>
                <h4 class="text-muted">Tidak ada data absensi</h4>
                <p class="text-muted">Belum ada data absensi untuk tanggal yang dipilih.</p>
            </div>
        @endif
    </div>
</div>

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
                location: 'Web Application'
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
