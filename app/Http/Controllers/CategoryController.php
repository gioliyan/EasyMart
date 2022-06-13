<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

use Str;
use Session;

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

        $this->data['currentAdminMenu'] = 'product';
        $this->data['currentAdminSubMenu'] = 'tambah';
    }
    
    public function index()
    {
        $this->data['categories'] = Category::where('isActive','=',1)->orderBy('name', 'ASC')->paginate(10);
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

        try {
            Category::create($params);
            Session::flash('success', 'Kategori berhasil disimpan');
        } catch (\Throwable $th) {
            Session::flash('error', 'Kategori tidak bisa disimpan, terdapat duplikasi nama kategori');
        }

        // if (Category::create($params)) {
        //     Session::flash('success', 'Category has been saved');
        // }else{
        //     Session::flash('error', 'Category has not been saved');
        // }
        return redirect('admin/categories');
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
        $categories = Category::orderBy('name', 'asc')->get();

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

        try {
            $category->update($params);
            Session::flash('success', 'Kategori berhasil diperbarui');
        } catch (\Throwable $th) {
            Session::flash('error', 'Kategori tidak bisa diperbarui, terdapat duplikasi nama kategori');
        }

        return redirect('admin/categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->isActive = 0;
        if ($category->save()) {
            Session::flash('success', 'Kategori berhasil dihapus');
        }
        return redirect('admin/categories');
    }
}