@extends('layouts.custom-template.main')

@section('title', 'Daftar')

@section('auth-content')
    <div class="register-box">
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <b><i class="bi bi-x-octagon"></i> Error :</b>
                <ul>
                    @error('name')
                        <li>{{ $message }}</li>
                    @enderror
                    @error('email')
                        <li>{{ $message }}</li>
                    @enderror
                    @error('password')
                        <li>{{ $message }}</li>
                    @enderror
                </ul>
            </div>
        @endif

        <!-- /.register-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h1 class="mb-0 text-center"><b>{{ $prefs_composer['title'] }}</b></h1>
            </div>
            <div class="card-body register-card-body">
                <p class="register-box-msg">Buat akun baru anda disini</p>
                <form action="{{ route('register.store') }}" method="POST">
                    @csrf
                    <div class="input-group mb-1 has-validation">
                        <div class="form-floating">
                            <input name="name" id="registerFullName" type="text"
                                class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                required autocomplete="name" placeholder="Masukkan nama anda disini ..." />
                            <label for="registerFullName">Nama</label>
                        </div>
                        <div class="input-group-text"><span class="bi bi-person"></span></div>
                    </div>
                    <div class="input-group mb-1 has-validation">
                        <div class="form-floating">
                            <input name="email" id="registerEmail" type="email"
                                class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                required autocomplete="email" placeholder="Masukkan email anda disini ..." />
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
                            <input name="password_confirmation" id="registerConfirmationPassword" type="password" class="form-control"
                                required autocomplete="new-password" placeholder="Konfirmasi kata sandi anda disini ..." />
                            <label for="registerConfirmationPassword">Konfirmasi Kata Sandi</label>
                        </div>
                        <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                    </div>
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-8 d-inline-flex align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required />
                                <label class="form-check-label" for="flexCheckDefault">
                                    <small><small>Saya menyetujui data tersebut adalah benar</small></small>
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Daftar</button>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!--end::Row-->
                </form>
                <div class="social-auth-links text-center mb-3 d-grid gap-2">
                    <p>- Atau -</p>
                    <a href="javascript:void(0);" class="btn btn-primary">
                        <i class="bi bi-facebook me-2"></i> Daftar menggunakan Facebook
                    </a>
                </div>
                <!-- /.social-auth-links -->
                <p class="mb-0">
                    <a href="{{ route('login') }}" class="link-primary text-center"> Saya sudah memiliki akun. </a>
                </p>
            </div>
            <!-- /.register-card-body -->
        </div>
    </div>
@endsection

@push('script')
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (() => {
            'use strict';

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation');

            // Loop over them and prevent submission
            Array.from(forms).forEach((form) => {
                form.addEventListener(
                    'submit',
                    (event) => {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }

                        form.classList.add('was-validated');
                    },
                    false,
                );
            });
        })();
    </script>
@endpush

{{-- 
<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}
