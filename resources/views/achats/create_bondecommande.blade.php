@php
use App\Models\Fournisseur;
use App\Models\Product;
$auth_user = getAuthenticatedUser();
$fournisseurs = Fournisseur::all();
$products = Product::all();
@endphp

@extends('layout')
@section('title')
<?= get_label('create_bon_commande', 'Create Bon de Commande') ?>
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
                    <a href="{{url('/boncommandes')}}"><?= get_label('bon_commandes', 'Bon Commandes') ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <?= get_label('create', 'Create') ?>
                </li>
            </ol>
        </nav>
        <div>
            <button type="button" id="add_supplier_btn" class="btn btn-outline-secondary me-2"><?= get_label('add_new_supplier', 'Add New Supplier') ?></button>
            <button type="button" id="add_product_btn" class="btn btn-outline-secondary"><?= get_label('add_new_product', 'Add New Product') ?></button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{url('/boncommandes/store')}}" method="POST" class="form-submit-event" enctype="multipart/form-data">
                <input type="hidden" name="redirect_url" value="/boncommandes">
                @csrf
                <div class="row">
                    <!-- Supplier -->
                    <div class="mb-3 col-md-6">
                        @include('partials.select_single', [
                            'label' => get_label('select_supplier', 'Select Supplier'),
                            'name' => 'fournisseur_id',
                            'items' => $fournisseurs,
                            'authUserId' => $auth_user->id,
                            'for' => 'suppliers'
                        ])
                    </div>

                    <!-- Reference -->
                    <div class="mb-3 col-md-6">
                        <label for="reference" class="form-label"><?= get_label('reference', 'Reference') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="reference" name="reference" placeholder="<?= get_label('enter_reference', 'Enter Reference') ?>" value="{{ old('reference') }}" required>
                    </div>

                    <!-- Date of Command -->
                    <div class="mb-3 col-md-6">
                        <label for="date_commande" class="form-label"><?= get_label('date_commande', 'Date of Command') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="date" id="date_commande" name="date_commande" value="{{ old('date_commande') }}" required>
                    </div>

                    <!-- Status -->
                    <div class="mb-3 col-md-6">
                        <label for="status" class="form-label"><?= get_label('status', 'Status') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" id="status" name="status" required>
                            <option value=""><?= get_label('select_status', 'Select Status') ?></option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }} ><?= get_label('pending', 'Pending') ?></option>
                            <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }} ><?= get_label('confirmed', 'Confirmed') ?></option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }} ><?= get_label('cancelled', 'Cancelled') ?></option>
                        </select>
                    </div>

                    <!-- Product List -->
                    <div id="products-container">
                        <div class="product-entry mb-3">
                            <h5><?= get_label('product', 'Product') ?> 1</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="products[0][product_id]" class="form-label"><?= get_label('select_product', 'Select product') ?></label>
                                    <select class="form-select" name="products[0][product_id]" required>
                                        <option value=""><?= get_label('select_product', 'Select product') ?></option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="products[0][quantity]" class="form-label"><?= get_label('quantity', 'Quantity') ?> <span class="asterisk">*</span></label>
                                    <input class="form-control" type="number" name="products[0][quantity]" required min="1" placeholder="<?= get_label('enter_quantity', 'Enter Quantity') ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="products[0][price]" class="form-label"><?= get_label('price', 'Price') ?> <span class="asterisk">*</span></label>
                                    <input class="form-control" type="number" name="products[0][price]" required step="0.01" placeholder="<?= get_label('enter_price', 'Enter Price') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button type="button" id="add-product" class="btn btn-secondary"><?= get_label('add_another_product', 'Add Another Product') ?></button>
                        <button type="button" id="remove-product" class="btn btn-danger" style="display: none;"><?= get_label('remove_last_product', 'Remove Last Product') ?></button>
                    </div>

                    <!-- Montant Total -->
                    <div class="mb-3 col-md-6">
                        <label for="montant_total" class="form-label"><?= get_label('montant_total', 'Montant Total') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="montant_total" name="montant_total" step="0.01" placeholder="<?= get_label('enter_montant_total', 'Enter Total Amount') ?>" required>
                    </div>

                    <!-- TVA -->
                    <div class="mb-3 col-md-6">
                        <label for="tva" class="form-label"><?= get_label('tva', 'TVA') ?></label>
                        <input class="form-control" type="number" id="tva" name="tva" step="0.01" placeholder="<?= get_label('enter_tva', 'Enter TVA') ?>" value="{{ old('tva') }}">
                    </div>

                    <!-- Date de Livraison -->
                    <div class="mb-3 col-md-6">
                        <label for="date_livraison" class="form-label"><?= get_label('date_livraison', 'Delivery Date') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="date" id="date_livraison" name="date_livraison" required>
                    </div>

                    <!-- Facture -->
                    <div class="mb-3 col-md-6">
                        <label for="fichier_facture" class="form-label"><?= get_label('facture', 'Facture') ?></label>
                        <input class="form-control" type="file" id="fichier_facture" name="fichier_facture">
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
                        <button type="reset" class="btn btn-outline-secondary"><?= get_label('cancel', 'Cancel') ?></button>
                    </div>
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
            const productTemplate = `
                <div class="product-entry mb-3">
                    <h5><?= get_label('product', 'Product') ?> ${productCount + 1}</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="products[${productCount}][product_id]" class="form-label"><?= get_label('select_product', 'Select product') ?></label>
                            <select class="form-select" name="products[${productCount}][product_id]" required>
                                <option value=""><?= get_label('select_product', 'Select product') ?></option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="products[${productCount}][quantity]" class="form-label"><?= get_label('quantity', 'Quantity') ?> <span class="asterisk">*</span></label>
                            <input class="form-control" type="number" name="products[${productCount}][quantity]" required min="1" placeholder="<?= get_label('enter_quantity', 'Enter Quantity') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="products[${productCount}][price]" class="form-label"><?= get_label('price', 'Price') ?> <span class="asterisk">*</span></label>
                            <input class="form-control" type="number" name="products[${productCount}][price]" required step="0.01" placeholder="<?= get_label('enter_price', 'Enter Price') ?>">
                        </div>
                    </div>
                </div>
            `;

            productsContainer.insertAdjacentHTML('beforeend', productTemplate);
            productCount++;
            if (productCount > 1) {
                removeProductBtn.style.display = 'block';
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
    });
</script>
@endsection
