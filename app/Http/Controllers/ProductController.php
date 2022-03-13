<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Http\Request;

use Str;
use Auth;
use DB;
use Session;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        parent::__construct();

        $this->data['currentAdminMenu'] = 'product';
        $this->data['currentAdminSubMenu'] = 'add';
    }


    public function index()
    {
        $this->data['products'] = Product::orderBy('name', 'ASC')->paginate(10);

        return view('admin.products.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::pluck('name', 'id');

        $this->data['categories'] = $categories;
        $this->data['product'] = null;
        $this->data['productID'] = 0;
        $this->data['categoryIDs'] = [];

        return view('admin.products.form', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->request->add(['stock' => '0']);
        $params = $request->except('_token');

        if (Product::create($params)) {
            Session::flash('success', 'Product has been saved');
        }else {
            Session::flash('error', 'Product could not be saved');
        }

        return redirect('admin/products');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(  $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        if (empty($product)) {
            return redirect('admin/products/create');
        }

        $categories = Category::pluck('name', 'id');

        $this->data['categories'] = $categories;
        $this->data['product'] = $product;
        $this->data['productID'] = $product->id;
        $this->data['categoryIDs'] = $product->category->pluck('id')->toArray();

        return view('admin.products.form', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $params = $request->except('_token');

        if ($product->update($params)) {
            Session::flash('success', 'Product updated successfully');
        }

        return redirect('admin/products');
    }


    public function updateStok($request)
    {
        $productInventory = Product::where('id', '=', $request['product_id'])->firstOrFail();
        
        if ($request['type'] == 1) {
            $productInventory->stok += $request['count'];
        }else{
            $productInventory->stok -= $request['count'];
        }
        $productInventory->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product->delete()) {
            Session::flash('success', 'Produk berhasil dihapus');
        }

        return redirect('admin/products');
    }
}
