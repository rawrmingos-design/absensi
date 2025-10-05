@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan - Sistem Absensi Archemi')
@section('page-title', 'Halaman Tidak Ditemukan')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="bi bi-question-circle text-warning" style="font-size: 5rem;"></i>
                    </div>
                    
                    <h1 class="display-4 text-warning mb-3">404</h1>
                    <h4 class="mb-3">Halaman Tidak Ditemukan</h4>
                    
                    <p class="text-muted mb-4">
                        Maaf, halaman yang Anda cari tidak dapat ditemukan.<br>
                        Mungkin halaman telah dipindahkan atau URL salah.
                    </p>
                    
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
