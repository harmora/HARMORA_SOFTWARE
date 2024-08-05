@extends('layout')
@section('title')
<?= get_label('user_profile', 'User profile') ?>
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
                        <?= $user->first_name . ' ' . $user->last_name; ?>
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
                        <img src="{{$user->photo ? asset('storage/' . $user->photo) : asset('/photos/1.png')}}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                        <h4 class="card-header fw-bold">{{ $user->first_name }} {{$user->last_name}}</h4> <?= $user->status == 1 ? '<span class="badge bg-success">' . get_label('active', 'Active') . '</span>' : '<span class="badge bg-danger">' . get_label('deactive', 'Deactive') . '</span>' ?>
                    </div>
                </div>
                <hr class="my-0" />
                <div class="card-body">
                    <h5 class="mb-4"><?= get_label('user_information', 'User Information') ?></h5>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="email"><?= get_label('email', 'E-mail') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="exampleFormControlReadOnlyInput1" value="{{$user->email}}" readonly="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label"><?= get_label('country_code_and_phone_number', 'Country code and phone number') ?></label>
                            <div class="input-group">
                                <!-- Country Code Input -->
                                <input type="text" name="country_code" class="form-control country-code-input" placeholder="+1" value="{{$user->country_code??'--'}}" readonly>
                                <!-- Mobile Number Input -->
                                <input type="text" name="phone" class="form-control" placeholder="1234567890" value="{{$user->phone??'--'}}" readonly>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="role"><?= get_label('role', 'Role') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control text-capitalize" type="text" id="role" value="{{$user->getRoleNames()->first()}}" readonly="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="address"><?= get_label('address', 'Address') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="address" value="{{$user->address??'--'}}" readonly="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="city"><?= get_label('city', 'City') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="city" value="{{$user->city??'--'}}" readonly="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="state"><?= get_label('state', 'State') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="state" value="{{$user->state??'--'}}" readonly="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="country"><?= get_label('country', 'Country') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="country" value="{{$user->country??'--'}}" readonly="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="zip"><?= get_label('zip_code', 'Zip code') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="zip" value="{{$user->zip??'--'}}" readonly="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="dob"><?= get_label('dob', 'Date of birth') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" value="{{ format_date($user->dob)}}" readonly="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="doj"><?= get_label('date_of_join', 'Date of joining') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" value="{{ format_date($user->doj)}}" readonly="">
                            </div>
                        </div>
                    </div>
                    <h5 class="mt-4 mb-4"><?= get_label('enterprise_information', 'Enterprise Information') ?></h5>                
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="denomenation_u" class="form-label"><?= get_label('denomination', 'Denomination') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" value="{{ $entreprise->denomination??'--' }}"readonly="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="forme" class="form-label"><?= get_label('Forme_juridique', "forme juridique") ?></label>
                            <input class="form-control" value="{{ $formeJuridiqueName??'--' }}" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="ICE" class="form-label"><?= get_label('Identifiant_commun_entreprise', "Identifiant Commun de l'Entreprise") ?></label>
                            <input class="form-control" value="{{ $entreprise->ICE??'--' }}" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="RC" class="form-label"><?= get_label('REGISTRE_COMMERCE', "REGISTRE DU COMMERCE") ?></label>
                            <input class="form-control" value="{{ $entreprise->RC??'--' }}" readonly>
                        </div>
                        
                        <div class="mb-3 col-md-6">
                            <label for="IF" class="form-label"><?= get_label('IDENTIFIANT_FISCALE', "IDENTIFIANT FISCALE") ?></label>
                            <input class="form-control"  value="{{ $entreprise->IF??'--' }}" readonly>
                        </div>
                        
                        <div class="mb-3 col-md-6">
                            <label for="address" class="form-label"><?= get_label('address', 'Address') ?></label>
                            <input class="form-control" value="{{ $entreprise->address??'--' }}" readonly>
                        </div>
                        
                        <div class="mb-3 col-md-6">
                            <label for="city" class="form-label"><?= get_label('city', 'City') ?></label>
                            <input class="form-control"  value="{{ $entreprise->city??'--' }}" readonly>
                        </div>
                        
                        <div class="mb-3 col-md-6">
                            <label for="state" class="form-label"><?= get_label('state', 'State') ?></label>
                            <input class="form-control" value="{{ $entreprise->state??'--' }}" readonly>
                        </div>
                        
                        <div class="mb-3 col-md-6">
                            <label for="country" class="form-label"><?= get_label('country', 'Country') ?></label>
                            <input class="form-control" value="{{ $entreprise->country??'--' }}" readonly>
                        </div>     
                   </div>    
            </div>
    </div>
    <!-- Tabs -->
        <div class="tab-content">
            @if ($auth_user->can('manage_projects'))
            <div class="tab-pane fade active show" id="navs-top-projects" role="tabpanel">
                <div class="d-flex justify-content-between">
                    <h4 class="fw-bold">{{$user->first_name}}'s <?= get_label('projects', 'Projects') ?></h4>
                </div>
                @if (is_countable($projects) && count($projects) > 0)
                <?php
                $id = 'user_' . $user->id;
                ?>
                <x-projects-card :projects="$projects" :id="$id" :users="$users" :clients="$clients" />
                @else
                <?php
                $type = 'Projects'; ?>
                <x-empty-state-card :type="$type" />
                @endif
            </div>
            @endif
            @if ($auth_user->can('manage_tasks'))
            <div class="tab-pane fade {{!$auth_user->can('manage_projects')?'active show':''}}" id="navs-top-tasks" role="tabpanel">
                <div class="d-flex justify-content-between">
                    <h4 class="fw-bold">{{$user->first_name}}'s <?= get_label('tasks', 'Tasks') ?></h4>
                </div>
                @if ($tasks > 0)
                <?php
                $id = 'user_' . $user->id;
                ?>
                <x-tasks-card :tasks="$tasks" :id="$id" :users="$users" :clients="$clients" :projects="$projects" />
                @else
                <?php
                $type = 'Tasks'; ?>
                <x-empty-state-card :type="$type" />
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection