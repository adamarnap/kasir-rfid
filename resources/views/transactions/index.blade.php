@extends('layouts.custom-template.main')

@section('title', 'Transaksi')

@section('breadcrumb')
    {{ Breadcrumbs::render('transactions') }}
@endsection

@section('content')
    <div class="row">
        {{-- Start table items list --}}
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    @can('transactions.create')
                        <form
                            action="{{ isset($transactionId) ? route('transactions.store-new-item-in-same-transaction', $transactionId) : route('transactions.store') }}"
                            method="POST">
                            <div class="row g-2 align-items-center">
                                @csrf
                                @if (isset($transactionId))
                                    @method('PUT')
                                @endif
                                <div class="col-md-8">
                                    {{-- Selected Product --}}
                                    <select name="product_id" id="product_id" class="orm-select select2" required>
                                        <option value="">Pilih Produk</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                                                data-stock="{{ $product->stock }}"
                                                data-price="{{ number_format($product->price, 0, '.', ',') }}">
                                                {{ $product->name }} - (Stok : {{ $product->stock }}) - (Harga :
                                                {{ number_format($product->price, 0, '.', ',') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-cart-plus"></i> Tambah produk ke transaksi
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endcan
                </div>
                <div class="card-body">
                    <table id="itemsTable" class="table table-striped display  nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama Produk</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-end">Harga</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Sub Total</th>
                                @can('transactions.update')
                                    <th class="text-center">Aksi</th>
                                @endcan
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($chart->items ?? [] as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ $item->product->name ?? '-' }}</td>
                                    <td class="text-center">{{ $item->product->category->name ?? '-' }}</td>
                                    <td class="text-end">Rp {{ number_format((float) rsa_decrypt($item->product_price), 0, '.', ',') }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp
                                        {{ number_format((float) rsa_decrypt($item->product_price) * $item->quantity, 0, '.', ',') }}</td>
                                    <td class="text-center">
                                        @can('transactions.update')
                                            {{-- Edit --}}
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal_{{ $item->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                        @endcan
                                        @can('transactions.delete')
                                            {{-- Delete --}}
                                            <form action="{{ route('transactions.item-destroy', $item->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete(this)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <i class="bi bi-exclamation-circle"></i> Tidak ada produk dalam transaksi ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- End table items list --}}

        {{-- Start total transaction --}}
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-dark text-white text-center">
                    <h5 class="mb-1 mt-1">Total Transaksi</h5>
                </div>

                @if ($transactionId)
                    <div class="card-body text-center">
                        {{-- Kotak harga seperti layar kalkulator --}}
                        <div class="bg- text-success py-4 px-3 mb-4 rounded" style="border: 2px solid #28a745;">
                            <h1 class="display-3 font-weight-bold m-0">
                                Rp {{ number_format((float) rsa_decrypt($chart->total_amount) ?? 0, 0, '.', ',') }}
                            </h1>
                        </div>

                        @can('transactions.delete')
                            <form action="{{ route('transactions.destroy', $transactionId) }}" method="POST" class="mb-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-lg w-100"
                                    onclick="confirmDelete(this)">
                                    <i class="bi bi-trash"></i> Hapus Transaksi
                                </button>
                            </form>
                        @endcan

                        @can('transactions.update')
                            <button type="submit" class="btn btn-success btn-lg w-100" data-bs-toggle="modal" data-bs-target="#bayarModal">
                                <i class="bi bi-check2-circle"></i> Selesaikan Transaksi
                            </button>
                        @endcan
                    </div>


                    <div class="card-footer text-muted text-center small">
                        * Pastikan semua produk sudah benar sebelum menyelesaikan transaksi.
                    </div>
                @else
                    <div class="card-body text-center text-muted py-5">
                        <i class="bi bi-receipt" style="font-size: 3rem;"></i>
                        <p class="mt-3 mb-0">Belum ada transaksi yang aktif.</p>
                        <small>Silakan mulai transaksi baru untuk melihat detail di sini.</small>
                    </div>
                @endif
            </div>
        </div>

        {{-- End total transaction --}}
    </div>

    {{-- Include Modal Edit --}}
    @include('transactions.partials.modal-edit')
    @if ($transactionId)
        {{-- Include Modal Bayar --}}
        @include('transactions.partials.modal-bayar')
        {{-- Include Modal Input PIN --}}
        @include('transactions.partials.modal-pin')
    @endif

@endsection

@push('scripts')
    <script>
        // Initialize Select2
        $('.select2').select2({
            placeholder: "Pilih produk ...",
            allowClear: true,
            width: '100%',
            theme: 'classic'
        });

        // Custom Select2 Styling
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Pilih produk ...",
                required: true,
                allowClear: true,
                width: '100%',
                theme: 'classic',
                templateResult: function(data) {
                    if (!data.id) {
                        return data.text;
                    }
                    var $option = $(data.element);
                    var name = $option.data('name');
                    var stock = $option.data('stock');
                    var price = $option.data('price');
                    return $(
                        '<div style="display: flex; flex-direction: column;">' +
                        '<span style="font-weight: bold;">' + name + '</span>' +
                        '<span style="font-size: 12px;">Stok: ' + stock + ' | Harga: Rp ' + price +
                        '</span>' +
                        '</div>'
                    );
                },
                templateSelection: function(data) {
                    if (!data.id) {
                        return data.text;
                    }
                    var $option = $(data.element);
                    var name = $option.data('name');
                    var stock = $option.data('stock');
                    var price = $option.data('price');
                    // Return the name or text if name is not available
                    return name || data.text;
                }
            });
        });
    </script>
@endpush
