@extends('layouts.custom-template.main')

@section('title', 'Wali Siswa')

@section('breadcrumb')
    {{ Breadcrumbs::render('parents') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    @can('users.create')
                        <a href="javascript:void(0);" class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-circle-dotted me-2"></i> {{ __('app.add') }}</a>
                    @endcan
                </div>
                <div class="card-body">
                    <table id="parentsTable" class="table table-striped display  nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Nama Siswa</th>
                                <th>Hubungan</th>
                                <th>Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach ($parents as $parent)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $parent->userData->name }}</td>
                                    <td>{{ $parent->userData->email }}</td>
                                    <td>
                                        @if ($parent->studentAccount)
                                            <strong>
                                                {{ $parent->studentAccount->userData->name }}
                                            </strong>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $parent->relationship->label() }}</td>
                                    <td>{{ $parent->userData->telepon ?? '-' }}</td>
                                    <td>
                                        @can('users.update')
                                            <a href="javascript:void(0);" class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#editModal_{{ $parent->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        @endcan
                                        @can('users.delete')
                                            <form action="{{ route('master.parents.destroy', $parent->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="confirmDelete(this);"><i class="bi bi-trash"></i></button>
                                            </form>
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
{{-- Load Modal Add --}}
@include('master.parents.partials.modal-add')

{{-- Load Modal Edit --}}
@include('master.parents.partials.modal-edit')

@endsection

@push('scripts')
    <script>
        $('#parentsTable').DataTable({
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

