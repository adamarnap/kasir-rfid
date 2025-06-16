<!-- Modal -->
@foreach ($users as $user)
    <div class="modal fade modal-animate" id="editModal_{{ $user->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('app.edit') }} Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form method="POST" action="{{ route('users.update', $user->id ?? '') }}">
                    @method('PUT')
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="" class="form-label">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" id="name_{{ $user->id }}"
                                        placeholder="Silahkan masukkan nama lengkap pengguna" required />
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" id="email_{{ $user->id }}"
                                        aria-describedby="emailHelp_{{ $user->id }}" placeholder="Silahkan masukkan email pengguna"
                                        required readonly/>
                                    <div id="emailHelp_{{ $user->id }}" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Peran</label>
                                    <select name="role" class="form-select" id="peranPengguna_{{ $user->id }}" required>
                                        <option value="">- Pilih peran pengguna -</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role }}" {{ in_array($role, $user->roles->pluck('name')->toArray() ?? []) ? 'selected' : '' }}>{{ ucwords($role) }}</option>
                                        @endforeach
                                    </select>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                                <hr>
                                <p class="text-center">Ubah Kata Sandi</p>
                                <div class="mb-3">
                                    <label for="" class="form-label">Kata Sandi Baru</label>
                                    <input type="password" name="password" class="form-control" id="password_{{ $user->id }}"
                                        placeholder="Silahkan masukkan kata sandi baru pengguna" autocomplete="new-password"/>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Konfirmasi Kata Sandi Baru</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        id="password_confirmation_{{ $user->id }}" placeholder="Konfirmasikan kata sandi baru pengguna" autocomplete="off"/>
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
@endforeach
