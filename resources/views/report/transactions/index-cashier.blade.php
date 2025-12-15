@extends('layouts.custom-template.main')

@section('title', 'Laporan Transaksi')

@section('breadcrumb')
    {{ Breadcrumbs::render('report.transactions') }}
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
                                <th>Nama Siswa</th>
                                <th class="text-end">Banyaknya Transaksi</th>
                                <th class="text-end">Total Transaksi (Rp)</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach ($students as $key => $student)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td>
                                        <strong>{{ $student->userData->name ?? '-' }}</strong>
                                    </td>
                                    <td class="text-end">{{ $student->transactions->count() }}</td>
                                    @php
                                        $totalAmount = $student->transactions->sum(fn($trx) => simple_rsa_decrypt($trx->total_amount));
                                    @endphp
                                    <td class="text-end">Rp. {{ number_format((float) $totalAmount, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @can('report-transactions.read')
                                            <a href="{{ route('report.transactions.show', $student->student_id) }}" class="btn btn-sm btn-info">
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
@endsection

@push('scripts')
    <script>
        $('#myTable').DataTable({
            responsive: true,
            "pageLength": 50
        });
    </script>
@endpush

