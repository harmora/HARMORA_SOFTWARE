@props(['commande'])

@php
$user = getAuthenticatedUser();
@endphp

<div class="card m-2 shadow" data-commande-id="{{ $commande->id }}">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h6 class="card-title">
                <a href="{{ url('/commandes/information/' . $commande->id) }}" target="_blank">
                    <strong>{{ $commande->title }}</strong>
                </a>
            </h6>
            <div class="d-flex align-items-center">
                @if ($user->can('edit_commandes') || $user->can('delete_commandes'))
                <div class="input-group">
                    <a href="javascript:void(0);" class="mx-2" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class='bx bx-cog'></i>
                    </a>
                    <ul class="dropdown-menu">
                        @if ($user->can('edit_commandes'))
                        <a href="javascript:void(0);" class="edit-commande" data-id="{{ $commande->id }}">
                            <li class="dropdown-item">
                                <i class='menu-icon tf-icons bx bx-edit text-primary'></i> Edit
                            </li>
                        </a>
                        @endif
                        @if ($user->can('delete_commandes'))
                        <a href="javascript:void(0);" class="delete" data-reload="true" data-type="commandes" data-id="{{ $commande->id }}">
                            <li class="dropdown-item">
                                <i class='menu-icon tf-icons bx bx-trash text-danger'></i> Delete
                            </li>
                        </a>
                        @endif
                    </ul>
                </div>
                @endif
                <a href="javascript:void(0);" class="quick-view" data-id="{{ $commande->id }}" data-type="commande">
                    <i class='bx bx-info-circle text-info' data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Quick View"></i>
                </a>
            </div>
        </div>
        <div class="card-subtitle text-muted mb-3">{{ $commande->title }}</div>
        <div class="row mt-2">
            <div class="col-md-12">

            </div>
            <div class="col-md-12">

            </div>
        </div>
        <div class="d-flex flex-column">
            <div>
                <label for="statusSelect">Status</label>
                <select class="form-select form-select-sm select-bg-label-{{ $commande->status_color }} mb-3" id="statusSelect" data-id="{{ $commande->id }}" data-type="commande" data-reload="true">
                    <option value="pending" class="badge bg-label-warning" {{ $commande->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" class="badge bg-label-success" {{ $commande->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" class="badge bg-label-danger" {{ $commande->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <small class="text-muted">Created At: {{ format_date($commande->created_at) }}</small>
            </div>
        </div>
    </div>
</div>
