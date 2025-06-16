@extends('layouts.custom-template.main')

@section('title', 'Verifikasi Email')

@section('auth-content')
    <div class="login-box">
        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success" role="alert">
                Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
            </div>
        @endif
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h1 class="mb-0 text-center"><b>{{ $prefs_composer['title'] }}</b></h1>
            </div>
            <div class="card-body login-card-body">
                <p class="login-box-msg">
                    Silahkan cek email yang Anda berikan saat pendaftaran, link verifikasi ada di dalamnya!
                </p>
                <div class="social-auth-links text-center mb-3 d-grid gap-2">
                    <p><b>Belum mendapatkan email verifikasi ?</b></p>
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send-arrow-down-fill mx-3"></i> Kirim ulang email verifikasi
                        </button>
                    </form>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-box-arrow-left mx-3"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection