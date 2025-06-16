@extends('layouts.custom-template.main')

@section('title', 'Ganti Kata Sandi')

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
                    Atur ulang kata sandi Anda disini
                </p>
                <div class="social-auth-links text-center mb-3 d-grid gap-2">
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf
                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                        <div class="input-group mb-1 has-validation">
                            <div class="form-floating">
                                <input name="email" id="registerEmail" type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $request->email) }}" required autocomplete="email"
                                    placeholder="Masukkan email anda disini ..." />
                                <label for="registerEmail">Email</label>
                            </div>
                            <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                        </div>
                        <div class="input-group mb-1 has-validation">
                            <div class="form-floating">
                                <input name="password" id="registerPassword" type="password"
                                    class="form-control @error('password') is-invalid @enderror" required
                                    autocomplete="new-password" placeholder="Masukkan kata sandi anda disini ..." />
                                <label for="registerPassword">Kata Sandi</label>
                            </div>
                            <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                        </div>
                        <div class="input-group mb-1 has-validation">
                            <div class="form-floating">
                                <input name="password_confirmation" id="registerConfirmationPassword" type="password"
                                    class="form-control" required autocomplete="new-password"
                                    placeholder="Konfirmasi kata sandi anda disini ..." />
                                <label for="registerConfirmationPassword">Konfirmasi Kata Sandi</label>
                            </div>
                            <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="bi bi-lock-fill"></i> Atur Ulang Kata Sandi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection