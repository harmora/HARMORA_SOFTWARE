@extends('layout')
@section('title')
<?= get_label('bon_livraison', 'Bon Livraison') ?>
@endsection
@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-2 mt-2">
        <nav aria-label="breadcrumb" class="mb-0">
            <ol class="breadcrumb breadcrumb-style1 mb-0">
                <li class="breadcrumb-item"><a href="{{url('/home')}}"><?= get_label('home', 'Home') ?></a></li>
                <li class="breadcrumb-item"><a href="{{url('/commandes')}}"><?= get_label('achat', 'Commandes') ?></a></li>
                <li class="breadcrumb-item active"><?= get_label('bon_livraison', 'Bon Livraison') ?></li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('commandes.livraison', $commande->id) }}" method="POST" id="bonLivraisonForm" class="form-submit-event">
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect_url" value="/commandes/draggable">

                <div class="row">
                    <!-- Status: Total or Partial -->
                    <div class="mb-3 col-md-6">
                        <label for="status" class="form-label">{{ get_label('status', 'Shipping Status') }} <span class="asterisk">*</span></label>
                        <select name="status" id="status" class="form-select">
                            @if($previousBonLivraisons->isNotEmpty())
                            @if($previousBonLivraisons->contains('status', 'partial'))
                                <option value="partial">{{ get_label('partial', 'Partial') }}</option>
                            @elseif($previousBonLivraisons->contains('status', 'total'))
                                <option value="total">{{ get_label('total', 'Total') }}</option>
                            @endif
                            @else
                                <option value="total">{{ get_label('total', 'Total') }}</option>
                                <option value="partial">{{ get_label('partial', 'Partial') }}</option>
                            @endif
                        </select>
                    </div>

                    <!-- Title -->
                    <div class="mb-3 col-md-6">
                        <label for="title" class="form-label">{{ get_label('title', 'Title') }} <span class="asterisk">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $commande->title) }}" readonly>
                    </div>

                    <!-- Products and Quantity -->
                    <div id="products-container">
                        @foreach($commande->products as $index => $product)
                            <div class="product-entry mb-3">
                                <h5>{{ get_label('product', 'Product') }} {{ $index + 1 }}</h5>
                                <div class="row">
                                    <!-- Product Name (Read-only) -->
                                    <div class="col-md-4">
                                        <label for="products[{{$index}}][product_id]" class="form-label">{{ get_label('select_product', 'Select product') }}</label>
                                        <input type="text" class="form-control" value="{{ $products->firstWhere('id', $product->pivot->product_id)->name }}" readonly>
                                        <input type="hidden" name="products[{{$index}}][product_id]" value="{{ $product->pivot->product_id }}">
                                    </div>
                                    
                                    <!-- Quantity -->
                                    <div class="col-md-4">
                                        <label for="products[{{$index}}][quantity]" class="form-label">{{ get_label('quantity', 'Quantity') }} <span class="asterisk">*</span></label>

                                        @if($previousBonLivraisons->isNotEmpty())
                                            <!-- Display shipped quantity in read-only mode -->
                                            @php
                                                $totalShipped = $previousBonLivraisons->sum(function($bl) use ($product) {
                                                    return $bl->products->where('id', $product->id)->sum('pivot.quantity');
                                                });
                                                $remainingQuantity = $product->pivot->quantity - $totalShipped;
                                            @endphp

                                            <input type="number" name="products[{{$index}}][previous_quantity]" class="form-control" value="{{ $totalShipped }}" readonly>
                                            <label for="products[{{$index}}][remaining_quantity]" class="form-label">{{ get_label('remaining_quantity', 'Remaining Quantity') }}</label>
                                            <input type="number" name="products[{{$index}}][remaining_quantity]" class="form-control " value="{{ $remainingQuantity }}" max="{{ $remainingQuantity }}" {{ $remainingQuantity == 0 ? 'readonly' : '' }}>
                                        @else
                                            <!-- First entry - allow user to input quantity -->
                                            <input type="number" name="products[{{$index}}][quantity]" class="form-control" value="{{ $product->pivot->quantity }}" max="{{ $product->pivot->quantity }}" placeholder="{{ get_label('enter_quantity', 'Enter quantity') }}" readonly>
                                        @endif
                                    </div>

                                    <!-- Price -->
                                    <div class="col-md-4">
                                        <label for="products[{{$index}}][price]" class="form-label">{{ get_label('price', 'price') }} <span class="asterisk">*</span></label>
                                        <input type="number" name="products[{{$index}}][price]" class="form-control" value="{{ $product->pivot->price }}" placeholder="{{ get_label('enter_price', 'Enter price') }}" readonly>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- TVA (Read-only) -->
                    <div class="mb-3 col-md-6">
                        <label for="tva" class="form-label">{{ get_label('tva', 'TVA (%)') }}</label>
                        <input type="number" name="tva" class="form-control" value="{{ old('tva', 20) }}" step="0.01" min="0" max="100" readonly>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-4">
                    @if($commande->isFullyShipped())
                        <button type="button" class="btn btn-success" disabled>{{ get_label('validated', 'Validated') }}</button>
                    @else
                        <button type="submit" id="submitBtn" class="btn btn-primary">{{ get_label('confirmer_bonliv', 'Confirmer Bon Livraison') }}</button>
                    @endif
                </div>
            </form>

            <!-- Previous Bon Livraisons -->
            @if($previousBonLivraisons->isNotEmpty())
                <div id="previous-bon-livraisons" class="mt-2">
                    <h5>{{ get_label('previous_bon_livraisons', 'Previous Bon Livraisons') }}</h5>
                    @foreach($previousBonLivraisons as $bonLivraison)
                        <div class="bon-livraison-entry mb-3">
                            <p>{{ get_label('bon_livraison', 'Bon Livraison') }} #{{ $loop->iteration }}</p>
                            <p>{{ get_label('start_date', 'Start Date') }}: {{ $bonLivraison->start_date }}</p>
                            @foreach($bonLivraison->products as $product)
                                <p>{{ $product->name }}: {{ $product->pivot->quantity }} {{ get_label('quantity_shipped', 'shipped') }}</p>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const statusSelect = document.getElementById('status');
    const remainquantityInputs = document.querySelectorAll('input[name^="products"][name$="[remaining_quantity]"]');
    const quantityInputs = document.querySelectorAll('input[name^="products"][name$="[quantity]"]');
    const startDateInput = document.getElementById('start_date');
    const submitBtn = document.getElementById('submitBtn');

    function toggleReadonly(status) {
        if (status === 'total') {
            quantityInputs.forEach(input => input.setAttribute('readonly', true));
            remainquantityInputs.forEach(input => input.setAttribute('readonly', true));
            startDateInput.removeAttribute('readonly');
        } else if (status === 'partial') {
            remainquantityInputs.forEach(input => {
                // Only make the quantity input editable if there is remaining quantity
                if (parseInt(input.value) === 0) {
                    input.setAttribute('readonly', true);
                } else {
                    input.removeAttribute('readonly');
                }
            });
            quantityInputs.forEach(input => input.removeAttribute('readonly'));
            startDateInput.removeAttribute('readonly');
        }
    }

    statusSelect.addEventListener('change', function () {
        toggleReadonly(this.value);
    });

    toggleReadonly(statusSelect.value);
        // Check if all quantities are shipped
        function checkAllQuantitiesShipped() {
        const allShipped = Array.from(remainquantityInputs).every(input => parseInt(input.value) === 0);
        if (allShipped) {
            submitBtn.textContent = '{{ get_label('validated', 'Validated') }}';
            submitBtn.classList.remove('btn-primary');
            submitBtn.classList.add('btn-success');
            submitBtn.disabled = true;
        }
    }

    // Add event listeners to remaining quantity inputs
    remainquantityInputs.forEach(input => {
        input.addEventListener('change', checkAllQuantitiesShipped);
    });

    // Initial check
    checkAllQuantitiesShipped();

});

</script>
@endsection
