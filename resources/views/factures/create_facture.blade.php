@extends('layout')

@section('title')
<?= get_label('create_facture', 'Create Facture') ?>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{url('/home')}}"><?= get_label('home', 'Home') ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{url('/factures')}}"><?= get_label('factures', 'Factures') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= get_label('create', 'Create') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{url('/factures/store')}}" method="POST" class="form-submit-event" enctype="multipart/form-data">
                <input type="hidden" name="redirect_url" value="/factures">
                @csrf
                <div class="row">

                    <div class="mb-3 col-md-6">
                        <label for="company_name" class="form-label">Company Name<span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="company_name" name="company_name" value="{{ $company_name }}" readonly>
                    </div>


                    <div class="mb-3 col-md-6">
                        <label for="address" class="form-label"><?= get_label('address', 'Address') ?><span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="address" name="address" placeholder="<?= get_label('please_enter_address', 'Please enter address') ?>" value="{{ old('address', $address) }}" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="contact_details" class="form-label"><?= get_label('contact_details', 'Contact Details') ?><span class="asterisk">*</span></label>
                        <textarea class="form-control" id="contact_details" name="contact_details" placeholder="<?= get_label('please_enter_contact_details', 'Please enter contact details') ?>" required>{{ old('contact_details') }}</textarea>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label"><?= get_label('email', 'Email') ?><span class="asterisk">*</span></label>
                        <input class="form-control" type="email" id="email" name="email" placeholder="<?= get_label('please_enter_email', 'Please enter email') ?>" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="date" class="form-label"><?= get_label('date', 'Date') ?><span class="asterisk">*</span></label>
                        <input class="form-control" type="date" id="date" name="date" value="{{ old('date') }}" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="invoice_number" class="form-label"><?= get_label('invoice_number', 'Invoice Number') ?><span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="invoice_number" name="invoice_number" placeholder="<?= get_label('please_enter_invoice_number', 'Please enter invoice number') ?>" value="{{ old('invoice_number') }}" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="logo" class="form-label"><?= get_label('logo', 'Logo') ?></label>
                        <input class="form-control" type="file" id="logo" name="logo">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="client_name" class="form-label"><?= get_label('client_name', 'Client Name') ?><span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="client_name" name="client_name" placeholder="<?= get_label('please_enter_client_name', 'Please enter client name') ?>" value="{{ old('client_name') }}" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="client_address" class="form-label"><?= get_label('client_address', 'Client Address') ?><span class="asterisk">*</span></label>
                        <textarea class="form-control" id="client_address" name="client_address" placeholder="<?= get_label('please_enter_client_address', 'Please enter client address') ?>" required>{{ old('client_address') }}</textarea>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="client_contact_details" class="form-label"><?= get_label('client_contact_details', 'Client Contact Details') ?><span class="asterisk">*</span></label>
                        <textarea class="form-control" id="client_contact_details" name="client_contact_details" placeholder="<?= get_label('please_enter_client_contact_details', 'Please enter client contact details') ?>" required>{{ old('client_contact_details') }}</textarea>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="item_description" class="form-label"><?= get_label('item_description', 'Item Description') ?><span class="asterisk">*</span></label>
                        <textarea class="form-control" id="item_description" name="item_description" placeholder="<?= get_label('please_enter_item_description', 'Please enter item description') ?>" required>{{ old('item_description') }}</textarea>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="item_quantity" class="form-label"><?= get_label('item_quantity', 'Item Quantity') ?><span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="item_quantity" name="item_quantity" step="1" placeholder="<?= get_label('please_enter_item_quantity', 'Please enter item quantity') ?>" value="{{ old('item_quantity') }}" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="item_price" class="form-label"><?= get_label('item_price', 'Item Price') ?><span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="item_price" name="item_price" step="0.01" placeholder="<?= get_label('please_enter_item_price', 'Please enter item price') ?>" value="{{ old('item_price') }}" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="total_amount" class="form-label"><?= get_label('total_amount', 'Total Amount') ?><span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="total_amount" name="total_amount" step="0.01" placeholder="<?= get_label('please_enter_total_amount', 'Please enter total amount') ?>" value="{{ old('total_amount') }}" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="tax_rate" class="form-label"><?= get_label('tax_rate', 'Tax Rate') ?><span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="tax_rate" name="tax_rate" step="0.01" placeholder="<?= get_label('please_enter_tax_rate', 'Please enter tax rate') ?>" value="{{ old('tax_rate') }}" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="tax_amount" class="form-label"><?= get_label('tax_amount', 'Tax Amount') ?><span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="tax_amount" name="tax_amount" step="0.01" placeholder="<?= get_label('please_enter_tax_amount', 'Please enter tax amount') ?>" value="{{ old('tax_amount') }}" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="grand_total" class="form-label"><?= get_label('grand_total', 'Grand Total') ?><span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="grand_total" name="grand_total" step="0.01" placeholder="<?= get_label('please_enter_grand_total', 'Please enter grand total') ?>" value="{{ old('grand_total') }}" required>
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
