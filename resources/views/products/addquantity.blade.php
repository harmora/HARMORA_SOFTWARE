@extends('layout')
@section('title')
<?= get_label('add_quantity', 'Add Quantity') ?>
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
                        <?= get_label('add_quantity', 'Add Quantity') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('products.add_quantity') }}" method="POST" class="form-submit-event">
                <input type="hidden" name="redirect_url" value="/products">
                @csrf
                <div class="row">
                    <!-- Product Selection -->
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="product_id"><?= get_label('select_product', 'Select Product') ?> <span class="asterisk">*</span></label>
                        <select class="form-select text-capitalize" id="product_id" name="product_id">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            @foreach ($products as $product)
                            <option value="{{$product->id}}" {{ old('product_id') == $product->id ? "selected" : "" }}>
                                {{ ucfirst($product->name) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Depot Selection -->
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="depot_id"><?= get_label('select_depot', 'Select Depot') ?> <span class="asterisk">*</span></label>
                        <select class="form-select text-capitalize" id="depot_id" name="depot_id">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            @foreach ($depots as $depot)
                            <option value="{{$depot->id}}" {{ old('depot_id') == $depot->id ? "selected" : "" }}>
                                {{ ucfirst($depot->name) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Quantity Field -->
                    <div class="mb-3 col-md-6">
                        <label for="quantity" class="form-label"><?= get_label('quantity', 'Quantity') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="quantity" name="quantity" placeholder="<?= get_label('please_enter_quantity', 'Please enter quantity') ?>" value="{{ old('quantity') }}">
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2" id="submit_btn"><?= get_label('add_quantity', 'Add Quantity') ?></button>
                    <button type="reset" class="btn btn-outline-secondary"><?= get_label('cancel', 'Cancel') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
