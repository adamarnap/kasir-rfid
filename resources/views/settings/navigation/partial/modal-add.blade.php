    <!-- Modal -->
    <div class="modal fade modal-animate" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('app.add') }} Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form method="POST" action="{{ route('navs.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-2">
                                    <label class="form-label" for="namaMenu">Induk Menu</label>
                                    <div class="col-lg-12 col-md-11 col-sm-12">
                                        <select class="form-control" data-trigger name="parent_id" id="choices-single-default">
                                            <option value="">Pilih induk menu</option>
                                            @if ($navigations)
                                            @foreach ($navigations as $navigationOption)
                                            <option value="{{ $navigationOption->id }}">{{ $navigationOption->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="" class="form-label">Nama Menu</label>
                                    <input type="text" name="name" class="form-control" id="name"
                                        placeholder="Silahkan masukkan nama menu" required autocomplete="off"/>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label class="form-label" for="slugMenu">Pengidentifikasi Hak Akses</label>
                                    <input type="text" name="slug" class="form-control" id="slugMenu"
                                        aria-describedby="" placeholder="Masukkan slug menu baru" required>
                                </div>
                                <div id="" class="form-text text-danger">
                                    *Wajib diisi.
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label class="form-label" for="urlMenu">URL Route</label>
                                    <input type="text" name="url" class="form-control" id="urlMenu"
                                        aria-describedby="" placeholder="Masukkan url menu baru" required>
                                </div>
                                <div id="" class="form-text text-danger">
                                    *Wajib diisi.
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label class="form-label" for="order">No. Urut Menu</label>
                                    <input type="number" name="order" class="form-control" id="order"
                                        aria-describedby="" placeholder="Masukkan no. urut menu baru" required>
                                </div>
                                <div id="" class="form-text text-danger">
                                    *Wajib diisi.
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label class="form-label" for="iconMenu">Icon Menu</label>
                                    <input type="text" name="icon" class="form-control" id="iconMenu"
                                        aria-describedby="" placeholder="Masukkan icon menu baru">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check form-switch custom-switch-v1 mb-2">
                                    <input class="form-check-input" type="checkbox" name="active" role="switch" value="1" checked data-style="slow">
                                    <label class="form-check-label" for="">Status Aktif Menu</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check form-switch custom-switch-v1 mb-2">
                                    <input class="form-check-input" type="checkbox" name="display" role="switch" value="1" checked data-style="slow">
                                    <label class="form-check-label" for="">Menu ditampilkan ?</label>
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
