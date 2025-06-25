@extends('layouts.custom-template.main')

@section('title', 'Produk')

@section('breadcrumb')
    {{ Breadcrumbs::render('products') }}
@endsection

@push('css')
<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    @can('products.create')
                        <a href="javascript:void(0);" class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-circle-dotted me-2"></i> {{ __('app.add') }} Product</a>
                    @endcan
                </div>
                <div class="card-body">
                    <table id="myTable" class="table table-striped display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kategori</th>
                                <th>Nama Produk</th>
                                <th>Deskripsi</th>
                                <th>Tarif (Rp.)</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach ($products as $key => $product)                                
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        @if(strlen($product->description) > 50)
                                            {{ Str::limit($product->description, 50, '...') }}
                                        @else
                                            {{ $product->description }}
                                        @endif
                                    </td>
                                    <td>{{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>
                                        @can('products.update')
                                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editModal_{{ $product->id }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                        @endcan
                                        @can('products.delete')
                                            <form action="{{ route('master.products.destroy', $product->id) }}" method="post" class="d-inline">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="confirmDelete(this);"><i class="bi bi-trash"></i></button>
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

    {{-- Start load modal add --}}
    @include('master.products.partials.modal-add')
    {{-- End load modal add --}}

    {{-- Start load modal edit --}}
    @include('master.products.partials.modal-edit')
    {{-- End load modal edit --}}
@endsection

@push('scripts')
    <script>
        $('#myTable').DataTable({
            responsive: true,
            "pageLength": 50
        });
    </script>
@endpush

