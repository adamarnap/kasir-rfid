@extends('layouts.custom-template.main')

@section('title', 'Kategori')

@section('breadcrumb')
    {{ Breadcrumbs::render('categories') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    @can('categories.create')
                        <a href="javascript:void(0);" class="btn btn-primary text-white" data-bs-toggle="modal"
                            data-bs-target="#addModal"><i class="bi bi-plus-circle-dotted me-2"></i> Tambah Kategori</a>
                    @endcan
                </div>
                <div class="card-body">
                    <table id="myTable" class="table table-striped display  nowrap" style="width:100%">
                        <thead>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </thead>

                        <tbody>
                            @forelse ($categories as $key => $category)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->description }}</td>
                                    <td>
                                        @can('categories.update')
                                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                                data-bs-target="#editModal_{{ $category->id }}"
                                                class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                        @endcan
                                        @can('categories.delete')
                                            <form action="{{ route('master.categories.destroy', $category->id) }}" method="post"
                                                class="d-inline">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete(this);"><i class="bi bi-trash"></i></button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data kategori.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
{{-- Load Modal Add --}}
@include('master.categories.partials.modal-add')
{{-- Load Modal Edit --}}
@include('master.categories.partials.modal-edit')
@endsection

@push('scripts')
    <script>
        $('#myTable').DataTable({
            responsive: true,
            "pageLength": 50
        });
    </script>
@endpush
