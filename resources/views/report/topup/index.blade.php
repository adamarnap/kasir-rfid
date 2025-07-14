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
                                <th>Nama Siswa <br> NISN</th>
                                <th class="text-end">Jumlah Topup</th>
                                <th class="text-center">Tgl. Topup</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @forelse ($topups as $key => $topup)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td>
                                        <strong>{{ $topup->studentAccount->userData->name ?? 'Topup Tanpa Siswa' }}</strong>
                                        <br> 
                                        {{ $topup->studentAccount->nisn ?? '' }}
                                    </td>
                                    <td class="text-end">Rp. {{ number_format($topup->amount, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $topup->created_at->format('d-m-Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data topup.</td>
                                </tr>
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
        $('#topupTable').DataTable({
            responsive: true,
            "pageLength": 50
        });
    </script>
@endpush

