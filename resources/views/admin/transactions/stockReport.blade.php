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
                    <div class="mb-3 d-flex flex-row-reverse">
                        <a type="button" class="btn  ml-2 {{ ($currentSortmenu == 'all day') ? 'btn-warning' : ''}}"
                            href="{{ url('admin/transactions/stockReport') }}">Semua Hari</a>
                        <a type="button" class="btn  ml-2 {{ ($currentSortmenu == 'day 30') ? 'btn-warning' : ''}}"
                            href="{{ url('admin/transactions/stockReportbydate/30') }}">30 Hari</a>
                        <a type="button" class="btn  ml-2 {{ ($currentSortmenu == 'day 7') ? 'btn-warning' : ''}}"
                            href="{{ url('admin/transactions/stockReportbydate/7') }}">7 Hari</a>
                        <a type="button" class="btn  ml-2 {{ ($currentSortmenu == 'day 1') ? 'btn-warning' : ''}}"
                            href="{{ url('admin/transactions/stockReportbydate/1') }}">1 Hari</a>
                    </div>
                    @include('admin.partials.flash')
                    <table class="table table-bordered table-stripped">
                        <thead>
                            <th>Kode Produk</th>
                            <th>Nama</th>
                            <th>Stok</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->total }}</td>
                                <td>Rp {{ number_format ($product->purchaseprice , 0 , '.', '.') }}</td>
                                <td>Rp {{ number_format ($product->sellingprice , 0 , '.', '.') }}</td>
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
            </div>
        </div>
    </div>
</div>
@endsection