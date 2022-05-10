<?php

namespace App\Http\Controllers;

use App\Order;
use App\Cart;
use App\Product;
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
                'email' => 'budi.pra@example.com',
                'phone' => $order->phone_number,
            ),
        );
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        Order::where('no_order', $order->no_order)->first()->update(['token' => $snapToken]);        
    }

    public function confirmOrder(Request $request){
        $order = Order::where('no_order', $request['order_id'])->first();
        $orderEncryption = sha512($order->no_order+'200'+$order->total+serverkey);

        if ($request['signature_key'] == $orderEncryption) {
            $order->transaction_status = $request['transaction_status'];
            $order->settlement_time = $request['settlement_time'];
            $order->payment_type = $request['payment_type'];
            $order->save();
        }
        
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