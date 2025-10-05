@extends('layouts.app')

@section('title', 'Edit Departemen - Sistem Absensi Archemi')
@section('page-title', 'Edit Departemen')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Edit Departemen</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('departments.update', $department) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Departemen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $department->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" 
                                  placeholder="Deskripsi departemen...">{{ old('description', $department->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="head_of_department" class="form-label">Kepala Departemen</label>
                        <input type="text" class="form-control @error('head_of_department') is-invalid @enderror" 
                               id="head_of_department" name="head_of_department" 
                               value="{{ old('head_of_department', $department->head_of_department) }}"
                               placeholder="Nama kepala departemen">
                        @error('head_of_department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="is_active" class="form-label">Status</label>
                        <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
                            <option value="1" {{ old('is_active', $department->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active', $department->is_active) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Departemen yang tidak aktif tidak akan muncul dalam pilihan saat menambah karyawan baru.
                        </div>
                    </div>
                    
                    <!-- Current Stats Display -->
                    <div class="mb-3">
                        <label class="form-label">Informasi Saat Ini</label>
                        <div class="row">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded text-center">
                                    <h4 class="text-primary">{{ $department->employees->count() }}</h4>
                                    <small class="text-muted">Total Karyawan</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded text-center">
                                    <h4 class="text-success">{{ $department->employees->where('status', 'active')->count() }}</h4>
                                    <small class="text-muted">Karyawan Aktif</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($department->employees->count() > 0 && !$department->is_active)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Perhatian:</strong> Departemen ini memiliki {{ $department->employees->count() }} karyawan. 
                        Menonaktifkan departemen akan mempengaruhi proses penambahan karyawan baru.
                    </div>
                    @endif
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('departments.show', $department) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Departemen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
