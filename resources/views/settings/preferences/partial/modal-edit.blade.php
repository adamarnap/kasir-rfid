<!-- Modal -->
@foreach ($preferences as $pref)
    <div class="modal fade modal-animate" id="editPreferencesModal_{{ $pref->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('app.edit') }} Preferensi Asset</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form method="POST" action="{{ route('preferences.update', $pref->id ?? '') }}" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    {{-- Hidden input --}}
                    @if ($pref->is_asset)
                        <input type="hidden" name="is_asset" value="1">
                    @else
                        <input type="hidden" name="is_asset" value="0">
                    @endif
                    {{-- End Hidden Input --}}
                    <div class="modal-body">
                        @if ($pref->is_asset)
                            @if (!empty($pref->value))
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <img src="{{ Storage::url($pref->path . '/' . $pref->value) }}"
                                            class="img-fluid mb-3" alt="View Static Asset" />
                                        <div class="text-muted">
                                            <p>Fig. {{ $pref->value }}</p>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @endif
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="" class="form-label">Nama Preferensi</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ $pref->name }}" id="name_{{ $pref->id }}"
                                        placeholder="Silahkan masukkan nama Preferensi" required />
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            @if ($pref->is_asset)
                            @php
                                $path = explode('/', $pref->value);
                                $fileName = end($path);
                                // Remove file name from path
                                $path = rtrim(str_replace($fileName, '', $pref->value), '/');
                            @endphp
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="" class="form-label">Path</label>
                                    <input name="path" class="form-control" id="value_{{ $pref->id }}"
                                        value="{{ $path }}" placeholder="Silahkan masukkan path disini"
                                        required>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2">
                                    <label for="" class="form-label">File</label>
                                    <div class="input-group mb-3">
                                        <input type="file" name="file_asset" class="form-control"
                                            id="inputGroupFile02" />
                                        <label class="input-group-text" for="inputGroupFile02">Upload</label>
                                    </div>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="" class="form-label">Value</label>
                                    <textarea name="value" class="form-control" id="value_{{ $pref->id }}" placeholder="Silahkan masukkan value disini" required>{{ $pref->value }}</textarea>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            @endif
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
