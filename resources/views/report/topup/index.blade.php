@extends('layouts.custom-template.main')

@section('title', 'Laporan Topup')

@section('breadcrumb')
    {{ Breadcrumbs::render('report.topup') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="topupTable" class="table table-striped display  nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                @if (!$isStudentOrParent)
                                    <th>Nama Siswa <br> NISN</th>
                                @endif
                                <th class="text-end">Jumlah Topup</th>
                                <th class="text-center">Tgl. Topup</th>
                                <th>Petugas Kasir</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach ($topups as $key => $topup)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    @if (!$isStudentOrParent)
                                        <td>
                                            <strong>{{ $topup->studentAccount->userData->name ?? 'Topup Tanpa Siswa' }}</strong>
                                            <br> 
                                            {{ $topup->studentAccount->nisn ?? '' }}
                                        </td>
                                    @endif
                                    <td class="text-end">Rp. {{ number_format($topup->amount, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $topup->created_at->format('d-m-Y H:i') }}</td>
                                    <td class="text-center">
                                        {{ $topup->cashier->name ?? '-' }}
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
        $('#topupTable').DataTable({
            responsive: true,
            "pageLength": 50
        });
    </script>
@endpush

