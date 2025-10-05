@extends('layouts.app')

@section('title', 'Data Departemen - Sistem Absensi Archemi')
@section('page-title', 'Data Departemen')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <a href="{{ route('departments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Departemen
        </a>
    </div>
    <div class="col-md-6">
        <form method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Cari departemen..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-outline-secondary">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Department List -->
<div class="row">
    @if($departments->count() > 0)
        @foreach($departments as $department)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">{{ $department->name }}</h6>
                    @if($department->is_active)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Tidak Aktif</span>
                    @endif
                </div>
                <div class="card-body">
                    <p class="card-text text-muted">{{ Str::limit($department->description, 100) }}</p>
                    
                    <div class="mb-3">
                        <small class="text-muted">Kepala Departemen:</small>
                        <div class="fw-bold">{{ $department->head_of_department ?? 'Belum ditentukan' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Jumlah Karyawan:</small>
                        <div class="fw-bold">
                            <i class="bi bi-people"></i> {{ $department->employees_count }} orang
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('departments.show', $department) }}" 
                           class="btn btn-outline-info btn-sm">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                        <a href="{{ route('departments.edit', $department) }}" 
                           class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('departments.destroy', $department) }}" 
                              method="POST" class="d-inline" 
                              onsubmit="return confirm('Yakin ingin menghapus departemen ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-building display-1 text-muted"></i>
                <h4 class="text-muted">Tidak ada departemen ditemukan</h4>
                <p class="text-muted">Silakan tambah departemen baru atau ubah filter pencarian.</p>
                <a href="{{ route('departments.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Departemen
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Pagination -->
@if($departments->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $departments->withQueryString()->links() }}
</div>
@endif

<!-- Department Statistics -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Statistik Departemen</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="border-end">
                            <h3 class="text-primary">{{ $departments->where('is_active', true)->count() }}</h3>
                            <small class="text-muted">Departemen Aktif</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-end">
                            <h3 class="text-secondary">{{ $departments->where('is_active', false)->count() }}</h3>
                            <small class="text-muted">Departemen Tidak Aktif</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-end">
                            <h3 class="text-success">{{ $departments->sum('employees_count') }}</h3>
                            <small class="text-muted">Total Karyawan</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h3 class="text-info">{{ number_format($departments->avg('employees_count'), 1) }}</h3>
                        <small class="text-muted">Rata-rata Karyawan/Dept</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
