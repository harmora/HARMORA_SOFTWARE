@php
use App\Models\Workspace;
$auth_user = getAuthenticatedUser();
$roles = \Spatie\Permission\Models\Role::where('name', '!=', 'admin')->get();
@endphp
@extends('layout')
@section('title')
<?= get_label('bon_livraision', 'Bon Livraision') ?>
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
                    <a href="{{url('/commandes')}}"><?= get_label('achat', 'devise') ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <?= get_label('bon_livraision', 'Bon Livraision') ?>
                </li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('commandes.livraision', $commande->id) }}" method="POST" class="form-submit-event">
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
                    <div id="" >
                        <div id="products-container">
                            @foreach($commande->products as $index => $product)
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
                            <button type="button" id="remove-product" class="btn btn-danger" style="display: {{ count($commande->products) > 1 ? 'inline-block' : 'none' }};"><?= get_label('remove_last_product', 'Remove Last Product') ?></button>
                        </div>
                    </div>
                    <!-- TVA -->
                    <div class="mb-3 col-md-6">
                        <label for="tva" class="form-label">{{ get_label('tva', 'TVA (%)') }}</label>
                        <input type="number" name="tva" class="form-control" value="{{ old('tva', 20) }}" step="0.01" min="0" max="100" readonly>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">{{ get_label('Confirmer_devis', 'Confirmer Devis') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Reference to the container that holds product fields


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
