@extends('layouts.custom-template.main')

@section('title', 'Peran')

@section('breadcrumb')
    {{ Breadcrumbs::render('roles') }}
@endsection


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    @can('roles.create')
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addModal" class="btn btn-primary text-white"><i
                                class="bi bi-plus-circle-dotted me-2"></i> {{ __('app.add') }}</a>
                    @endcan
                </div>
                <div class="card-body">
                    <table id="myTable" class="table table-striped display  nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-center" width="10%">No.</th>
                                <th class="px-4 py-2" width="70%">{{ __('app.name') }}</th>
                                <th class="px-4 py-2 text-center" width="20%">{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($roles as $role)
                                <tr>
                                    <td class="px-4 py-2 text-center">{{ $no++ }}</td>
                                    <td class="px-4 py-2">{{ ucwords($role->name) }}</td>
                                    <td class="px-4 py-2 text-center">
                                        @can('roles.update')
                                            <a href="{{ route('roles.show', $role->id) }}" class="btn btn-sm btn-primary"><i
                                                    class="bi bi-person-lock"></i></a>
                                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editModal_{{ $role->id }}" class="btn btn-sm btn-warning"><i
                                                    class="bi bi-pencil"></i></a>
                                        @endcan
                                        @can('roles.delete')
                                            <form action="{{ route('roles.destroy', $role->id) }}" method="post"
                                                class="d-inline">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="confirmDelete(this)"><i class="bi bi-trash"></i></button>
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

    {{-- Modal Add --}}
    @include('settings.role.partial.modal-add')

    {{-- Modal Edit --}}
    @include('settings.role.partial.modal-edit')
@endsection

@push('scripts')
    <script>
        $('#myTable').DataTable({
            responsive: true,
        });
    </script>
@endpush