@foreach ($students as $student)
<!-- Modal -->
    <div class="modal fade modal-animate" id="editModal_{{ $student->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('app.edit') }} Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('master.students.update', $student->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="text" name="user_id" id="user-id" value="{{ $student->userData->id }}" hidden>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="" class="form-label">Nama</label>
                                    <input type="text" name="name" class="form-control" id="name" value="{{ $student->userData->name }}"
                                        placeholder="Silahkan masukkan nama siswa" required autocomplete="off"/>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="email" value="{{ $student->userData->email }}"
                                        placeholder="Silahkan masukkan email" required autocomplete="off"/>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="" class="form-label">Password Baru Akun</label>
                                    <input type="password" name="password" class="form-control" id="password"
                                        placeholder="Masukkan password baru apabila ingin diubah" autocomplete="off"/>
                                    <div id="" class="form-text text-warning">
                                        <strong>Biarkan kosong apabila tidak ingin mengubah password.</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="" class="form-label">NISN</label>
                                    <input type="number" name="nisn" class="form-control" id="nisn" value="{{ $student->nisn }}"
                                        placeholder="Silahkan masukkan NISN" required autocomplete="off"/>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="" class="form-label">Kelas</label>
                                    <input type="text" name="kelas" class="form-control" id="kelas" value="{{ $student->kelas }}"
                                        placeholder="Silahkan masukkan kelas" required autocomplete="off"/>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="" class="form-label">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="l" {{ $student->userData->jenis_kelamin->value == 'l' ? 'selected' : '' }}>Laki - Laki</option>
                                        <option value="p" {{ $student->userData->jenis_kelamin->value == 'p' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="" class="form-label">Telepon</label>
                                    <input type="number" name="telepon" class="form-control" id="telepon" value="{{ $student->userData->telepon }}"
                                        placeholder="Silahkan masukkan nomor telepon" required autocomplete="off"/>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="" class="form-label">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="">Pilih Status</option>
                                        <option value="active" {{ $student->status == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" {{ $student->status == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="" class="form-label">Alamat</label>
                                    <textarea name="alamat" class="form-control" placeholder="Silahkan masukkan alamat kategori" required>{{ $student->userData->alamat }}</textarea>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('app.close') }}</button>
                        <button type="submit" class="btn btn-primary shadow-2">{{ __('app.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach