    <!-- Modal -->
    <div class="modal fade modal-animate" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('app.add') }} Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="" class="form-label">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control" id="name"
                                        placeholder="Silahkan masukkan nama lengkap pengguna" required autocomplete="off"/>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="email"
                                        aria-describedby="emailHelp" placeholder="Silahkan masukkan email pengguna"
                                        required autocomplete="off"/>
                                    <div id="emailHelp" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Peran</label>
                                    <select name="role" class="form-select" id="peranPengguna" required>
                                        <option value="">- Pilih peran pengguna -</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role }}">{{ ucwords($role) }}</option>
                                        @endforeach
                                    </select>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Kata Sandi</label>
                                    <input type="password" name="password" class="form-control" id="password"
                                        placeholder="Silahkan masukkan kata sandi pengguna" autocomplete="new-password" required/>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Konfirmasi Kata Sandi</label>
                                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation"
                                        placeholder="Konfirmasikan kata sandi pengguna" required />
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
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
