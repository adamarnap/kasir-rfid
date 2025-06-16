@extends('layouts.custom-template.main')

@section('title', 'Lupa Kata Sandi')

@section('auth-content')
    <div class="login-box">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <!-- Validation Errors -->
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
        <!-- Form Reset Password-->
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h1 class="mb-0 text-center"><b>{{ $prefs_composer['title'] }}</b></h1>
            </div>
            <div class="card-body login-card-body">
                <p class="login-box-msg">
                    Lupa kata sandi Anda? Tidak masalah. Cukup beri tahu kami alamat email Anda dan kami akan mengirimkan
                    email berisi tautan pengaturan ulang kata sandi yang memungkinkan Anda memilih yang baru.
                </p>
                <div class="social-auth-links text-center mb-3 d-grid gap-2">
                    <p><b>Belum mendapatkan email verifikasi ?</b></p>
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="input-group mb-1">
                            <div class="form-floating">
                                <input name="email" id="loginEmail" type="email"
                                    class="form-control @error('email') is-invalid @enderror" value="" required
                                    autofocus autocomplete="email" placeholder="Masukkan email anda ..." />
                                <label for="loginEmail">Email</label>
                            </div>
                            <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="bi bi-send-arrow-down-fill mx-3"></i> Meminta email reset password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection