@extends('layouts.custom-template.main')

@section('title', 'Detail Transaksi - ' . $student->userData->name)

@section('breadcrumb')
    {{ Breadcrumbs::render('report.transactions.show', $student) }}
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-end">Total Tagihan</th>
                                <th class="text-center">Metode Pembayaran</th>
                                <th class="text-center">Tgl. Transaksi</th>
                                <th class="text-center">Status Transaksi</th>
                                <th class="text-center">Petugas Kasir</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach ($transactions as $key => $transaction)
                                <!-- Main Transaction Row -->
                                <tr class="table-primary">
                                    <td class="text-center"><strong>{{ $key + 1 }}</strong></td>
                                    <td class="text-end"><strong>Rp {{ number_format((float) simple_rsa_decrypt($transaction->total_amount), 0, ',', '.') }}</strong></td>
                                    <td class="text-center">
                                        @if ($transaction->payment_method == 'cash')
                                            <span class="badge bg-success">Tunai</span>
                                        @elseif ($transaction->payment_method == 'rfid')
                                            <span class="badge bg-warning">RFID</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                                    <td class="text-center">
                                        @if ($transaction->status == 'paid')
                                            <span class="badge bg-success">Lunas</span>
                                        @elseif ($transaction->status == 'draft')
                                            <span class="badge bg-secondary">Draft</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $transaction->cashier->name ?? '-' }}
                                    </td>
                                </tr>
                                
                                <!-- Detail Items Row -->
                                <tr>
                                    <td colspan="6" class="p-0">
                                        <div class="bg-light p-3">
                                            <h6 class="mb-2"><i class="bi bi-list-ul"></i> Detail Produk:</h6>
                                            <table class="table table-sm table-bordered mb-0">
                                                <thead class="table-secondary">
                                                    <tr>
                                                        <th class="text-center" style="width: 50px;">No</th>
                                                        <th>Nama Produk</th>
                                                        <th class="text-end" style="width: 150px;">Harga Satuan</th>
                                                        <th class="text-center" style="width: 100px;">Jumlah</th>
                                                        <th class="text-end" style="width: 150px;">Total Harga</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($transaction->items as $itemKey => $item)
                                                        <tr>
                                                            <td class="text-center">{{ $itemKey + 1 }}</td>
                                                            <td>{{ $item->product->name }}</td>
                                                            <td class="text-end">Rp {{ number_format((float) simple_rsa_decrypt($item->product_price), 0, ',', '.') }}</td>
                                                            <td class="text-center">{{ $item->quantity }}</td>
                                                            <td class="text-end">Rp {{ number_format(((float) simple_rsa_decrypt($item->product_price) * $item->quantity), 0, ',', '.') }}</td>
                                                        </tr>
                                                    @endforeach
                                                    <tr class="table-info">
                                                        <td colspan="4" class="text-end"><strong>Total Keseluruhan:</strong></td>
                                                        <td class="text-end"><strong>Rp {{ number_format((float) simple_rsa_decrypt($transaction->total_amount), 0, ',', '.') }}</strong></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

