@extends('layout')
@section('title')
<?= get_label('update_client_profile', 'Update client profile') ?>
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
                    <li class="breadcrumb-item">
                        <a href="{{url('/clients/profile/'.$client->id)}}">
                            @if ($client->internal_purpose == 1)
                            <?= $client->first_name . ' ' . $client->last_name; ?>
                            @else
                            <?= $client->denomenation; ?>
                            @endif
                        </a>
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
            <form action="{{url('/clients/update/' . $client->id)}}" method="POST" class="form-submit-event" enctype="multipart/form-data">
                <input type="hidden" name="redirect_url" value="/clients">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="internal_client" name="internal_purpose" {{$client->internal_purpose==1?'checked':''}}>
                            <label class="form-check-label" for="update_internal_client"><?= get_label('internal_client', 'Is this a client for internal purpose only?') ?></label>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="firstNamec" class="form-label"><?= get_label('first_name', 'First name') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="first_name" name="first_name" placeholder="<?= get_label('please_enter_first_name', 'Please enter first name') ?>" value="{{ $client->first_name }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="lastNamec" class="form-label"><?= get_label('last_name', 'Last name') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" name="last_name" placeholder="<?= get_label('please_enter_last_name', 'Please enter last name') ?>" id="last_name" value="{{ $client->last_name }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="denomenation" class="form-label"><?= get_label('denomenation', 'Denomenation') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" name="denomenation" placeholder="<?= get_label('please_enter_debomenation', 'Please enter your Denomenation') ?>" id="denomenation" value="{{ $client->denomenation }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label"><?= get_label('email', 'E-mail') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="email" name="email" placeholder="<?= get_label('please_enter_email', 'Please enter email') ?>" value="{{ $client->email }}" autocomplete="off">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label"><?= get_label('country_code_and_phone_number', 'Country code and phone number') ?></label>
                        <div class="input-group">
                            <input type="text" name="country_code" class="form-control country-code-input" placeholder="+1" value="{{ $client->country_code }}">
                            <input type="text" name="phone" class="form-control" placeholder="1234567890" value="{{ $client->phone }}">
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="ICE" class="form-label"><?= get_label('Identifient_commun_entreprise', "Identifiant Commun de l'Entreprise") ?></label>
                        <input class="form-control" type="text" id="ICE" name="ICE" placeholder="<?= get_label('ice', "Identifiant Commun de l'Entreprise") ?>" value="{{ old('ICE') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="RC" class="form-label"><?= get_label('REGISTRE_COMMERCE', "REGISTRE DU COMMERCE") ?></label>
                        <input class="form-control" type="text" id="RC" name="RC" placeholder="<?= get_label('rc', "REGISTRE DU COMMERCE") ?>" value="{{ old('RC') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="IF" class="form-label"><?= get_label('IDENTIFIANT_FISCALE', "IDENTIFIANT FISCALE") ?></label>
                        <input class="form-control" type="text" id="IF" name="IF" placeholder="<?= get_label('if', "IDENTIFIANT FISCALE") ?>" value="{{ old('IF') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="address" class="form-label"><?= get_label('address', 'Address') ?></label>
                        <input class="form-control" type="text" id="address" name="address" placeholder="<?= get_label('please_enter_address', 'Please enter address') ?>" value="{{ $client->address }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="city" class="form-label"><?= get_label('city', 'City') ?></label>
                        <input class="form-control" type="text" id="city" name="city" placeholder="<?= get_label('please_enter_city', 'Please enter city') ?>" value="{{ $client->city }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="state" class="form-label"><?= get_label('state', 'State') ?></label>
                        <input class="form-control" type="text" id="state" name="state" placeholder="<?= get_label('please_enter_state', 'Please enter state') ?>" value="{{ $client->state }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="country" class="form-label"><?= get_label('country', 'Country') ?></label>
                        <input class="form-control" type="text" id="country" name="country" placeholder="<?= get_label('please_enter_country', 'Please enter country') ?>" value="{{ $client->country }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="zip" class="form-label"><?= get_label('zip_code', 'Zip code') ?></label>
                        <input class="form-control" type="text" id="zip" name="zip" placeholder="<?= get_label('please_enter_zip_code', 'Please enter ZIP code') ?>" value="{{ $client->zip }}">
                    </div>
                    @if(isAdminOrHasAllDataAccess())
                    <div class="mb-3 col-md-6 {{$client->internal_purpose==1?'d-none':''}}" id="passDiv">
                        <label for="password" class="form-label">
                            <?= get_label('password', 'Password') ?>
                            @if ($client->password !== null)
                            <small class="text-muted"> (Leave it blank if no change)</small>
                            @else
                            <span class="asterisk">*</span>
                            @endif
                        </label>
                        <input class="form-control" type="password" id="password" name="password" placeholder="<?= get_label('please_enter_password', 'Please enter password') ?>" autocomplete="off">
                    </div>
                    <div class="mb-3 col-md-6 {{$client->internal_purpose==1?'d-none':''}}" id="confirmPassDiv">
                        <label for="password_confirmation" class="form-label"><?= get_label('confirm_password', 'Confirm password') ?>
                            @if ($client->password === null)
                            <span class="asterisk">*</span>
                            @endif
                        </label>
                        <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" placeholder="<?= get_label('please_re_enter_password', 'Please re enter password') ?>" autocomplete="off">
                    </div>
                    @endif
                    <div class="mb-3 col-md-6">
                        <label for="photo" class="form-label"><?= get_label('profile_picture', 'Profile picture') ?></label>
                        <div class="d-flex align-items-start gap-4">
                            <img src="{{$client->photo ? asset('storage/' . $client->photo) : asset('storage/photos/no-image.jpg')}}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                            <div class="button-wrapper">
                                <div class="input-group d-flex">
                                    <input type="file" class="form-control" id="inputGroupFile02" name="upload">
                                </div>
                                <p class="text-muted mt-2"><?= get_label('allowed_jpg_png', 'Allowed JPG or PNG.') ?></p>
                            </div>
                        </div>
                    </div>
                    @if(isAdminOrHasAllDataAccess())
                    <div class="mb-3 col-md-6 {{$client->internal_purpose==1?'d-none':''}}" id="statusDiv">
                        <label class="form-label" for=""><?= get_label('status', 'Status') ?> (<small class="text-muted mt-2"><?= get_label('deactivated_client_login_restricted', 'If Deactivated, the Client Won\'t Be Able to Log In to Their Account') ?></small>)</label>
                        <div class="">
                            <div class="btn-group btn-group d-flex justify-content-center" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" id="client_active" name="status" value="1" <?= $client->status == 1 ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary" for="client_active"><?= get_label('active', 'Active') ?></label>
                                <input type="radio" class="btn-check" id="client_deactive" name="status" value="0" <?= $client->status == 0 ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary" for="client_deactive"><?= get_label('deactive', 'Deactive') ?></label>
                            </div>
                        </div>
                    </div>
                    @if ($client->email_verification_mail_sent==0 && empty($client->email_verified_at))

                    <div class="mb-3 col-md-6 {{$client->internal_purpose==1?'d-none':''}}" id="requireEvDiv">
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
                    @endif
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2" id="submit_btn"><?= get_label('update', 'Update') ?></button>
                        <button type="reset" class="btn btn-outline-secondary"><?= get_label('cancel', 'Cancel') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
