<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use App\ProductImage;
use Illuminate\Http\Request;
use App\Http\Requests\ProductImageRequest;


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
        $this->data['currentAdminSubMenu'] = 'manage';
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
        $params = $request->except('_token');
        $product = Product::create($params);
        if ($product) {
            Session::flash('success', 'Product has been saved');
            $request->request->add(['amount' => '0']);
            $request->request->add(['product_id' => $product->id]);
            app('App\Http\Controllers\RestockBatchController')->store($request);
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
    public function show(Product $product)
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
            $productInventory->stock += $request['amount'];
        }else{
            $productInventory->stock -= $request['amount'];
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

    public function images($id)
    {
        if (empty($id)) {
            return redirect('admin/products/create');
        }

        $product = Product::findOrFail($id);

        $this->data['productID'] = $id;
        $this->data['productImages'] = $product->productImages;

        return view('admin.products.images', $this->data);
    }

    public function add_image($id)
    {
        if (empty($id)) {
            return redirect('admin/products');
        }

        $product = Product::findOrFail($id);

        $this->data['productID'] = $id;
        $this->data['product'] = $product;

        return view('admin.products.image_form', $this->data);
    }

    public function upload_image(ProductImageRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($request->has('image')) {
            $image = $request->file('image');
            $name = $product->slug . "_" . time();
            $fileName = $name . "." . $image->getClientOriginalExtension();

            $folder = '/uploads/images';
            $filePath = $image->storeAs($folder, $fileName, 'public');

            $params = [
                'product_id' => $product->id,
                'path' => $filePath,
            ];

            if (ProductImage::create($params)) {
                Session::flash('success', 'Image has been uploaded');
            }else {
                Session::flash('error', 'Image could not be uploaded');
            }

            return redirect('admin/products/' . $id . '/images');
        }
    }

    public function delete_image($id)
    {
        $image = ProductImage::findOrFail($id);

        if($image->delete()){
            Session::flash('success', 'Image has been deleted');
        }

        return redirect('admin/products/' . $image->product->id . '/images');
    }

    public function getAllProducts(){
        $this->data['categories'] = Category::orderBy('name', 'ASC')->get();
        $this->data['products'] = Product::with('productImages','category')
                    ->select('products.*',DB::raw("SUM(amount) AS total"))
                    ->leftJoin('restock_batches', 'products.id', '=', 'restock_batches.product_id')
                    ->groupBy('restock_batches.product_id')
                    ->orderBy('id', 'ASC')
                    ->paginate(10);
        return response()->json($this->data);
    }
    public function getProductsByCategory($category_id){
        $this->data['categories'] = Category::orderBy('name', 'ASC')->get();
        $this->data['products'] = Product::with('productImages','category')
                    ->select('products.*',DB::raw("SUM(amount) AS total"))
                    ->where('category_id', $category_id)
                    ->leftJoin('restock_batches', 'products.id', '=', 'restock_batches.product_id')
                    ->groupBy('restock_batches.product_id')
                    ->orderBy('id', 'ASC')
                    ->paginate(10);
        return response()->json($this->data);
    }
}