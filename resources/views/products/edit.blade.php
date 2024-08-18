@extends('layout')
@section('title')
<?= get_label('edit_product', 'Edit Product') ?>
@endsection
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{url('/home')}}"><?= get_label('home', 'Home') ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{url('/products')}}"><?= get_label('stock', 'Stock') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= get_label('edit', 'Edit') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('products/update/'. $product->id) }}" method="POST" class="form-submit-event" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect_url" value="/products">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="name" class="form-label"><?= get_label('product_name', 'Product Name') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="name" name="name" placeholder="<?= get_label('please_enter_product_name', 'Please enter product name') ?>" value="{{ old('name', $product->name) }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="description" class="form-label"><?= get_label('description', 'Description') ?></label>
                        <textarea class="form-control" id="description" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description')?>">{{ old('description', $product->description) }}</textarea>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="category"><?= get_label('category', 'Category') ?> <span class="asterisk">*</span></label>
                        <select class="form-select text-capitalize" id="category_id" name="category_id">
                            <option value="">{{ get_label('please_select', 'Please select') }}</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('category_id', $product->product_category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ ucfirst($cat->name_cat) }}
                                </option>
                            @endforeach
                        </select>

                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="price" class="form-label"><?= get_label('price', 'Price') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="price" name="price" placeholder="<?= get_label('please_enter_price', 'Please enter price') ?>" value="{{ old('price', $product->price) }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="stock" class="form-label"><?= get_label('stock', 'Stock') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="stock" name="stock" placeholder="<?= get_label('please_enter_stock', 'Please enter stock') ?>" value="{{ old('stock', $product->stock) }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="stock_defective" class="form-label"><?= get_label('stock_defective', 'Stock Defective') ?></label>
                        <input class="form-control" type="text" id="stock_defective" name="stock_defective" placeholder="<?= get_label('please_enter_stock_defective', 'Please enter stock defective') ?>" value="{{ old('stock_defective', $product->stock_defective) }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="image" class="form-label"><?= get_label('product_image', 'Product Image') ?></label>
                        <div class="d-flex align-items-start align-items-sm-center gap-4 my-3">
                            <img src="{{ $product->photo ? asset('storage/' . $product->photo) : asset('storage/images/no-image.jpg') }}" alt="product-image" class="d-block rounded" height="100" width="100" id="uploadedImage" />
                            <div class="button-wrapper">
                                <div class="input-group d-flex">
                                    <input type="file" class="form-control" id="inputGroupFile02" name="photo">
                                </div>
                                <p class="text-muted mt-2"><?= get_label('allowed_jpg_png', 'Allowed JPG or PNG.') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" id="submit_btn" class="btn btn-primary me-2"><?= get_label('update', 'Update') ?></button>
                    <a href="{{ url('/products') }}" class="btn btn-outline-secondary"><?= get_label('cancel', 'Cancel') ?></a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
