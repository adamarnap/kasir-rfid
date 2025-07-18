@foreach ($parents as $parent)
<!-- Modal Edit -->
    <div class="modal fade modal-animate" id="editModal_{{ $parent->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('app.edit') }} Wali Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('master.parents.update', $parent->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="text" name="user_id" value="{{ $parent->userData->id }}" hidden>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="" class="form-label">Nama</label>
                                    <input type="text" name="name" class="form-control" value="{{ $parent->userData->name }}"
                                        placeholder="Silahkan masukkan nama wali siswa" required autocomplete="off"/>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $parent->userData->email }}"
                                        placeholder="Silahkan masukkan email" required autocomplete="off"/>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="" class="form-label">Password Baru Akun</label>
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Masukkan password baru apabila ingin diubah" autocomplete="off"/>
                                    <div id="" class="form-text text-warning">
                                        <strong>Biarkan kosong apabila tidak ingin mengubah password.</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="" class="form-label">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="l" {{ $parent->userData->jenis_kelamin->value == 'l' ? 'selected' : '' }}>Laki - Laki</option>
                                        <option value="p" {{ $parent->userData->jenis_kelamin->value == 'p' ? 'selected' : '' }}>Perempuan</option>
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
                                            <option value="{{ $relationship->value }}" {{ $parent->relationship->value == $relationship->value ? 'selected' : '' }}>
                                                {{ $relationship->label() }}
                                            </option>
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
                                    <input type="number" name="telepon" class="form-control" value="{{ $parent->userData->telepon }}"
                                        placeholder="Silahkan masukkan nomor telepon" required autocomplete="off"/>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="" class="form-label">Alamat</label>
                                    <textarea name="alamat" class="form-control" placeholder="Silahkan masukkan alamat kategori" required>{{ $parent->userData->alamat }}</textarea>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="" class="form-label">Nama Siswa</label>
                                    <select name="student_id" class="form-select" id="select2_{{ $parent->id }}" required>
                                        <option value="">Pilih Siswa</option>
                                        @foreach ($students as $student)
                                            <option value="{{ $student->student_id }}" {{ $parent->studentAccount->student_id == $student->student_id ? 'selected' : '' }}>
                                                {{ $student->userData->name }}
                                            </option>
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
@endforeach