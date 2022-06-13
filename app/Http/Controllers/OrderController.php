<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderDetail;
use App\Cart;
use App\Product;
use App\RestockBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($transaction)
    {
        $order = Order::create([
            'no_order' => 'OD-'.date('Ymd').rand(1111,9999),
            'total' => $transaction->sum('total'),
            'payment' => $transaction->sum('total'),
            'change' => 0,
            'transaction_status' => 'pending',
            'token' => rand(1111,9999).'xxi'.rand(1111,9999),
        ]);
        app('App\Http\Controllers\OrderController')->requestPayment($order);
        return $order;
    }

    public function getOrderStatus(Request $request){
        $this->data['orderStatus'] = Order::where('no_order', $request->no_order)->first();
        return response()->json($this->data,200); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {

    }

    public function showNota(Request $request)
    {
        foreach ($request->all() as $queue) {

            $cart = Cart::create([
                'product_id' => $queue['product_id'],
                'qty' => $queue['qty'],
                'total' => $queue['qty'] * $queue['price'],
            ]);
        }

        if (Cart::count()>0) {
            $transaction = Cart::get();
            $order = app('App\Http\Controllers\OrderController')->store($transaction);
            foreach ($transaction as $value) {
                app('App\Http\Controllers\OrderDetailController')->store($value,$order);
                Cart::where('id', $value->id)->delete();
            }
            $this->data['order'] = Order::where('id', $order->id)
                            ->with('orderDetails','orderDetails.product')
                            ->first();
            
            return response()->json($this->data,200);
        }
    }

    public function sellingReport(){
        $this->data['currentAdminMenu'] = 'reports';
        $this->data['currentAdminSubMenu'] = 'selling report';
        $this->data['currentSortmenu'] = 'all day';
        $this->data['totalRevenue'] = Order::where('transaction_status', 'settlement')
                            ->sum('payment');
        $this->data['orders'] = Order::where('transaction_status', 'settlement')
                            ->orderBy('updated_at', 'DESC')
                            ->paginate(10);
        return view('admin.transactions.sellingReport', $this->data);
    }

    public function sellingReportByDate(int $days){
        $this->data['currentAdminMenu'] = 'reports';
        $this->data['currentAdminSubMenu'] = 'selling report';
        $this->data['currentSortmenu'] = 'day '.$days;
        $date = \Carbon\Carbon::today()->subDays($days);
        $this->data['orders'] = Order::where('transaction_status', 'settlement')
                            ->where('updated_at', '>=', $date)
                            ->orderBy('updated_at', 'DESC')
                            ->paginate(10);
        $this->data['totalRevenue'] = Order::where('transaction_status', 'settlement')
                                    ->where('updated_at', '>=', $date)
                                    ->sum('payment');
        return view('admin.transactions.sellingReport', $this->data);
    }

    public function searchSellingreport(Request $request)
    {
        $search = $request->search;
        $days = substr($request->sortmenu,4);
        $from = date($request->from);
        $to = date($request->to);
        $this->data['currentAdminMenu'] = 'reports';
        $this->data['currentAdminSubMenu'] = 'selling report';
        $this->data['currentSortmenu'] = 'all day';
        $this->data['totalRevenue'] = Order::where('transaction_status', 'settlement')
                                ->whereBetween('updated_at', [$from, $to])
                                ->sum('payment');
        $this->data['orders'] = Order::where('transaction_status', 'settlement')
                                ->whereBetween('updated_at', [$from, $to])
                                ->orderBy('updated_at', 'DESC')
                                ->paginate(10);
        // $this->data['products'] = Product::with('productImages','category')
        //                             ->select('products.*',DB::raw("SUM(amount) AS total"))
        //                             ->leftJoin('restock_batches', 'products.id', '=', 'restock_batches.product_id')
        //                             ->groupBy('restock_batches.product_id')
        //                             ->where('name', 'like', '%' . $search . '%')
        //                             ->paginate(10);
        return view('admin.transactions.sellingReport', $this->data);    
    }

    public function requestPayment(Order $order){

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = 'SB-Mid-server-dx5ECjkef78uZ5c8_pl2_pGU';
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;
 
        $params = array(
            'transaction_details' => array(
                'order_id' => $order->no_order,
                'gross_amount' => $order->total,
            ),
            'customer_details' => array(
                'first_name' => 'Fumiko',
                'last_name' => 'Vape Store',
                'email' => 'chrisdionisius@gmail.com',
                'phone' => '088235906292',
            ),
        );
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        Order::where('no_order', $order->no_order)->first()->update(['token' => $snapToken]);        
    }


    public function jajalEncrypt(Request $request){
        $order = Order::where('no_order', $request->order_id)->first();
        $orderEncryption = hash("sha512",$order->no_order.'200'.$order->total.'.00'.'SB-Mid-server-dx5ECjkef78uZ5c8_pl2_pGU');
        return $orderEncryption;
    }
    public function confirmOrder(Request $request){
        $orderPending = Order::where('no_order', $request['order_id'])->first();
        $orderEncryption = hash("sha512",$orderPending->no_order.'200'.$orderPending->total.'.00'.'SB-Mid-server-dx5ECjkef78uZ5c8_pl2_pGU');
        if ($request->signature_key == $orderEncryption 
            && $orderPending->transaction_status == 'pending' 
            && $request->transaction_status == 'settlement') {
            $orderPending->transaction_status = $request['transaction_status'];
            $orderPending->settlement_time = $request['settlement_time'];
            $orderPending->payment_type = $request['payment_type'];
            $orderPending->save();
            //ambil query daftar product dari order 
            $orders = OrderDetail::where('order_id', $orderPending->id)->get();
            foreach ($orders as $order) {
                $requestedAmount = $order->qty;
                while ($requestedAmount > 0) {
                    $storedAmount = 0;
                    //ambil data batch teratas dengan sisa stok > 0
                    $batch = RestockBatch::where('product_id', $order->product_id)
                            ->where('amount','>',0)
                            ->orderBy('id', 'asc')
                            ->first();
                    $remainingStockAmount = $batch->amount;
                    //cek apabila jumlah produk yang diminta < stok yang dimiliki
                    if ($remainingStockAmount > $requestedAmount) {
                        $storedAmount = $requestedAmount;
                        $remainingStockAmount = $remainingStockAmount - $requestedAmount;
                        $requestedAmount = 0;
                    }
                    //cek apabila jumlah produk yang diminta > stok yang dimiliki
                    else{
                        $storedAmount = $remainingStockAmount;
                        $requestedAmount = $requestedAmount - $remainingStockAmount;
                        $remainingStockAmount = 0;
                    }
                    //simpan perubahan jumlah stok pada table Restock Batch
                    $batch->amount = $remainingStockAmount;
                    $batch->save();
                    //tambahkan data transaksi penjualan
                    $this->data['product_id'] = $order->product_id;
                    $this->data['batch_id'] = $batch->id;
                    $this->data['storedAmount'] = $storedAmount;
                    app('App\Http\Controllers\TransactionController')->sellingTransaction($this->data);
                }
                // return $batch;
            }
        }
        return $orderPending;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}