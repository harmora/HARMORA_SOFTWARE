@php
use App\Models\Workspace;
$auth_user = getAuthenticatedUser();
$roles = \Spatie\Permission\Models\Role::where('name', '!=', 'admin')->get();
@endphp
@extends('layout')
@section('title')
<?= get_label('update_facture', 'Update Facture') ?>
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
                    <a href="{{url('/factures')}}"><?= get_label('factures', 'Factures') ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <?= get_label('update', 'Update') ?>
                </li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{url('/factures/update/' . $facture->id)}}" method="POST" class="form-submit-event" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="type_facture" class="form-label"><?= get_label('type', 'Type') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" id="type_facture" name="type_facture">
                            <option value="service" {{ $facture->type_facture == 'service' ? 'selected' : '' }}><?= get_label('service', 'Service') ?></option>
                            <option value="product" {{ $facture->type_facture == 'product' ? 'selected' : '' }}><?= get_label('product', 'Product') ?></option>
                            <option value="mixed" {{ $facture->type_facture == 'mixed' ? 'selected' : '' }}><?= get_label('mixed', 'Mixed') ?></option>
                        </select>                    
                    </div>
                    <div class="mb-3 col-md-6" id="client_name_field">
                        @include('partials.select_single', ['label' => get_label(
                            'select_client', 'Select Client'), 'name' => 'client_id', 
                            'items' => $clients??[], 'authUserId' => $auth_user->id, 'for' => 'clients', 'selected' => $facture->client_id]
                        )
                    </div>
                    <div id="product_name_field" style="display: {{ $facture->type_facture == 'product' ? 'block' : 'none' }};">
                        <div id="products-container">
                            @foreach($facture->products as $index => $product)
                            <div class="product-entry mb-3">
                                <h5><?= get_label('product', 'Product') ?> {{ $index + 1 }}</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="products[{{$index}}][product_id]" class="form-label"><?= get_label('select_product', 'Select product') ?></label>
                                        <select class="form-select" name="products[{{$index}}][product_id]" >
                                            <option value=""><?= get_label('select_product', 'Select product') ?></option>
                                            @foreach($products ?? [] as $prod)
                                                <option value="{{ $prod->id }}" {{ $product->pivot->product_id == $prod->id ? 'selected' : '' }}>{{ $prod->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="products[{{$index}}][quantity]" class="form-label"><?= get_label('quantity', 'Quantity') ?> <span class="asterisk">*</span></label>
                                        <input class="form-control" type="number" name="products[{{$index}}][quantity]" value="{{ $product->pivot->quantity }}" placeholder="<?= get_label('enter_quantity', 'Enter quantity') ?>" >
                                    </div>
                                    <div class="col-md-4">
                                        <label for="products[{{$index}}][price]" class="form-label"><?= get_label('price', 'Price') ?> <span class="asterisk">*</span></label>
                                        <input class="form-control" type="number" name="products[{{$index}}][price]" value="{{ $product->pivot->price }}" step="0.01" placeholder="<?= get_label('enter_price', 'Enter price') ?>" >
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mb-3">
                            <button type="button" id="add-product" class="btn btn-secondary"><?= get_label('add_another_product', 'Add Another Product') ?></button>
                            <button type="button" id="remove-product" class="btn btn-danger" style="display: {{ count($facture->products) > 1 ? 'inline-block' : 'none' }};"><?= get_label('remove_last_product', 'Remove Last Product') ?></button>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="montant" class="form-label"><?= get_label('montant', 'Montant total') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="montant" name="montant" step="0.01" placeholder="<?= get_label('please_enter_montant', 'Please enter montant') ?>" value="{{ $facture->montant }}" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="tva" class="form-label"><?= get_label('tva', 'TVA') ?><span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="tva" name="tva" step="0.1" placeholder="<?= get_label('please_enter_tva', 'Please enter TVA') ?>" value="{{ $facture->tva }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="montant_ht" class="form-label"><?= get_label('montant_ht', 'Montant hors taxes') ?></label>
                        <input class="form-control" type="number" id="montant_ht" name="montant_ht" placeholder="<?= get_label('please_enter_montant_ht', 'Please enter montant HT') ?>" value="{{ $facture->montant_ht }}">
                    </div>                    
                    <div class="mb-3 col-md-6">
                        <label for="status_payement" class="form-label"><?= get_label('status_payement', 'Payment Status') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" id="status_payement" name="status_payement" required>
                            <option value=""><?= get_label('select_status', 'Select Status') ?></option>
                            <option value="paid" {{ $facture->status_payement == 'paid' ? 'selected' : '' }}> {{ get_label('paid', 'Paid') }}</option>
                            <option value="unpaid" {{ $facture->status_payement == 'unpaid' ? 'selected' : '' }}> {{ get_label('unpaid', 'Unpaid') }}</option>
                            <option value="partial" {{ $facture->status_payement == 'partial' ? 'selected' : '' }}> {{ get_label('partial', 'Partial') }} </option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-6" id="montant_payée_name_field" style="display: {{ $facture->status_payement == 'partial' ? 'block' : 'none' }};">
                        <label for="montant_payée" class="form-label"><?= get_label('montant_payée', 'Montant Payée') ?><span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="montant_payée" name="montant_payée"  placeholder="<?= get_label('please_enter_montant_payée', 'Please enter montant payée') ?>" value="{{ $facture->montant_payée }}">
                    </div>
                    <div class="mb-3 col-md-6" id="montant_restant_name_field" style="display: {{ $facture->status_payement == 'partial' ? 'block' : 'none' }};">
                        <label for="montant_restant" class="form-label"><?= get_label('montant_restant', 'Montant Restant') ?></label>
                        <input class="form-control" type="number" id="montant_restant" name="montant_restant" placeholder="<?= get_label('please_enter_montant_restant', 'Please enter montant restant') ?>" value="{{ $facture->montant_restant }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="facture_file" class="form-label"><?= get_label('facture', 'Facture') ?></label>
                        <div class="d-flex align-items-start">
                            <input class="form-control" type="file" id="facture_file" name="facture_file">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><?= get_label('save', 'Save') ?></button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let productCount = {{ count($facture->products) }};
    const addProductBtn = document.getElementById('add-product');
    const removeProductBtn = document.getElementById('remove-product');
    const productsContainer = document.getElementById('products-container');
    const statusPayement = document.getElementById('status_payement');
    const montantPayéeField = document.getElementById('montant_payée_name_field');
    const montantRestantField = document.getElementById('montant_restant_name_field');

    // Add product entry
    addProductBtn.addEventListener('click', function() {
        productCount++;
        const productEntry = document.createElement('div');
        productEntry.classList.add('product-entry', 'mb-3');
        productEntry.innerHTML = `
            <h5><?= get_label('product', 'Product') ?> ${productCount}</h5>
            <div class="row">
                <div class="col-md-4">
                    <label for="products[${productCount - 1}][product_id]" class="form-label"><?= get_label('select_product', 'Select product') ?></label>
                    <select class="form-select" name="products[${productCount - 1}][product_id]">
                        <option value=""><?= get_label('select_product', 'Select product') ?></option>
                        @foreach($products ?? [] as $prod)
                            <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="products[${productCount - 1}][quantity]" class="form-label"><?= get_label('quantity', 'Quantity') ?> <span class="asterisk">*</span></label>
                    <input class="form-control" type="number" name="products[${productCount - 1}][quantity]" placeholder="<?= get_label('enter_quantity', 'Enter quantity') ?>">
                </div>
                <div class="col-md-4">
                    <label for="products[${productCount - 1}][price]" class="form-label"><?= get_label('price', 'Price') ?> <span class="asterisk">*</span></label>
                    <input class="form-control" type="number" name="products[${productCount - 1}][price]" step="0.01" placeholder="<?= get_label('enter_price', 'Enter price') ?>">
                </div>
            </div>
        `;
        productsContainer.appendChild(productEntry);
        removeProductBtn.style.display = 'inline-block';
    });

    // Remove product entry
    removeProductBtn.addEventListener('click', function() {
        if (productCount > 0) {
            productsContainer.removeChild(productsContainer.lastChild);
            productCount--;
            if (productCount === 0) {
                removeProductBtn.style.display = 'none';
            }
        }
    });

    // Show/Hide fields based on payment status
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
