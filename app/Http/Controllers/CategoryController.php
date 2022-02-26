<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        parent::__construct();

        $this->data['currentAdminMenu'] = 'produk';
        $this->data['currentAdminSubMenu'] = 'tambah';
    }
    
    public function index()
    {
        $this->data['categories'] = Category::orderBy('nama', 'ASC')->paginate(10);
        return view('admin.categories.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.form',$this->data);
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

        if (Category::create($params)) {
            Session::flash('success', 'Category has been saved');
        }
        return redirect('admin/kategoris');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $category = Category::findOrFail($category->id);
        $categories = Category::orderBy('nama', 'asc')->get();

        $this->data['categories'] = $categories;
        $this->data['category'] = $category;
        return view('admin.categories.form', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $params = $request->except('_token');

        if ($category->update($params)) {
            Session::flash('success', 'Category has been updated.');
        }

        return redirect('admin/kategoris');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if ($category->delete()) {
            Session::flash('success', 'Category has been deleted');
        }

        return redirect('admin/kategoris');
    }
}
