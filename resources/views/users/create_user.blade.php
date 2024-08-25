@extends('layout')
@section('title')
<?= get_label('create_user', 'Create user') ?>
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
                        <a href="{{url('/users')}}"><?= get_label('users', 'Users') ?></a>
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
            <form action="{{url('/users/store')}}" method="POST" class="form-submit-event" enctype="multipart/form-data">
                <input type="hidden" name="redirect_url" value="/users">
                @csrf

                <!-- User Information Section -->
                <h5 class="mb-4"><?= get_label('user_information', 'User Information') ?></h5>

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="firstName" class="form-label"><?= get_label('first_name', 'First name') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="first_name" name="first_name" placeholder="<?= get_label('please_enter_first_name', 'Please enter first name') ?>" value="{{ old('first_name') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="lastName" class="form-label"><?= get_label('last_name', 'Last name') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" name="last_name" id="last_name" placeholder="<?= get_label('please_enter_last_name', 'Please enter last name') ?>" value="{{ old('last_name') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label"><?= get_label('email', 'E-mail') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="email" name="email" placeholder="<?= get_label('please_enter_email', 'Please enter email') ?>" value="{{ old('email') }}">
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
                    <div class="mb-3 col-md-6">
                        <label for="password" class="form-label"><?= get_label('password', 'Password') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="password" id="password" name="password" placeholder="<?= get_label('please_enter_password', 'Please enter password') ?>" autocomplete="off">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="password_confirmation" class="form-label"><?= get_label('confirm_password', 'Confirm password') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" placeholder="<?= get_label('please_re_enter_password', 'Please re enter password') ?>" autocomplete="off">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="role"><?= get_label('role', 'Role') ?> </label>
                        <select class="form-select text-capitalize js-example-basic-multiple" id="role" name="role">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            @foreach ($roles as $role)
                            <option value="{{$role->id}}" {{ old('role') == $role->id ? "selected" : "" }}>{{ ucfirst($role->rolename) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="paque_id"><?= get_label('paque', 'pack') ?></label>
                        <select class="form-select" id="paque_id" name="paque_id">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            @foreach ($paques as $paque)
                                <option value="{{ $paque->id }}" {{ old('paque_id') == $paque->id ? "selected" : "" }}>
                                    {{ ucfirst($paque->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="addressuser" class="form-label"><?= get_label('address', 'Address') ?></label>
                        <input class="form-control" type="text" id="addressuser" name="addressuser" placeholder="<?= get_label('please_enter_address', 'Please enter address') ?>" value="{{ old('address') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="cityuser" class="form-label"><?= get_label('city', 'City') ?></label>
                        <input class="form-control" type="text" id="cityuser" name="cityuser" placeholder="<?= get_label('please_enter_city', 'Please enter city') ?>" value="{{ old('city') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="stateuser" class="form-label"><?= get_label('state', 'State') ?></label>
                        <input class="form-control" type="text" id="stateuser" name="stateuser" placeholder="<?= get_label('please_enter_state', 'Please enter state') ?>" value="{{ old('state') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="countryuser" class="form-label"><?= get_label('country', 'Country') ?></label>
                        <input class="form-control" type="text" id="countryuser" name="countryuser" placeholder="<?= get_label('please_enter_country', 'Please enter country') ?>" value="{{ old('country') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="dob" class="form-label"><?= get_label('date_of_birth', 'Date of birth') ?></label>
                        <input class="form-control" type="text" id="dob" name="dob" placeholder="<?= get_label('please_select', 'Please select') ?>" autocomplete="off">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="doj" class="form-label"><?= get_label('date_of_join', 'Date of joining') ?></label>
                        <input class="form-control" type="text" id="doj" name="doj" placeholder="<?= get_label('please_select', 'Please select') ?>" autocomplete="off">
                    </div>
                    
                    <div class="mb-3 col-md-6">
                        <label for="photo" class="form-label"><?= get_label('profile_picture', 'Profile picture') ?></label>
                        <input class="form-control" type="file" id="photo" name="profile">
                        <p class="text-muted mt-2"><?= get_label('allowed_jpg_png', 'Allowed JPG or PNG.') ?></p>
                    </div>
                </div>

                <!-- Enterprise Information Section -->
                <h5 class="mt-4 mb-4"><?= get_label('enterprise_information', 'Enterprise Information') ?></h5>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="entreprise_id"><?= get_label('entreprise', 'Entreprise') ?></label>
                        <select class="form-select" id="entreprise_id" name="entreprise_id">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            @foreach ($entreprises as $etp)
                                <option value="{{ $etp->id }}" {{ old('entreprise_id') == $etp->id ? "selected" : "" }}>
                                    {{ ucfirst($etp->denomination) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div> 
                
                {{-- <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="denomenation_u" class="form-label">Denomination <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" name="denomenation_u" id="denomenation_u" placeholder="Denomenation" value="{{ old('denomenation') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="forme_juridique_id"><?= get_label('forme_juridique', 'Forme Juridique') ?></label>
                        <select class="form-select" id="forme_juridique_id" name="forme_juridique_id">
                            <option value=""><?= get_label('please_select', 'Please select') ?></option>
                            @foreach ($formesJuridique as $forme)
                                <option value="{{ $forme->id }}" {{ old('forme_juridique_id') == $forme->id ? "selected" : "" }}>
                                    {{ ucfirst($forme->label) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="ICE" class="form-label"><?= get_label('Identifient_commun_entreprise', "Identifiant Commun de l'Entreprise") ?></label>
                        <input class="form-control" type="text" id="ICE" name="ICE" placeholder="<?= get_label('fe', "Identifiant Commun de l'Entreprise") ?>" value="{{ old('ICE') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="RC" class="form-label"><?= get_label('REGISTRE_COMMERCE', "REGISTRE DU COMMERCE") ?></label>
                        <input class="form-control" type="text" id="RC" name="RC" placeholder="<?= get_label('de', "REGISTRE DU COMMERCE") ?>" value="{{ old('RC') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="IF" class="form-label"><?= get_label('IDENTIFIANT_FISCALE', "IDENTIFIANT FISCALE") ?></label>
                        <input class="form-control" type="text" id="IF" name="IF" placeholder="<?= get_label('dfg', "IDENTIFIANT FISCALE") ?>" value="{{ old('IF') }}">
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
                </div> --}}
                @if(isAdminOrHasAllDataAccess())
                <div class="mb-3 col-md-6">
                    <label class="form-label" for=""><?= get_label('status', 'Status') ?> (<small class="text-muted mt-2"><?= get_label('deactivated_user_login_restricted', 'If Deactivated, the User Won\'t Be Able to Log In to Their Account') ?></small>)</label>
                    <div class="">
                        <div class="btn-group btn-group d-flex justify-content-center" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" id="user_active" name="status" value="1">
                            <label class="btn btn-outline-primary" for="user_active"><?= get_label('active', 'Active') ?></label>
                            <input type="radio" class="btn-check" id="user_deactive" name="status" value="0" checked>
                            <label class="btn btn-outline-primary" for="user_deactive"><?= get_label('deactive', 'Deactive') ?></label>
                        </div>
                    </div>
                </div>
                <div class="mb-3 col-md-6">
                    <label class="form-label" for="">
                        <?= get_label('require_email_verification', 'Require email verification?') ?>
                        <i class='bx bx-info-circle text-primary' data-bs-toggle="tooltip" data-bs-placement="top" title="<?= get_label('user_require_email_verification_info', 'If Yes is selected, user will receive a verification link via email. Please ensure that email settings are configured and operational.') ?>"></i>
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
            </form>
        </div>
    </div>
</div>
@endsection