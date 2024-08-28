@php
use App\Models\Workspace;
$auth_user = getAuthenticatedUser();
$roles = \Spatie\Permission\Models\Role::where('name', '!=', 'admin')->get();
@endphp
@extends('layout')
@section('title')
<?= get_label('create_commande', 'Create Commande') ?>
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
                    <?= get_label('create', 'Create') ?>
                </li>
            </ol>
        </nav>
        <div>
            <!-- <button type="button" id="add_client_btn" class="btn btn-outline-secondary me-2"><?= get_label('add_new_client', 'Add New Client') ?></button> -->
            <button type="button" id="add_product_btn" class="btn btn-outline-secondary"><?= get_label('add_new_product', 'Add New Product') ?></button>
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
            <form action="{{ route('commandes.store') }}" method="POST" class="form-submit-event" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label for="title" class="form-label">{{ get_label('title', 'Title') }} <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" name="title" placeholder="{{ get_label('please_enter_title', 'Please enter title') }}" value="{{ old('title') }}" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="status" class="form-label">{{ get_label('status', 'Status') }}</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">{{ get_label('select_status', 'Select status') }}</option>
                            <option value="pending">{{ get_label('pending', 'Pending') }}</option>
                            <option value="completed">{{ get_label('completed', 'Completed') }}</option>
                            <option value="cancelled">{{ get_label('cancelled', 'Cancelled') }}</option>
                        </select>
                    </div>

                    <div class="mb-3 col-md-12">
                        <label for="total_amount" class="form-label">{{ get_label('total_amount', 'Total Amount') }}</label>
                        <input class="form-control" type="number" name="total_amount" placeholder="{{ get_label('please_enter_total_amount', 'Please enter total amount') }}" value="{{ old('total_amount') }}">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="start_date" class="form-label">{{ get_label('starts_at', 'Starts at') }} <span class="asterisk">*</span></label>
                        <input type="text" id="commande_start_date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="due_date" class="form-label">{{ get_label('ends_at', 'Ends at') }} <span class="asterisk">*</span></label>
                        <input type="text" id="commande_end_date" name="due_date" class="form-control" value="{{ old('due_date') }}" required>
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
                                    <label for="products[0][price]" class="form-label">{{ get_label('price', 'Price') }} <span class="asterisk">*</span></label>
                                    <input class="form-control" id="products[0][price]" type="number" name="products[0][price]" step="0.01" placeholder="{{ get_label('enter_price', 'Enter price') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <button type="button" id="add-product" class="btn btn-secondary">{{ get_label('add_another_product', 'Add Another Product') }}</button>
                        <button type="button" id="remove-product" class="btn btn-danger" style="display: none;">{{ get_label('remove_last_product', 'Remove Last Product') }}</button>
                    </div>

                    <div class="mb-3">
                        <label for="client_id" class="form-select">{{ get_label('select_client', 'Select Client') }}</label>
                        <select class="form-control" name="client_id">
                            <option value=""></option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->first_name }} {{ $client->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="user_id" class="form-select">{{ get_label('select_user', 'Select User') }}</label>
                        <select class="form-control" name="user_id">
                            <option value=""></option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
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
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let productIndex = 1;

    document.getElementById('add-product').addEventListener('click', function () {
        const productsContainer = document.getElementById('products-container');
        
        const productEntry = document.createElement('div');
        productEntry.classList.add('product-entry', 'mb-3');
        productEntry.innerHTML = `
            <h5>{{ get_label('product', 'Product') }} ${productIndex + 1}</h5>
            <div class="row">
                <div class="col-md-4">
                    <label for="products[${productIndex}][product_id]" class="form-label">{{ get_label('select_product', 'Select product') }}</label>
                    <select class="form-select" name="products[${productIndex}][product_id]" required>
                        <option value="">{{ get_label('select_product', 'Select product') }}</option>
                        @foreach($products ?? [] as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="products[${productIndex}][quantity]" class="form-label">{{ get_label('quantity', 'Quantity') }} <span class="asterisk">*</span></label>
                    <input class="form-control" type="number" id="products[${productIndex}][quantity]" name="products[${productIndex}][quantity]" placeholder="{{ get_label('enter_quantity', 'Enter quantity') }}" required>
                </div>
                <div class="col-md-4">
                    <label for="products[${productIndex}][price]" class="form-label">{{ get_label('price', 'Price') }} <span class="asterisk">*</span></label>
                    <input class="form-control" id="products[${productIndex}][price]" type="number" name="products[${productIndex}][price]" step="0.01" placeholder="{{ get_label('enter_price', 'Enter price') }}" required>
                </div>
            </div>
        `;

        productsContainer.appendChild(productEntry);
        productIndex++;

        if (productIndex > 1) {
            document.getElementById('remove-product').style.display = 'inline-block';
        }
    });

    document.getElementById('remove-product').addEventListener('click', function () {
        const productsContainer = document.getElementById('products-container');
        
        if (productIndex > 1) {
            productsContainer.lastElementChild.remove();
            productIndex--;

            if (productIndex === 1) {
                document.getElementById('remove-product').style.display = 'none';
            }
        }
    });
});
</script>
@endsection
