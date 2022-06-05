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
                                <td> <div class="text-primary">Rp {{ number_format ($transaction->amount * $transaction->product->purchaseprice , 0 , '.', '.') }} </div> </td>
                                @else
                                <td>
                                    <div class="bg-success text-white" align="center">Barang keluar</div>
                                </td>
                                <td> <div class="text-success">Rp {{ number_format ($transaction->amount * $transaction->product->sellingprice , 0 , '.', '.') }} </div> </td>
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
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection