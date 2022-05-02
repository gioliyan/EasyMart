<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Product;
use Illuminate\Http\Request;

use Str;
use Auth;
use DB;
use Session;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        parent::__construct();

        $this->data['currentAdminMenu'] = 'transactions';
        $this->data['currentAdminSubMenu'] = 'add';
    }

    public function index()
    {
        $this->data['currentAdminSubMenu'] = 'input stok';
        $this->data['products'] = Product::orderBy('name', 'ASC')->paginate(10);

        return view('admin.transactions.index', $this->data); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($product_id)
    {
        $this->data['product'] = Product::find($product_id);
        return view('admin.transactions.form', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product_id = $request->product_id;
        $this->data['user_id'] = Auth::user()->id;
        $this->data['product_id'] = $product_id;
        $this->data['type'] = $request->type;
        $this->data['amount'] = $request->amount;
        $this->data['initial_amount'] = Product::where('id', '=', $product_id)->firstOrFail()->stock;


        $transaction = Transaction::create($this->data);
        if ($transaction) {
            $this->data['price'] = $request->amount;
            $this->data['transaction_id'] = $transaction->id;
            app('App\Http\Controllers\RestockBatchController')->store($this->data);
            // app('App\Http\Controllers\ProductController')->updateStok($this->data);
        }
        return redirect('admin/transactions');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}