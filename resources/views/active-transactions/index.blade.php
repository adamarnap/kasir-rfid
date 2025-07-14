@extends('layouts.custom-template.main')

@section('title', 'Transaksi Aktif')

@section('breadcrumb')
    {{ Breadcrumbs::render('active-transactions') }}
@endsection

@section('content')
    <div class="row">
        {{-- Start table transactions list --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Item Transaksi Aktif</h3>
                </div>
                <div class="card-body">
                    <table id="transactionsTable" class="table table-striped display  nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kasir</th>
                                    <th class="text-center">Tgl. Transaksi</th>
                                    <th class="text-end">Total Tagihan</th>
                                    <th class="text-center">Metode Pembayaran</th>
                                    <th class="text-center">Lanjutakan Transaksi</th>
                                </tr>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($activeTransactions as $key => $transaction)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td>
                                        <strong>{{ $transaction->cashier->name ?? 'Kasir Tidak Ditemukan' }}</strong>
                                    </td>
                                    <td class="text-center">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                                    <td class="text-end">{{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if ($transaction->payment_method == 'cash')
                                            <span class="badge bg-success">Tunai</span>
                                        @elseif ($transaction->payment_method == 'rfid')
                                            <span class="badge bg-warning">RFID</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @can('transactions.update')
                                            <a href="{{ route('transactions.index', $transaction->id) }}" class="btn btn-sm btn-success">
                                                <strong>
                                                    <i class="bi bi-cart-plus"></i> Lanjutkan Transaksi
                                                </strong>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada transaksi aktif.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- End table transactions list --}}
    </div>


@endsection