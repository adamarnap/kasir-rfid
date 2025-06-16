@extends('layouts.custom-template.main')

@section('title', 'Preferensi')

@section('breadcrumb')
    {{ Breadcrumbs::render('preferences') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="myTable" class="table table-striped display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">{{ __('app.group') }}</th>
                                <th class="px-4 py-2">Is Asset</th>
                                <th class="px-4 py-2">{{ __('app.name') }}</th>
                                <th class="px-4 py-2">{{ __('app.value') }}</th>
                                <th class="px-4 py-2">{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($preferences as $pref)
                                <tr>
                                    <td class="px-4 py-2 text-center">{{ $pref->group }}</td>
                                    <td class="px-4 py-2 text-center">
                                        @if ($pref->is_asset)
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                        @else
                                            <i class="bi bi-x-circle-fill text-danger"></i>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-center">{{ $pref->name }}</td>
                                    <td class="px-4 py-2 text-left">{{ $pref->value }}</td>
                                    <td class="px-4 py-2 text-center">
                                        @can('preferences.update')
                                            <a data-bs-toggle="modal" data-bs-target="#editPreferencesModal_{{ $pref->id }}"
                                                class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
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

    {{-- Modal Edit Preferences --}}
    @include('settings.preferences.partial.modal-edit')

@endsection

@push('scripts')
    <script>
        $('#myTable').DataTable({
            responsive: true,
        });
    </script>
@endpush
