@extends('layouts.app')

@section('title', 'Akses Ditolak - Sistem Absensi Archemi')
@section('page-title', 'Akses Ditolak')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="bi bi-shield-exclamation text-danger" style="font-size: 5rem;"></i>
                    </div>
                    
                    <h1 class="display-4 text-danger mb-3">403</h1>
                    <h4 class="mb-3">Akses Ditolak</h4>
                    
                    <p class="text-muted mb-4">
                        Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.<br>
                        Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.
                    </p>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Info:</strong> Anda login sebagai 
                        <span class="badge bg-primary">{{ ucfirst(auth()->user()->role ?? 'Guest') }}</span>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="bi bi-house"></i> Kembali ke Dashboard
                        </a>
                        <a href="javascript:history.back()" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali ke Halaman Sebelumnya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
