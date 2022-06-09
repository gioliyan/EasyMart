@extends('admin.layout')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Transaksi</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="d-flex col-lg-6 align-items-center">
                            <h4>Total Pemasukan:
                                Rp {{ number_format ($totalRevenue , 0 , '.', '.') }}</h4>
                        </div>
                        <div class="mb-3 d-flex flex-row-reverse col-lg-6">
                            <form action="{{ url('admin/transactions/searchTransactionreport') }}">
                                <input type="hidden" class="form-control" name="sortmenu" value="all day">
                                <input type="hidden" class="form-control" name="search" value="{{$search}}">
                                <button class="btn  ml-2 {{ ($currentSortmenu == 'all day') ? 'btn-warning' : ''}}"
                                    type="submit">Semua Hari</button>
                            </form>
                            <form action="{{ url('admin/transactions/searchTransactionreport') }}">
                                <input type="hidden" class="form-control" name="sortmenu" value="day 30">
                                <input type="hidden" class="form-control" name="search" value="{{$search}}">
                                <button class="btn  ml-2 {{ ($currentSortmenu == 'day 30') ? 'btn-warning' : ''}}"
                                    type="submit">30 Hari</button>
                            </form>
                            <form action="{{ url('admin/transactions/searchTransactionreport') }}">
                                <input type="hidden" class="form-control" name="sortmenu" value="day 7">
                                <input type="hidden" class="form-control" name="search" value="{{$search}}">
                                <button class="btn  ml-2 {{ ($currentSortmenu == 'day 7') ? 'btn-warning' : ''}}"
                                    type="submit">7 Hari</button>
                            </form>
                            <form action="{{ url('admin/transactions/searchTransactionreport') }}">
                                <input type="hidden" class="form-control" name="sortmenu" value="day 1">
                                <input type="hidden" class="form-control" name="search" value="{{$search}}">
                                <button class="btn  ml-2 {{ ($currentSortmenu == 'day 1') ? 'btn-warning' : ''}}"
                                    type="submit">1 Hari</button>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="d-flex col-lg-6 align-items-center">
                            <h4>Total Pengeluaran:
                                Rp {{ number_format ($totalExpense , 0 , '.', '.') }}</h4>
                        </div>
                        <div class="d-flex flex-row-reverse col-lg-6">
                            <div class="col-md-10">
                                <form action="{{ url('admin/transactions/searchTransactionreport') }}">
                                    <div class="input-group mb-3">
                                        <input type="hidden" class="form-control" name="sortmenu"
                                            value="{{$currentSortmenu }}">
                                        <input type="text" class="form-control" placeholder="Search.." name="search"
                                            value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @include('admin.partials.flash')
                    <table class="table table-bordered table-stripped">
                        <thead>
                            <th>#ID</th>
                            <th>Produk</th>
                            <th>Jenis</th>
                            <th>Total Harga</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->id }}</td>
                                <td>{{ $transaction->product->name }}</td>
                                @if($transaction->type == "1")
                                <td>
                                    <div class="bg-primary text-white" align="center">Barang masuk</div>
                                </td>
                                <td>
                                    <div class="text-primary">Rp
                                        {{ number_format ($transaction->amount * $transaction->product->purchaseprice , 0 , '.', '.') }}
                                    </div>
                                </td>
                                @else
                                <td>
                                    <div class="bg-success text-white" align="center">Barang keluar</div>
                                </td>
                                <td>
                                    <div class="text-success">Rp
                                        {{ number_format ($transaction->amount * $transaction->product->sellingprice , 0 , '.', '.') }}
                                    </div>
                                </td>
                                @endif
                                <td>{{ $transaction->amount }}</td>
                                <td>{{ date('Y-m-d', strtotime($transaction->created_at) )}}</td>
                                <td>{{ date('H:i', strtotime($transaction->created_at) )}}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">No records found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $transactions->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection