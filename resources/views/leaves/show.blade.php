@extends('layouts.app')

@section('title', 'Detail Pengajuan Cuti - Sistem Absensi Archemi')
@section('page-title', 'Detail Pengajuan Cuti')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Pengajuan Cuti</h5>
                <div>
                    @if($leave->status == 'pending')
                        @if(auth()->user()->canApproveLeaves())
                            <button type="button" class="btn btn-success btn-sm" onclick="approveLeave({{ $leave->id }})">
                                <i class="bi bi-check-circle"></i> Setujui
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="rejectLeave({{ $leave->id }})">
                                <i class="bi bi-x-circle"></i> Tolak
                            </button>
                        @endif
                        
                        @if(auth()->user()->employee_id == $leave->employee_id || auth()->user()->isAdmin())
                            <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        @endif
                    @endif
                    
                    <a href="{{ route('leaves.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Employee Info -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="d-flex align-items-center mb-3">
                            @if($leave->employee && $leave->employee->profile_photo)
                                <img src="{{ Storage::url($leave->employee->profile_photo) }}" 
                                     class="rounded-circle me-3" width="60" height="60" alt="Profile">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 60px; height: 60px;">
                                    <i class="bi bi-person text-white"></i>
                                </div>
                            @endif
                            <div>
                                <h4 class="mb-1">{{ $leave->employee->name ?? 'Unknown Employee' }}</h4>
                                <p class="text-muted mb-0">
                                    {{ $leave->employee->employee_id ?? 'N/A' }} - {{ $leave->employee->position ?? 'N/A' }}
                                </p>
                                <small class="text-muted">{{ $leave->employee->department->name ?? 'No Department' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Leave Details -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Jenis Cuti</label>
                        <div>
                            <span class="badge bg-info fs-6">{{ $leave->type_label }}</span>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Status</label>
                        <div>
                            @switch($leave->status)
                                @case('pending')
                                    <span class="badge bg-warning fs-6">Pending</span>
                                    @break
                                @case('approved')
                                    <span class="badge bg-success fs-6">Disetujui</span>
                                    @break
                                @case('rejected')
                                    <span class="badge bg-danger fs-6">Ditolak</span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Tanggal Mulai</label>
                        <div class="fw-bold fs-5">
                            {{ $leave->start_date ? $leave->start_date->format('d F Y') : 'Tanggal tidak tersedia' }}
                        </div>
                        <small class="text-muted">
                            {{ $leave->start_date ? $leave->start_date->format('l') : '' }}
                        </small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Tanggal Selesai</label>
                        <div class="fw-bold fs-5">
                            {{ $leave->end_date ? $leave->end_date->format('d F Y') : 'Tanggal tidak tersedia' }}
                        </div>
                        <small class="text-muted">
                            {{ $leave->end_date ? $leave->end_date->format('l') : '' }}
                        </small>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Durasi Cuti</label>
                        <div class="fw-bold fs-4 text-primary">{{ $leave->total_days }} hari</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Tanggal Pengajuan</label>
                        <div class="fw-bold">{{ $leave->created_at ? $leave->created_at->format('d/m/Y H:i') : 'Tidak tersedia' }}</div>
                    </div>
                </div>
                
                <!-- Reason -->
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label text-muted">Alasan Cuti</label>
                        <div class="p-3 bg-light rounded">
                            {{ $leave->reason }}
                        </div>
                    </div>
                </div>
                
                <!-- Attachment -->
                @if($leave->attachment)
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label text-muted">Lampiran</label>
                        <div>
                            <a href="{{ Storage::url($leave->attachment) }}" target="_blank" class="btn btn-outline-primary">
                                <i class="bi bi-paperclip"></i> Lihat Lampiran
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Approval Information -->
                @if($leave->status != 'pending')
                <hr>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">
                            @if($leave->status == 'approved')
                                Disetujui Oleh
                            @else
                                Ditolak Oleh
                            @endif
                        </label>
                        <div class="fw-bold">{{ $leave->approvedBy->name ?? '-' }}</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">
                            @if($leave->status == 'approved')
                                Tanggal Persetujuan
                            @else
                                Tanggal Penolakan
                            @endif
                        </label>
                        <div class="fw-bold">{{ $leave->approved_at ? $leave->approved_at->format('d/m/Y H:i') : '-' }}</div>
                    </div>
                </div>
                
                @if($leave->admin_notes)
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label text-muted">Catatan Admin</label>
                        <div class="p-3 {{ $leave->status == 'approved' ? 'bg-success' : 'bg-danger' }} bg-opacity-10 rounded">
                            {{ $leave->admin_notes }}
                        </div>
                    </div>
                </div>
                @endif
                @endif
            </div>
        </div>
        
        <!-- Leave Summary Card -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Ringkasan Cuti</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="border-end">
                            <h4 class="text-info">{{ $leave->start_date ? $leave->start_date->format('d M') : 'N/A' }}</h4>
                            <small class="text-muted">Mulai</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-end">
                            <h4 class="text-info">{{ $leave->end_date ? $leave->end_date->format('d M') : 'N/A' }}</h4>
                            <small class="text-muted">Selesai</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-end">
                            <h4 class="text-primary">{{ $leave->total_days }}</h4>
                            <small class="text-muted">Hari</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h4 class="text-{{ $leave->status == 'approved' ? 'success' : ($leave->status == 'rejected' ? 'danger' : 'warning') }}">
                            {{ ucfirst($leave->status) }}
                        </h4>
                        <small class="text-muted">Status</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Setujui Pengajuan Cuti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="approvalForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>{{ $leave->employee->name ?? 'Unknown Employee' }}</strong> mengajukan cuti <strong>{{ $leave->type_label }}</strong> 
                        selama <strong>{{ $leave->total_days }} hari</strong> 
                        ({{ $leave->start_date ? $leave->start_date->format('d/m/Y') : 'N/A' }} - {{ $leave->end_date ? $leave->end_date->format('d/m/Y') : 'N/A' }})
                    </div>
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">Catatan Admin</label>
                        <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" 
                                  placeholder="Berikan catatan untuk karyawan (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Setujui Cuti</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Pengajuan Cuti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectionForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <strong>{{ $leave->employee->name ?? 'Unknown Employee' }}</strong> mengajukan cuti <strong>{{ $leave->type_label }}</strong> 
                        selama <strong>{{ $leave->total_days }} hari</strong> 
                        ({{ $leave->start_date ? $leave->start_date->format('d/m/Y') : 'N/A' }} - {{ $leave->end_date ? $leave->end_date->format('d/m/Y') : 'N/A' }})
                    </div>
                    <div class="mb-3">
                        <label for="reject_notes" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="reject_notes" name="admin_notes" rows="3" 
                                  placeholder="Berikan alasan penolakan yang jelas" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Cuti</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function approveLeave(leaveId) {
    document.getElementById('approvalForm').action = `{{ url('/leaves') }}/${leaveId}/approve`;
    document.getElementById('admin_notes').value = '';
    new bootstrap.Modal(document.getElementById('approvalModal')).show();
}

function rejectLeave(leaveId) {
    document.getElementById('rejectionForm').action = `{{ url('/leaves') }}/${leaveId}/reject`;
    document.getElementById('reject_notes').value = '';
    new bootstrap.Modal(document.getElementById('rejectionModal')).show();
}
</script>
@endsection
