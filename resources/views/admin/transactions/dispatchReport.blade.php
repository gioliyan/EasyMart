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
                    <div class="mb-3 d-flex flex-row-reverse">
                        <a type="button" class="btn  ml-2 {{ ($currentSortmenu == 'all day') ? 'btn-warning' : ''}}"
                            href="{{ url('admin/transactions/dispatchReport') }}">Semua Hari</a>
                        <a type="button" class="btn  ml-2 {{ ($currentSortmenu == 'day 30') ? 'btn-warning' : ''}}"
                            href="{{ url('admin/transactions/dispatchReportbydate/30') }}">30 Hari</a>
                        <a type="button" class="btn  ml-2 {{ ($currentSortmenu == 'day 7') ? 'btn-warning' : ''}}"
                            href="{{ url('admin/transactions/dispatchReportbydate/7') }}">7 Hari</a>
                        <a type="button" class="btn  ml-2 {{ ($currentSortmenu == 'day 1') ? 'btn-warning' : ''}}"
                            href="{{ url('admin/transactions/dispatchReportbydate/1') }}">1 Hari</a>
                    </div>
                    @include('admin.partials.flash')
                    <table class="table table-bordered table-stripped">
                        <thead>
                            <th>#ID</th>
                            <th>Produk</th>
                            <th>Toal Harga</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->id }}</td>
                                <td>{{ $transaction->product->name }}</td>
                                <td>Rp {{ number_format ($transaction->amount * $transaction->product->sellingprice , 0 , '.', '.') }}</td>
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