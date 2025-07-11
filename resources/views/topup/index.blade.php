@extends('layouts.custom-template.main')

@section('title', 'Top Up Kartu RFID')

@section('breadcrumb')
    {{ Breadcrumbs::render('topup') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-lg-4"></div>
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class=" text-center fw-bold">
                        <i class="bi bi-credit-card-2-front"></i>&nbsp; Top Up Kartu RFID
                    </h3>
                </div>
                <div class="card-body">                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="amount" class="form-label text-center">Jumlah Top Up</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Rp.</div>
                                </div>
                                <input type="number" class="form-control attach-keypad" id="amount" name="amount" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">,00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <x-number-keypad />
                    </div>
                    <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#scanRFIDModal" id="scan-rfid-button">
                        <strong>
                            <i class="bi bi-credit-card-2-front"></i>&nbsp;
                            Top Up Kartu RFID
                        </strong>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4"></div>
    </div>

    {{-- Include Modal To Scan RFID Number --}}
    @include('topup.partials.modal-scan-rfid-card')
@endsection

@push('scripts')
    <script>
        // Auto filled amount to the hidden input field when the modal is opened
        document.addEventListener('DOMContentLoaded', function() {
            const scanRfidModal = document.getElementById('scanRFIDModal');
            scanRfidModal.addEventListener('shown.bs.modal', function() {
                const amountInput = document.getElementById('amount');
                const hiddenAmountInput = document.getElementById('hidden-amount');
                hiddenAmountInput.value = amountInput.value; // Set the value of hidden input to the amount input
            });

            // Handle the amount input to ensure it is a valid number
            const amountInput = document.getElementById('amount');
            amountInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, ''); // Allow only numbers
            });
        });
    </script>
@endpush

