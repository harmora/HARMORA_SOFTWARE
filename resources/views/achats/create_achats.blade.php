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
                    <div class="mb-3 col-md-6">
                        <label for="fournisseur_id" class="form-label"><?= get_label('fournisseur', 'Fournisseur') ?><span class="asterisk">*</span></label>
                        <select class="form-select" id="fournisseur_id" name="fournisseur_id" required>
                            <option value=""><?= get_label('select_fournisseur', 'Select Fournisseur') ?></option>
                            @foreach ($fournisseurs as $fournisseur)
                                <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>{{ $fournisseur   ->name }}</option>
                            @endforeach
                        </select>
                    </div>
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
                    {{-- <div class="mb-3 col-md-6" id="product_name_field" style="display: block;">
                        <label for="product_name" class="form-label"><?= get_label('product_name', 'Product Name') ?></label>
                        <input class="form-control" type="text" id="product_name" name="product_name" placeholder="<?= get_label('please_enter_product_name', 'Please enter product name') ?>" value="{{ old('product_name') }}">
                    </div> --}}
                    <div class="mb-3 col-md-6 " id="product_name_field" style="display: block;">
                        <label for="product_id" class="form-label"><?= get_label('product', 'Product') ?></label>
                        <select class="form-select" id="product_id" name="product_id">
                            <option value=""><?= get_label('select_product', 'Select Product') ?></option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        @include('partials.select_single', ['label' => get_label(
                            'select_suppliers', 'Select suppliers'), 'name' => 'supplier_ids[]', 
                            'items' => $fournisseurs??[], 'authUserId' => $auth_user->id, 'for' => 'suppliers']
                                )
                    </div>                
                    <div class="mb-3 col-md-6">
                        <label for="montant" class="form-label"><?= get_label('montant', 'Montant') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="montant" name="montant" step="0.01" placeholder="<?= get_label('please_enter_montant', 'Please enter montant') ?>" value="{{ old('montant') }}" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="status_payement" class="form-label"><?= get_label('status_payement', 'Payment Status') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" id="status_payement" name="status_payement" required>
                            <option value=""><?= get_label('select_status', 'Select Status') ?></option>
                            <option value="paid" {{ old('status_payement') == 'paid' ? 'selected' : '' }}>{{ get_label('paid', 'Paid') }}</option>
                            <option value="unpaid" {{ old('status_payement') == 'unpaid' ? 'selected' : '' }}>{{ get_label('unpaid', 'Unpaid') }}</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="tva" class="form-label"><?= get_label('tva', 'TVA') ?></label>
                        <input class="form-control" type="number" id="tva" name="tva" step="0.1" placeholder="<?= get_label('please_enter_tva', 'Please enter TVA') ?>" value="{{ old('tva') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="facture" class="form-label"><?= get_label('facture', 'Facture') ?></label>
                        <input class="form-control" type="file" id="facture" name="facture" placeholder="<?= get_label('please_enter_facture', 'Please enter invoice number') ?>" value="{{ old('facture') }}">
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
                        <label for="reference" class="form-label"><?= get_label('reference', 'Reference') ?></label>
                        <input class="form-control" type="text" id="reference" name="reference" placeholder="<?= get_label('please_enter_reference', 'Please enter reference') ?>" value="{{ old('reference') }}">
                    </div>
                </div>                    
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2" id="submit_btn"><?= get_label('create', 'Create') ?></button>
                    <button type="reset" class="btn btn-outline-secondary"><?= get_label('cancel', 'Cancel') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection



{{--

<!-- Modal for Adding New Product -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel"><?= get_label('add_new_product', 'Add New Product') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addProductForm" action="{{ url('/products/store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="product_name" class="form-label"><?= get_label('product_name', 'Product Name') ?></label>
                        <input class="form-control" type="text" id="product_name" name="product_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="product_description" class="form-label"><?= get_label('product_description', 'Product Description') ?></label>
                        <textarea class="form-control" id="product_description" name="product_description" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= get_label('close', 'Close') ?></button>
                        <button type="submit" class="btn btn-primary"><?= get_label('save', 'Save') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@section('scripts')
<script>
    $(document).ready(function () {
        // Show modal if "Add New Product" is selected
        $('#product_id').change(function () {
            if ($(this).val() === 'add_new') {
                $('#addProductModal').modal('show');
            }
        });

        // Handle form submission to add new product
        $('#addProductForm').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    if (response.success) {
                        $('#product_id').append(new Option(response.product_name, response.product_id, true, true));
                        $('#addProductModal').modal('hide');
                    }
                }
            });
        });
    });
</script>
@endsection --}}
