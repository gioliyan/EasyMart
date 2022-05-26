@extends('admin.layout')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Products</h2>
                </div>
                <div class="card-body">
                    @include('admin.partials.flash')
                    <table class="table table-bordered table-stripped">
                        <thead>
                            <th>Kode Batch</th>
                            <th>Kode Produk</th> 
                            <th>Nama</th>
                            <th>Stok</th>
                            <th>Harga Beli</th>
                            <th>Tanggal</th>
                        </thead>
                        <tbody>
                            @forelse ($restocks as $restock)
                            <tr>
                                <td>{{ $restock->id }}</td>
                                <td>{{ $restock->product_id }}</td>
                                <td>{{ $restock->product->name }}</td>
                                <td>{{ $restock->amount }}</td>
                                <td>{{ $restock->purchaseprice }}</td>
                                <td>{{ $restock->created_at }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">No records found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $restocks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection