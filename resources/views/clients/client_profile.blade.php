@extends('layout')
@section('title')
<?= get_label('client_profile', 'Client profile') ?>
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
                        @if ($client->internal_purpose == 1)
                        <?= $client->first_name . ' ' . $client->last_name; ?>
                        @else
                        <?= $client->denomenation; ?>
                        @endif
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <!-- Account -->
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <img src="{{$client->photo ? asset('storage/' . $client->photo) : asset('/profiles/1.png')}}" alt="client-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                        <div>
                            <h4 class=" fw-bold">
                                <?=get_label('full_name', 'full name') ?>: <?=$client->first_name ? $client->first_name.' '.$client->last_name : " -- --" ?>
                            </h4> 
                            <h4 class=" fw-bold ">
                                <?=get_label('denomination', 'denomination') ?> : {{$client->denomenation??'--'}}
                            </h4> 
                        </div>
                        <?= $client->status == 1 ? '<span class="badge bg-success">' . get_label('active', 'Active') . '</span>' : '<span class="badge bg-danger">' . get_label('deactive', 'Deactive') . '</span>' ?>
                    </div>
                </div>
                <hr class="my-0" />
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="email"><?= get_label('email', 'E-mail') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="exampleFormControlReadOnlyInput1" placeholder="" value="{{$client->email}}" readonly="">
                            </div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label"><?= get_label('country_code_and_phone_number', 'Country code and phone number') ?></label>
                            <div class="input-group">
                                <!-- Country Code Input -->
                                <input type="text" name="country_code" class="form-control country-code-input" placeholder="+1" value="{{$client->country_code??'--'}}" readonly>
                                <!-- Mobile Number Input -->
                                <input type="text" name="phone" class="form-control" placeholder="1234567890" value="{{$client->phone??'--'}}" readonly>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="address"><?= get_label('address', 'Address') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="address" placeholder="" value="{{$client->address??'--'}}" readonly="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="city"><?= get_label('city', 'City') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="city" placeholder="" value="{{$client->city??'--'}}" readonly="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="state"><?= get_label('if', "IDENTIFIANT FISCALE") ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="state" placeholder="" value="{{$client->IF??'--'}}" readonly="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="country"><?= get_label('rc', "REGISTRE DU COMMERCE") ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="country" placeholder="" value="{{$client->RC??'--'}}" readonly="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="zip"><?= get_label('ice', "Identifiant Commun de l'Entreprise") ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="zip" placeholder="" value="{{$client->ICE??'--'}}" readonly="">
                            </div>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="state"><?= get_label('state', 'State') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="state" placeholder="" value="{{$client->state??'--'}}" readonly="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="country"><?= get_label('country', 'Country') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="country" placeholder="" value="{{$client->country??'--'}}" readonly="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="zip"><?= get_label('zip_code', 'Zip code') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="zip" placeholder="" value="{{$client->zip??'--'}}" readonly="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
