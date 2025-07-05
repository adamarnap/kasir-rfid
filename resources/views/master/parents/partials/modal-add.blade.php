    <!-- Modal -->
    <div class="select2ModalAdd modal fade modal-animate" id="addModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('app.add') }} Wali Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('master.parents.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="" class="form-label">Nama</label>
                                    <input type="text" name="name" class="form-control" id="name"
                                        placeholder="Silahkan masukkan nama wali siswa" required autocomplete="off"/>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="email"
                                        placeholder="Silahkan masukkan email" required autocomplete="off"/>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="" class="form-label">Password Akun</label>
                                    <input type="password" name="password" class="form-control" id="password"
                                        placeholder="Silahkan masukkan password" required autocomplete="off" value="1234"/>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi. | Password default: <strong>1234</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="" class="form-label">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="l">Laki - Laki</option>
                                        <option value="p">Perempuan</option>
                                    </select>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="" class="form-label">Hubungan</label>
                                    <select name="relationship" class="form-select" required>
                                        <option value="">Pilih Hubungan</option>
                                        @foreach (\App\Enums\ParentRelationshipEnum::cases() as $relationship)
                                            <option value="{{ $relationship->value }}">{{ $relationship->label() }}</option>
                                        @endforeach
                                    </select>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="" class="form-label">Telepon</label>
                                    <input type="number" name="telepon" class="form-control" id="telepon"
                                        placeholder="Silahkan masukkan nomor telepon" required autocomplete="off"/>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="" class="form-label">Alamat Wali Siswa</label>
                                    <textarea name="alamat" class="form-control" placeholder="Silahkan masukkan alamat siswa" required></textarea>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="" class="form-label">Pilih Siswa</label>
                                    <select name="student_id" class="select2 form-select" required>
                                        <option value="">Pilih Siswa</option>
                                        @foreach ($students as $student)
                                            <option value="{{ $student->userData->id }}">{{ $student->userData->name }} - ({{ $student->nisn }})</option>
                                        @endforeach
                                    </select>
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
