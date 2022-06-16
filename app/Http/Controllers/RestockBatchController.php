<?php

namespace App\Http\Controllers;

use App\RestockBatch;
use Illuminate\Http\Request;

class RestockBatchController extends Controller
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
    public function store(Request $request)
    {
        $params = $request->except('_token');
        if ($request->amount > 0) {
            $restockBatch = RestockBatch::create($params);
        }
        return $restockBatch;
    }
    public function storeInit(Request $request)
    {
        $params = $request->except('_token');

        $restockBatch = RestockBatch::create($params);
        return $restockBatch;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RestockBatch  $restockBatch
     * @return \Illuminate\Http\Response
     */
    public function show(RestockBatch $restockBatch)
    {
        //
    }
    public function showBySearch(Request $keyword){
        $this->data['restockBatch'] = RestockBatch::select('restock_batches.*')
                                    ->leftJoin('products', 'restock_batches.product_id', '=', 'products.id')
                                    ->where('products.name', 'like','%'.$keyword->key.'%')
                                    ->paginate(10);
        return $this->data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RestockBatch  $restockBatch
     * @return \Illuminate\Http\Response
     */
    public function edit(RestockBatch $restockBatch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RestockBatch  $restockBatch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RestockBatch $restockBatch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RestockBatch  $restockBatch
     * @return \Illuminate\Http\Response
     */
    public function destroy(RestockBatch $restockBatch)
    {
        //
    }
}