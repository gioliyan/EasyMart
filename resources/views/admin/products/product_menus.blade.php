<div class="card card-default">
    <div class="card-header card-header-border-bottom">
        <h2>Menu Product</h2>
    </div>
    <div class="card-body">
        <nav class="nav flex-column">
            <a class="nav-link" href="{{ url('admin/products/'. $productID .'/edit') }}">Detail Product</a>
            <a class="nav-link" href="{{ url('admin/products/'. $productID .'/images') }}">Product Image</a>
        </nav>
    </div>
</div>