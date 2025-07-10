@foreach ($chart->items ?? [] as $item)
<!-- Modal Edit -->
    <div class="modal fade modal-animate" id="editModal_{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('app.edit') }} | {{ $item->product->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('transactions.update-quantity', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @php
                        $availableStock = $item->product->stock ?? 0;
                        $maxQuantity = $availableStock + $item->quantity; 
                    @endphp
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="" class="form-label">Jumlah</label>
                                    <input type="number" name="quantity" class="form-control" value="{{ $item->quantity }}"
                                        placeholder="Silahkan masukkan jumlah produk" max="{{ $maxQuantity }}" required autocomplete="off"/>
                                    <div id="" class="form-text text-danger">
                                        *Wajib diisi | Jumlah Maksimal Produk = {{ $maxQuantity }} 
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