@php
use App\Models\Workspace;
$auth_user = getAuthenticatedUser();
$roles = \Spatie\Permission\Models\Role::where('name', '!=', 'admin')->get();
@endphp
@extends('layout')
@section('title')
<?= get_label('edit_commande', 'Edit Commande') ?>
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
                    <a href="{{url('/commandes')}}"><?= get_label('commandes', 'Commandes') ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <?= get_label('edit', 'Edit') ?>
                </li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('commandes.update', $commande->id) }}" method="POST" class="form-submit-event">
                @csrf
                <input type="hidden" name="redirect_url" value="/commandes">
                @method('PUT')
                <div class="row">
                    <!-- Title -->
                    <div class="mb-3 col-md-6">
                        <label for="title" class="form-label">Title <span class="asterisk">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $commande->title) }}" required>
                    </div>
                    <!-- Client Selection -->
                    <div class="mb-3 col-md-6">
                        <label for="client_id" class="form-label">{{ get_label('select_client', 'Select Client') }} <span class="asterisk">*</span></label>
                        <select class="form-select" name="client_id" required>
                            <option value="">{{ get_label('select_client', 'Select Client') }}</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id', $commande->client_id) == $client->id ? 'selected' : '' }}>
                                    {{ $client->first_name ? $client->first_name . ' ' . $client->last_name : $client->denomenation }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <!-- Start Date -->
                    <div class="mb-3 col-md-6">
                        <label for="start_date" class="form-label">{{ get_label('start_date', 'Start Date') }} <span class="asterisk">*</span></label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $commande->start_date) }}" required>
                    </div>
                    <!-- Description -->
                    <div class="mb-3 col-md-6">
                        <label for="description" class="form-label">{{ get_label('description', 'Description') }}</label>
                        <textarea name="description" class="form-control">{{ old('description', $commande->description) }}</textarea>
                    </div>
                    <!-- Products -->
                    <div class="mb-3 col-md-12">
                        <label for="products" class="form-label">{{ get_label('products', 'Products') }}</label>
                        <div id="product-fields">
                            @foreach($commande->products as $product)
                                <div class="product-field">
                                    <select name="products[{{ $loop->index }}][product_id]" class="form-select" required>
                                        <option value="">{{ get_label('select_product', 'Select Product') }}</option>
                                        @foreach($allProducts as $prod)
                                            <option value="{{ $prod->id }}" {{ $prod->id == $product->pivot->product_id ? 'selected' : '' }}>
                                                {{ $prod->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="products[{{ $loop->index }}][quantity]" class="form-control" value="{{ $product->pivot->quantity }}" required>

                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-product" class="btn btn-secondary mt-2">{{ get_label('add_another_product', 'Add Another Product') }}</button>
                    </div>
                    <!-- TVA -->
                    <div class="mb-3 col-md-6">
                        <label for="tva" class="form-label">{{ get_label('tva', 'TVA (%)') }}</label>
                        <input type="number" name="tva" class="form-control" value="{{ old('tva', $commande->tva) }}" step="0.01" min="0" max="100">
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">{{ get_label('update_commande', 'Update Commande') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Reference to the container that holds product fields
        const productFieldsContainer = document.getElementById('product-fields');
        const addProductButton = document.getElementById('add-product');

        // Function to create a new product field
        function createProductField(index) {
            const productFieldHTML = `
                <div class="product-entry mb-3">
                    <h5><?= get_label('product', 'Product') ?> ${index + 1}</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="products[${index}][product_id]" class="form-label"><?= get_label('select_product', 'Select Product') ?></label>
                            <select name="products[${index}][product_id]" class="form-select" required>
                                <option value=""><?= get_label('select_product', 'Select Product') ?></option>
                                @foreach($allProducts as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="products[${index}][quantity]" class="form-label"><?= get_label('quantity', 'Quantity') ?> <span class="asterisk">*</span></label>
                            <input type="number" name="products[${index}][quantity]" class="form-control" placeholder="<?= get_label('enter_quantity', 'Enter Quantity') ?>" required>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger mt-2 remove-product"><?= get_label('remove', 'Remove') ?></button>
                </div>
            `;
            const wrapper = document.createElement('div');
            wrapper.innerHTML = productFieldHTML;
            productFieldsContainer.appendChild(wrapper);

            // Add event listener to the remove button
            const removeButton = wrapper.querySelector('.remove-product');
            removeButton.addEventListener('click', function () {
                wrapper.remove();
            });
        }

        // Attach click event to "Add Another Product" button
        addProductButton.addEventListener('click', function () {
            const index = productFieldsContainer.querySelectorAll('.product-entry').length;
            createProductField(index);
        });

        // Attach remove functionality to existing product fields
        productFieldsContainer.querySelectorAll('.remove-product').forEach(function (button) {
            button.addEventListener('click', function () {
                button.closest('.product-entry').remove();
            });
        });
    });
    </script>

@endsection
