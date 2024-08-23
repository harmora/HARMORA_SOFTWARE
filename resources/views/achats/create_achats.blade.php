@php
use App\Models\Workspace;
$auth_user = getAuthenticatedUser();
$roles = \Spatie\Permission\Models\Role::where('name', '!=', 'admin')->get();
@endphp
@extends('layout')
@section('title')
<?= get_label('create_user', 'Create user') ?>
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
                    <a href="{{url('/achats')}}"><?= get_label('achats', 'Achats') ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <?= get_label('create', 'Create') ?>
                </li>
            </ol>
        </nav>
        <div>
            <button type="button" id="add_supplier_btn" class="btn btn-outline-secondary me-2" ><?=  get_label('add_new_supplier', 'Add New Supplier') ?></button>
            <button type="button" id="add_product_btn" class="btn btn-outline-secondary"       ><?=  get_label('add_new_product', 'Add New Product') ?></button>
        </div>
    </div>
    @role('admin')
    @php
    $account_creation_template = App\Models\Template::where('type', 'email')
    ->where('name', 'account_creation')
    ->first();
    @endphp

    @if (!$account_creation_template || $account_creation_template->status == 1)
    <div class="alert alert-primary" role="alert">
        {{ get_label('user_acc_crea_email_enabled_inf', 'As Account Creation Email Status Is Active, Please Ensure Email Settings Are Configured and Operational.') }}
        <a href="/settings/templates" target="_blank">
            {{ get_label('click_to_change_acc_crea_email_sts', 'Click Here to Change Account Creation Email Status.') }}
        </a>
    </div>
    @endif
    @endrole

    <div class="card">
        <div class="card-body">
            <form action="{{url('/achats/store')}}" method="POST" class="form-submit-event" enctype="multipart/form-data">
                <input type="hidden" name="redirect_url" value="/achats">
                @csrf
                <div class="row">
                    <!-- ... (keep other fields like type_achat, fournisseur_id, etc.) ... -->
                    <div class="mb-3 col-md-6">
                        <label for="type_achat" class="form-label"><?= get_label('type', 'Type') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" id="type_achat" name="type_achat">
                            <option value="materielle/produits"><?= get_label('materielle_produits', 'Materielle/Produits') ?></option>
                            <option value="recherche/developpement"><?= get_label('recherche_developpement', 'Recherche/Developpement') ?></option>
                            <option value="investissements"><?= get_label('investissements', 'Investissements') ?></option>
                            <option value="salaires/avantages sociaux"><?= get_label('salaires_avantages_sociaux', 'Salaires/Avantages Sociaux') ?></option>
                            <option value="mainetenances/amélioration"><?= get_label('mainetenances_amélioration', 'Mainetenances/Amélioration') ?></option>
                        </select>                    
                    </div>
                    <div class="mb-3 col-md-6" id="supplier_name_field" >
                        @include('partials.select_single', ['label' => get_label(
                            'select_suppliers', 'Select supplier'), 'name' => 'fournisseur_id', 
                            'items' => $fournisseurs??[], 'authUserId' => $auth_user->id, 'for' => 'suppliers']
                            )
                    </div>
                    <div id="product_name_field" style="display: block;">
                        <div id="products-container">
                            <div class="product-entry mb-3">
                                <h5><?= get_label('product', 'Product') ?> 1</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="products[0][product_id]" class="form-label"><?= get_label('select_product', 'Select product') ?></label>
                                        <select class="form-select" name="products[0][product_id]" >
                                            <option value=""><?= get_label('select_product', 'Select product') ?></option>
                                            @foreach($products ?? [] as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="products[0][quantity]" class="form-label"><?= get_label('quantity', 'Quantity') ?> <span class="asterisk">*</span></label>
                                        <input class="form-control" type="number" id="products[0][quantity]" name="products[0][quantity]" placeholder="<?= get_label('enter_quantity', 'Enter quantity') ?>" >
                                    </div>
                                    <div class="col-md-4">
                                        <label for="products[0][price]" class="form-label"><?= get_label('price', 'Price') ?> <span class="asterisk">*</span></label>
                                        <input class="form-control" id="products[0][price]" type="number" name="products[0][price]" step="0.01" placeholder="<?= get_label('enter_price', 'Enter price') ?>" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="button" id="add-product" class="btn btn-secondary"><?= get_label('add_another_product', 'Add Another Product') ?></button>
                            <button type="button" id="remove-product" class="btn btn-danger" style="display: none;"><?= get_label('remove_last_product', 'Remove Last Product') ?></button>
                        </div>
    
                    </div>
                    {{-- <div class="mb-3 col-md-6" id="stock_name_field" style="display: block;">
                        <label for="stock" class="form-label"><?= get_label('stock', 'Stock') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="stock" name="stock" placeholder="<?= get_label('please_enter_stock', 'Please enter stock') ?>" value="{{ old('stock') }}">
                    </div>  --}}
                    <div class="mb-3 col-md-6">
                            <label for="montant" class="form-label"><?= get_label('montant', 'Montant total') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="montant" name="montant" step="0.01" placeholder="<?= get_label('please_enter_montant', 'Please enter montant') ?>" value="{{ old('montant') }}" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="tva" class="form-label"><?= get_label('tva', 'TVA') ?><span class="asterisk">*</span>   </label>
                        <input class="form-control" type="number" id="tva" name="tva" step="0.1" placeholder="<?= get_label('please_enter_tva', 'Please enter TVA') ?>" value="{{ old('tva') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="montant_ht" class="form-label"><?= get_label('montant_ht', 'Montant hors taxes') ?></label>
                        <input class="form-control" type="number" id="montant_ht" name="montant_ht" placeholder="<?= get_label('please_enter_montant_ht', 'Please enter montant') ?>" value="{{ old('montant_ht') }}">
                    </div>                    
                    <div class="mb-3 col-md-6">
                        <label for="status_payement" class="form-label"><?= get_label('status_payement', 'Payment Status') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" id="status_payement" name="status_payement" required>
                            <option value=""><?= get_label('select_status', 'Select Status') ?></option>
                            <option value="paid" {{ old('status_payement') == 'paid' ? 'selected' : '' }} > {{ get_label('paid', 'Paid') }}</option>
                            <option value="unpaid" {{ old('status_payement') == 'unpaid' ? 'selected' : '' }} > {{ get_label('unpaid', 'Unpaid') }}</option>
                            <option value="partial" {{ old('status_payement') == 'partial' ? 'selected' : '' }} > {{ get_label('partial', 'partial') }} </option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-6" id="montant_payée_name_field" style="display: none;">
                        <label for="montant_payée" class="form-label"><?= get_label('montant_payée', 'montant payée') ?><span class="asterisk">*</span>  </label>
                        <input class="form-control" type="number" id="montant_payée" name="montant_payée"  placeholder="<?= get_label('please_enter_monntatnt_payée', 'Please enter montant payée') ?>" value="{{ old('montant_payée') }}">
                    </div>
                    <div class="mb-3 col-md-6" id="montant_restant_name_field" style="display: none;">
                        <label for="montant_restant" class="form-label"><?= get_label('montant_restant', 'montant restant') ?></label>
                        <input class="form-control" type="number" id="montant_restant" name="montant_restant" placeholder="<?= get_label('please_enter_montant_restant', 'Please enter montant restant') ?>" value="{{ old('montant_ht') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="facture" class="form-label"><?= get_label('facture', 'Facture') ?></label>
                        <input class="form-control" type="file" id="facture" name="facture" placeholder="<?= get_label('please_enter_facture', 'Please enter invoice number') ?>" value="{{ old('facture') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="devis" class="form-label"><?= get_label('devis', 'Devis') ?></label>
                        <input class="form-control" type="file" id="devis" name="devis" placeholder="<?= get_label('please_enter_devis', 'Please enter devis') ?>" value="{{ old('devis') }}">
                    </div>                  
                    <div class="mb-3 col-md-6">
                        <label for="date_paiement" class="form-label"><?= get_label('date_paiement', 'Payment Date') ?></label>
                        <input class="form-control" type="date" id="date_paiement" name="date_paiement" value="{{ old('date_paiement') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="date_limit" class="form-label"><?= get_label('date_limit', 'Payment Due Date') ?></label>
                        <input class="form-control" type="date" id="date_limit" name="date_limit" value="{{ old('date_limit') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="reference" class="form-label"><?= get_label('reference', 'Reference') ?><span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="reference" name="reference" placeholder="<?= get_label('please_enter_reference', 'Please enter reference') ?>" value="{{ old('reference') }}">
                    </div>


                    <!-- ... (keep other fields like montant, tva, etc.) ... -->
                </div>                    
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2" id="submit_btn"><?= get_label('create', 'Create') ?></button>
                    <button type="reset" class="btn btn-outline-secondary"><?= get_label('cancel', 'Cancel') ?></button>
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
@endsection