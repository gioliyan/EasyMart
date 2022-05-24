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

    public function requestPayment(Order $order){

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = 'SB-Mid-server-il7RmP0ASZ_1g70GlP5SCg6T';
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

    public function confirmOrder(Request $request){
        $order = Order::where('no_order', $request['order_id'])->first();
        // $orderEncryption = sha512($order->no_order+'200'+$order->total+serverkey);

        // if ($request['signature_key'] == $orderEncryption) {
        //     $order->transaction_status = $request['transaction_status'];
        //     $order->settlement_time = $request['settlement_time'];
        //     $order->payment_type = $request['payment_type'];
        //     $order->save();
        // }

        //ambil query daftar product dari order 
        $orders = OrderDetail::where('order_id', $order->id)->get();
        
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
        return $orders;
        
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