@props([
    'prefix' => 'rfid',
    'required' => true,
])

@php
    $pinId = $prefix . '-pin-input';
@endphp

{{-- RFID PIN --}}
<div class="mb-3" id="{{ $prefix }}PinForm">
    <label for="{{ $pinId }}" class="form-label">PIN Kartu RFID</label>
    <input type="number" name="{{ $prefix }}_pin" id="{{ $pinId }}" class="form-control"
        placeholder="Masukkan PIN Kartu RFID" autocomplete="new-password" maxlength="6" inputmode="numeric"
        pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')"  />
    <div class="form-text text-danger">
        *Wajib diisi | Digunakan untuk verifikasi transaksi menggunakan kartu RFID.
    </div>
</div>
