<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Product;
use App\Category;
use App\RestockBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Response;
use Carbon\Carbon;

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
        // Document::groupBy('users_editor_id')
        //         ->selectRaw('sum(no_of_pages) as sum, users_editor_id')
        //         ->pluck('sum','users_editor_id');
        // $this->data['restockBatch'] = 
        //         RestockBatch::groupBy('product_id')
        //         ->selectRaw('sum(amount) as sum, product_id')
        //         ->pluck('sum','product_id');
        // $this->data['products'] = Product::orderBy('name', 'ASC')->paginate(10);
        $this->data['categories'] = Category::orderBy('name', 'ASC')->get();
        $this->data['products'] = Product::with('productImages','category')
                    ->select('products.*',DB::raw("SUM(amount) AS total"))
                    ->leftJoin('restock_batches', 'products.id', '=', 'restock_batches.product_id')
                    ->groupBy('restock_batches.product_id')
                    ->orderBy('id', 'ASC')
                    ->paginate(10);

        // $this->data['products']=DB::select("
        // SELECT p.id,p.name,p.purchaseprice,p.sellingprice,p.description, x.total
        // FROM products p 
        // JOIN (
        //     SELECT
        //       product_id,
        //       SUM(amount) AS total
        //     FROM restock_batches
        //     GROUP BY product_id) x
        // ON p.id = x.product_id");

        
        // $products = DB::table('products as p')
        //     ->leftJoin('restock_batches as r', 'p.id', '=', 'r.product_id')
        //     ->groupBy('p.id')
        //     ->get();

        
        // return $this->data['raw'];
        // return Response::json($products,200);
        // return response()->json($this->data);
        return view('admin.transactions.index', $this->data); 
    }

    public function listTransaction()
    {
        $this->data['currentAdminMenu'] = 'reports';
        $this->data['currentAdminSubMenu'] = 'transaction report';
        $this->data['transactions'] = Transaction::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.transactions.listTransaction', $this->data);
    }

    public function dispatchReport()
    {
        $this->data['currentAdminMenu'] = 'reports';
        $this->data['currentAdminSubMenu'] = 'dispatch report';
        $this->data['transactions'] = Transaction::where('type', 2)->orderBy('id', 'DESC')->paginate(10);
        return view('admin.transactions.dispatchReport', $this->data);
    }

    public function stockReport()
    {
        $this->data['currentAdminMenu'] = 'reports';
        $this->data['currentAdminSubMenu'] = 'stock report';
        $this->data['products'] = Product::with('productImages','category')
                                    ->select('products.*',DB::raw("SUM(amount) AS total"))
                                    ->leftJoin('restock_batches', 'products.id', '=', 'restock_batches.product_id')
                                    ->groupBy('restock_batches.product_id')
                                    ->orderBy('id', 'ASC')
                                    ->paginate(10);
        return view('admin.transactions.stockReport', $this->data);
    }

    public function transactionReport()
    {
        $this->data['currentAdminMenu'] = 'reports';
        $this->data['currentAdminSubMenu'] = 'transaction report';
        $this->data['transactions'] = Transaction::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.transactions.transactionReport', $this->data);
    }

    public function purchaseReport()
    {
        $this->data['currentAdminMenu'] = 'reports';
        $this->data['currentAdminSubMenu'] = 'purchase report';
        $this->data['currentSortmenu'] = 'all day';
        $this->data['restocks'] = RestockBatch::orderBy('product_id', 'DESC')->paginate(10);
        return view('admin.transactions.purchaseReport', $this->data);
    }

    public function purchaseReportbydate(int $days)
    {
        $this->data['currentAdminMenu'] = 'reports';
        $this->data['currentAdminSubMenu'] = 'purchase report';
        $this->data['currentSortmenu'] = 'day '.$days;
        $date = \Carbon\Carbon::today()->subDays($days);
        $this->data['restocks'] = RestockBatch::where('created_at', '>=', $date)->paginate(10);
        return view('admin.transactions.purchaseReport', $this->data);
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
        $this->data['$request->type'] = $request->type;
        $this->data['amount'] = $request->amount;
        $this->data['initial_amount'] = Product::where('id', '=', $product_id)->firstOrFail()->stock;
        $restockBatch = app('App\Http\Controllers\RestockBatchController')->store($request);
        $updateProduct = app('App\Http\Controllers\ProductController')->update($request,Product::find($product_id));
        if ($restockBatch && $updateProduct) {
            $this->data['batch_id'] = $restockBatch->id;
            $transaction = Transaction::create($this->data);
        }
        
        // if ($transaction) {
        //     $this->data['price'] = $request->amount;
        //     $this->data['transaction_id'] = $transaction->id;
        //     app('App\Http\Controllers\RestockBatchController')->store($this->data);
        //     app('App\Http\Controllers\ProductController')->updateStok($this->data);
        // }
        return $this->data;
        // return redirect('admin/transactions');
    }

    public function sellingTransaction($request){
        $this->data['user_id'] = 1;
        $this->data['product_id'] = $request['product_id'];
        $this->data['type'] = 2;
        $this->data['amount'] = $request['storedAmount'];      
        $this->data['batch_id'] = $request['batch_id'];
        //ambil harga jual saat ini
        $sellingPrice = Product::where('id', '=', $request['product_id'])->firstOrFail()->sellingprice;
        //ambil harga beli/kulak sebelumnya
        $purchasePrice = RestockBatch::where('id', '=', $request['batch_id'])->firstOrFail()->purchaseprice;
        //insert total margin
        $this->data['margin'] = $sellingPrice - $purchasePrice;
        $transaction = Transaction::create($this->data);
        
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