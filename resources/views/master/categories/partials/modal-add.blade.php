    <!-- Modal -->
    <div class="modal fade modal-animate" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('app.add') }} Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('master.categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="" class="form-label">Nama</label>
                                    <input type="text" name="name" class="form-control" id="name"
                                        placeholder="Silahkan masukkan nama kategori" required autocomplete="off"/>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="" class="form-label">Deskripsi</label>
                                    <textarea name="description" class="form-control" placeholder="Silahkan masukkan deskripsi kategori" required></textarea>
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
