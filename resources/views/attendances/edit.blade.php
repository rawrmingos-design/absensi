@extends('layouts.app')

@section('title', 'Edit Absensi - Sistem Absensi Archemi')
@section('page-title', 'Edit Absensi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Edit Absensi</h5>
            </div>
            <div class="card-body">
                <!-- Employee Info Display -->
                <div class="alert alert-info">
                    <div class="d-flex align-items-center">
                        @if($attendance->employee && $attendance->employee->profile_photo)
                            <img src="{{ Storage::url($attendance->employee->profile_photo) }}" 
                                 class="rounded-circle me-3" width="50" height="50" alt="Profile">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px;">
                                <i class="bi bi-person text-white"></i>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-1">{{ $attendance->employee->name ?? 'Unknown Employee' }}</h6>
                            <small>{{ $attendance->employee->employee_id ?? 'N/A' }} - {{ $attendance->employee->department->name ?? 'No Department' }}</small>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('attendances.update', $attendance) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Karyawan <span class="text-danger">*</span></label>
                        <select class="form-select @error('employee_id') is-invalid @enderror" 
                                id="employee_id" name="employee_id" required>
                            <option value="">Pilih Karyawan</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" 
                                        {{ old('employee_id', $attendance->employee_id) == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }} - {{ $employee->employee_id }} ({{ $employee->department->name }})
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" 
                               id="date" name="date" value="{{ old('date', $attendance->date->format('Y-m-d')) }}" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="clock_in" class="form-label">Jam Masuk</label>
                            <input type="time" class="form-control @error('clock_in') is-invalid @enderror" 
                                   id="clock_in" name="clock_in" value="{{ old('clock_in', $attendance->clock_in) }}">
                            @error('clock_in')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="clock_out" class="form-label">Jam Keluar</label>
                            <input type="time" class="form-control @error('clock_out') is-invalid @enderror" 
                                   id="clock_out" name="clock_out" value="{{ old('clock_out', $attendance->clock_out) }}">
                            @error('clock_out')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="break_start" class="form-label">Mulai Istirahat</label>
                            <input type="time" class="form-control @error('break_start') is-invalid @enderror" 
                                   id="break_start" name="break_start" value="{{ old('break_start', $attendance->break_start) }}">
                            @error('break_start')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="break_end" class="form-label">Selesai Istirahat</label>
                            <input type="time" class="form-control @error('break_end') is-invalid @enderror" 
                                   id="break_end" name="break_end" value="{{ old('break_end', $attendance->break_end) }}">
                            @error('break_end')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="present" {{ old('status', $attendance->status) == 'present' ? 'selected' : '' }}>Hadir</option>
                            <option value="absent" {{ old('status', $attendance->status) == 'absent' ? 'selected' : '' }}>Tidak Hadir</option>
                            <option value="late" {{ old('status', $attendance->status) == 'late' ? 'selected' : '' }}>Terlambat</option>
                            <option value="half_day" {{ old('status', $attendance->status) == 'half_day' ? 'selected' : '' }}>Setengah Hari</option>
                            <option value="sick" {{ old('status', $attendance->status) == 'sick' ? 'selected' : '' }}>Sakit</option>
                            <option value="permission" {{ old('status', $attendance->status) == 'permission' ? 'selected' : '' }}>Izin</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="location" class="form-label">Lokasi</label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" 
                               id="location" name="location" value="{{ old('location', $attendance->location) }}" 
                               placeholder="Contoh: Kantor Pusat, WFH, dll">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3" 
                                  placeholder="Catatan tambahan (opsional)">{{ old('notes', $attendance->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="alert alert-info" id="working-hours-info">
                            <i class="bi bi-info-circle"></i> 
                            @if($attendance->total_working_hours > 0)
                                Total jam kerja saat ini: <strong>{{ $attendance->total_working_hours }} jam</strong>
                            @else
                                Masukkan jam masuk dan keluar untuk menghitung total jam kerja
                            @endif
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('attendances.show', $attendance) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Absensi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clockInInput = document.getElementById('clock_in');
    const clockOutInput = document.getElementById('clock_out');
    const breakStartInput = document.getElementById('break_start');
    const breakEndInput = document.getElementById('break_end');
    const workingHoursInfo = document.getElementById('working-hours-info');
    
    function calculateWorkingHours() {
        const clockIn = clockInInput.value;
        const clockOut = clockOutInput.value;
        const breakStart = breakStartInput.value;
        const breakEnd = breakEndInput.value;
        
        if (clockIn && clockOut) {
            const clockInTime = new Date(`1970-01-01T${clockIn}:00`);
            const clockOutTime = new Date(`1970-01-01T${clockOut}:00`);
            
            if (clockOutTime > clockInTime) {
                let totalMinutes = (clockOutTime - clockInTime) / (1000 * 60);
                
                // Subtract break time if provided
                if (breakStart && breakEnd) {
                    const breakStartTime = new Date(`1970-01-01T${breakStart}:00`);
                    const breakEndTime = new Date(`1970-01-01T${breakEnd}:00`);
                    
                    if (breakEndTime > breakStartTime) {
                        const breakMinutes = (breakEndTime - breakStartTime) / (1000 * 60);
                        totalMinutes -= breakMinutes;
                    }
                }
                
                const hours = Math.floor(totalMinutes / 60);
                const minutes = Math.round(totalMinutes % 60);
                
                workingHoursInfo.innerHTML = `<i class="bi bi-clock"></i> Total jam kerja: <strong>${hours} jam ${minutes} menit</strong>`;
                workingHoursInfo.className = 'alert alert-success';
            } else {
                workingHoursInfo.innerHTML = `<i class="bi bi-exclamation-triangle"></i> Jam keluar harus setelah jam masuk`;
                workingHoursInfo.className = 'alert alert-warning';
            }
        } else {
            workingHoursInfo.innerHTML = `<i class="bi bi-info-circle"></i> Masukkan jam masuk dan keluar untuk menghitung total jam kerja`;
            workingHoursInfo.className = 'alert alert-info';
        }
    }
    
    clockInInput.addEventListener('change', calculateWorkingHours);
    clockOutInput.addEventListener('change', calculateWorkingHours);
    breakStartInput.addEventListener('change', calculateWorkingHours);
    breakEndInput.addEventListener('change', calculateWorkingHours);
    
    // Calculate on page load
    calculateWorkingHours();
});
</script>
@endsection
