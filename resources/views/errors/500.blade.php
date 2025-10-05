@extends('layouts.app')

@section('title', 'Server Error - Sistem Absensi Archemi')
@section('page-title', 'Server Error')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="bi bi-exclamation-triangle text-danger" style="font-size: 5rem;"></i>
                    </div>
                    
                    <h1 class="display-4 text-danger mb-3">500</h1>
                    <h4 class="mb-3">Server Error</h4>
                    
                    <p class="text-muted mb-4">
                        Terjadi kesalahan pada server.<br>
                        Tim teknis kami sedang menangani masalah ini.
                    </p>
                    
                    <div class="alert alert-warning">
                        <i class="bi bi-info-circle"></i>
                        <strong>Saran:</strong> Silakan coba lagi dalam beberapa menit atau hubungi administrator.
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="bi bi-house"></i> Kembali ke Dashboard
                        </a>
                        <button onclick="location.reload()" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-clockwise"></i> Muat Ulang Halaman
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
