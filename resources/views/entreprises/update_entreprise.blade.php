@extends('layout')
@section('title')
<?= get_label('update_entreprise_profile', 'Update entreprise profile') ?>
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
                    <li class="breadcrumb-item">
                        <a href="{{url('/entreprises/profile/'.$entreprise->id)}}">{{$entreprise->denomination}}</a>
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
            <form action="{{url('/entreprises/update_entreprise/' . $entreprise->id)}}" class="form-submit-event" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="redirect_url" value="/entreprises">
                @csrf
                @method('PUT')
                <!-- Enterprise Information Section -->
                <h5 class="mt-4 mb-4"><?= get_label('enterprise_information', 'Enterprise Information') ?></h5>
               <div class="row">

                <div class="mb-3 col-md-12">
                    <label for="photo" class="form-label"><?= get_label('profile_picture', 'Profile picture') ?></label>
                    <div class="d-flex align-items-start align-items-sm-center gap-4 my-3">
                        <img src="{{ $entreprise->photo ? asset('storage/' . $entreprise->photo) : asset('storage/photos/no-image.jpg') }}" alt="entreprise-logo" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                        <div class="button-wrapper">
                            <div class="input-group d-flex">
                                <input type="file" class="form-control" id="inputGroupFile02" name="upload">
                            </div>
                            <p class="text-muted mt-2"><?= get_label('allowed_jpg_png', 'Allowed JPG or PNG.') ?></p>
                        </div>
                    </div>
                </div>


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

                    {{-- @if(isAdminOrHasAllDataAccess() && $user->getRoleNames()->first() !== 'admin')
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
                    @endif --}}
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
