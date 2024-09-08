@extends('layout')
@section('title')
<?= get_label('create_client', 'Create client') ?>
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
                        <a href="{{url('/clients')}}"><?= get_label('clients', 'Clients') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= get_label('create', 'Create') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    @role('admin')
    @php
    $account_creation_template = App\Models\Template::where('type', 'email')
        ->where('name', 'account_creation')
        ->first();
    @endphp
    @if (!($account_creation_template) || ($account_creation_template && $account_creation_template->status == 1))
    <div class="alert alert-primary" role="alert">
        {{ get_label('acc_crea_email_enabled_inf', 'As Account Creation Email Status Is Active, Please Ensure Email Settings Are Configured and Operational (Not Applicable If the Client Is for Internal Purposes).') }}
        <a href="/settings/templates" target="_blank">
            {{ get_label('click_to_change_acc_crea_email_sts', 'Click Here to Change Account Creation Email Status.') }}
        </a>
    </div>
    @endif
    @endrole




    <div class="card">
        <div class="card-body">
            <form action="{{url('/clients/store')}}" method="POST" class="form-submit-event" enctype="multipart/form-data">
                <input type="hidden" name="redirect_url" value="/clients">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="internal_client" name="internal_purpose">
                            <label class="form-check-label" for="internal_client"><?= get_label('individual', 'Is this an induvual representant?') ?></label>
                            <i class='bx bx-info-circle text-primary' data-bs-toggle="tooltip" data-bs-placement="top" title="<?= get_label('internal_client_info', 'Select this option if you want to create a client for internal use only, without granting account access to the client.') ?>"></i>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="firstNamec" class="form-label"><?= get_label('first_name', 'First name') ?> <span class="asterisk d-none">*</span></label>
                        <input class="form-control" type="text" id="first_name" name="first_name" placeholder="<?= get_label('please_enter_first_name', 'Please enter first name') ?>" value="{{ old('first_name') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="lastNamec" class="form-label"><?= get_label('last_name', 'Last name') ?> <span class="asterisk d-none">*</span></label>
                        <input class="form-control" type="text" name="last_name" id="last_name" placeholder="<?= get_label('please_enter_last_name', 'Please enter last name') ?>" value="{{ old('last_name') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="denomenation" class="form-label">Denomination <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" name="denomenation" id="denomenation" placeholder="Denomenation" value="{{ old('denomenation') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label"><?= get_label('email', 'E-mail') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="email" name="email" placeholder="<?= get_label('please_enter_email', 'Please enter email') ?>" value="{{ old('email') }}" autocomplete="off">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label"><?= get_label('country_code_and_phone_number', 'Country code and phone number') ?></label>
                        <div class="input-group">
                            <!-- Country Code Input -->
                            <input type="text" name="country_code" class="form-control country-code-input" placeholder="+1" value="{{ old('country_code') }}">
                            <!-- Mobile Number Input -->
                            <input type="text" name="phone" class="form-control" placeholder="1234567890" value="{{ old('phone') }}">
                        </div>
                    </div>
                    <div class="mb-3 col-md-6 d-none">
                        <label for="dob" class="form-label"><?= get_label('date_of_birth', 'Date of birth') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="dob" name="dob" placeholder="<?= get_label('please_select', 'Please select') ?>" autocomplete="off">
                    </div>
                    <div class="mb-3 col-md-6 d-none">
                        <label for="doj" class="form-label"><?= get_label('date_of_join', 'Date of joining') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="doj" name="doj" placeholder="<?= get_label('please_select', 'Please select') ?>" autocomplete="off">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="ICE" class="form-label"><?= get_label('ice', "Identifiant Commun de l'Entreprise") ?></label>
                        <input class="form-control" type="text" id="ICE" name="ICE" placeholder="<?= get_label('ice', "Identifiant Commun de l'Entreprise") ?>" value="{{ old('ICE') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="RC" class="form-label"><?= get_label('rc', "REGISTRE DU COMMERCE") ?></label>
                        <input class="form-control" type="text" id="RC" name="RC" placeholder="<?= get_label('rc', "REGISTRE DU COMMERCE") ?>" value="{{ old('RC') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="IF" class="form-label"><?= get_label('if', "IDENTIFIANT FISCALE") ?></label>
                        <input class="form-control" type="text" id="IF" name="IF" placeholder="<?= get_label('if', "IDENTIFIANT FISCALE") ?>" value="{{ old('IF') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="address" class="form-label"><?= get_label('address', 'Address') ?></label>
                        <input class="form-control" type="text" id="address" name="address" placeholder="<?= get_label('please_enter_address', 'Please enter address') ?>" value="{{ old('address') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="city" class="form-label"><?= get_label('city', 'City') ?></label>
                        <input class="form-control" type="text" id="city" name="city" placeholder="<?= get_label('please_enter_city', 'Please enter city') ?>" value="{{ old('city') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="state" class="form-label"><?= get_label('state', 'State') ?></label>
                        <input class="form-control" type="text" id="state" name="state" placeholder="<?= get_label('please_enter_state', 'Please enter state') ?>" value="{{ old('state') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="country" class="form-label"><?= get_label('country', 'Country') ?></label>
                        <input class="form-control" type="text" id="country" name="country" placeholder="<?= get_label('please_enter_country', 'Please enter country') ?>" value="{{ old('country') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="zip" class="form-label"><?= get_label('zip_code', 'Zip code') ?></label>
                        <input class="form-control" type="text" id="zip" name="zip" placeholder="<?= get_label('please_enter_zip_code', 'Please enter ZIP code') ?>" value="{{ old('zip') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="profile" class="form-label"><?= get_label('profile_picture', 'Profile picture') ?></label>
                        <input class="form-control" type="file" id="profile" name="profile">
                        <p class="text-muted mt-2"><?= get_label('allowed_jpg_png', 'Allowed JPG or PNG.') ?></p>
                    </div>
                    @if(isAdminOrHasAllDataAccess())
                    <div class="mb-3 col-md-6" id="statusDiv">
                        <label class="form-label" for=""><?= get_label('status', 'Status') ?> (<small class="text-muted mt-2"><?= get_label('deactivated_client_login_restricted', 'If Deactivated, the Client Won\'t Be Able to Log In to Their Account') ?></small>)</label>
                        <div class="">
                            <div class="btn-group btn-group d-flex justify-content-center" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" id="client_active" name="status" value="1">
                                <label class="btn btn-outline-primary" for="client_active"><?= get_label('active', 'Active') ?></label>
                                <input type="radio" class="btn-check" id="client_deactive" name="status" value="0" checked>
                                <label class="btn btn-outline-primary" for="client_deactive"><?= get_label('deactive', 'Deactive') ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6" id="requireEvDiv">
                        <label class="form-label" for="">
                            <?= get_label('require_email_verification', 'Require email verification?') ?>
                            <i class='bx bx-info-circle text-primary' data-bs-toggle="tooltip" data-bs-placement="top" title="<?= get_label('client_require_email_verification_info', 'If Yes is selected, client will receive a verification link via email. Please ensure that email settings are configured and operational.') ?>"></i>
                        </label>
                        <div class="">
                            <div class="btn-group btn-group d-flex justify-content-center" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" id="require_ev_yes" name="require_ev" value="1" checked>
                                <label class="btn btn-outline-primary" for="require_ev_yes"><?= get_label('yes', 'Yes') ?></label>
                                <input type="radio" class="btn-check" id="require_ev_no" name="require_ev" value="0">
                                <label class="btn btn-outline-primary" for="require_ev_no"><?= get_label('no', 'No') ?></label>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2" id="submit_btn"><?= get_label('create', 'Create') ?></button>
                        <button type="reset" class="btn btn-outline-secondary"><?= get_label('cancel', 'Cancel') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
