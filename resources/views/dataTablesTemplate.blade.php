
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    @can('users.create')
                        <a href="{{ route('users.create') }}" class="btn btn-primary text-white"><i class="bi bi-plus-circle-dotted me-2"></i> {{ __('app.add') }}</a>
                    @endcan
                </div>
                <div class="card-body">
                    <table id="myTable" class="table table-striped display  nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>{{ __('app.name') }}</th>
                                <th>{{ __('app.email') }}</th>
                                <th>{{ __('app.roles') }}</th>
                                <th>{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->roles->pluck('name') }}</td>
                                    <td>
                                        @can('users.update')
                                            <a href="{{ route('users.edit', $user->id) }}"
                                                class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                        @endcan
                                        @can('users.delete')
                                            <form action="{{ route('users.destroy', $user->id) }}" method="post"
                                                class="d-inline">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"><i
                                                        class="bi bi-trash"></i></button>
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
@endsection

@push('scripts')
    <script>
        $('#myTable').DataTable({
            responsive: true,
        });
    </script>
@endpush

