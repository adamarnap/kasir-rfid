@extends('layouts.custom-template.main')

@section('title', 'Masuk')

@section('auth-content')
    <div class="login-box">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <b><i class="bi bi-x-octagon"></i> Error :</b>
                <ul>
                    @error('email')
                        <li>{{ $message }}</li>
                    @enderror
                    @error('password')
                        <li>{{ $message }}</li>
                    @enderror
                </ul>
            </div>
        @endif

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h1 class="mb-0 text-center"><b>{{ $prefs_composer['title'] }}</b></h1>
            </div>
            <div class="card-body login-card-body">
                <p class="login-box-msg">Silahkan masuk dengan akun yang telah anda buat.</p>
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="input-group mb-1">
                        <div class="form-floating">
                            <input name="email" id="loginEmail" type="email"
                                class="form-control @error('email') is-invalid @enderror" value="" required
                                autocomplete="email" placeholder="Masukkan email anda ..." />
                            <label for="loginEmail">Email</label>
                        </div>
                        <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                    </div>
                    <div class="input-group mb-1">
                        <div class="form-floating">
                            <input name="password" id="loginPassword" type="password"
                                class="form-control @error('password') is-invalid @enderror" required
                                autocomplete="password" placeholder="Masukkan katasandi anda ..." />
                            <label for="loginPassword">Kata Sandi</label>
                        </div>
                        <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                    </div>
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-8 d-inline-flex align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" required autocomplete="email"
                                    id="flexCheckDefault" />
                                <label class="form-check-label" for="flexCheckDefault"> Ingat saya </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Masuk</button>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!--end::Row-->
                </form>
                <div class="social-auth-links text-center mb-3 d-grid gap-2">
                    <p>- Atau -</p>
                    <a href="javascript:void(0)" class="btn btn-primary">
                        <i class="bi bi-facebook me-2"></i> Masuk menggunakan Facebook
                    </a>
                </div>
                <!-- /.social-auth-links -->
                <p class="mb-1"><a href="{{ route('password.request') }}">Lupa kata sandi</a></p>
                <p class="mb-0">
                    <a href="{{ route('register') }}" class="text-center">Belum punya akun?. Daftar disini.</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
@endsection
