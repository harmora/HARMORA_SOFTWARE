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
                    <a href="{{url('/commandes/draggable')}}"><?= get_label('devise', 'devise') ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <?= get_label('edit', 'Edit') ?>
                </li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('commandes.accepter', $commande->id) }}" method="POST" class="form-submit-event">
                @csrf
                {{-- <input type="hidden" name="redirect_url" value="/commandes/{{$commande->id}}/bonliv"> --}}
                <input type="hidden" name="redirect_url" value="/commandes/draggable">
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
                        <label for="start" class="form-label"><?= get_label('start_date', 'Start Date') ?></label>
                        <input class="form-control" type="date" id="start" name="start" value="{{ old('start_date', $commande->start_date) }}">
                    </div>
                    <!-- Description -->
                    <div class="mb-3 col-md-6">
                        <label for="description" class="form-label">{{ get_label('description', 'Description') }}</label>
                        <textarea name="description" class="form-control">{{ old('description', $commande->description) }}</textarea>
                    </div>
                    <!-- Products -->
                    <div id="products-container">
                        @foreach($commande->products as $index => $product)
                        <div class="product-entry mb-3">
                            <h5><?= get_label('product', 'Product') ?> {{ $index + 1 }}</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="products[{{$index}}][product_id]" class="form-label"><?= get_label('select_product', 'Select product') ?></label>
                                    <select class="form-select product-select" name="products[{{$index}}][product_id]" required data-index="{{ $index }}">
                                        <option value=""><?= get_label('select_product', 'Select product') ?></option>
                                        @foreach($productsWithDepots as $prod)
                                            <option value="{{ $prod['id'] }}" {{ $product->id == $prod['id'] ? 'selected' : '' }}>{{ $prod['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="products[{{$index}}][depot_id]" class="form-label"><?= get_label('select_depot', 'Select depot') ?></label>
                                    <select class="form-select depot-select" name="products[{{$index}}][depot_id]" required>
                                        <option value=""><?= get_label('select_depot', 'Select depot') ?></option>
                                        @foreach($productsWithDepots->firstWhere('id', $product->id)['depots'] as $depot)
                                            <option value="{{ $depot['id'] }}" {{ $product->pivot->depot_id == $depot['id'] ? 'selected' : '' }}>
                                                {{ $depot['name'] }} (<?= get_label('available', 'Available') ?>: {{ $depot['quantity'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="products[{{$index}}][quantity]" class="form-label"><?= get_label('quantity', 'Quantity') ?> <span class="asterisk">*</span></label>
                                    <input class="form-control" type="number" name="products[{{$index}}][quantity]" value="{{ $product->pivot->quantity }}" placeholder="<?= get_label('enter_quantity', 'Enter quantity') ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="products[{{$index}}][price]" class="form-label"><?= get_label('price', 'Price') ?> <span class="asterisk">*</span></label>
                                    <input class="form-control" type="number" name="products[{{$index}}][price]" value="{{ $product->pivot->price }}" step="0.01" placeholder="<?= get_label('enter_price', 'Enter price') ?>" required>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mb-3">
                        <button type="button" id="add-product" class="btn btn-secondary"><?= get_label('add_another_product', 'Add Another Product') ?></button>
                        <button type="button" id="remove-product" class="btn btn-danger" style="display: {{ count($commande->products) > 1 ? 'inline-block' : 'none' }};"><?= get_label('remove_last_product', 'Remove Last Product') ?></button>
                    </div>
                    <!-- TVA -->
                    <div class="mb-3 col-md-6">
                        <label for="tva" class="form-label">{{ get_label('tva', 'TVA (%)') }}</label>
                        <input type="number" name="tva" class="form-control" value="{{ old('tva', 20) }}" step="0.01" min="0" max="100" readonly>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary" id="submit_btn">{{ get_label('Confirmer_devis', 'Confirmer Devis') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let productCount = {{ count($commande->products) }};
    const addProductBtn = document.getElementById('add-product');
    const removeProductBtn = document.getElementById('remove-product');
    const productsContainer = document.getElementById('products-container');
    const productsWithDepots = @json($productsWithDepots);
    
    function updateDepotOptions(productSelect, depotSelect) {
        const selectedProductId = productSelect.value;
        const selectedProduct = productsWithDepots.find(p => p.id == selectedProductId);
        
        depotSelect.innerHTML = `<option value=""><?= get_label('select_depot', 'Select depot') ?></option>`;
        
        if (selectedProduct) {
            selectedProduct.depots.forEach(depot => {
                const option = document.createElement('option');
                option.value = depot.id;
                option.textContent = `${depot.name} (<?= get_label('available', 'Available') ?>: ${depot.quantity})`;
                depotSelect.appendChild(option);
            });
        }
    }
    
    // Add event listeners to existing product selects
    document.querySelectorAll('.product-select').forEach(select => {
        select.addEventListener('change', function() {
            const depotSelect = this.closest('.row').querySelector('.depot-select');
            updateDepotOptions(this, depotSelect);
        });
    });
    
    addProductBtn.addEventListener('click', function() {
        const newProductDiv = document.createElement('div');
        newProductDiv.classList.add('product-entry', 'mb-3');
        newProductDiv.innerHTML = `
            <h5><?= get_label('product', 'Product') ?> ${++productCount}</h5>
            <div class="row">
                <div class="col-md-3">
                    <label for="products[${productCount-1}][product_id]" class="form-label"><?= get_label('select_product', 'Select product') ?></label>
                    <select class="form-select product-select" name="products[${productCount-1}][product_id]" required data-index="${productCount-1}">
                        <option value=""><?= get_label('select_product', 'Select product') ?></option>
                        ${productsWithDepots.map(prod => `<option value="${prod.id}">${prod.name}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="products[${productCount-1}][depot_id]" class="form-label"><?= get_label('select_depot', 'Select depot') ?></label>
                    <select class="form-select depot-select" name="products[${productCount-1}][depot_id]" required>
                        <option value=""><?= get_label('select_depot', 'Select depot') ?></option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="products[${productCount-1}][quantity]" class="form-label"><?= get_label('quantity', 'Quantity') ?> <span class="asterisk">*</span></label>
                    <input class="form-control" type="number" name="products[${productCount-1}][quantity]" placeholder="<?= get_label('enter_quantity', 'Enter quantity') ?>" required min="1">
                </div>
                <div class="col-md-3">
                    <label for="products[${productCount-1}][price]" class="form-label"><?= get_label('price', 'Price') ?> <span class="asterisk">*</span></label>
                    <input class="form-control" type="number" name="products[${productCount-1}][price]" step="0.01" placeholder="<?= get_label('enter_price', 'Enter price') ?>" required>
                </div>
            </div>
        `;
        productsContainer.appendChild(newProductDiv);
        
        const newProductSelect = newProductDiv.querySelector('.product-select');
        const newDepotSelect = newProductDiv.querySelector('.depot-select');
        
        newProductSelect.addEventListener('change', function() {
            updateDepotOptions(this, newDepotSelect);
        });
        
        if (productCount > 0) {
            removeProductBtn.style.display = 'inline-block';
        }
    });

        removeProductBtn.addEventListener('click', function() {
            if (productCount > 1) {
                productsContainer.removeChild(productsContainer.lastElementChild);
                productCount--;
    
                if (productCount === 1) {
                    removeProductBtn.style.display = 'none';
                }
            }
        });

        statusPayement.addEventListener('change', function() {
            if (this.value === 'partial') {
                montantPayéeField.style.display = 'block';
                montantRestantField.style.display = 'block';
            } else {
                montantPayéeField.style.display = 'none';
                montantRestantField.style.display = 'none';
            }
        });
    });
    </script>

@endsection
