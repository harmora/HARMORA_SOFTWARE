@extends('layout')

@section('title')
    <?= get_label('manage_bon_commande', 'Manage Bon de Commande') ?>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-2 mt-2">
        <nav aria-label="breadcrumb" class="mb-0">
            <ol class="breadcrumb breadcrumb-style1 mb-0">
                <li class="breadcrumb-item">
                    <a href="{{url('/home')}}"><?= get_label('home', 'Home') ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <?= get_label('manage_bon_commande', 'Manage Bon de Commande') ?>
                </li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Progress Bar -->
            <div class="progress-container">
                <ul class="progressbar">
                    <li class="active"> <?= get_label('purchase_details', 'Purchase Details') ?></li>
                    <li> <?= get_label('payment_informations', 'Payment informations') ?></li>
                    <li> <?= get_label('your_stock', 'Your Stock') ?></li>
                </ul>
            </div>

            <!-- Multi-Step Form Starts Here -->
            <h1 class="text-center fs-4 mb-4">Validate Your Purchase</h1>
            <form id="orderForm" action="{{ route('achats.storeValidated') }}" method="POST" class="form-submit-event" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="redirect_url" value="/achats">

                <!-- Step One: Purchase Details -->
                <div class="step active">
                    <h5>Purchase Details</h5>

                    <!-- Row 1 -->
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="type_achat" class="form-label"><?= get_label('type', 'Type') ?> <span class="asterisk">*</span></label>
                            <select class="form-select" id="type_achat" name="type_achat">
                                <option value="Matériel/Produits" selected><?= get_label('Matériel/Produits', 'Materielle/Products') ?></option>
                            </select>

                        </div>


                        <div class="mb-3 col-md-6" id="supplier_name_field">
                            @include('partials.select_single', ['label' => get_label(
                                'select_suppliers', 'Select supplier'), 'name' => 'fournisseur_id',
                                'items' => $fournisseurs??[], 'authUserId' => Auth::user()->id, 'for' => 'suppliers', 'selected' => $bonDeCommande->fournisseur->id]
                            )
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="date_achat" class="form-label"><?= get_label('date_achat', 'Purchase Date') ?><span class="asterisk">*</span></label>
                            <input class="form-control" type="date" id="date_achat" name="date_achat"
                                   value="{{ old('date_achat', date('Y-m-d')) }}">
                        </div>



                        <div class="mb-3 col-md-6">
                            <label for="tva" class="form-label"><?= get_label('tva', 'TVA') ?> <span class="asterisk">*</span></label>
                            <select class="form-select" id="tva" name="tva">
                                <option value="0" {{ $bonDeCommande->tva == '0' ? 'selected' : '' }}><?= get_label('0 ', '0%') ?></option>
                                <option value="7" {{ $bonDeCommande->tva == '7' ? 'selected' : '' }}><?= get_label('7', '7%') ?></option>
                                <option value="10" {{ $bonDeCommande->tva == '10' ? 'selected' : '' }}><?= get_label('10', '10%') ?></option>
                                <option value="14" {{ $bonDeCommande->tva == '14' ? 'selected' : '' }}><?= get_label('14', '14%') ?></option>
                                <option value="16" {{ $bonDeCommande->tva == '16' ? 'selected' : '' }}><?= get_label('16', '16%') ?></option>
                                <option value="20" {{ $bonDeCommande->tva == '20' ? 'selected' : '' }}><?= get_label('20', '20%') ?></option>
                            </select>
                        </div>
                    </div>


                    <div class="mb-3 col-md-6">
                        <label for="marge" class="form-label"><?= get_label('marge', 'Margin (%)') ?></label>
                        <input class="form-control" type="number" id="marge" name="marge" step="0.01" min="0" max="100"
                               value="{{ old('marge') }}" placeholder="Enter margin percentage">
                    </div>




                    <div id="product_name_field">
                        <div id="products-container">
                            @foreach($bonDeCommande->products as $index => $product)
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
                            <button type="button" id="remove-product" class="btn btn-danger" style="display: {{ count($bonDeCommande->products) > 1 ? 'inline-block' : 'none' }};"><?= get_label('remove_last_product', 'Remove Last Product') ?></button>
                        </div>
                    </div>


                    <!-- Row 2 -->



                </div>


                <!-- Step Two: Supplier Information -->
                <div class="step">
                    <h5>Payment informations</h5>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="montant" class="form-label"><?= get_label('montant', 'Montant total') ?></label>
                            <input class="form-control" type="number" id="montant" name="montant" step="0.01" placeholder="<?= get_label('please_enter_montant', 'Please enter montant') ?>" value="{{ old('montant') }}" required readonly style="border: 1px solid #ced4da;">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="montant_ht" class="form-label"><?= get_label('montant_ht', 'Montant hors taxes') ?></label>
                            <input class="form-control" type="number" id="montant_ht" name="montant_ht" placeholder="<?= get_label('please_enter_montant_ht', 'Please enter montant') ?>" value="{{ old('montant_ht') }}" readonly style="border: 1px solid #ced4da;">
                        </div>

                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="date_limit" class="form-label"><?= get_label('date_limit', 'Payment Due Date') ?></label>
                        <input class="form-control" type="date" id="date_limit" name="date_limit">
                    </div>



                    <div class="mb-3 col-md-6">

                        @if($bonDeCommande->devis)
                        <input type="hidden" name="boncmddevis" value="{{ $bonDeCommande->devis }}">
                       @endif

                        <label for="devis" class="form-label fw-bold text-primary"><?= get_label('devis', 'Devis') ?></label>
                        <div class="d-flex align-items-start gap-4 p-3 rounded shadow-sm bg-light">
                            @if($bonDeCommande->devis)
                                @php
                                    $fileExtension2 = pathinfo($bonDeCommande->devis, PATHINFO_EXTENSION);
                                @endphp
                                @if(in_array(strtolower($fileExtension2), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                    <img src="{{ asset('storage/' . $bonDeCommande->devis) }}"
                                         alt="devis-file"
                                         class="d-block rounded"
                                         height="130"
                                         width="130" />
                                @elseif (in_array(strtolower($fileExtension2), ['pdf']))
                                    <embed src="{{ asset('storage/' . $bonDeCommande->devis) }}"
                                           type="application/pdf"
                                           height="130"
                                           width="130"
                                           style="overflow:auto;" />
                                @else
                                    <p class="text-muted mt-2"><?= get_label('file_not_supported', 'File not supported.') ?></p>
                                @endif
                            @endif


                        </div>
                        @if($bonDeCommande->devis)
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $bonDeCommande->devis) }}" class="btn btn-outline-dark" target="_blank">
                                    <?= get_label('view', 'View') ?>
                                </a>
                            </div>
                        @endif
                    </div>



                    <div class="mb-3 col-md-12">
                        <label for="facture" class="form-label fw-bold text-primary"><?= get_label('facture', 'Facture') ?></label>
                        <div class="d-flex align-items-start gap-4 p-3 border border-info rounded shadow-sm bg-light">
                            <div class="button-wrapper">
                                <div class="input-group">
                                    <input type="file" class="form-control" id="inputGroupFile05" name="facture" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                                    <button class="btn btn-outline-primary" type="button" id="inputGroupFileAddon04">
                                        <?= get_label('upload', 'Upload') ?>
                                    </button>
                                </div>
                                <p class="text-muted mt-2">
                                    <small><?= get_label('allowed_jpg_png_pdf', 'Allowed JPG, PNG, or PDF only.') ?></small>
                                </p>
                            </div>
                        </div>
                    </div>


                </div>


                <!-- Step Four: Confirm Purchase -->
                <div class="step text-center">
                    <h5>Your Stock</h5>

                    

                    <p class="badge bg-primary p-3">
                        Please review your purchase details before submission.
                    </p>
                </div>


                <!-- Navigation Buttons -->
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" id="prevBtn" style="display:none;" onclick="nextPrev(-1)">Previous</button>
                    <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextPrev(1)">Next</button>
                </div>
            </form>

            <!-- Multi-Step Form Ends Here -->
        </div>
    </div>
</div>

<style>
   .progress-container {
    width: 100%;
    margin: 20px 0;
}

.progressbar {
    counter-reset: step;
    display: flex;
    justify-content: space-between;
    list-style: none;
    padding: 0;
    margin: 0;
    position: relative;
}

.progressbar li {
    text-align: center;
    position: relative;
    flex: 1;
    color: gray;
    text-transform: uppercase;
    font-size: 12px;
}

.progressbar li::before {
    counter-increment: step;
    content: counter(step);
    width: 30px;
    height: 30px;
    border: 2px solid gray;
    display: block;
    text-align: center;
    margin: 0 auto 10px auto;
    border-radius: 50%;
    background-color: white;
    line-height: 30px;
    transition: background-color 0.3s, border-color 0.3s, color 0.3s;
    z-index: 1;
    position: relative;
}

.progressbar li::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 4px;
    background-color: gray; /* Default color for the line */
    top: 14px;
    left: -50%;
    z-index: 0;
    transition: background-color 0.3s;
}

.progressbar li:first-child::after {
    content: none; /* No line before the first step */
}

.progressbar li.active::before {
    border-color: #007bff;
    background-color: #007bff;
    color: white;
}

.progressbar li.active::after {
    background-color: #007bff;
}

.progressbar li.completed::before {
    border-color: #007bff;
    background-color: #007bff;
    color: white;
}

.progressbar li.completed::after {
    background-color: #007bff;
}

.progressbar li.active {
    color: #007bff;
}

.step {
    display: none;
}

.step.active {
    display: block;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: none;
}

</style>

<script>
 var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
    var x = document.getElementsByClassName("step");
    x[n].style.display = "block";
    // Change the button text and visibility
    document.getElementById("prevBtn").style.display = n === 0 ? "none" : "inline";
    document.getElementById("nextBtn").innerHTML = n === (x.length - 1) ? "Submit" : "Next";
    // Update the step indicators
    updateStepIndicators(n);
}

function nextPrev(n) {
    var x = document.getElementsByClassName("step");

    // If moving to the next step, calculate amounts for step 1
    if (n === 1 && currentTab === 0) {
        calculateAmounts(); // Call this function before moving to the next tab
    }

    // Exit the function if any field in the current tab is invalid:
    if (n === 1 && !validateForm()) return false;

    // Hide the current tab:
    x[currentTab].style.display = "none";

    // Increase or decrease the current tab by 1:
    currentTab += n;

    // If you have reached the end of the form... :
    if (currentTab >= x.length) {
        // Submit the form
        document.getElementById("orderForm").submit();
        return false;
    }

    // Otherwise, display the correct tab:
    showTab(currentTab);
}


function validateForm() {
    var x, y, valid = true;
    x = document.getElementsByClassName("step");
    y = x[currentTab].getElementsByTagName("input");
    // Check all input fields in the current tab
    for (var i = 0; i < y.length; i++) {
        if (y[i].value === "" && y[i].type !== "checkbox" && y[i].id !== "inputGroupFile04") {
            y[i].className += " is-invalid"; // add bootstrap invalid class
            valid = false;
        } else {
            y[i].className = y[i].className.replace(" is-invalid", ""); // remove invalid class if filled
        }
    }
    return valid; // return the valid status
}

function updateStepIndicators(n) {
    var indicators = document.getElementsByClassName("progressbar")[0].children;
    for (var i = 0; i < indicators.length; i++) {
        // Remove the 'active' and 'completed' classes from all steps
        indicators[i].classList.remove("active");
        indicators[i].classList.remove("completed");

        // Add the 'completed' class to previous steps
        if (i < n) {
            indicators[i].classList.add("completed");
        }
    }
    // Add 'active' class to the current step
    indicators[n].classList.add("active");
}
function calculateAmounts() {
    var totalAmount = 0;
    var montantHT = 0;

    // Loop through each product entry
    var products = document.querySelectorAll('.product-entry');
    products.forEach(function(product) {
        // Get the price and quantity for each product
        var price = parseFloat(product.querySelector('[name*="[price]"]').value) || 0;
        var quantity = parseFloat(product.querySelector('[name*="[quantity]"]').value) || 0;

        // Calculate the total amount for this product (price * quantity)
        var productTotal = price * quantity;

        // Add to the total amount excluding tax
        montantHT += productTotal;
    });

    // Calculate tax (e.g., 20% VAT)
    var tvaValue = parseFloat(document.getElementById('tva').value) || 0;

// Calculate the tax based on the selected TVA
var taxAmount = montantHT * (tvaValue / 100); // Multiply montantHT by selected TVA percentage

// Calculate total amount including tax
var montant = montantHT + taxAmount;

// Fill the inputs with the calculated values
document.getElementById('montant').value = montant.toFixed(2); // Total amount including tax
document.getElementById('montant_ht').value = montantHT.toFixed(2); // Amount excluding tax
}


</script>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        let productCount = {{ count($bonDeCommande->products) }};
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
