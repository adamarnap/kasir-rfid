@extends('layouts.custom-template.main')

@section('title', 'Hak Akses Peran')

@section('breadcrumb')
    {{ Breadcrumbs::render('roles-permissions', $role->id, $role->name) }}
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    {{-- back button --}}
                    <a href="{{ route('roles.index') }}" class="btn btn-sm btn-secondary text-white float-start"><i class="bi bi-box-arrow-in-left me-2"></i> {{ __('app.back') }}</a>
                    {{-- submit save button --}}
                    <button type="submit" class="btn btn-sm btn-danger text-white float-end" onclick="submitForm()"><i class="bi bi-floppy me-2"></i> {{ __('app.save') }}</button>
                </div>
                <div class="card-body">
                    <form id="permissionsForm" method="POST" action="{{ route('roles.permissions', $role->id) }}">
                        @csrf
                        @method('PUT')
                        <table id="myTable" class="table table-striped display  nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 align-middle" rowspan="2">{{ __('app.navigations') }}</th>
                                    <th class="px-4 py-2 text-center" colspan="4">{{ __('app.permission') }}</th>
                                </tr>
                                <tr>
                                    <th class="px-4 py-2 text-center">Create</th>
                                    <th class="px-4 py-2 text-center">Read</th>
                                    <th class="px-4 py-2 text-center">Update</th>
                                    <th class="px-4 py-2 text-center">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @can('roles.read')
                                    @foreach ($navigations as $nav)
                                        <tr>
                                            <td class="px-4 py-2 text-start">
                                            <i class="{{ $nav->icon }}" style="margin-right:5px"></i>
                                                {{ $nav->name }}
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                <input type="checkbox" name="permission[]"
                                                    value="{{ strtolower($nav->slug) . '.create' }}"
                                                    {{ in_array(strtolower($nav->slug) . '.create', $permissions) ? 'checked' : '' }}>
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                <input type="checkbox" name="permission[]"
                                                    value="{{ strtolower($nav->slug) . '.read' }}"
                                                    {{ in_array(strtolower($nav->slug) . '.read', $permissions) ? 'checked' : '' }}>
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                <input type="checkbox" name="permission[]"
                                                    value="{{ strtolower($nav->slug) . '.update' }}"
                                                    {{ in_array(strtolower($nav->slug) . '.update', $permissions) ? 'checked' : '' }}>
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                <input type="checkbox" name="permission[]"
                                                    value="{{ strtolower($nav->slug) . '.delete' }}"
                                                    {{ in_array(strtolower($nav->slug) . '.delete', $permissions) ? 'checked' : '' }}>
                                            </td>
                                        </tr>
                                        @if ($nav->child->count() > 0)
                                            @foreach ($nav->child as $child)
                                                <tr>
                                                    <td class="px-4 py-2 text-start">
                                                    <small><i class="bi bi-arrow-return-right mx-2"></i></small>
                                                        {{ $child->name }}
                                                    </td>
                                                    <td class="px-4 py-2 text-center">
                                                        <input type="checkbox" name="permission[]"
                                                            value="{{ strtolower($child->slug) . '.create' }}"
                                                            {{ in_array(strtolower($child->slug) . '.create', $permissions) ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="px-4 py-2 text-center">
                                                        <input type="checkbox" name="permission[]"
                                                            value="{{ strtolower($child->slug) . '.read' }}"
                                                            {{ in_array(strtolower($child->slug) . '.read', $permissions) ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="px-4 py-2 text-center">
                                                        <input type="checkbox" name="permission[]"
                                                            value="{{ strtolower($child->slug) . '.update' }}"
                                                            {{ in_array(strtolower($child->slug) . '.update', $permissions) ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="px-4 py-2 text-center">
                                                        <input type="checkbox" name="permission[]"
                                                            value="{{ strtolower($child->slug) . '.delete' }}"
                                                            {{ in_array(strtolower($child->slug) . '.delete', $permissions) ? 'checked' : '' }}>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endcan
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#myTable').DataTable({
            responsive: true,
            "ordering": false,
            "pageLength": 100
        });
    </script>
    <script>
        function submitForm() {
            document.getElementById("permissionsForm").submit();
        }
    </script>
@endpush



