@extends('admin.layout')

@section('content')

<div class="content-wrapper">
    <div class="content">
        <div class="row justify-content-end ">
            <div class="col d-flex justify-content-end">
                <div class="dropdown d-inline-block mb-1">
                    <button class="btn btn-outline-primary btn-square dropdown-toggle" type="button"
                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                        data-display="static">
                        Pilih Durasi
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="{{ url('admin/dashboard') }}">Semua hari</a>
                        <a class="dropdown-item" href="{{ url('admin/dashboard/day 1') }}">Hari ini</a>
                        <a class="dropdown-item" href="{{ url('admin/dashboard/day 7') }}">Minggu ini</a>
                        <a class="dropdown-item" href="{{ url('admin/dashboard/day 30') }}">Bulan ini</a>
                    </div>
                </div>
            </div>
        </div>
        <form action="{{ url('admin/dashboard/byRange') }}">
            <div class="row justify-content-end ">
                <div class="col-6 d-flex justify-content-end mb-1">
                    <div class="align-items-center d-flex">
                        <h5>Cari berdasarkan tanggal</h2>
                    </div>
                    <div class="col justify-content-end">
                        <input class="date form-control" type="text" name="from">
                    </div>
                    <div class="align-items-center d-flex">
                        <h5>-</h2>
                    </div>
                    <div class="col justify-content-end">
                        <input class="date form-control" type="text" name="to">
                    </div>
                    <div class="dropdown d-inline-block">
                        <button class="btn btn-outline-warning btn-square" type="submit" aria-haspopup="true"
                            aria-expanded="false" data-display="static">
                            <i class="mdi mdi-calendar-search"></i>
                            Cari
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-xl-3 col-sm-6">
                <div class="card card-mini mb-4">
                    <div class="card-body">
                        <h2 class="mb-1">{{$transactions->count()}}</h2>
                        <p>Total Transaksi</p>
                        <div class="chartjs-wrapper">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card card-mini  mb-4">
                    <div class="card-body">
                        <h2 class="mb-1">{{$totalPurchasingAmount}}</h2>
                        <p>Barang Masuk</p>
                        <div class="chartjs-wrapper">
                            <canvas id="dual-line"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card card-mini mb-4">
                    <div class="card-body">
                        <h2 class="mb-1">{{$totalSellingAmount}}</h2>
                        <p>Barang Keluar</p>
                        <div class="chartjs-wrapper">
                            <canvas id="area-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card card-mini mb-4">
                    <div class="card-body">
                        <h2 class="mb-1">{{$totalExistingStock}}</h2>
                        <p>Sisa stok saat ini</p>
                        <div class="chartjs-wrapper">
                            <canvas id="line"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-xl-3 col-sm-6">
                <div class="card card-mini  mb-4">
                    <div class="card-body">
                        <h2 class="mb-1">Rp {{number_format($capital)}}</h2>
                        <p>Total Pengeluaran</p>
                        <div class="chartjs-wrapper">
                            <canvas id="dual-line"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card card-mini mb-4">
                    <div class="card-body">
                        <h2 class="mb-1">Rp {{number_format($selling)}}</h2>
                        <p>Total Pemasukkan</p>
                        <div class="chartjs-wrapper">
                            <canvas id="line"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-sm-6">
                <div class="card card-mini mb-4">
                    <div class="card-body">
                        <h2 class="mb-1">Rp {{number_format($profit)}}</h2>
                        <p>Total Profit</p>
                        <div class="chartjs-wrapper">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 col-lg-6 col-12">
                <!-- Top Sell Table -->
                <div class="card card-table-border-none">
                    <div class="card-header justify-content-between">
                        <h2>Top Sales</h2>
                        <div>
                            <button class="text-black-50 mr-2 font-size-20"><i class="mdi mdi-cached"></i></button>
                            <div class="dropdown show d-inline-block widget-dropdown">
                                <a class="dropdown-toggle icon-burger-mini" href="#" role="button" id="dropdown-units"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    data-display="static"></a>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-units">
                                    <li class="dropdown-item"><a href="#">Action</a></li>
                                    <li class="dropdown-item"><a href="#">Another action</a></li>
                                    <li class="dropdown-item"><a href="#">Something else here</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-0 compact-units" data-simplebar style="height: 384px;">
                        <table class="table ">
                            <tbody>
                                @foreach($sales as $sale)
                                <tr>
                                    <td class="text-dark">{{$sale->product->name}}</td>
                                    <td class="text-center">{{$sale->amount}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="card-footer bg-white py-4">
                        <a href="#" class="btn-link py-3 text-uppercase">View Report</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-12">
                <!-- Top Sell Table -->
                <div class="card card-table-border-none">
                    <div class="card-header justify-content-between">
                        <h2>Most Inventory</h2>
                        <div>
                            <button class="text-black-50 mr-2 font-size-20"><i class="mdi mdi-cached"></i></button>
                            <div class="dropdown show d-inline-block widget-dropdown">
                                <a class="dropdown-toggle icon-burger-mini" href="#" role="button" id="dropdown-units"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    data-display="static"></a>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-units">
                                    <li class="dropdown-item"><a href="#">Action</a></li>
                                    <li class="dropdown-item"><a href="#">Another action</a></li>
                                    <li class="dropdown-item"><a href="#">Something else here</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-0 compact-units" data-simplebar style="height: 384px;">
                        <table class="table ">
                            <tbody>
                                @foreach($inventories as $inventory)
                                <tr>
                                    <td class="text-dark">{{$inventory->name}}</td>
                                    <td class="text-center">{{$inventory->total}}</td>
                                    <td class="text-right">
                                        {{round($inventory->total/$inventories->sum('total') *100)}}%
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="card-footer bg-white py-4">
                        <a href="#" class="btn-link py-3 text-uppercase">View Report</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-12">
                <!-- Top Sell Table -->
                <div class="card card-table-border-none">
                    <div class="card-header justify-content-between">
                        <h2>Dead Stocks</h2>
                        <div>
                            <button class="text-black-50 mr-2 font-size-20"><i class="mdi mdi-cached"></i></button>
                            <div class="dropdown show d-inline-block widget-dropdown">
                                <a class="dropdown-toggle icon-burger-mini" href="#" role="button" id="dropdown-units"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    data-display="static"></a>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-units">
                                    <li class="dropdown-item"><a href="#">Action</a></li>
                                    <li class="dropdown-item"><a href="#">Another action</a></li>
                                    <li class="dropdown-item"><a href="#">Something else here</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-0 compact-units" data-simplebar style="height: 384px;">
                        <table class="table ">
                            <tbody>
                                @foreach($deadStocks as $deadStock)
                                <tr>
                                    <td class="text-dark"><a
                                            href="{{ url('admin/products/'. $deadStock->id .'/edit') }}">{{$deadStock->name}}</a>
                                    </td>
                                    <td class="text-dark">{{$deadStock->total}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="card-footer bg-white py-4">
                        <a href="#" class="btn-link py-3 text-uppercase">View Report</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop