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
                    <div class="row">
                        <div class="d-flex col-lg-6 align-items-center">
                            <h4>Pengeluaran Total :
                                Rp {{ number_format ($totalRevenue , 0 , '.', '.') }}</h4>
                        </div>
                        <div class="mb-3 d-flex flex-row-reverse col-lg-6">
                            <a type="button" class="btn  ml-2 {{ ($currentSortmenu == 'all day') ? 'btn-warning' : ''}}"
                                href="{{ url('admin/transactions/purchaseReport') }}">Semua Hari</a>
                            <a type="button" class="btn  ml-2 {{ ($currentSortmenu == 'day 30') ? 'btn-warning' : ''}}"
                                href="{{ url('admin/transactions/purchaseReportbydate/30') }}">30 Hari</a>
                            <a type="button" class="btn  ml-2 {{ ($currentSortmenu == 'day 7') ? 'btn-warning' : ''}}"
                                href="{{ url('admin/transactions/purchaseReportbydate/7') }}">7 Hari</a>
                            <a type="button" class="btn  ml-2 {{ ($currentSortmenu == 'day 1') ? 'btn-warning' : ''}}"
                                href="{{ url('admin/transactions/purchaseReportbydate/1') }}">1 Hari</a>
                        </div>
                    </div>
                    <div class="row flex-row-reverse">
                        <div class="col-md-5">
                            <form action="{{ url('admin/transactions/searchPurchasereport') }}">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Search.." name="search"
                                        value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @include('admin.partials.flash')
                    <table class="table table-bordered table-stripped">
                        <thead>
                            <th>Kode Batch</th>
                            <th>Kode Produk</th>
                            <th>Nama</th>
                            <th>Stok</th>
                            <th>Harga Beli</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                        </thead>
                        <tbody>
                            @forelse ($restocks as $restock)
                            <tr>
                                <td>{{ $restock->id }}</td>
                                <td>{{ $restock->product_id }}</td>
                                <td>{{ $restock->product->name }}</td>
                                <td>{{ $restock->amount }}</td>
                                <td>Rp {{ number_format ($restock->purchaseprice , 0 , '.', '.') }}</td>
                                <td>{{ date('Y-m-d', strtotime($restock->created_at) )}}</td>
                                <td>{{ date('H:i', strtotime($restock->created_at) )}}</td>
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