@extends('layouts.custom-template.main')

@section('title', 'Profile Saya')

@section('breadcrumb')
    {{ Breadcrumbs::render('profile') }}
@endsection

@section('content')
    <div class="row">
        {{-- Update General Info Form --}}
        <div class="col-7">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h5 class="float-start fw-bold">Informasi Profile Pribadi</h5>
                            <button type="submit" class="btn btn-sm btn-warning float-end fw-bold"
                                onclick="submitFormGeneralInformation()"><i class="bi bi-floppy me-2"></i>
                                {{ __('app.save') }} Update Informasi Profile</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6"
                        id="generalInformationForm" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class="row mb-3">
                            {{-- Foto Profile --}}
                            @php
                                if($user->userProfile->profile_photo != null) {
                                    $profile_photo = URL::asset('storage/' . $user->userProfile->profile_photo);
                                } else {
                                    $profile_photo = URL::asset('storage/lte/assets/img/default_photo_profile.png');
                                }
                            @endphp
                            <div class="col-3 text-center">
                                <img id="profilePreview" src="{{ $profile_photo }}"
                                    class="rounded-circle img-fluid mb-2" alt="Photo Profile"
                                    style="width: 100px; height: 100px; object-fit: cover; border: 2px solid #ffca2c;">
                                <!-- Role User -->
                                <span class="badge bg-warning text-dark rounded-pill mb-2">{{ ucwords(implode(', ', $user->roles->pluck('name')->toArray())) }}</span>
                                
                                <!-- Input File untuk Upload Foto -->
                                <label class="btn btn-outline-warning btn-sm w-100 rounded-5">
                                    <input type="file" name="profile_photo" id="profileInput" class="d-none"
                                        accept="image/*"><i class="bi bi-camera"></i>
                                    Ganti Foto
                                </label>
                            </div>

                            <div class="col-9">
                                <div class="row">
                                    <div class="col">
                                        <label for="" class="col-form-label">Nama Lengkap</label>
                                        <div class="col-sm-10">
                                            <input name="name" type="text" class="form-control"
                                                value="{{ $user->name }}" id="" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="" class="col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input name="email" type="email" class="form-control"
                                                value="{{ $user->email }}" id="" required readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Update Password Form --}}
        <div class="col-5">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="float-start fw-bold">Ganti Kata Sandi Akun</h5>
                            <button type="submit" class="btn btn-sm btn-danger text-white float-end fw-bold"
                                onclick="submitFormUpdatePassword()"><i class="bi bi-key-fill me-2"></i>
                                {{ __('app.save') }} Update Kata
                                Sandi</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post" id="updatePasswordForm" action="{{ route('password.update') }}"
                        class="mt-6 space-y-6">
                        @csrf
                        @method('put')
                        <div class="row">
                            <div class="col">
                                <label for="" class="col-form-label">Kata Sandi Saat Ini</label>
                                <div class="">
                                    <input name="current_password" type="password" class="form-control"
                                        placeholder="Kata sandi anda saat ini ..." id="current_password" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="" class="col-form-label">Kata Sandi Baru</label>
                                <div class="">
                                    <input name="password" type="password" class="form-control" id="update_password"
                                        placeholder="Masukkan kata sandi baru ..." required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="" class="col-form-label">Konfirmasi Kata Sandi Baru</label>
                                <div class="">
                                    <input name="password_confirmation" type="password" class="form-control"
                                        id="update_password_password_confirmation"
                                        placeholder="Konfirmasi kata sandi baru ..." required />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="float-start fw-bold">Penghapusan Akun</h5>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#deleteAccountModal"
                                class="btn btn-sm btn-danger text-white float-end fw-bold"><i
                                    class="bi bi-trash-fill me-2"></i> {{ __('app.delete') }} Akun Anda</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete Account -->
    <div class="modal fade modal-animate" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="text-center">
                        <h5 class="modal-title fw-bold text-danger">
                            {{ __('Are you sure you want to delete your account?') }}</h5>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <label for="" class="col-form-label fw-bold">Masukkan Kata Sandi Anda</label>
                                <div class="">
                                    <input name="password" type="password" class="form-control" id="update_password"
                                        placeholder="Masukkan kata sandi anda sebagai konfirmasi penghapusan akun anda ..."
                                        required />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">{{ __('app.close') }}</button>
                        <button type="submit" class="btn btn-danger shadow-2 fw-bold text-warning"><i
                                class="bi bi-trash-fill me-2"></i> Iya, {{ __('app.delete') }} Akun Saya</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function submitFormGeneralInformation() {
            let form = document.getElementById('generalInformationForm');

            // Cek validitas form sebelum menjalankan SweetAlert
            if (!form.checkValidity()) {
                form.reportValidity(); // Menampilkan error bawaan HTML
                return;
            }

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda ingin memperbarui informasi profile anda?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, perbarui!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Jika valid, kirim form
                }
            });
        }

        function submitFormUpdatePassword() {
            let form = document.getElementById('updatePasswordForm');

            // Cek validitas form sebelum menjalankan SweetAlert
            if (!form.checkValidity()) {
                form.reportValidity(); // Menampilkan error bawaan HTML
                return;
            }

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda ingin memperbarui kata sandi?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, perbarui!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Jika valid, kirim form
                }
            });
        }
    </script>

    <script>
        document.getElementById('profileInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush







{{-- 
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}
