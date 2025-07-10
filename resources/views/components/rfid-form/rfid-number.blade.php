@props([
    'prefix' => 'rfid',
    'required' => true,
])

@php
    $rfidId = $prefix . '-number-input';
@endphp

{{-- RFID Number --}}
<div class="mb-3" id="{{ $prefix }}NumberForm">
    <label for="{{ $rfidId }}" class="form-label">Nomor Kartu RFID</label>
    <input type="number" name="{{ $prefix }}_number" id="{{ $rfidId }}" class="form-control"
        placeholder="Scan atau ketikkan nomor kartu RFID" autocomplete="new-username" maxlength="10"/>
    <div class="form-text text-danger">
        *Wajib diisi | <strong>Scan kartu RFID atau input manual</strong>.
    </div>
</div>