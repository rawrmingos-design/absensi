@extends('layouts.app')

@section('title', 'Tambah Departemen - Sistem Absensi Archemi')
@section('page-title', 'Tambah Departemen')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Tambah Departemen</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('departments.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Departemen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required
                               placeholder="Contoh: Human Resources, Information Technology">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" 
                                  placeholder="Deskripsi singkat tentang departemen ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Opsional - Jelaskan fungsi dan tanggung jawab departemen</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="head_of_department" class="form-label">Kepala Departemen</label>
                        <input type="text" class="form-control @error('head_of_department') is-invalid @enderror" 
                               id="head_of_department" name="head_of_department" 
                               value="{{ old('head_of_department') }}"
                               placeholder="Nama kepala departemen">
                        @error('head_of_department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Opsional - Dapat diisi nanti setelah departemen dibuat</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="is_active" class="form-label">Status</label>
                        <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
                            <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Departemen aktif akan muncul dalam pilihan saat menambah karyawan baru.
                        </div>
                    </div>
                    
                    <!-- Info Card -->
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Tips:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Nama departemen harus unik</li>
                            <li>Setelah departemen dibuat, Anda dapat menambahkan karyawan</li>
                            <li>Departemen dapat dinonaktifkan tanpa menghapus data</li>
                        </ul>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Departemen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
