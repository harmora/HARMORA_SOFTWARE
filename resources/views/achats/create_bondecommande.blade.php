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

    </div>

    <div class="card">
        <div class="card-body">

            {{-- <div class="mb-4 d-flex justify-content-end">
                <button type="button" id="" class="btn btn-outline-info me-2"><?= get_label('add_new_supplier', 'Add New Supplier') ?></button>
                <button type="button" id="" class="btn btn-outline-info"><?= get_label('add_new_product', 'Add New Product') ?></button>
            </div> --}}



            <form action="{{url('/boncommande/store')}}" method="POST" class="form-submit-event" enctype="multipart/form-data">
                <input type="hidden" name="redirect_url" value="/bonnecommande">
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

                    <div class="mb-3 col-md-6">
                        <label for="type_achat" class="form-label"><?= get_label('type', 'Type') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" id="type_achat" name="type_achat">
                            <option value="Matériel/Produits"><?= get_label('Matériel/Produits', 'Materielle/Products') ?></option>
                            <option value="recherche/developpement"><?= get_label('recherche/developpement', 'Research/Development') ?></option>
                            <option value="investissements"><?= get_label('investissements', 'Investments') ?></option>
                            <option value="salaires/avantages sociaux"><?= get_label('salaires/avantages sociaux', 'Salaries/Social Benefits') ?></option>
                            <option value="mainetenances/amélioration"><?= get_label('mainetenances/amélioration', 'Maintenance/Improvement') ?></option>
                        </select>
                    </div>



                    <!-- Date of Command -->
                    <div class="mb-3 col-md-6">
                        <label for="date_commande" class="form-label"><?= get_label('date_commande', 'Date of Command') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="date" id="date_commande" name="date_commande" value="<?= old('date_commande', date('Y-m-d')) ?>" required>
                    </div>


                    <div class="container">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header">
                                <h4><?= get_label('existing_products', 'Existing Products') ?></h4>
                            </div>
                            <div class="card-body">
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
                                                <label for="products[0][quantity]" class="form-label"><?= get_label('quantity', 'Quantity') ?> </label>
                                                <input class="form-control" type="number" name="products[0][quantity]" required min="1" placeholder="<?= get_label('enter_quantity', 'Enter Quantity') ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="products[0][price]" class="form-label"><?= get_label('price', 'Price') ?> </label>
                                                <input class="form-control" type="number" name="products[0][price]" required step="0.01" placeholder="<?= get_label('enter_price', 'Enter Price') ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Buttons for existing products -->
                                <div class="d-flex justify-content-between">
                                    <button type="button" id="add-product" class="btn btn-secondary"><?= get_label('add_another_product', 'Add Another Product') ?></button>
                                    <button type="button" id="remove-product" class="btn btn-danger" style="display: none;"><?= get_label('remove_last_product', 'Remove Last Product') ?></button>
                                </div>
                            </div>
                        </div>

                        <!-- Separator -->
                        <div class="text-center mb-4">
                            <hr class="my-4">
                            <span class="badge bg-warning"><?= get_label('or_add_new', 'OR Add a New Non-Existent Product') ?></span>
                        </div>

                        <!-- New Non-Existent Product Section -->
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header">
                                <h4><?= get_label('new_non_existent_products', 'New Non-Existent Products') ?></h4>
                            </div>
                            <div class="card-body">
                                <div id="new-products-container"></div>

                                <!-- Buttons for new non-existent products -->
                                <div class="d-flex justify-content-between">
                                    <button type="button" id="add-new-product" class="btn btn-info"><?= get_label('add_non_existent_product', 'Add New Product (Non-Existent)') ?></button>
                                    <button type="button" id="remove-new-product" class="btn btn-danger" style="display: none;"><?= get_label('remove_last_new_product', 'Remove Last New Product') ?></button>
                                </div>
                            </div>
                        </div>
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
        let newProductCount = 0;  // For new products
        const addProductBtn = document.getElementById('add-product');
        const removeProductBtn = document.getElementById('remove-product');
        const productsContainer = document.getElementById('products-container');
        const newProductsContainer = document.getElementById('new-products-container');
        const addNewProductBtn = document.getElementById('add-new-product');
        const removeNewProductBtn = document.getElementById('remove-new-product');

        // Add existing product
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
                            <label for="products[${productCount}][quantity]" class="form-label"><?= get_label('quantity', 'Quantity') ?> </label>
                            <input class="form-control" type="number" name="products[${productCount}][quantity]" required min="1" placeholder="<?= get_label('enter_quantity', 'Enter Quantity') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="products[${productCount}][price]" class="form-label"><?= get_label('price', 'Price') ?></label>
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

        // Remove existing product
        removeProductBtn.addEventListener('click', function() {
            if (productCount > 1) {
                productsContainer.removeChild(productsContainer.lastElementChild);
                productCount--;
                if (productCount === 1) {
                    removeProductBtn.style.display = 'none';
                }
            }
        });

        // Add new non-existent product
        addNewProductBtn.addEventListener('click', function() {
            const newProductTemplate = `
                <div class="new-product-entry mb-3">
                    <h5><?= get_label('new_product', 'New Product') ?> ${newProductCount + 1}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="new_products[${newProductCount}][name]" class="form-label"><?= get_label('product_name', 'Product Name') ?></label>
                            <input class="form-control" type="text" name="new_products[${newProductCount}][name]" required placeholder="<?= get_label('please_enter_product_name', 'Please enter product name') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="new_products[${newProductCount}][category_id]" class="form-label"><?= get_label('category', 'Category') ?></label>
                            <select class="form-select" name="new_products[${newProductCount}][category_id]" required>
                                <option value=""><?= get_label('please_select', 'Please select') ?></option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ ucfirst($cat->name_cat) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="new_products[${newProductCount}][quantity]" class="form-label"><?= get_label('quantity', 'Quantity') ?></label>
                            <input class="form-control" type="number" name="new_products[${newProductCount}][quantity]" required min="1" placeholder="<?= get_label('enter_quantity', 'Enter Quantity') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="new_products[${newProductCount}][price]" class="form-label"><?= get_label('price', 'Price') ?></label>
                            <input class="form-control" type="number" name="new_products[${newProductCount}][price]" required step="0.01" placeholder="<?= get_label('enter_price', 'Enter Price') ?>">
                        </div>
                    </div>
                </div>
            `;
            newProductsContainer.insertAdjacentHTML('beforeend', newProductTemplate);
            newProductCount++;
            if (newProductCount > 0) {
                removeNewProductBtn.style.display = 'block';
            }
        });

        // Remove last non-existent product
        removeNewProductBtn.addEventListener('click', function() {
            if (newProductCount > 0) {
                newProductsContainer.removeChild(newProductsContainer.lastElementChild);
                newProductCount--;
                if (newProductCount === 0) {
                    removeNewProductBtn.style.display = 'none';
                }
            }
        });
    });
    </script>
@endsection
