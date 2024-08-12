@props(['commande', 'statuses', 'priorities'])

<div class="card m-2 shadow" data-commande-id="{{ $commande->id }}">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h6 class="card-title">
                <a href="{{ url('/commandes/information/' . $commande->id) }}" target="_blank">
                    <strong>{{ $commande->title }}</strong>
                </a>
            </h6>
            <div class="d-flex align-items-center justify-content-center">
                <a href="javascript:void(0);" class="quick-view" data-id="{{ $commande->id }}" data-type="commande">
                    <i class='bx bx bx-info-circle text-info' data-bs-toggle="tooltip" data-bs-placement="right" title="Quick View"></i>
                </a>
                <a href="{{ url('/chat?type=commande&id=' . $commande->id) }}" class="mx-2" target="_blank">
                    <i class='bx bx-message-rounded-dots text-danger' data-bs-toggle="tooltip" data-bs-placement="right" title="Discussion"></i>
                </a>
            </div>
        </div>

        <div class="card-subtitle text-muted mb-3">{{ $commande->title }}</div>

        <div class="row mt-2">
            <div class="col-md-12">
                <p class="card-text mb-1">
                    Users:
                    <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                        @foreach($commande->users->take(9) as $user)
                            <li class="avatar avatar-sm pull-up" title="{{ $user->first_name }} {{ $user->last_name }}">
                                <a href="/users/profile/{{ $user->id }}" target="_blank">
                                    <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('storage/photos/no-image.jpg') }}" class="rounded-circle" alt="{{ $user->first_name }} {{ $user->last_name }}">
                                </a>
                            </li>
                        @endforeach
                        @if($commande->users->count() > 9)
                            <span class="badge badge-center rounded-pill bg-primary mx-1">+{{ $commande->users->count() - 9 }}</span>
                        @endif
                    </ul>
                </p>
            </div>
            <div class="col-md-12">
                <p class="card-text mb-1">
                    Clients:
                    <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                        @foreach($commande->clients->take(10) as $client)
                            <li class="avatar avatar-sm pull-up" title="{{ $client->first_name }} {{ $client->last_name }}">
                                <a href="/clients/profile/{{ $client->id }}" target="_blank">
                                    <img src="{{ $client->photo ? asset('storage/' . $client->photo) : asset('storage/photos/no-image.jpg') }}" class="rounded-circle" alt="{{ $client->first_name }} {{ $client->last_name }}">
                                </a>
                            </li>
                        @endforeach
                        @if($commande->clients->count() > 10)
                            <span class="badge badge-center rounded-pill bg-primary mx-1">+{{ $commande->clients->count() - 10 }}</span>
                        @endif
                    </ul>
                </p>
            </div>
        </div>

        <div class="d-flex flex-column">
            <div>
                <label for="statusSelect">Status</label>
                <select class="form-select form-select-sm mb-3" id="statusSelect" data-id="{{ $commande->id }}">
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" {{ $commande->status->id == $status->id ? 'selected' : '' }}>
                            {{ $status->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="prioritySelect">Priority</label>
                <select class="form-select form-select-sm" id="prioritySelect" data-id="{{ $commande->id }}">
                    @foreach($priorities as $priority)
                        <option value="{{ $priority->id }}" {{ $commande->priority && $commande->priority->id == $priority->id ? 'selected' : '' }}>
                            {{ $priority->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mt-2">
                <small class="text-muted">Created At: {{ $commande->created_at->format('Y M d') }}</small>
            </div>
        </div>
    </div>
</div>





<!-- @props(['commande'])

@php
$user = getAuthenticatedUser();
@endphp

<div class="card m-2 shadow" data-commande-id="{{$commande->id}}">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h6 class="card-title">
                <a href="{{url('/commandes/information/' . $commande->id)}}" target="_blank">
                    <strong>{{$commande->title}}</strong>
                </a>
            </h6>
            <div class="d-flex align-items-center justify-content-center">
                <div class="input-group">
                    <a href="javascript:void(0);" class="mx-2" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class='bx bx-cog'></i>
                    </a>
                    <ul class="dropdown-menu">
                        <a href="javascript:void(0);" class="edit-commande" data-id="{{$commande->id}}">
                            <li class="dropdown-item">
                                <i class='menu-icon tf-icons bx bx-edit text-primary'></i> Update
                            </li>
                        </a>
                        <a href="javascript:void(0);" class="delete" data-id="{{ $commande->id }}">
                            <li class="dropdown-item">
                                <i class='menu-icon tf-icons bx bx-trash text-danger'></i> Delete
                            </li>
                        </a>
                        <a href="javascript:void(0);" class="duplicate" data-id="{{$commande->id}}">
                            <li class="dropdown-item">
                                <i class='menu-icon tf-icons bx bx-copy text-warning'></i> Duplicate
                            </li>
                        </a>
                    </ul>
                </div>
                <a href="javascript:void(0);" class="quick-view" data-id="{{$commande->id}}">
                    <i class='bx bx-info-circle text-info'></i>
                </a>
                <a href="{{ url('/chat?type=commande&id=' . $commande->id) }}" class="mx-2" target="_blank">
                    <i class='bx bx-message-rounded-dots text-danger'></i>
                </a>
            </div>
        </div>

        <div class="card-subtitle text-muted mb-3">
            {{$commande->product->title ?? 'No Product Title'}}
        </div>

        <div class="row mt-2">
            <div class="col-md-12">
                <p class="card-text mb-1">Users:</p>
                <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                    @forelse ($commande->users as $user)
                        <li class="avatar avatar-sm pull-up" title="{{ $user->first_name }} {{ $user->last_name }}">
                            <a href="/users/profile/{{ $user->id }}" target="_blank">
                                <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('storage/photos/no-image.jpg') }}" class="rounded-circle" alt="{{ $user->first_name }} {{ $user->last_name }}">
                            </a>
                        </li>
                    @empty
                        <span class="badge bg-primary">Not assigned</span>
                    @endforelse
                </ul>
            </div>

            <div class="col-md-12">
                <p class="card-text mb-1">Clients:</p>
                <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                    @forelse ($commande->clients as $client)
                        <li class="avatar avatar-sm pull-up" title="{{ $client->first_name }} {{ $client->last_name }}">
                            <a href="/clients/profile/{{ $client->id }}" target="_blank">
                                <img src="{{ $client->photo ? asset('storage/' . $client->photo) : asset('storage/photos/no-image.jpg') }}" class="rounded-circle" alt="{{ $client->first_name }} {{ $client->last_name }}">
                            </a>
                        </li>
                    @empty
                        <span class="badge bg-primary">Not assigned</span>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="d-flex flex-column">
            <div>
                <label for="statusSelect">Status</label>
                <select class="form-select form-select-sm select-bg-label-{{$commande->status->color}}" id="statusSelect" data-id="{{ $commande->id }}">
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" class="badge bg-label-{{ $status->color }}" {{ $commande->status->id == $status->id ? 'selected' : '' }}>
                            {{ $status->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="prioritySelect">Priority</label>
                <select class="form-select form-select-sm select-bg-label-{{$commande->priority->color ?? 'secondary'}}" id="prioritySelect" data-id="{{ $commande->id }}">
                    @foreach($priorities as $priority)
                        <option value="{{ $priority->id }}" class="badge bg-label-{{ $priority->color }}" {{ $commande->priority && $commande->priority->id == $priority->id ? 'selected' : '' }}>
                            {{ $priority->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mt-2">
                <small class="text-muted">Created At: {{ format_date($commande->created_at) }}</small>
            </div>
        </div>
    </div>
</div> -->




<!-- @props(['commande'])
@php
$user = getAuthenticatedUser();
@endphp
<div class="card m-2 shadow" data-commande-id="{{$commande->id}}">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h6 class="card-title"><a href="{{url('/commandes/information/' . $commande->id)}}" target="_blank"><strong>{{$commande->title}}</strong></a></h6>
            <div class="d-flex align-items-center justify-content-center">
                <!-- @if ($user->can('edit_commandes') || $user->can('delete_commandes') || $user->can('create_commandes')) 
                <div class="input-group">
                    <a href="javascript:void(0);" class="mx-2" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class='bx bx-cog'></i>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- @if ($user->can('edit_commandes')) 
                        <a href="javascript:void(0);" class="edit-commande" data-id="{{$commande->id}}">
                            <li class="dropdown-item">
                                <i class='menu-icon tf-icons bx bx-edit text-primary'></i> <?= get_label('update', 'Update') ?>
                            </li>
                        </a>
                        <!-- @endif
                        @if ($user->can('delete_commandes')) -->
                        <a href="javascript:void(0);" class="delete" data-reload="true" data-type="commandes" data-id="{{ $commande->id }}">
                            <li class="dropdown-item">
                                <i class='menu-icon tf-icons bx bx-trash text-danger'></i> <?= get_label('delete', 'Delete') ?>
                            </li>
                        </a>
                        <!-- @endif
                        @if ($user->can('create_commandes')) 
                        <a href="javascript:void(0);" class="duplicate" data-reload="true" data-type="commandes" data-id="{{$commande->id}}" data-title="{{$commande->title}}">
                            <li class="dropdown-item">
                                <i class='menu-icon tf-icons bx bx-copy text-warning'></i><?= get_label('duplicate', 'Duplicate') ?>
                            </li>
                        </a>
                        <!-- @endif 
                    </ul>
                </div>
                <!-- @endif 
                <a href="javascript:void(0);" class="quick-view" data-id="{{$commande->id}}" data-type="commande">
                    <i class='bx bx bx-info-circle text-info' data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="{{get_label('quick_view', 'Quick View')}}"></i>
                </a>
                <a href="{{ url('/chat?type=commande&id=' . $commande->id) }}" class="mx-2" target="_blank">
                    <i class='bx bx-message-rounded-dots text-danger' data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="{{get_label('discussion', 'Discussion')}}"></i>
                </a>
            </div>
        </div>
        <div class="card-subtitle text-muted mb-3">{{$commande->product->title}}</div>
        <div class="row mt-2">
            <div class="col-md-12">
                <p class="card-text mb-1">
                    <?= get_label('users', 'Users') ?>:
                <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                    <?php
                    $users = $commande->users;
                    $count = count($users);
                    $displayed = 0;
                    if ($count > 0) {
                        foreach ($users as $user) {
                            if ($displayed < 9) { ?>
                                <li class="avatar avatar-sm pull-up" title="<?= $user->first_name ?> <?= $user->last_name ?>">
                                    <a href="/users/profile/<?= $user->id ?>" target="_blank">
                                        <img src="<?= $user->photo ? asset('storage/' . $user->photo) : asset('storage/photos/no-image.jpg') ?>" class="rounded-circle" alt="<?= $user->first_name ?> <?= $user->last_name ?>">
                                    </a>
                                </li>
                    <?php
                                $displayed++;
                            } else {
                                $remaining = $count - $displayed;
                                echo '<span class="badge badge-center rounded-pill bg-primary mx-1">+' . $remaining . '</span>';
                                break;
                            }
                        }
                        // Add edit option at the end
                        echo '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-outline-primary btn-sm rounded-circle edit-commande update-users-clients" data-id="' . $commande->id . '"><span class="bx bx-edit"></span></a>';
                    } else {
                        echo '<span class="badge bg-primary">' . get_label('not_assigned', 'Not assigned') . '</span>';
                        // Add edit option at the end
                        echo '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-outline-primary btn-sm rounded-circle edit-commande update-users-clients" data-id="' . $commande->id . '"><span class="bx bx-edit"></span></a>';
                    }
                    ?>
                </ul>
                </p>
            </div>
            <div class="col-md-12">
                <p class="card-text mb-1">
                    Clients:
                <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                    <?php
                    $clients = $commande->clients;
                    $count = $clients->count();
                    $displayed = 0;
                    if ($count > 0) {
                        foreach ($clients as $client) {
                            if ($displayed < 10) { ?>
                                <li class="avatar avatar-sm pull-up" title="<?= $client->first_name ?> <?= $client->last_name ?>">
                                    <a href="/clients/profile/<?= $client->id ?>" target="_blank">
                                        <img src="<?= $client->photo ? asset('storage/' . $client->photo) : asset('storage/photos/no-image.jpg') ?>" class="rounded-circle" alt="<?= $client->first_name ?> <?= $client->last_name ?>">
                                    </a>
                                </li>
                    <?php
                                $displayed++;
                            } else {
                                $remaining = $count - $displayed;
                                echo '<span class="badge badge-center rounded-pill bg-primary mx-1">+' . $remaining . '</span>';
                                break;
                            }
                        }
                    } else {
                        // Display "Not assigned" badge
                        echo '<span class="badge bg-primary">' . get_label('not_assigned', 'Not assigned') . '</span>';
                    }
                    ?>
                </ul>
                </p>
            </div>
        </div>
        <div class="d-flex flex-column">
            <div>
                <label for="statusSelect"><?= get_label('status', 'Status') ?></label>
                <select class="form-select form-select-sm select-bg-label-{{$commande->status->color}} mb-3" id="statusSelect" data-id="{{ $commande->id }}" data-original-status-id="{{ $commande->status->id }}" data-original-color-class="select-bg-label-{{$commande->status->color}}" data-type="commande" data-reload="true">
                    @foreach($statuses as $status)
                    @php
                    $disabled = canSetStatus($status) ? '' : 'disabled';
                    @endphp
                    <option value="{{ $status->id }}" class="badge bg-label-{{ $status->color }}" {{ $commande->status->id == $status->id ? 'selected' : '' }} {{ $disabled }}>
                        {{ $status->title }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="prioritySelect"><?= get_label('priority', 'Priority') ?></label>
                <select class="form-select form-select-sm select-bg-label-{{$commande->priority?$commande->priority->color:'secondary'}}" id="prioritySelect" data-id="{{ $commande->id }}" data-original-priority-id="{{ $commande->priority ? $commande->priority->id : '' }}" data-original-color-class="select-bg-label-{{$commande->priority?$commande->priority->color:'secondary'}}" data-type="commande">
                    @foreach($priorities as $priority)
                    <option value="{{ $priority->id }}" class="badge bg-label-{{ $priority->color }}" {{ $commande->priority && $commande->priority->id == $priority->id ? 'selected' : '' }}>
                        {{ $priority->title }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="mt-2">
                <small class="text-muted"><?= get_label('created_at', 'Created At') ?>: {{ format_date($commande->created_at) }}</small>
            </div>
        </div>
    </div>
</div> -->

