@extends('layouts.custom-template.main')

@section('title', 'Kartu RFID')

@section('breadcrumb')
    {{ Breadcrumbs::render('users') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    @can('rfid.create')
                        <a href="javascript:void(0);" class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="bi bi-plus-circle-dotted me-2"></i> 
                            {{ __('app.add') }} Kartu RFID</a>
                    @endcan
                </div>
                <div class="card-body">
                    <table id="rfidTable" class="table table-striped display  nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Siswa</th>
                                <th class="text-center">Nomor Kartu</th>
                                <th class="text-center">Status</th>
                                <th>Keterangan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach ($cards as $index => $card)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $card->studentAccount->userData->name ?? '' }}</td>
                                    <td class="text-center">********</td>
                                    <td class="text-center">
                                        @if ($card->status == 'active')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </td></td>
                                    <td>{{ $card->description ?? '-' }}</td>
                                    <td class="text-center">
                                        @can('rfid.update')
                                            <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#editModal_{{ $card->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
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

    {{-- Include add modal --}}
    @can('rfid.update')
        @include('rfid.partials.modal-add')
    @endcan
    {{-- Include edit modal --}}
    @can('rfid.update')
        @include('rfid.partials.modal-edit')
    @endcan
@endsection

@push('scripts')
    <script>
        $('#rfidTable').DataTable({
            responsive: true,
            "pageLength": 50
        });

        // In your Javascript (external .js resource or <script> tag)
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Pilih Siswa",
                allowClear: true,
                width: '100%',
                dropdownParent: $(".select2ModalAdd") // Ensure dropdown is inside modal
            });
        });
    </script>
@endpush

