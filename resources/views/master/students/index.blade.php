@extends('layouts.custom-template.main')

@section('title', '')

{{-- @section('breadcrumb')
    {{ Breadcrumbs::render('users') }}
@endsection --}}

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
                    <table id="myTable" class="table table-striped display  nowrap" style="width:100%">
                        <thead>
                        </thead>
                        
                        <tbody>
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

