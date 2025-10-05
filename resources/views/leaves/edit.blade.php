@extends('layouts.app')

@section('title', 'Edit Pengajuan Cuti - Sistem Absensi Archemi')
@section('page-title', 'Edit Pengajuan Cuti')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Edit Pengajuan Cuti</h5>
            </div>
            <div class="card-body">
                @if($leave->status !== 'pending')
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Perhatian:</strong> Pengajuan cuti ini sudah {{ $leave->status == 'approved' ? 'disetujui' : 'ditolak' }} 
                    dan tidak dapat diedit lagi.
                </div>
                @endif
                
                <!-- Employee Info Display -->
                <div class="alert alert-info">
                    <div class="d-flex align-items-center">
                        @if($leave->employee && $leave->employee->profile_photo)
                            <img src="{{ Storage::url($leave->employee->profile_photo) }}" 
                                 class="rounded-circle me-3" width="50" height="50" alt="Profile">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px;">
                                <i class="bi bi-person text-white"></i>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-1">{{ $leave->employee->name ?? 'Unknown Employee' }}</h6>
                            <small>{{ $leave->employee->employee_id ?? 'N/A' }} - {{ $leave->employee->department->name ?? 'No Department' }}</small>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('leaves.update', $leave) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    @if(auth()->user()->isAdmin() || auth()->user()->isHR())
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Karyawan <span class="text-danger">*</span></label>
                        <select class="form-select @error('employee_id') is-invalid @enderror" 
                                id="employee_id" name="employee_id" required>
                            <option value="">Pilih Karyawan</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" 
                                        {{ old('employee_id', $leave->employee_id) == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }} - {{ $employee->employee_id }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @else
                    <input type="hidden" name="employee_id" value="{{ $leave->employee_id }}">
                    @endif
                    
                    <div class="mb-3">
                        <label for="type" class="form-label">Jenis Cuti <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Pilih Jenis Cuti</option>
                            <option value="annual" {{ old('type', $leave->type) == 'annual' ? 'selected' : '' }}>Cuti Tahunan</option>
                            <option value="sick" {{ old('type', $leave->type) == 'sick' ? 'selected' : '' }}>Sakit</option>
                            <option value="maternity" {{ old('type', $leave->type) == 'maternity' ? 'selected' : '' }}>Cuti Melahirkan</option>
                            <option value="paternity" {{ old('type', $leave->type) == 'paternity' ? 'selected' : '' }}>Cuti Ayah</option>
                            <option value="emergency" {{ old('type', $leave->type) == 'emergency' ? 'selected' : '' }}>Darurat</option>
                            <option value="unpaid" {{ old('type', $leave->type) == 'unpaid' ? 'selected' : '' }}>Tanpa Gaji</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" 
                                   value="{{ old('start_date', $leave->start_date ? $leave->start_date->format('Y-m-d') : '') }}" 
                                   min="{{ date('Y-m-d') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" 
                                   value="{{ old('end_date', $leave->end_date ? $leave->end_date->format('Y-m-d') : '') }}" 
                                   min="{{ date('Y-m-d') }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Durasi Cuti</label>
                        <div class="alert alert-success" id="duration-info">
                            <i class="bi bi-calendar-check"></i> Durasi cuti saat ini: <strong>{{ $leave->total_days }} hari</strong>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reason" class="form-label">Alasan Cuti <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('reason') is-invalid @enderror" 
                                  id="reason" name="reason" rows="4" required 
                                  placeholder="Jelaskan alasan pengajuan cuti...">{{ old('reason', $leave->reason) }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        @if($leave->attachment)
                        <label class="form-label">Lampiran Saat Ini</label>
                        <div class="mb-2">
                            <a href="{{ Storage::url($leave->attachment) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-paperclip"></i> Lihat Lampiran Saat Ini
                            </a>
                        </div>
                        @endif
                        
                        <label for="attachment" class="form-label">
                            {{ $leave->attachment ? 'Ganti Lampiran' : 'Lampiran' }}
                        </label>
                        <input type="file" class="form-control @error('attachment') is-invalid @enderror" 
                               id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png">
                        @error('attachment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Format: PDF, JPG, PNG. Maksimal 2MB. 
                            {{ $leave->attachment ? 'Kosongkan jika tidak ingin mengubah lampiran.' : '(Opsional)' }}
                        </div>
                    </div>
                    
                    <!-- Status Information -->
                    <div class="mb-3">
                        <label class="form-label">Status Pengajuan</label>
                        <div>
                            @switch($leave->status)
                                @case('pending')
                                    <span class="badge bg-warning fs-6">Pending - Menunggu Persetujuan</span>
                                    @break
                                @case('approved')
                                    <span class="badge bg-success fs-6">Disetujui</span>
                                    @if($leave->approved_at)
                                        <small class="text-muted d-block">
                                            Disetujui pada {{ $leave->approved_at ? $leave->approved_at->format('d/m/Y H:i') : 'N/A' }} 
                                            oleh {{ $leave->approvedBy->name ?? '-' }}
                                        </small>
                                    @endif
                                    @break
                                @case('rejected')
                                    <span class="badge bg-danger fs-6">Ditolak</span>
                                    @if($leave->approved_at)
                                        <small class="text-muted d-block">
                                            Ditolak pada {{ $leave->approved_at ? $leave->approved_at->format('d/m/Y H:i') : 'N/A' }} 
                                            oleh {{ $leave->approvedBy->name ?? '-' }}
                                        </small>
                                    @endif
                                    @break
                            @endswitch
                        </div>
                        
                        @if($leave->admin_notes)
                        <div class="mt-2">
                            <small class="text-muted">Catatan Admin:</small>
                            <div class="p-2 bg-light rounded small">{{ $leave->admin_notes }}</div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('leaves.show', $leave) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        @if($leave->status === 'pending')
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Pengajuan
                        </button>
                        @endif
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
    
    // Calculate on page load
    calculateDuration();
});
</script>
@endsection
