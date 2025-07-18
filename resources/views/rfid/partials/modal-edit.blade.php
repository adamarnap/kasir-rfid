@foreach ($cards as $card)
    <!-- Modal Edit-->
    <div class="select2ModalEdit modal fade modal-animate" id="editModal_{{ $card->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('app.edit') }} Kartu RFID</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('rfid.update', $card->id) }}" method="POST" id="editRfidForm" autocomplete="off">
                    <div class="modal-body">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12">
                                {{-- Student Selection --}}
                                <div class="mb-2">
                                    <label for="student_id" class="form-label">Pilih Siswa</label>
                                    <select name="student_id" id="student_id" class="form-select" disabled>
                                        {{-- Disabled to prevent changes --}}
                                        <option value="">Pilih Siswa</option>
                                        @foreach ($students as $student)
                                            <option value="{{ $student->student_id }}" {{ $card->student_id == $student->student_id ? 'selected' : '' }}>
                                                {{ $student->userData->name ?? '-' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text text-danger">
                                        *Wajib diisi
                                    </div>
                                </div>
                                <div class="card p-1">
                                    <div class="card-body">
                                        <div class="alert alert-warning" role="alert">
                                            <h5 class="text-center text-danger">
                                                <strong>
                                                    <i class="bi bi-exclamation-triangle-fill"></i><br>
                                                    Abaikan pengisian pada PIN dan Nomor Kartu jika tidak ingin mengubah PIN kartu RFID.
                                                </strong>
                                            </h5>
                                        </div>

                                        {{-- RFID PIN --}}
                                        <x-rfid-form.rfid-pin prefix="rfid" />
                                        {{-- RFID Number --}}
                                        <x-rfid-form.rfid-number prefix="rfid" />
                                    </div>
                                </div>
                                
                                {{-- Status --}}
                                <div class="mb-2">
                                    <label for="status" class="form-label">Status Kartu</label>
                                    <select name="status" id="status" class="form-select" required>
                                        <option value="active" {{ $card->status == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" {{ $card->status == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                    <div class="form-text text-danger">
                                        *Wajib diisi
                                    </div>
                                </div>
                                {{-- Description --}}
                                <div class="mb-2">
                                    <label for="description" class="form-label">Keterangan</label>
                                    <textarea name="description" id="description" class="form-control"
                                        placeholder="Masukkan keterangan tambahan (opsional)">
                                        {{ $card->description ?? '' }}
                                    </textarea>
                                    <div class="form-text text-muted">
                                        Keterangan tambahan untuk kartu RFID ini.
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
@endforeach

@push('scripts')
    <script>
    </script>
@endpush

