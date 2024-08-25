@php
use App\Models\Workspace;
$auth_user = getAuthenticatedUser();
$roles = \Spatie\Permission\Models\Role::where('name', '!=', 'admin')->get();
@endphp
@extends('layout')
@section('title')
<?= get_label('update_achat', 'Update Achat') ?>
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
                    <?= get_label('update', 'Update') ?>
                </li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{url('/achats/update/' . $achat->id)}}" method="POST" class="form-submit-event" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="type_achat" class="form-label"><?= get_label('type', 'Type') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" id="type_achat" name="type_achat">
                            <option value="materielle/produits" {{ $achat->type_achat == 'materielle/produits' ? 'selected' : '' }}><?= get_label('materielle_produits', 'Materielle/Produits') ?></option>
                            <option value="recherche/developpement" {{ $achat->type_achat == 'recherche/developpement' ? 'selected' : '' }}><?= get_label('recherche_developpement', 'Recherche/Developpement') ?></option>
                            <option value="investissements" {{ $achat->type_achat == 'investissements' ? 'selected' : '' }}><?= get_label('investissements', 'Investissements') ?></option>
                            <option value="salaires/avantages sociaux" {{ $achat->type_achat == 'salaires/avantages sociaux' ? 'selected' : '' }}><?= get_label('salaires_avantages_sociaux', 'Salaires/Avantages Sociaux') ?></option>
                            <option value="mainetenances/amélioration" {{ $achat->type_achat == 'mainetenances/amélioration' ? 'selected' : '' }}><?= get_label('mainetenances_amélioration', 'Mainetenances/Amélioration') ?></option>
                        </select>                    
                    </div>
                    <div class="mb-3 col-md-6" id="supplier_name_field">
                        @include('partials.select_single', ['label' => get_label(
                            'select_suppliers', 'Select supplier'), 'name' => 'fournisseur_id', 
                            'items' => $fournisseurs??[], 'authUserId' => $auth_user->id, 'for' => 'suppliers', 'selected' => $achat->fournisseur_id]
                        )
                    </div>
                    <div id="product_name_field" style="display: {{ $achat->type_achat == 'materielle/produits' ? 'block' : 'none' }};">
                        <div id="products-container">
                            @foreach($achat->products as $index => $product)
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
                            <button type="button" id="remove-product" class="btn btn-danger" style="display: {{ count($achat->products) > 1 ? 'inline-block' : 'none' }};"><?= get_label('remove_last_product', 'Remove Last Product') ?></button>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="montant" class="form-label"><?= get_label('montant', 'Montant total') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="montant" name="montant" step="0.01" placeholder="<?= get_label('please_enter_montant', 'Please enter montant') ?>" value="{{ $achat->montant }}" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="tva" class="form-label"><?= get_label('tva', 'TVA') ?><span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="tva" name="tva" step="0.1" placeholder="<?= get_label('please_enter_tva', 'Please enter TVA') ?>" value="{{ $achat->tva }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="montant_ht" class="form-label"><?= get_label('montant_ht', 'Montant hors taxes') ?></label>
                        <input class="form-control" type="number" id="montant_ht" name="montant_ht" placeholder="<?= get_label('please_enter_montant_ht', 'Please enter montant') ?>" value="{{ $achat->montant_ht }}">
                    </div>                    
                    <div class="mb-3 col-md-6">
                        <label for="status_payement" class="form-label"><?= get_label('status_payement', 'Payment Status') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" id="status_payement" name="status_payement" required>
                            <option value=""><?= get_label('select_status', 'Select Status') ?></option>
                            <option value="paid" {{ $achat->status_payement == 'paid' ? 'selected' : '' }}> {{ get_label('paid', 'Paid') }}</option>
                            <option value="unpaid" {{ $achat->status_payement == 'unpaid' ? 'selected' : '' }}> {{ get_label('unpaid', 'Unpaid') }}</option>
                            <option value="partial" {{ $achat->status_payement == 'partial' ? 'selected' : '' }}> {{ get_label('partial', 'partial') }} </option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-6" id="montant_payée_name_field" style="display: {{ $achat->status_payement == 'partial' ? 'block' : 'none' }};">
                        <label for="montant_payée" class="form-label"><?= get_label('montant_payée', 'montant payée') ?><span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="montant_payée" name="montant_payée"  placeholder="<?= get_label('please_enter_monntatnt_payée', 'Please enter montant payée') ?>" value="{{ $achat->montant_payée }}">
                    </div>
                    <div class="mb-3 col-md-6" id="montant_restant_name_field" style="display: {{ $achat->status_payement == 'partial' ? 'block' : 'none' }};">
                        <label for="montant_restant" class="form-label"><?= get_label('montant_restant', 'montant restant') ?></label>
                        <input class="form-control" type="number" id="montant_restant" name="montant_restant" placeholder="<?= get_label('please_enter_montant_restant', 'Please enter montant restant') ?>" value="{{ $achat->montant_restant }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="facture" class="form-label"><?= get_label('facture', 'Facture') ?></label>
                        <div class="d-flex align-items-start gap-4">
                            @if($achat->facture)
                                @php
                                    $fileExtension = pathinfo($achat->facture, PATHINFO_EXTENSION);
                                @endphp
                                @if(in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif','webp']))
                                    <img src="{{$achat->facture? asset('storage/' . $achat->facture) : asset('storage/photos/doc.png')}}" alt="user-avatar" class="d-block rounded" height="130" width="130" id="uploadedAvatar" />
                                @elseif (in_array(strtolower($fileExtension), ['pdf']))
                                    <embed src="{{ asset('storage/' . $achat->facture) }}" type="application/pdf" height="130" width="130" style="overflow:auto;" /> 
                                @else
                                    <p class="text-muted mt-2"><?= get_label('file_not_supported', 'File not supported.') ?></p>        
                                @endif
                            @endif
                            <div class="button-wrapper">
                                <div class="input-group d-flex">
                                    <input type="file" class="form-control" id="inputGroupFile04" name="upload1">
                                </div>
                                <p class="text-muted mt-2"><?= get_label('allowed_jpg_png_pdf', 'Allowed JPG or PNG or PDF .') ?>{{$fileExtension}}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="devis" class="form-label"><?= get_label('devis', 'Devis') ?></label>
                        <div class="d-flex align-items-start gap-4">
                            @if($achat->devis)
                                @php
                                    $fileExtension2 = pathinfo($achat->devis, PATHINFO_EXTENSION);
                                @endphp
                                @if(in_array(strtolower($fileExtension2), ['jpg', 'jpeg', 'png', 'gif','webp']))
                                    <img src="{{$achat->devis? asset('storage/' . $achat->devis) : asset('storage/photos/doc.png')}}" alt="user-avatar" class="d-block rounded" height="130" width="130" id="uploadedAvatar" />
                                @elseif (in_array(strtolower($fileExtension2), ['pdf']))
                                    <embed src="{{ asset('storage/' . $achat->devis) }}" type="application/pdf" height="130" width="130" style="overflow:auto;" /> 
                                @else
                                    <p class="text-muted mt-2"><?= get_label('file_not_supported', 'File not supported.') ?></p>        
                                @endif
                            @endif
                            <div class="button-wrapper">
                                <div class="input-group d-flex">
                                    <input type="file" class="form-control" id="inputGroupFile04" name="upload1">
                                </div>
                                <p class="text-muted mt-2"><?= get_label('allowed_jpg_png_pdf', 'Allowed JPG or PNG or PDF .') ?>{{$fileExtension2}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="date_paiement" class="form-label"><?= get_label('date_paiement', 'Payment Date') ?></label>
                        <input class="form-control" type="date" id="date_paiement" name="date_paiement" value="{{ $achat->date_paiement }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="date_limit" class="form-label"><?= get_label('date_limit', 'Payment Due Date') ?></label>
                        <input class="form-control" type="date" id="date_limit" name="date_limit" value="{{ $achat->date_limit }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="reference" class="form-label"><?= get_label('reference', 'Reference') ?><span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="reference" name="reference" placeholder="<?= get_label('please_enter_reference', 'Please enter reference') ?>" value="{{ $achat->reference }}">
                    </div>
                </div>                    
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2" id="submit_btn"><?= get_label('update', 'Update') ?></button>
                    <a href="{{ url('/achats') }}" class="btn btn-outline-secondary"><?= get_label('cancel', 'Cancel') ?></a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let productCount = {{ count($achat->products) }};
        const addProductBtn = document.getElementById('add-product');
        const removeProductBtn = document.getElementById('remove-product');
        const productsContainer = document.getElementById('products-container');
        const statusPayement = document.getElementById('status_payement');
        const montantPayéeField = document.getElementById('montant_payée_name_field');
        const montantRestantField = document.getElementById('montant_restant_name_field');
    
        addProductBtn.addEventListener('click', function() {
            const newProductDiv = document.createElement('div');
            newProductDiv.classList.add('product-entry', 'mb-3');
            newProductDiv.innerHTML = `
                <h5><?= get_label('product', 'Product') ?> ${++productCount}</h5>
                <div class="row">
                    <div class="col-md-4">
                        <label for="products[${productCount-1}][product_id]" class="form-label"><?= get_label('select_product', 'Select product') ?></label>
                        <select class="form-select" name="products[${productCount-1}][product_id]" required>
                            <option value=""><?= get_label('select_product', 'Select product') ?></option>
                            @foreach($products ?? [] as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="products[${productCount-1}][quantity]" class="form-label"><?= get_label('quantity', 'Quantity') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="number" name="products[${productCount-1}][quantity]" placeholder="<?= get_label('enter_quantity', 'Enter quantity') ?>" required min="1">
                    </div>
                    <div class="col-md-4">
                        <label for="products[${productCount-1}][price]" class="form-label"><?= get_label('price', 'Price') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="number" name="products[${productCount-1}][price]" step="0.01" placeholder="<?= get_label('enter_price', 'Enter price') ?>" required>
                    </div>
                </div>
            `;
            productsContainer.appendChild(newProductDiv);
            
            if (productCount > 0) {
                removeProductBtn.style.display = 'inline-block';
            }
        });
    
        removeProductBtn.addEventListener('click', function() {
            if (productCount > 0) {
                productsContainer.removeChild(productsContainer.lastElementChild);
                productCount--;
    
                if (productCount === 0) {
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