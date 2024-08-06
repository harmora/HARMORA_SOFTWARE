@extends('layout')
@section('title')
<?= get_label('create_entreprise', 'Create Entreprise') ?>
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
                        <a href="{{url('/entreprises')}}"><?= get_label('entreprises', 'Entreprises') ?></a>
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
            <form action="{{url('/entreprises/store')}}" method="POST" class="form-submit-event" enctype="multipart/form-data">
                <input type="hidden" name="redirect_url" value="/entreprises">
                @csrf
                <!-- User Information Section -->
                <div class="row">
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