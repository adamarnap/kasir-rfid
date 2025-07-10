<!-- Modal Input Pin -->
<div class="modal fade modal-animate" id="pinModal" aria-hidden="true">
    <div class="modal-dialog modal-m">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Masukkan PIN Katu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        {{-- RFID Number --}}
                        <x-rfid-form.rfid-pin prefix="rfid" />
                        
                        {{-- Numeric Keypad --}}
                        <x-number-keypad />
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer flex-column">
                <div class="text-muted text-center small mb-3">
                    * Pastikan semua produk sudah benar sebelum menyelesaikan transaksi.
                </div>
                <div class="row w-100">
                    <div class="col-6 pr-1">
                        <button type="button"
                            class="btn btn-outline-secondary btn-block d-flex align-items-center justify-content-center w-100"
                            data-dismiss="modal">
                            <i class="bi bi-x-circle mr-2"></i>&nbsp; {{ __('app.close') }}
                        </button>
                    </div>
                    <div class="col-6 pl-1">
                        <button type="button" id="done-button-pin"
                            class="btn btn-success btn-block shadow-2 d-flex align-items-center justify-content-center w-100">
                            <i class="bi bi-check2-circle"></i>&nbsp; Verifikasi & Bayar
                        </button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>

    
