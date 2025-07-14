@foreach ($transactions as $transaction)
    <!-- Modal Show -->
    <div class="modal fade modal-animate" id="showModal_{{ $transaction->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">List Produk Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped display  nowrap">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Produk</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Jumlah</th>
                                <th class="text-end">Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaction->items as $key => $item)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td>{{ $item->product->name }}</td>
                                    <td class="text-end">Rp. {{ number_format($item->product_price, 0, ',', '.') }}</td>
                                    <td class="text-end">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp. {{ number_format(($item->product_price * $item->quantity), 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                <td class="text-end"><strong>Rp. {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endforeach
