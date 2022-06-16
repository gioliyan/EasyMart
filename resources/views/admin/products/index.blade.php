@extends('admin.layout')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Product</h2>
                </div>
                <div class="card-body">
                <div class="row flex-row-reverse">
                    <div class="col-md-5">
                        <form action="{{ url('admin/products/search') }}">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Search.." name="search" value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
                    @include('admin.partials.flash')
                    <table class="table table-bordered table-stripped">
                        <thead>
                            <th>#</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category->name}}</td>
                                <td>Rp {{ number_format($product->purchaseprice, 2) }}</td>
                                <td>Rp {{ number_format($product->sellingprice, 2) }}</td>
                                <td>
                                    <a href="{{ url('admin/products/'. $product->id .'/edit') }}"
                                        class="btn btn-warning btn-sm">edit</a>
                                    {!! Form::open(['url' => 'admin/products/'. $product->id, 'class' => 'delete',
                                    'style' => 'display:inline-block']) !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    {!! Form::submit('remove', ['class' => 'btn btn-danger btn-sm']) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">No records found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $products->links() }}
                </div>
                <div class="card-footer text-right">
                    <a href="{{ url('admin/products/create') }}" class="btn btn-primary">Add New</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection