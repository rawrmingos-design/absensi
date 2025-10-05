@extends('layouts.app')

@section('title', 'Ajukan Cuti - Sistem Absensi Archemi')
@section('page-title', 'Ajukan Cuti')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Pengajuan Cuti</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('leaves.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    @if(auth()->user()->isAdmin() || auth()->user()->isHR())
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Karyawan <span class="text-danger">*</span></label>
                        <select class="form-select @error('employee_id') is-invalid @enderror" 
                                id="employee_id" name="employee_id" required>
                            <option value="">Pilih Karyawan</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" 
                                        {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }} - {{ $employee->employee_id }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @else
                    <input type="hidden" name="employee_id" value="{{ auth()->user()->employee_id }}">
                    <div class="mb-3">
                        <label class="form-label">Karyawan</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="type" class="form-label">Jenis Cuti <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Pilih Jenis Cuti</option>
                            <option value="annual" {{ old('type') == 'annual' ? 'selected' : '' }}>Cuti Tahunan</option>
                            <option value="sick" {{ old('type') == 'sick' ? 'selected' : '' }}>Sakit</option>
                            <option value="maternity" {{ old('type') == 'maternity' ? 'selected' : '' }}>Cuti Melahirkan</option>
                            <option value="paternity" {{ old('type') == 'paternity' ? 'selected' : '' }}>Cuti Ayah</option>
                            <option value="emergency" {{ old('type') == 'emergency' ? 'selected' : '' }}>Darurat</option>
                            <option value="unpaid" {{ old('type') == 'unpaid' ? 'selected' : '' }}>Tanpa Gaji</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" value="{{ old('start_date') }}" 
                                   min="{{ date('Y-m-d') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" value="{{ old('end_date') }}" 
                                   min="{{ date('Y-m-d') }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Durasi Cuti</label>
                        <div class="alert alert-info" id="duration-info">
                            <i class="bi bi-info-circle"></i> Pilih tanggal mulai dan selesai untuk menghitung durasi
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reason" class="form-label">Alasan Cuti <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('reason') is-invalid @enderror" 
                                  id="reason" name="reason" rows="4" required 
                                  placeholder="Jelaskan alasan pengajuan cuti...">{{ old('reason') }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="attachment" class="form-label">Lampiran</label>
                        <input type="file" class="form-control @error('attachment') is-invalid @enderror" 
                               id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png">
                        @error('attachment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Format: PDF, JPG, PNG. Maksimal 2MB. (Opsional)</div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('leaves.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Ajukan Cuti
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const durationInfo = document.getElementById('duration-info');
    
    function calculateDuration() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        
        if (startDate && endDate && endDate >= startDate) {
            const timeDiff = endDate.getTime() - startDate.getTime();
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
            
            durationInfo.innerHTML = `<i class="bi bi-calendar-check"></i> Durasi cuti: <strong>${daysDiff} hari</strong>`;
            durationInfo.className = 'alert alert-success';
        } else if (startDate && endDate && endDate < startDate) {
            durationInfo.innerHTML = `<i class="bi bi-exclamation-triangle"></i> Tanggal selesai harus setelah tanggal mulai`;
            durationInfo.className = 'alert alert-warning';
        } else {
            durationInfo.innerHTML = `<i class="bi bi-info-circle"></i> Pilih tanggal mulai dan selesai untuk menghitung durasi`;
            durationInfo.className = 'alert alert-info';
        }
    }
    
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
        calculateDuration();
    });
    
    endDateInput.addEventListener('change', calculateDuration);
});
</script>
@endsection
