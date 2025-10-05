@extends('layouts.app')

@section('title', 'Home - Sistem Absensi Archemi')
@section('page-title', 'Selamat Datang')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Selamat Datang di Sistem Absensi Archemi</h5>
                </div>
                <div class="card-body text-center">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h4 class="text-success mb-3">Login Berhasil!</h4>
                    <p class="text-muted mb-4">
                        Selamat datang, <strong>{{ Auth::user()->name }}</strong>!<br>
                        Anda login sebagai <span class="badge bg-primary">{{ ucfirst(Auth::user()->role) }}</span>
                    </p>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-speedometer2"></i> Menuju Dashboard
                        </a>
                        
                        @if(auth()->user()->employee_id)
                        <div class="row mt-3">
                            <div class="col-6">
                                <button class="btn btn-success btn-block" onclick="clockIn()">
                                    <i class="bi bi-clock"></i> Clock In
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-danger btn-block" onclick="clockOut()">
                                    <i class="bi bi-clock-history"></i> Clock Out
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function clockIn() {
    if (!{{ auth()->user()->employee_id ?? 0 }}) {
        alert('Anda tidak memiliki data karyawan yang terkait.');
        return;
    }
    
    if (confirm('Apakah Anda yakin ingin clock in?')) {
        fetch('{{ route("attendance.clock-in") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                employee_id: {{ auth()->user()->employee_id ?? 0 }},
                location: 'Home Page'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
                window.location.href = '{{ route("dashboard") }}';
            } else {
                alert(data.error || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan sistem');
        });
    }
}

function clockOut() {
    if (!{{ auth()->user()->employee_id ?? 0 }}) {
        alert('Anda tidak memiliki data karyawan yang terkait.');
        return;
    }
    
    if (confirm('Apakah Anda yakin ingin clock out?')) {
        fetch('{{ route("attendance.clock-out") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                employee_id: {{ auth()->user()->employee_id ?? 0 }}
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
                window.location.href = '{{ route("dashboard") }}';
            } else {
                alert(data.error || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan sistem');
        });
    }
}
</script>
@endsection
