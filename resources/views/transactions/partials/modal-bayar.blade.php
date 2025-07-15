<!-- Modal Bayar -->
    <div class="modal fade modal-animate" id="bayarModal" aria-hidden="true">
        <div class="modal-dialog modal-m">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Selesaikan Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('transactions.pay', $transactionId) }}" method="POST" autocomplete="off" id="bayarForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                {{-- Review total pembayaran --}}
                                <div class="mb-2 text-center">
                                    <div class="bg- text-success py-4 px-3 mb-4 rounded" style="border: 2px solid #28a745;">
                                        <h1 class="display-3 font-weight-bold m-0">
                                            Rp {{ number_format((float) rsa_decrypt($chart->total_amount) ?? 0, 0, '.', ',') }}
                                        </h1>
                                    </div>
                                </div>
                                {{-- Metode Pembayaran --}}
                                <div class="mb-2">
                                    <label for="" class="form-label">Metode Pembayaran</label>
                                    <select name="payment_method" class="form-select" id="payment-method-selector">
                                        <option value="" disabled selected>Pilih Metode Pembayaran</option>
                                        <option value="rfid" selected>Kartu</option>
                                        <option value="cash">Tunai</option>
                                    </select>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi
                                    </div>
                                </div>
                                {{-- RFID Number --}}
                                <x-rfid-form.rfid-number prefix="rfid" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer flex-column">
                        <div class="text-muted text-center small mb-3">
                            * Pastikan semua produk sudah benar sebelum menyelesaikan transaksi.
                        </div>
                        <div class="row w-100">
                            <div class="col-6 pr-1">
                                <button type="button" class="btn btn-outline-secondary btn-block d-flex align-items-center justify-content-center w-100" data-dismiss="modal">
                                    <i class="bi bi-x-circle mr-2"></i>&nbsp; {{ __('app.close') }}
                                </button>
                            </div>
                            <div class="col-6 pl-1">
                                <button type="button" id="done-button-rfid" class="btn btn-success btn-block shadow-2 d-flex align-items-center justify-content-center w-100">
                                    <i class="bi bi-check2-circle"></i>&nbsp; Selesaikan Transaksi
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Form
        const form = document.getElementById('bayarForm');
        // Initialize Payment Method Selector
        const paymentMethodSelect = document.getElementById('payment-method-selector');
        // Initialize Forms for RFID Number and PIN
        const rfidNumberForm = document.getElementById('rfidNumberForm');
        // Initialize Input Form for RFID PIN and Number
        const rfidNumberInput = document.getElementById('rfid-number-input');
        const rfidPinInput = document.getElementById('rfid-pin-input');
        // Initialize label for submit button
        const labelDoneButton = document.getElementById('label-done-button');
        // Initialize Submit Button In Modal Bayar
        const doneButtonRfid = document.getElementById('done-button-rfid');
        // Initialize Submit Button in Modal PIN
        const doneButtonPin = document.getElementById('done-button-pin');
        

        
        // Show/Hide RFID number field based on payment method selection
        paymentMethodSelect.addEventListener('change', function () {
            if (this.value === 'rfid') {
                rfidNumberForm.style.display = 'block';
                rfidPinInput.style.display = 'block';
                // required attribute for RFID Number & PIN input
                rfidNumberInput.setAttribute('required', 'required');
                rfidPinInput.setAttribute('required', 'required');                
            } else {
                rfidNumberForm.style.display = 'none';
                // remove required attribute for RFID Number & PIN input
                rfidNumberInput.removeAttribute('required');
                rfidPinInput.removeAttribute('required');
                rfidNumberInput.value = ''; // Clear input if not using RFID
                rfidPinInput.value = ''; // Clear PIN input if not using RFID
            }
        });

        // Focus on RFID input when modal is opened
        const bayarModal = document.getElementById('bayarModal');
        bayarModal.addEventListener('shown.bs.modal', function () {
            const rfidNumberInput = document.getElementById('rfid-number-input');
            if (paymentMethodSelect.value === 'rfid') {
                rfidNumberInput.focus();

                // Handle done button click for RFID payment method
                if (!window.rfidListenerInitialized) {
                handleDoneButtonClickForRfidPaymentMethod();
                window.rfidListenerInitialized = true;
            }
            } else {
                rfidNumberInput.value = ''; // Clear input if not using RFID
                rfidPinInput.value = ''; // Clear PIN input if not using RFID
            }
        });

        function handleDoneButtonClickForRfidPaymentMethod() {
            // Step 1: When RFID Card scanned or number entered, show PIN modal
            doneButtonRfid.addEventListener('click', function () {
                // Jika metode pembayaran adalah RFID, tampilkan modal PIN. Jika metode pembayaran cash, langsung kirim form
                if (paymentMethodSelect.value !== 'rfid') {
                    form.submit(); // Submit form if not using RFID
                    return;
                }
                // Tutup modal pertama
                const bayarModalInstance = bootstrap.Modal.getInstance(bayarModal);
                bayarModalInstance.hide();

                // Buka modal PIN
                const pinModal = new bootstrap.Modal(document.getElementById('pinModal'));
                pinModal.show();

                setTimeout(() => rfidPinInput.focus(), 300);
            });
            
            // Step 2: Event Listener when RFID PIN input entered (this for modal PIN)
            rfidPinInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    doneButtonPin.click();
                }
            });

            // Step 3: When done button in PIN modal is clicked, submit the form
            doneButtonPin.addEventListener('click', function () {
                const pin = rfidPinInput.value.trim();

                // Inject ke form utama sebagai input hidden
                let hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'rfid_pin';
                hidden.value = pin;
                form.appendChild(hidden);

                form.submit();
            });

            // Step 4: Event Listener when RFID Number input entered (this for modal bayar)
            rfidNumberInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    doneButtonRfid.click();
                }
            });
        }

    });
</script>
    
@endpush