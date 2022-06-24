@extends('admin.layout')

@section('content')
<div class="content-wrapper">
    <div class="content">
        <div class="invoice-wrapper rounded border bg-white py-5 px-3 px-md-4 px-lg-5">
            <div class="d-flex justify-content-between">
                <h2 class="text-dark font-weight-medium">Invoice #{{$order->no_order}}</h2>
                <div class="btn-group">
                    <button class="btn btn-sm btn-secondary">
                        <i class="mdi mdi-content-save"></i> Save
                    </button>
                    <button class="btn btn-sm btn-secondary">
                        <i class="mdi mdi-printer"></i> Print
                    </button>
                </div>
            </div>
            <div class="row pt-5">
                <div class="col-xl-3 col-lg-4">
                    <p class="text-dark mb-2">Details</p>
                    <address>
                        Invoice ID:
                        <span class="text-dark">#{{$order->no_order}}</span>
                        <br />
                        {{ date('F d, Y ', strtotime($order->updated_at) )}}
                        <br />
                        {{ date('H:i', strtotime($order->updated_at) )}}
                        <br />
                        {{$order->payment_type}}
                    </address>
                </div>
            </div>
            <table class="table mt-3 table-striped table-responsive table-responsive-large" style="width: 100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Unit Cost</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderDetails as $orderDetail)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$orderDetail->product->name}}</td>
                        <td>{{$orderDetail->qty}}</td>
                        <td>Rp {{ number_format ($orderDetail->total/$orderDetail->qty , 0 , '.', '.') }}</td>
                        <td>Rp {{ number_format ($orderDetail->total , 0 , '.', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row justify-content-end">
                <div class="col-lg-5 col-xl-4 col-xl-3 ml-sm-auto">
                    <ul class="list-unstyled mt-4">
                        <li class="mid pb-3 text-dark">
                            Subtotal
                            <span class="d-inline-block float-right text-default">Rp
                                {{ number_format ($order->total , 0 , '.', '.') }}</span>
                        </li>
                        <li class="mid pb-3 text-dark">
                            Change
                            <span class="d-inline-block float-right text-default">Rp
                                {{ number_format ($order->change , 0 , '.', '.') }}</span>
                        </li>
                        <li class="pb-3 text-dark">
                            Total
                            <span class="d-inline-block float-right">Rp
                                {{ number_format ($order->total , 0 , '.', '.') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="right-sidebar-2">
        <div class="right-sidebar-container-2">
            <div class="slim-scroll-right-sidebar-2">
                <div class="right-sidebar-2-header">
                    <h2>Layout Settings</h2>
                    <p>User Interface Settings</p>
                    <div class="btn-close-right-sidebar-2">
                        <i class="mdi mdi-window-close"></i>
                    </div>
                </div>

                <div class="right-sidebar-2-body">
                    <span class="right-sidebar-2-subtitle">Header Layout</span>
                    <div class="no-col-space">
                        <a href="javascript:void(0);"
                            class="btn-right-sidebar-2 header-fixed-to btn-right-sidebar-2-active">Fixed</a>
                        <a href="javascript:void(0);" class="btn-right-sidebar-2 header-static-to">Static</a>
                    </div>

                    <span class="right-sidebar-2-subtitle">Sidebar Layout</span>
                    <div class="no-col-space">
                        <select class="right-sidebar-2-select" id="sidebar-option-select">
                            <option value="sidebar-fixed">Fixed Default</option>
                            <option value="sidebar-fixed-minified">
                                Fixed Minified
                            </option>
                            <option value="sidebar-fixed-offcanvas">
                                Fixed Offcanvas
                            </option>
                            <option value="sidebar-static">Static Default</option>
                            <option value="sidebar-static-minified">
                                Static Minified
                            </option>
                            <option value="sidebar-static-offcanvas">
                                Static Offcanvas
                            </option>
                        </select>
                    </div>

                    <span class="right-sidebar-2-subtitle">Header Background</span>
                    <div class="no-col-space">
                        <a href="javascript:void(0);"
                            class="btn-right-sidebar-2 btn-right-sidebar-2-active header-light-to">Light</a>
                        <a href="javascript:void(0);" class="btn-right-sidebar-2 header-dark-to">Dark</a>
                    </div>

                    <span class="right-sidebar-2-subtitle">Navigation Background</span>
                    <div class="no-col-space">
                        <a href="javascript:void(0);"
                            class="btn-right-sidebar-2 btn-right-sidebar-2-active sidebar-dark-to">Dark</a>
                        <a href="javascript:void(0);" class="btn-right-sidebar-2 sidebar-light-to">Light</a>
                    </div>

                    <span class="right-sidebar-2-subtitle">Direction</span>
                    <div class="no-col-space">
                        <a href="javascript:void(0);"
                            class="btn-right-sidebar-2 btn-right-sidebar-2-active ltr-to">LTR</a>
                        <a href="javascript:void(0);" class="btn-right-sidebar-2 rtl-to">RTL</a>
                    </div>

                    <div class="d-flex justify-content-center" style="padding-top: 30px">
                        <div id="reset-options" style="width: auto; cursor: pointer"
                            class="btn-right-sidebar-2 btn-reset">
                            Reset Settings
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection