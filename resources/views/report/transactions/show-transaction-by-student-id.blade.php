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
                    <table id="myTable" class="table table-striped display  nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-end">Total Tagihan</th>
                                <th class="text-center">Metode Pembayaran</th>
                                <th class="text-center">Tgl. Transaksi</th>
                                <th class="text-center">Status Transaksi</th>
                                <th class="text-center">Petugas Kasir</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach ($transactions as $key => $transaction)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td class="text-end">{{ number_format((float) simple_rsa_decrypt($transaction->total_amount), 0, ',', '.') }}</td>
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
                                    <td class="text-center">
                                        @can('report-transactions.read')
                                            <a  class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#showModal_{{ $transaction->id }}">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Include show modal --}}
    @include('report.transactions.partials.modal-show-transaction-items')
@endsection

@push('scripts')
    <script>
        $('#myTable').DataTable({
            responsive: true,
            "pageLength": 50
        });
    </script>
@endpush

