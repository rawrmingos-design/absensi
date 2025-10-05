@extends('layouts.app')

@section('title', 'Data Karyawan - Sistem Absensi Archemi')
@section('page-title', 'Data Karyawan')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <a href="{{ route('employees.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Karyawan
        </a>
    </div>
    <div class="col-md-6">
        <form method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Cari karyawan..." value="{{ request('search') }}">
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
                <label class="form-label">Departemen</label>
                <select name="department" class="form-select">
                    <option value="">Semua Departemen</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Employee List -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Daftar Karyawan ({{ $employees->total() }})</h5>
    </div>
    <div class="card-body">
        @if($employees->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>ID Karyawan</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Posisi</th>
                            <th>Departemen</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td>
                                @if($employee->profile_photo)
                                    <img src="{{ Storage::url($employee->profile_photo) }}" 
                                         class="rounded-circle" width="40" height="40" alt="Profile">
                                @else
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-person text-white"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $employee->employee_id }}</td>
                            <td>
                                <div class="fw-bold">{{ $employee->name }}</div>
                                <small class="text-muted">{{ $employee->phone }}</small>
                            </td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->position }}</td>
                            <td>
                                <span class="badge bg-info">{{ $employee->department->name }}</span>
                            </td>
                            <td>
                                @if($employee->status == 'active')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($employee->status == 'inactive')
                                    <span class="badge bg-warning">Tidak Aktif</span>
                                @else
                                    <span class="badge bg-danger">Terminated</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('employees.show', $employee) }}" 
                                       class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('employees.edit', $employee) }}" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('employees.destroy', $employee) }}" 
                                          method="POST" class="d-inline" 
                                          onsubmit="return confirm('Yakin ingin menghapus karyawan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $employees->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="bi bi-people display-1 text-muted"></i>
                <h4 class="text-muted">Tidak ada karyawan ditemukan</h4>
                <p class="text-muted">Silakan tambah karyawan baru atau ubah filter pencarian.</p>
            </div>
        @endif
    </div>
</div>
@endsection
