<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transaction;
use App\Order;
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
}