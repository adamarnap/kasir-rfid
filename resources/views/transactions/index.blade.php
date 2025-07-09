@extends('layouts.custom-template.main')

@section('title', 'Transaksi')

@section('breadcrumb')
    {{ Breadcrumbs::render('transactions') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    @can('transactions.create')
                    <form action="{{ isset($transactionId) ? route('transactions.update', $transactionId) : route('transactions.store') }}" method="POST">
                        <div class="row g-2 align-items-center mt-2">
                                @csrf
                                @if (isset($transactionId))
                                    @method('PUT')
                                @endif
                                <div class="col-md-10">
                                    {{-- Selected Product --}}
                                    <select name="product_id" id="product_id" class="form-select select2" required>
                                        <option value="">Pilih Produk</option>
                                        @foreach ($products as $product)
                                            <option 
                                                value="{{ $product->id }}"
                                                data-name="{{ $product->name }}"
                                                data-stock="{{ $product->stock }}"
                                                data-price="{{ number_format($product->price, 0, '.', ',') }}"
                                            >
                                                {{ $product->name }} - (Stok : {{ $product->stock }}) - (Harga : {{ number_format($product->price, 0, '.', ',') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-cart-plus"></i> Tambah Ke Transaksi
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
                            @forelse ($chart->items as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ $item->product->name ?? '-' }}</td>
                                    <td class="text-center">{{ $item->product->category->name ?? '-' }}</td>
                                    <td class="text-end">Rp {{ number_format($item->product_price, 0, '.', ',') }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format(($item->product_price * $item->quantity ), 0, '.', ',') }}</td>
                                    <td class="text-center">
                                        @can('transactions.update')
                                            {{-- Edit --}}
                                            <button class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                        @endcan
                                        @can('transactions.delete')
                                            {{-- Delete --}}
                                            <form action="{{ route('transactions.destroy', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini dari transaksi?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>Belum terdapat product pada transaksi baru ini.</tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
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
            templateResult: function (data) {
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
                        '<span style="font-size: 12px;">Stok: ' + stock + ' | Harga: Rp ' + price + '</span>' +
                    '</div>'
                );
            },
            templateSelection: function (data) {
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

