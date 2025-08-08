@extends('layouts.custom-template.main')

@section('title', 'Cek Saldo')

@section('breadcrumb')
    {{ Breadcrumbs::render('balance') }}
@endsection

@push('css')
    <style>
        .numeric-keypad {
            max-width: 300px;
            margin: 0 auto;
        }

        .pin-number,
        #clear-pin,
        #clear-all-pin {
            height: 60px;
            font-size: 1.5rem;
            font-weight: bold;
            border-radius: 10px;
            transition: all 0.15s ease;
        }

        .pin-number:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .pin-number:active {
            transform: translateY(0);
        }

        #clear-pin:hover,
        #clear-all-pin:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12 col-lg-2"></div>
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header bg-dark">
                    <h3 class=" text-center text-white fw-bold">
                        <i class="bi bi-cash-stack"></i>&nbsp; Cek Saldo Kartu RFID
                    </h3>
                </div>
                <div class="card-body">                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            {{-- RFID Number --}}
                            <x-rfid-form.rfid-number prefix="rfid" />
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="mt-4">
                            <div class="numeric-keypad">
                                <div class="row g-2">
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary btn-lg w-100 pin-number" data-number="1">1</button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary btn-lg w-100 pin-number" data-number="2">2</button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary btn-lg w-100 pin-number"
                                            data-number="3">3</button>
                                    </div>
                                </div>
                                <div class="row g-2 mt-2">
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary btn-lg w-100 pin-number"
                                            data-number="4">4</button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary btn-lg w-100 pin-number"
                                            data-number="5">5</button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary btn-lg w-100 pin-number"
                                            data-number="6">6</button>
                                    </div>
                                </div>
                                <div class="row g-2 mt-2">
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary btn-lg w-100 pin-number"
                                            data-number="7">7</button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary btn-lg w-100 pin-number"
                                            data-number="8">8</button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary btn-lg w-100 pin-number"
                                            data-number="9">9</button>
                                    </div>
                                </div>
                                <div class="row g-2 mt-2">
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-secondary btn-lg w-100" id="clear-pin">
                                            <i class="bi bi-backspace"></i>
                                        </button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-primary btn-lg w-100 pin-number"
                                            data-number="0">0</button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-danger btn-lg w-100" id="clear-all-pin">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center" id="scan-rfid-button">
                        <strong>
                            <i class="bi bi-cash-stack"></i>&nbsp;
                            Cek Saldo Kartu RFID
                        </strong>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            {{-- Start total transaction --}}
            <div class="card shadow">
                <div class="card-header bg-dark text-white text-center">
                    <h3 class=" text-center fw-bold">
                        <i class="bi bi-cash-stack"></i>&nbsp; Nominal Saldo Kartu RFID
                    </h3>
                </div>
                {{-- Display the total amount --}}
                <div class="card-body text-center">
                    {{-- Kotak harga seperti layar kalkulator --}}
                    <div class="bg- text-success py-4 px-3 mb-4 rounded" style="border: 2px solid #28a745;">
                        <h1 class="display-3 font-weight-bold m-0" id="balance-amount">
                            <span id="balance-amount-value">Rp 0.00</span>
                        </h1>
                    </div>
                    {{-- Nama Siswa --}}
                    <h5 class="text-muted">
                        <i class="bi bi-person-circle"></i>&nbsp; Nama Siswa:
                    </h5>
                    {{-- Display student name --}}
                    <h5><span id="balance-student-name">-</span></h5>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-2"></div>
    </div>
@endsection

@push('scripts')
    <script>
        // Initialize the RFID number input and button
        const rfidNumberInput = document.getElementById('rfid-number-input');
        const btnSubmitCheckRfid = document.getElementById('scan-rfid-button');
        
        // Focus on RFID input
        rfidNumberInput.focus();

        // Event Listener when RFID Number input entered (this for modal bayar)
        rfidNumberInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                btnSubmitCheckRfid.click();
            }
        });

        // Event Listener for the button to check RFID balance (Via AJAX)
        btnSubmitCheckRfid.addEventListener('click', function () {
            const rfidNumber = rfidNumberInput.value.trim();
            if (rfidNumber === '') {
                alert('Mohon masukkan nomor RFID terlebih dahulu.');
                return;
            }
            // Make an AJAX request to check the balance
            $.ajax({
                url: '/balance/' + encodeURIComponent(rfidNumber), // atau pakai route helper via Blade
                type: 'GET',
                success: function (response) {
                    // Update the balance amount and student name
                    document.getElementById('balance-amount-value').textContent = 'Rp ' + response.balance.toLocaleString('id-ID');
                    document.getElementById('balance-student-name').textContent = response.student_name || 'Nama Siswa Tidak Ditemukan';
                },
                error: function (xhr) {
                    // Swal Alert jika terjadi error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal mendapatkan data saldo. Pastikan nomor RFID benar.',
                        confirmButtonText: 'OK',
                    });
                }
            });
        });

    </script>

    {{-- Start Keypad --}}
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the PIN input field
            const formInputAttachedKeypad = document.querySelector('#rfid-number-input');

            // Get all number buttons
            const numberButtons = document.querySelectorAll('.pin-number');
            const clearButton = document.getElementById('clear-pin');
            const clearAllButton = document.getElementById('clear-all-pin');

            // Add click event to number buttons
            numberButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const number = this.getAttribute('data-number');

                    // Add number to input 
                    
                    formInputAttachedKeypad.value += number;

                    // Add visual feedback
                    this.classList.add('btn-primary');
                    this.classList.remove('btn-outline-primary');

                    setTimeout(() => {
                        this.classList.remove('btn-primary');
                        this.classList.add('btn-outline-primary');
                    }, 150);

                    // Focus back to input
                    formInputAttachedKeypad.focus();
                });
            });

            // Clear last digit (backspace)
            clearButton.addEventListener('click', function() {
                if (formInputAttachedKeypad && formInputAttachedKeypad.value.length > 0) {
                    formInputAttachedKeypad.value = formInputAttachedKeypad.value.slice(0, -1);
                }
                formInputAttachedKeypad.focus();
            });

            // Clear all digits
            clearAllButton.addEventListener('click', function() {
                if (formInputAttachedKeypad) {
                    formInputAttachedKeypad.value = '';
                }
                formInputAttachedKeypad.focus();
            });
        });
    </script>
    {{-- End Keypad --}}
@endpush

