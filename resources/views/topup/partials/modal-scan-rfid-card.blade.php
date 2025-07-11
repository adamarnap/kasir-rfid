<!-- Modal Scan RFID-->
<div class="modal fade modal-animate" id="scanRFIDModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Kartu RFID</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <form action="{{ route('topup.store') }}" method="POST" id="addRfidForm" autocomplete="off">
                <div class="modal-body">
                    @csrf
                    <input type="text" name="amount" id="hidden-amount" class="d-none" value="{{ old('amount') }}">
                    <div class="row">
                        <div class="col-12">
                            {{-- RFID Number --}}
                            <x-rfid-form.rfid-number prefix="rfid" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">{{ __('app.close') }}</button>
                    <button type="submit" class="btn btn-primary shadow-2">
                        <strong>
                            <i class="bi bi-credit-card-2-front"></i>&nbsp;
                            Top Up Kartu RFID
                        </strong>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Focus on the RFID Number input when the modal is opened
        document.addEventListener('DOMContentLoaded', function() {
            const scanRfidModal = document.getElementById('scanRFIDModal');
            scanRfidModal.addEventListener('shown.bs.modal', function() {
                const rfidNumberInput = document.getElementById('rfid-number-input');
                rfidNumberInput.focus();
            });

            // Handle the amount input to ensure it is a valid number
            const amountInput = document.getElementById('amount');
            amountInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, ''); // Allow only numbers
            });
        });
    </script>
@endpush

