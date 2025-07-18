@extends('layouts.custom-template.main')

@section('title', 'Data Siswa')

@section('breadcrumb')
    {{ Breadcrumbs::render('students') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    @can('students.create')
                        <a href="javascript:void(0);" class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-circle-dotted me-2"></i> {{ __('app.add') }}</a>
                    @endcan
                </div>
                <div class="card-body">
                    <table id="studentsTable" class="table table-striped display  nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Kelas</th>
                                <th>Jenis Kelamin</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td>{{ $student->userData->name }}</td>
                                    <td>{{ $student->userData->email }}</td>
                                    <td>{{ $student->kelas ?? '-' }}</td>
                                    <td>{{ $student->userData->jenis_kelamin->label() }}</td>
                                    <td>
                                        @if ($student->status == 'active')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        @can('students.update')
                                            <a href="javascript:void(0);" class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#editModal_{{ $student->id }}"><i class="bi bi-pencil-square"></i></a>
                                        @endcan
                                        @can('students.delete')
                                            <form action="{{ route('master.students.destroy', $student->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="confirmDelete(this);"><i class="bi bi-trash"></i></button>
                                            </form>
                                        @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

{{-- Load Modal Add --}}
@include('master.students.partials.modal-add')

{{-- Load Modal Edit --}}
@include('master.students.partials.modal-edit')
@endsection

@push('scripts')
    <script>
        $('#studentsTable').DataTable({
            responsive: true,
            "pageLength": 50
        });
    </script>
@endpush

