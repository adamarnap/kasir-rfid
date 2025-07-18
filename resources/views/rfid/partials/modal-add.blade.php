<!-- Modal Add-->
<div class="select2ModalAdd modal fade modal-animate" id="addModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('app.add') }} Kartu RFID</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <form action="{{ route('rfid.store') }}" method="POST" id="addRfidForm" autocomplete="off">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            {{-- Student Selection --}}
                            <div class="mb-2">
                                <label for="student_id" class="form-label">Pilih Siswa</label>
                                <select name="student_id" id="student_id" class="form-select select2" required>
                                    <option value="" disabled selected>Pilih Siswa</option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->student_id }}">
                                            {{ $student->userData->name ?? '-' }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text text-danger">
                                    *Wajib diisi
                                </div>
                            </div>
                            {{-- RFID PIN --}}
                            <x-rfid-form.rfid-pin prefix="rfid" />
                            {{-- RFID Number --}}
                            <x-rfid-form.rfid-number prefix="rfid" />
                            {{-- Status --}}
                            <div class="mb-2">
                                <label for="status" class="form-label">Status Kartu</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Tidak Aktif</option>
                                </select>
                                <div class="form-text text-danger">
                                    *Wajib diisi
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">{{ __('app.close') }}</button>
                    <button type="submit" class="btn btn-primary shadow-2">{{ __('app.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Initialize Input Form for RFID PIN and Number
        const rfidNumberInput = document.getElementById('rfid-number-input');
        const rfidPinInput = document.getElementById('rfid-pin-input');
        // required attribute for RFID Number & PIN input
        rfidNumberInput.setAttribute('required', 'required');
        rfidPinInput.setAttribute('required', 'required'); 
    </script>
@endpush

