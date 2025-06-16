@extends('layouts.custom-template.main')

@section('title', 'Menu')

@section('breadcrumb')
    {{ Breadcrumbs::render('navigations') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    @can('navs.create')
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addModal" class="btn btn-primary text-white"><i
                                class="bi bi-plus-circle-dotted me-2"></i> {{ __('app.add') }}</a>
                    @endcan
                </div>
                <div class="card-body">
                    <table id="myTable" class="table table-striped display responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th width="30%" class="">{{ __('app.name') }}</th>
                                <th width="15%" class="">Pengidentifikasi Hak Akses</th>
                                <th width="30%" class="text-center">{{ __('app.url') }} Route</th>
                                <th width="5%" class="text-center">{{ __('app.order') }}</th>
                                <th width="5%" class="text-center">{{ __('app.active') }}</th>
                                <th width="5%" class="text-center">{{ __('app.display') }}</th>
                                <th width="10%" class="text-center">{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @can('navs.read')
                                @foreach ($navigations as $nav)
                                    <tr>
                                        <td class="px-4 py-1 text-start">
                                            <i class="{{ $nav->icon }}" style="margin-right:5px"></i>
                                            {{ $nav->name }}
                                        </td>
                                        <td class="px-0 py-1 text-start">{{ $nav->slug }}</td>
                                        <td class="px-0 py-1 text-start">{{ $nav->url }}</td>
                                        <td class="px-2 py-1 text-center">
                                            {{ $nav->order }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </td>
                                        <td class="px-3 py-1 text-start {{ $nav->active == 1 ? 'text-success' : 'text-danger' }}">
                                            <small>
                                                <i class="m-1 {{ $nav->active == 1 ? 'bi bi-check-circle-fill' : 'bi bi-x-circle-fill' }}"></i>
                                            </small>
                                        <td class="px-3 py-1 text-start {{ $nav->active == 1 ? 'text-success' : 'text-danger' }}">
                                            <small>
                                                <i class="m-1 {{ $nav->display == 1 ? 'bi bi-check-circle-fill' : 'bi bi-x-circle-fill' }}"></i>
                                            </small>
                                        </td>
                                        <td class="px-4 py-1 text-center">
                                            @can('navs.update')
                                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editModalParent_{{ $nav->id }}" class="btn btn-sm btn-warning"><i
                                                        class="bi bi-pencil"></i></a>
                                            @endcan
                                            @can('navs.delete')
                                                <form action="{{ route('navs.destroy', $nav->id) }}" method="post"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="confirmDelete(this);"><i
                                                            class="bi bi-trash"></i></button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                    @if ($nav->child->count() > 0)
                                        @foreach ($nav->child as $child)
                                            <tr>
                                                <td class="px-5 py-1 text-start">
                                                    <small><small>
                                                        <i class="bi bi-arrow-return-right"></i> {{ $child->name }}
                                                    </small></small>
                                                </td>
                                                <td class="px-1 py-1 text-start">
                                                    <small><small>
                                                        <i class="bi bi-arrow-return-right"></i> {{ $child->slug }} 
                                                    </small></small>
                                                </td>
                                                <td class="px-1 py-1 text-start">
                                                    <small><small>
                                                        <i class="bi bi-arrow-return-right"></i> {{ $child->url }}
                                                    </small></small>
                                                </td>
                                                <td class="px-5 py-1 text-center">
                                                    <small><small><i
                                                                class="bi bi-arrow-return-right mx-2"></i>{{ $child->order }}</small></small>
                                                </td>
                                                <td class="px-4 py-1 text-center {{ $child->active == 1 ? 'text-success' : 'text-danger' }}">
                                                    <small>
                                                        <small>
                                                            <i class="bi bi-arrow-return-right"></i>
                                                            <i class="m-1 {{ $child->active == 1 ? 'bi bi-check-circle-fill' : 'bi bi-x-circle-fill' }}"></i>
                                                        </small>
                                                    </small>
                                                </td>
                                                <td class="px-4 py-1 text-center {{ $child->display == 1 ? 'text-success' : 'text-danger' }}">
                                                    <small>
                                                        <small>
                                                            <i class="bi bi-arrow-return-right"></i>
                                                            <i class="m-1 {{ $child->display == 1 ? 'bi bi-check-circle-fill' : 'bi bi-x-circle-fill' }}"></i>
                                                        </small>
                                                    </small>
                                                </td>
                                                <td class="px-5 py-1 text-center">
                                                    @can('navs.update')
                                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editModalChild_{{ $child->id }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                                    @endcan
                                                    @can('navs.delete')
                                                        <form action="{{ route('navs.destroy', $child->id) }}" method="post"
                                                            class="d-inline">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="confirmDelete(this);"><i
                                                                    class="bi bi-trash"></i></button>
                                                        </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endcan
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Add --}}
    @include('settings.navigation.partial.modal-add')

    {{-- Modal Edit --}}
    @include('settings.navigation.partial.modal-edit')
@endsection

@push('scripts')
    <script>
        $('#myTable').DataTable({
            responsive: true,
            "ordering": false,
            "pageLength": 100
        });
    </script>
@endpush
