@extends('layout')
@section('title')
<?= get_label('update_fournisseur', 'Update Supplier') ?>
@endsection
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/home') }}"><?= get_label('home', 'Home') ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ url('/fournisseurs') }}"><?= get_label('fournisseurs', 'Suppliers') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= get_label('update', 'Update') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{url('/fournisseurs/update/' . $fournisseur->id)}}" class="form-submit-event" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="redirect_url" value="/fournisseurs">
                @csrf
                @method('PUT')

                <!-- Supplier Information Section -->
                <h5 class="mb-4"><?= get_label('supplier_information', 'Supplier Information') ?></h5>

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="name" class="form-label"><?= get_label('name', 'Name') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="name" name="name" placeholder="<?= get_label('please_enter_name', 'Please enter name') ?>" value="{{ old('name', $fournisseur->name) }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label"><?= get_label('email', 'E-mail') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="email" name="email" placeholder="<?= get_label('please_enter_email', 'Please enter email') ?>" value="{{ old('email', $fournisseur->email) }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label"><?= get_label('country_code_and_phone_number', 'Country code and phone number') ?></label>
                        <div class="input-group">
                            <!-- Country Code Input -->
                            <input type="text" name="country_code" class="form-control country-code-input" placeholder="+1" value="{{ old('country_code', $fournisseur->country_code) }}">
                            <!-- Mobile Number Input -->
                            <input type="text" name="phone" class="form-control" placeholder="1234567890" value="{{ old('phone', $fournisseur->phone) }}">
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="address" class="form-label"><?= get_label('address', 'Address') ?></label>
                        <input class="form-control" type="text" id="address" name="address" placeholder="<?= get_label('please_enter_address', 'Please enter address') ?>" value="{{ old('address', $fournisseur->address) }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="city" class="form-label"><?= get_label('city', 'City') ?></label>
                        <input class="form-control" type="text" id="city" name="city" placeholder="<?= get_label('please_enter_city', 'Please enter city') ?>" value="{{ old('city', $fournisseur->city) }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="state" class="form-label"><?= get_label('state', 'State') ?></label>
                        <input class="form-control" type="text" id="state" name="state" placeholder="<?= get_label('please_enter_state', 'Please enter state') ?>" value="{{ old('state', $fournisseur->state) }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="country" class="form-label"><?= get_label('country', 'Country') ?></label>
                        <input class="form-control" type="text" id="country" name="country" placeholder="<?= get_label('please_enter_country', 'Please enter country') ?>" value="{{ old('country', $fournisseur->country) }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="logo" class="form-label"><?= get_label('logo', 'Logo') ?></label>
                        <input class="form-control" type="file" id="logo" name="logo">
                        @if ($fournisseur->logo)
                            <p class="text-muted mt-2"><?= get_label('current_logo', 'Current Logo') ?></p>
                            <img src="{{ asset('storage/' . $fournisseur->logo) }}" alt="{{ $fournisseur->name }}" class="img-thumbnail" width="100">
                        @endif
                        <p class="text-muted mt-2"><?= get_label('allowed_jpg_png', 'Allowed JPG or PNG.') ?></p>
                    </div>
                </div>

                <h5 class="mt-2 mb-4"><?= get_label('enterprise_information', 'Enterprise Information') ?></h5>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="entreprise_id"><?= get_label('entreprise', 'Entreprise') ?></label>
                        <select class="form-select" id="entreprise_id" name="entreprise_id">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            @foreach ($entreprises as $etp)
                                <option value="{{ $etp->id }}" {{ old('entreprise_id', $fournisseur->entreprise_id) == $etp->id ? "selected" : "" }}>
                                    {{ ucfirst($etp->denomination) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Additional Information Section -->

                <div class="mt-4">
                    <button type="submit" id="submit_btn" class="btn btn-primary me-2"><?= get_label('update', 'Update') ?></button>
                    <button type="reset" class="btn btn-outline-secondary"><?= get_label('cancel', 'Cancel') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
