@extends('layout')
@section('title')
<?= get_label('update_user_profile', 'Update user profile') ?>
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
                    <li class="breadcrumb-item">
                        <a href="{{url('/users/profile/'.$user->id)}}">{{$user->first_name.' '.$user->last_name}}</a>
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
            <form action="{{url('/users/update_user/' . $user->id)}}" class="form-submit-event" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="redirect_url" value="/users">
                @csrf
                @method('PUT')
                <h5 class="mb-4"><?= get_label('user_information', 'User Information') ?></h5>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="firstName" class="form-label"><?= get_label('first_name', 'First name') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="first_name" name="first_name" placeholder="<?= get_label('please_enter_first_name', 'Please enter first name') ?>" value="{{ $user->first_name }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="lastName" class="form-label"><?= get_label('last_name', 'Last name') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" name="last_name" placeholder="<?= get_label('please_enter_last_name', 'Please enter last name') ?>" id="last_name" value="{{ $user->last_name }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label"><?= get_label('email', 'E-mail') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="email" name="email" placeholder="<?= get_label('please_enter_email', 'Please enter email') ?>" value="{{ $user->email }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label"><?= get_label('country_code_and_phone_number', 'Country code and phone number') ?></label>
                        <div class="input-group">
                            <!-- Country Code Input -->
                            <input type="text" name="country_code" class="form-control country-code-input" placeholder="+1" value="{{ $user->country_code }}">
                            <!-- Mobile Number Input -->
                            <input type="text" name="phone" class="form-control" placeholder="1234567890" value="{{ $user->phone }}">
                        </div>
                    </div>
                    @if(isAdminOrHasAllDataAccess())
                    <div class="mb-3 col-md-6">
                        <label for="password" class="form-label"><?= get_label('password', 'Password') ?> <small class="text-muted"> (Leave it blank if no change)</small></label>
                        <input class="form-control" type="password" id="password" name="password" placeholder="<?= get_label('please_enter_password', 'Please enter password') ?>" autocomplete="off">
                                            </div>
                    <div class="mb-3 col-md-6">
                        <label for="password_confirmation" class="form-label"><?= get_label('confirm_password', 'Confirm password') ?></label>
                        <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" placeholder="<?= get_label('please_re_enter_password', 'Please re enter password') ?>" autocomplete="off">
                                            </div>
                    @endif
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="role"><?= get_label('role', 'Role') ?></label>
                        <div class="input-group">
                            <select class="form-select text-capitalize js-example-basic-multiple" id="role" name="role">
                                @foreach ($roles as $role)
                                    <option value="{{$role->id}}" 
                                        <?php if ($user->getRoleNames()->first() == $role->name) {
                                                    echo 'selected'; }  ?>>{{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="addressuser" class="form-label"><?= get_label('address', 'Address') ?></label>
                        <input class="form-control" type="text" id="addressuser" name="addressuser" placeholder="<?= get_label('please_enter_address', 'Please enter address') ?>" value="{{ $user->address }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="cityuser" class="form-label"><?= get_label('city', 'City') ?></label>
                        <input class="form-control" type="text" id="cityuser" name="cityuser" placeholder="<?= get_label('please_enter_city', 'Please enter city') ?>"  value="{{ $user->city }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="stateuser" class="form-label"><?= get_label('state', 'State') ?></label>
                        <input class="form-control" type="text" id="stateuser" name="stateuser" placeholder="<?= get_label('please_enter_state', 'Please enter state') ?>" value="{{ $user->state }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="countryuser" class="form-label"><?= get_label('country', 'Country') ?></label>
                        <input class="form-control" type="text" id="countryuser" name="countryuser" placeholder="<?= get_label('please_enter_country', 'Please enter country') ?>" value="{{ $user->country }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="dob" class="form-label"><?= get_label('date_of_birth', 'Date of birth') ?></label>
                        <input class="form-control" type="text" id="dob" name="dob" value="{{ $user->dob?format_date($user->dob) : ''}}" placeholder="<?= get_label('please_select', 'Please select') ?>" autocomplete="off">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="doj" class="form-label"><?= get_label('date_of_join', 'Date of joining') ?></label>
                        <input class="form-control" type="text" id="doj" name="doj" value="{{ $user->doj?format_date($user->doj) : ''}}" placeholder="<?= get_label('please_select', 'Please select') ?>" autocomplete="off">
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="photo" class="form-label"><?= get_label('profile_picture', 'Profile picture') ?></label>
                        <div class="d-flex align-items-start align-items-sm-center gap-4 my-3">
                            <img src="{{$user->photo ? asset('storage/' . $user->photo) : asset('storage/photos/no-image.jpg')}}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                            <div class="button-wrapper">
                                <div class="input-group d-flex">
                                    <input type="file" class="form-control" id="inputGroupFile02" name="upload">
                                </div>
                                <p class="text-muted mt-2"><?= get_label('allowed_jpg_png', 'Allowed JPG or PNG.') ?></p>
                            </div>
                        </div>
                    </div>
                    <!-- Enterprise Information Section -->
                <h5 class="mt-4 mb-4"><?= get_label('enterprise_information', 'Enterprise Information') ?></h5>                
               <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="denomenation_u" class="form-label">Denomination <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" name="denomenation_u" id="denomenation_u" placeholder="Denomenation" value="{{ $entreprise->denomination }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="forme_juridique_id"><?= get_label('forme_juridique', 'Forme Juridique') ?></label>
                        <select class="form-select" id="forme_juridique_id" name="forme_juridique_id">
                            <option value="">Please select</option>
                            @foreach ($formesJuridique as $forme)
                                <option value="{{ $forme->id }}" {{ $entreprise->forme_juridique_id == $forme->id ? 'selected' : '' }}>
                                    {{ ucfirst($forme->label) }}
                                </option>
                            @endforeach
                        </select>
                    </div>                          
                    <div class="mb-3 col-md-6">
                        <label for="ICE" class="form-label"><?= get_label('Identifient_commun_entreprise', "Identifiant Commun de l'Entreprise") ?></label>
                        <input class="form-control" type="text" id="ICE" name="ICE" placeholder="<?= get_label('fe', "Identifiant Commun de l'Entreprise") ?>" value="{{ $entreprise->ICE }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="RC" class="form-label"><?= get_label('REGISTRE_COMMERCE', "REGISTRE DU COMMERCE") ?></label>
                        <input class="form-control" type="text" id="RC" name="RC" placeholder="<?= get_label('de', "REGISTRE DU COMMERCE") ?>" value="{{ $entreprise->RC }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="IF" class="form-label"><?= get_label('IDENTIFIANT_FISCALE', "IDENTIFIANT FISCALE") ?></label>
                        <input class="form-control" type="text" id="IF" name="IF" placeholder="<?= get_label('dfg', "IDENTIFIANT FISCALE") ?>" value="{{ $entreprise->IF }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="address" class="form-label"><?= get_label('address', 'Address') ?></label>
                        <input class="form-control" type="text" id="address" name="address" placeholder="<?= get_label('please_enter_address', 'Please enter address') ?>" value="{{ $entreprise->address }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="city" class="form-label"><?= get_label('city', 'City') ?></label>
                        <input class="form-control" type="text" id="city" name="city" placeholder="<?= get_label('please_enter_city', 'Please enter city') ?>" value="{{ $entreprise->city }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="state" class="form-label"><?= get_label('state', 'State') ?></label>
                        <input class="form-control" type="text" id="state" name="state" placeholder="<?= get_label('please_enter_state', 'Please enter state') ?>" value="{{ $entreprise->state }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="country" class="form-label"><?= get_label('country', 'Country') ?></label>
                        <input class="form-control" type="text" id="country" name="country" placeholder="<?= get_label('please_enter_country', 'Please enter country') ?>" value="{{ $entreprise->country }}">
                    </div>
                
                    @if(isAdminOrHasAllDataAccess() && $user->getRoleNames()->first() !== 'admin')
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for=""><?= get_label('status', 'Status') ?> (<small class="text-muted mt-2"><?= get_label('deactivated_user_login_restricted', 'If Deactivated, the User Won\'t Be Able to Log In to Their Account') ?></small>)</label>
                        <div class="">
                            <div class="btn-group btn-group d-flex justify-content-center" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" id="user_active" name="status" value="1" <?= $user->status == 1 ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary" for="user_active"><?= get_label('active', 'Active') ?></label>
                                <input type="radio" class="btn-check" id="user_deactive" name="status" value="0" <?= $user->status == 0 ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary" for="user_deactive"><?= get_label('deactive', 'Deactive') ?></label>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="mt-4">
                        <button type="submit" id="submit_btn" class="btn btn-primary me-2"><?= get_label('update', 'Update') ?></button>
                        <button type="reset" class="btn btn-outline-secondary"><?= get_label('cancel', 'Cancel') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
