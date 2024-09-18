@php
use App\Models\Workspace;
$auth_user = getAuthenticatedUser();
$roles = \Spatie\Permission\Models\Role::where('name', '!=', 'admin')->get();
@endphp
@extends('layout')
@section('title')
<?= get_label('create_devis', 'Create Devis') ?>
@endsection
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-2 mt-2">
        <nav aria-label="breadcrumb" class="mb-0">
            <ol class="breadcrumb breadcrumb-style1 mb-0">
                <li class="breadcrumb-item">
                    <a href="{{url('/home')}}"><?= get_label('home', 'Home') ?></a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{url('/commandes')}}"><?= get_label('devise', 'devise') ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <?= get_label('create_devis', 'Create Devis') ?>
                </li>
            </ol>
        </nav>
        <div>
            <button type="button" id="add_client_btn" class="btn btn-outline-secondary me-2"><?= get_label('add_new_client', 'Add New Client') ?></button>
            <button type="button" id="add_product_btn_commande" class="btn btn-outline-secondary me-2"><?= get_label('add_new_product', 'Add New Product') ?></button>
        </div>

    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('commandes.store_devise') }}" method="POST" class="form-submit-event" enctype="multipart/form-data">
                <input type="hidden" name="redirect_url" value="/commandes/draggable">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label for="title" class="form-label">{{ get_label('title', 'Title') }} <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" name="title" placeholder="{{ get_label('please_enter_title', 'Please enter title') }}" value="{{ old('title') }}" required>
                    </div>


                    <div class="mb-3 col-md-6">
                        <label for="start" class="form-label"><?= get_label('starts_at', 'Starts at') ?></label>
                        <input class="form-control" type="date" id="start" name="start" value="{{ old('start') }}">
                    </div>

                    <div id="products-container">
                        <div class="product-entry mb-3">
                            <h5>{{ get_label('product', 'Product') }} 1</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="products[0][product_id]" class="form-label">{{ get_label('select_product', 'Select product') }}</label>
                                    <select class="form-select" name="products[0][product_id]" required>
                                        <option value="">{{ get_label('select_product', 'Select product') }}</option>
                                        @foreach($products ?? [] as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="products[0][quantity]" class="form-label">{{ get_label('quantity', 'Quantity') }} <span class="asterisk">*</span></label>
                                    <input class="form-control" type="number" id="products[0][quantity]" name="products[0][quantity]" placeholder="{{ get_label('enter_quantity', 'Enter quantity') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="products[0][price]" class="form-label"><?= get_label('price', 'Price') ?> <span class="asterisk">*</span></label>
                                    <input class="form-control" id="products[0][price]" type="number" name="products[0][price]" step="0.01" placeholder="<?= get_label('enter_price', 'Enter price') ?>" >
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="mb-3">
                        <button type="button" id="add-product" class="btn btn-secondary">{{ get_label('add_another_product', 'Add Another Product') }}</button>
                        <button type="button" id="remove-product" class="btn btn-danger" style="display: none;">{{ get_label('remove_last_product', 'Remove Last Product') }}</button>
                    </div>

                    <div class="mb-3 col-md-12">    
                        <label for="tva" class="form-label">{{ get_label('tva', 'TVA') }}</label>
                        <input class="form-control" type="number" name="tva" placeholder="{{ get_label('please_enter_tva', 'Please enter TVA') }}" value="{{ old('tva', 20) }}" step="0.01" required>
                    </div>


                    <div class="mb-3">
                        <label for="client_id" class="form-select">{{ get_label('select_client', 'Select Client') }}</label>
                        <select class="form-control" name="client_id">
                            <option value=""></option>
                            @foreach($clients as $client)
=                                <option value="{{ $client->id }}">
                                    @if ($client->first_name)
                                        {{$client->first_name }} {{ $client->last_name }}
                                    @else
                                        {{$client->denomenation}}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>



                    <div class="mb-3">
                        <label for="description" class="form-label">{{ get_label('description', 'Description') }}</label>
                        <textarea class="form-control description" rows="5" name="description" placeholder="{{ get_label('please_enter_description', 'Please enter description') }}">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="note" class="form-label">{{ get_label('note', 'Note') }}</label>
                        <textarea class="form-control" name="note" rows="3" placeholder="{{ get_label('optional_note', 'Optional Note') }}"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ get_label('close', 'Close') }}</button>
                    <button type="submit" id="submit_btn" class="btn btn-primary">{{ get_label('create', 'Create') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        let productCount = 1;
        const addProductBtn = document.getElementById('add-product');
        const removeProductBtn = document.getElementById('remove-product');
        const productsContainer = document.getElementById('products-container');

        addProductBtn.addEventListener('click', function() {
            const newProductDiv = document.createElement('div');
            newProductDiv.classList.add('product-entry', 'mb-3');
            newProductDiv.innerHTML = `
                <h5><?= get_label('product', 'Product') ?> ${++productCount}</h5>
                <div class="row">
                    <div class="col-md-4">
                        <label for="products[${productCount-1}][product_id]" class="form-label"><?= get_label('select_product', 'Select product') ?></label>
                        <select class="form-select" id="products[${productCount-1}][product_id]" name="products[${productCount-1}][product_id]" required>
                            <option value=""><?= get_label('select_product', 'Select product') ?></option>
                            @foreach($products ?? [] as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="products[${productCount-1}][quantity]" class="form-label"><?= get_label('quantity', 'Quantity') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="products[${productCount-1}][quantity]" name="products[${productCount-1}][quantity]" placeholder="<?= get_label('enter_quantity', 'Enter quantity') ?>" required min="1">
                    </div>
                    <div class="col-md-4">
                        <label for="products[${productCount-1}][price]" class="form-label"><?= get_label('price', 'Price') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="products[${productCount-1}][price]" name="products[${productCount-1}][price]" step="0.01" placeholder="<?= get_label('enter_price', 'Enter price') ?>" required>
                    </div>
                </div>
            `;
            productsContainer.appendChild(newProductDiv);

            // Show the remove button when there's more than one product
            if (productCount > 1) {
                removeProductBtn.style.display = 'inline-block';
            }
        });

        removeProductBtn.addEventListener('click', function() {
            if (productCount > 1) {
                productsContainer.removeChild(productsContainer.lastElementChild);
                productCount--;

                // Hide the remove button when there's only one product left
                if (productCount === 1) {
                    removeProductBtn.style.display = 'none';
                }
            }
        });
    });
    </script>

<!-- @endsection
