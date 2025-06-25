<!-- Modal -->
@foreach ($products as $product)    
    <!-- Modal -->
    <div class="modal fade modal-animate" id="editModal_{{ $product->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('app.edit') }} Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('master.products.update', $product->id) }}" method="POST">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="" class="form-label">Nama</label>
                                    <input type="text" name="name" class="form-control" id="name"
                                        placeholder="Silahkan masukkan nama kategori" required autocomplete="off" value="{{ $product->name }}"/>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="" class="form-label">Deskripsi</label>
                                    <textarea name="description" class="form-control" placeholder="Silahkan masukkan deskripsi kategori" required>{{ $product->description }}</textarea>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="" class="form-label">Kategori Produk</label>
                                <select name="category_id" class="select2 form-control" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == $product->category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div id="" class="form-text text-danger">
                                    *Wajib diisi.
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <label for="" class="form-label">Harga Produk</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text" id="basic-addon1">Rp.</span>
                                    <input type="number" class="form-control" placeholder="Harga" aria-label="Harga"
                                        name="price" aria-describedby="basic-addon1" required value="{{ $product->price }}"/>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-2">
                                    <label for="" class="form-label">Stok Produk</label>
                                    <input type="number" class="form-control" placeholder="Stok Produk" aria-label="Stok Produk"
                                        name="stock" aria-describedby="basic-addon1" required value="{{ $product->stock }}"/>
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
