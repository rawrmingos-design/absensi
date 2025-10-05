@extends('layouts.app')

@section('title', 'Manajemen Cuti - Sistem Absensi Archemi')
@section('page-title', 'Manajemen Cuti & Izin')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <a href="{{ route('leaves.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Ajukan Cuti
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
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
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Jenis Cuti</label>
                <select name="type" class="form-select">
                    <option value="">Semua Jenis</option>
                    <option value="annual" {{ request('type') == 'annual' ? 'selected' : '' }}>Cuti Tahunan</option>
                    <option value="sick" {{ request('type') == 'sick' ? 'selected' : '' }}>Sakit</option>
                    <option value="maternity" {{ request('type') == 'maternity' ? 'selected' : '' }}>Cuti Melahirkan</option>
                    <option value="paternity" {{ request('type') == 'paternity' ? 'selected' : '' }}>Cuti Ayah</option>
                    <option value="emergency" {{ request('type') == 'emergency' ? 'selected' : '' }}>Darurat</option>
                    <option value="unpaid" {{ request('type') == 'unpaid' ? 'selected' : '' }}>Tanpa Gaji</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Tahun</label>
                <select name="year" class="form-select">
                    <option value="">Semua Tahun</option>
                    @for($i = date('Y'); $i >= date('Y') - 2; $i--)
                        <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Leave List -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Daftar Pengajuan Cuti ({{ $leaves->total() }})</h5>
    </div>
    <div class="card-body">
        @if($leaves->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Jenis Cuti</th>
                            <th>Tanggal</th>
                            <th>Durasi</th>
                            <th>Status</th>
                            <th>Diajukan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaves as $leave)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($leave->employee && $leave->employee->profile_photo)
                                        <img src="{{ Storage::url($leave->employee->profile_photo) }}" 
                                             class="rounded-circle me-2" width="32" height="32" alt="Profile">
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                             style="width: 32px; height: 32px;">
                                            <i class="bi bi-person text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $leave->employee->name ?? 'Unknown Employee' }}</div>
                                        <small class="text-muted">{{ $leave->employee->department->name ?? 'No Department' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $leave->type_label }}</span>
                            </td>
                            <td>
                                <div>{{ $leave->start_date ? $leave->start_date->format('d/m/Y') : 'N/A' }}</div>
                                <small class="text-muted">s/d {{ $leave->end_date ? $leave->end_date->format('d/m/Y') : 'N/A' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $leave->total_days }} hari</span>
                            </td>
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
                            <td>
                                <div>{{ $leave->created_at ? $leave->created_at->format('d/m/Y') : 'N/A' }}</div>
                                <small class="text-muted">{{ $leave->created_at ? $leave->created_at->format('H:i') : '' }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('leaves.show', $leave->id) }}" 
                                       class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    @if($leave->status == 'pending')
                                        @if(auth()->user()->canApproveLeaves())
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    onclick="approveLeave({{ $leave->id }})" title="Setujui">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="rejectLeave({{ $leave->id }})" title="Tolak">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        @else
                                            <!-- Debug: User role = {{ auth()->user()->role }} -->
                                        @endif
                                        
                                        @if(auth()->user()->employee_id == $leave->employee_id || auth()->user()->isAdmin())
                                            <a href="{{ route('leaves.edit', $leave->id) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('leaves.destroy', $leave->id) }}" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Yakin ingin menghapus pengajuan cuti ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
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
                {{ $leaves->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="bi bi-calendar-x display-1 text-muted"></i>
                <h4 class="text-muted">Tidak ada pengajuan cuti</h4>
                <p class="text-muted">Belum ada pengajuan cuti yang sesuai dengan filter yang dipilih.</p>
            </div>
        @endif
    </div>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvalModalTitle">Setujui Pengajuan Cuti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="approvalForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">Catatan Admin</label>
                        <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" 
                                  placeholder="Berikan catatan untuk karyawan (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success" id="approvalSubmitBtn">Setujui</button>
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
                    <div class="mb-3">
                        <label for="reject_notes" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="reject_notes" name="admin_notes" rows="3" 
                                  placeholder="Berikan alasan penolakan" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function approveLeave(leaveId) {
    console.log('Approve leave clicked for ID:', leaveId);
    const form = document.getElementById('approvalForm');
    const modal = document.getElementById('approvalModal');
    const notesField = document.getElementById('admin_notes');
    
    if (!form || !modal || !notesField) {
        console.error('Required elements not found');
        return;
    }
    
    form.action = `{{ url('/leaves') }}/${leaveId}/approve`;
    notesField.value = '';
    
    try {
        // Try Bootstrap 5 first
        if (typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(modal).show();
        } 
        // Fallback to jQuery if available
        else if (typeof $ !== 'undefined') {
            $(modal).modal('show');
        }
        // Manual fallback
        else {
            modal.style.display = 'block';
            modal.classList.add('show');
            document.body.classList.add('modal-open');
        }
    } catch (error) {
        console.error('Error showing modal:', error);
        // Last resort - use confirm dialog
        const notes = prompt('Catatan admin (opsional):');
        if (notes !== null) {
            notesField.value = notes;
            form.submit();
        }
    }
}

function rejectLeave(leaveId) {
    console.log('Reject leave clicked for ID:', leaveId);
    const form = document.getElementById('rejectionForm');
    const modal = document.getElementById('rejectionModal');
    const notesField = document.getElementById('reject_notes');
    
    if (!form || !modal || !notesField) {
        console.error('Required elements not found');
        return;
    }
    
    form.action = `{{ url('/leaves') }}/${leaveId}/reject`;
    notesField.value = '';
    
    try {
        // Try Bootstrap 5 first
        if (typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(modal).show();
        } 
        // Fallback to jQuery if available
        else if (typeof $ !== 'undefined') {
            $(modal).modal('show');
        }
        // Manual fallback
        else {
            modal.style.display = 'block';
            modal.classList.add('show');
            document.body.classList.add('modal-open');
        }
    } catch (error) {
        console.error('Error showing modal:', error);
        // Last resort - use confirm dialog
        const notes = prompt('Alasan penolakan (wajib):');
        if (notes && notes.trim() !== '') {
            notesField.value = notes;
            form.submit();
        } else {
            alert('Alasan penolakan harus diisi!');
        }
    }
}
</script>
@endsection
