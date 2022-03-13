@extends('admin.layout')

@section('content')

@php
$formTitle = !empty($category) ? 'Update' : 'Add'
@endphp

<div class="content">
    <div class="row">
        <div class="col-lg-4">
            @include('admin.products.product_menus')
        </div>
        <div class="col-lg-8">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>{{ $formTitle }} Product</h2>
                </div>
                <div class="card-body">
                    @include('admin.partials.flash', ['$errors' => $errors])
                    @if (!empty($product))
                    {!! Form::model($product, ['url' => ['admin/products', $product->id], 'method' => 'PUT']) !!}
                    {!! Form::hidden('id') !!}
                    @else
                    {!! Form::open(['url' => 'admin/products']) !!}
                    @endif

                    <div class="form-group">
                        {!! Form::label('name', 'Product Name') !!}
                        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'product name',
                        'required' => 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('price', 'Price') !!}
                        {!! Form::text('price', null, ['class' => 'form-control', 'placeholder' => 'price','required' =>
                        'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('category_ids', 'Category') !!}
                        {!! Form::select('category_id', $categories, null, ['placeholder' => 'Chose Category', 'required'
                        => 'required']); !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('description', 'product description') !!}
                        {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' =>
                        'description', 'required' => 'required']) !!}
                    </div>
                    <div class="form-footer pt-5 border-top">
                        <button type="submit" class="btn btn-primary btn-default">Save</button>
                        <a href="{{ url('admin/products') }}" class="btn btn-secondary btn-default">Back</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection