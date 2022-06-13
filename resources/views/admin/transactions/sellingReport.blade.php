@extends('admin.layout')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Laporan Penjualan</h2>
                </div>
                <div class="card-body row">
                    <div class="mb-3 d-flex col-lg-6 align-items-center pl-0">
                        <h4>Pemasukan Total :
                            Rp {{ number_format ($totalRevenue , 0 , '.', '.') }}</h4>
                    </div>
                    <div class="mb-3 d-flex flex-row-reverse col-lg-6 pr-0">
                        <a type="button" class="btn  ml-2 {{ ($currentSortmenu == 'all day') ? 'btn-warning' : ''}}"
                            href="{{ url('admin/transactions/sellingReport') }}">Semua Hari</a>
                        <a type="button" class="btn  ml-2 {{ ($currentSortmenu == 'day 30') ? 'btn-warning' : ''}}"
                            href="{{ url('admin/transactions/sellingReportByDate/30') }}">30 Hari</a>
                        <a type="button" class="btn  ml-2 {{ ($currentSortmenu == 'day 7') ? 'btn-warning' : ''}}"
                            href="{{ url('admin/transactions/sellingReportByDate/7') }}">7 Hari</a>
                        <a type="button" class="btn  ml-2 {{ ($currentSortmenu == 'day 1') ? 'btn-warning' : ''}}"
                            href="{{ url('admin/transactions/sellingReportByDate/1') }}">1 Hari</a>
                    </div>
                    @include('admin.partials.flash')
                    <table class="table table-bordered table-stripped">
                        <thead>
                            <th>Order ID</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Token</th>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                            <tr>
                                <td>{{ $order->no_order }}</td>
                                <td>Rp {{ number_format ($order->total , 0 , '.', '.') }}</td>
                                <td>{{ date('Y-m-d', strtotime($order->updated_at) )}}</td>
                                <td>{{ date('H:i', strtotime($order->updated_at) )}}</td>
                                <td>{{ $order->payment_type }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">No records found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $orders->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection