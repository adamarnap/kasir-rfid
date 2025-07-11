@props([
    'prefix' => 'rfid',
    'required' => true,
])

@php
    $rfidId = $prefix . '-number-input';
@endphp

{{-- RFID Number --}}
<div class="mb-3" id="{{ $prefix }}NumberForm">
    <div class="row">
        <div class="col-2">
            <i class="bi bi-upc-scan" style="font-size: 4rem;"></i>
        </div>
        <div class="col-10">
            <label for="{{ $rfidId }}" class="form-label">Nomor Kartu RFID</label>
            <input type="text" name="{{ $prefix }}_number" id="{{ $rfidId }}" class="form-control"
                placeholder="Scan atau ketikkan nomor kartu RFID" autocomplete="new-username" maxlength="10" inputmode="numeric"
                pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')"/>
            <div class="form-text text-danger">
                *Wajib diisi | <strong>Scan kartu RFID atau input manual</strong>.
            </div>
        </div>
    </div>
</div>