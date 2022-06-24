<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transaction;
use App\Order;
use App\OrderDetail;
use App\Product;
use App\RestockBatch;
use DB;


class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data['currentAdminMenu'] = 'dashboard';
        $this->data['currentAdminSubMenu'] = 'dashboard';
        // $this->data['deadStocksA'] = Product::select('products.*',DB::raw("SUM(restock_batches.amount) AS total"))
        //                         ->leftJoin('transactions', 'products.id', '=', 'transactions.product_id')
        //                         ->leftJoin('restock_batches', 'products.id', '=', 'restock_batches.product_id')
        //                         ->WhereNull('transactions.product_id')
        //                         ->where('products.created_at', '<=', date('Y-m-d', strtotime('1 day ago')))
        //                         ->where('products.isActive', '=', 1)
        //                         ->groupBy('restock_batches.product_id')
        //                         ->orderBy('total', 'desc');
        // $this->data['deadStocksB'] = Product::select('products.*',DB::raw("SUM(transactions.amount) AS total"))
        //                         ->leftJoin('transactions', 'products.id', '=', 'transactions.product_id')
        //                         ->where('products.created_at', '<=', date('Y-m-d', strtotime('1 day ago')))
        //                         ->where('products.isActive', '=', 1)
        //                         ->where('transactions.type', '=', 2)
        //                         ->groupBy('transactions.product_id')
        //                         ->having('total','<','2')
        //                         ->orderBy('total', 'desc');
        // $this->data['deadStocks'] = $this->data['deadStocksA']->union($this->data['deadStocksB'])->paginate(5);
        
        $this->data['emptyOrder'] = OrderDetail::select('order_details.*')
                                    ->join('orders', 'order_details.order_id', '=', 'orders.id')
                                    ->where('orders.transaction_status', '=', 'settlement');
        $this->data['deadStocks'] = Product::select("products.*",DB::raw("SUM(restock_batches.amount) AS total"))
                                ->leftJoinSub($this->data['emptyOrder'],'odj',function($join){
                                    $join->on('products.id', '=', 'odj.product_id');
                                })->leftJoin('restock_batches', 'products.id', '=', 'restock_batches.product_id')
                                ->WhereNull('odj.product_id')
                                ->where('products.isActive', '=', 1)
                                ->where('products.created_at', '<=', date('Y-m-d', strtotime('1 month ago')))
                                ->groupBy('restock_batches.product_id')
                                ->having('total','>',0)
                                ->paginate(5);
    }

    function index()
    {
        $this->data['currentSortmenu'] = 'all day';
        
        $this->data['transactions'] = Transaction::get();
        $this->data['totalExistingStock'] = RestockBatch::sum('amount');
        $this->data['totalPurchasingAmount'] = Transaction::where('type',1)->sum('amount');
        $this->data['totalSellingAmount'] = Transaction::where('type',2)->sum('amount');
        
        
                
        $this->data['capital'] = Transaction::where('type',1)->sum('margin');
        $this->data['selling'] = Order::where('transaction_status','settlement')->sum('payment');
        $this->data['profit'] = $this->data['selling'] - $this->data['capital'];
        $this->data['sales'] = Transaction::where('type',2)
                                ->groupBy('product_id')
                                ->selectRaw('product_id, sum(amount) as amount')
                                ->orderBy('product_id','DESC')
                                ->take(5)
                                ->get();
        $this->data['inventories'] = Product::select('products.*',DB::raw("SUM(amount) AS total"))
                                ->leftJoin('restock_batches', 'products.id', '=', 'restock_batches.product_id')
                                ->groupBy('restock_batches.product_id')
                                ->orderBy('total', 'desc')
                                ->take(5)
                                ->get();
        
        return view('admin.dashboard.index',$this->data);
    }
    function indexByDate($days)
    {
        $days = substr($days,4);
        $date = \Carbon\Carbon::today()->subDays($days);
        $this->data['transactions'] = Transaction::where('created_at', '>=', $date)->get();
        $this->data['totalExistingStock'] = RestockBatch::sum('amount');
        $this->data['totalPurchasingAmount'] = Transaction::where('type',1)->where('created_at', '>=', $date)->sum('amount');
        $this->data['totalSellingAmount'] = Transaction::where('type',2)->where('created_at', '>=', $date)->sum('amount');
                
        $this->data['capital'] = Transaction::where('type',1)->where('created_at', '>=', $date)->sum('margin');
        $this->data['selling'] = Order::where('transaction_status','settlement')->where('created_at', '>=', $date)->sum('payment');
        $this->data['profit'] = $this->data['selling'] - $this->data['capital'];
        $this->data['sales'] = Transaction::where('type',2)
                                ->where('created_at', '>=', $date)
                                ->groupBy('product_id')
                                ->selectRaw('product_id, sum(amount) as amount')
                                ->orderBy('product_id','DESC')
                                ->take(5)
                                ->get();
        // $this->data['inventories'] = Product::take(5)
                                    
        //                             ->get();
        
        $this->data['inventories'] = Product::select('products.*',DB::raw("SUM(amount) AS total"))
                                ->leftJoin('restock_batches', 'products.id', '=', 'restock_batches.product_id')
                                ->groupBy('restock_batches.product_id')
                                ->orderBy('total', 'desc')
                                ->take(5)
                                ->get();
        return view('admin.dashboard.index',$this->data);
    }
    function indexByRange(Request $request)
    {
        $from = date($request->from);
        $to = date($request->to);
        $this->data['transactions'] = Transaction::whereBetween('created_at', [$from, $to])->get();
        $this->data['totalExistingStock'] = RestockBatch::sum('amount');
        $this->data['totalPurchasingAmount'] = Transaction::where('type',1)->whereBetween('created_at', [$from, $to])->sum('amount');
        $this->data['totalSellingAmount'] = Transaction::where('type',2)->whereBetween('created_at', [$from, $to])->sum('amount');
                
        $this->data['capital'] = Transaction::where('type',1)->whereBetween('created_at', [$from, $to])->sum('margin');
        $this->data['selling'] = Order::where('transaction_status','settlement')->whereBetween('created_at', [$from, $to])->sum('payment');
        $this->data['profit'] = $this->data['selling'] - $this->data['capital'];
        $this->data['sales'] = Transaction::where('type',2)
                                ->whereBetween('created_at', [$from, $to])
                                ->groupBy('product_id')
                                ->selectRaw('product_id, sum(amount) as amount')
                                ->orderBy('product_id','DESC')
                                ->take(5)
                                ->get();
        // $this->data['inventories'] = Product::take(5)
                                    
        //                             ->get();
        
        $this->data['inventories'] = Product::select('products.*',DB::raw("SUM(amount) AS total"))
                                ->leftJoin('restock_batches', 'products.id', '=', 'restock_batches.product_id')
                                ->groupBy('restock_batches.product_id')
                                ->orderBy('total', 'desc')
                                ->take(5)
                                ->get();
        return view('admin.dashboard.index',$this->data);
    }
}