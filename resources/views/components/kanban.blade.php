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



                {{-- <a href="javascript:void(0);" class="quick-view" data-id="{{ $commande->id }}" data-type="commande">
                    <i class='bx bx-info-circle text-info' data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Quick View"></i>
                </a> --}}

                <div class="input-group">
                    <a href="javascript:void(0);" class="mx-2" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class='bx bx-cog'></i>
                    </a>
                    <ul class="dropdown-menu">

                        <a href="{{ route('commandes.edit', $commande->id) }}" class="edit-commande">
                            <li class="dropdown-item">
                                <i class='menu-icon tf-icons bx bx-edit text-primary'></i> <?= get_label('update', 'Update') ?>
                            </li>
                        </a>


                        <a href="javascript:void(0);" class="delete" data-reload="true" data-type="commandes" data-id="{{ $commande->id }}">
                            <li class="dropdown-item">
                                <i class='menu-icon tf-icons bx bx-trash text-danger'></i> <?= get_label('delete', 'Delete') ?>
                            </li>
                        </a>
                    </ul>
                </div>


            </div>
        </div>
        <div class="card-subtitle text-muted mb-3">{{ $commande->description }}</div>
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="card-subtitle  mb-3"><span class="text-danger">Total Amount : </span>  {{ $commande->total_amount }}</div>
            </div>
            <div class="col-md-12">

            </div>
        </div>
        <div class="d-flex flex-column">
            <div class="mb-2">
                <a class="me-2">
                    <button type="button" class="btn btn-sm btn-secondary">
                         {{ get_label('view devis', 'View Devis') }} <i class='bx bx-file'></i>
                    </button>
                </a>

                <a >
                    <button type="button" class="btn btn-sm btn-primary" >
                       {{ get_label('view facture', 'View Facture') }} <i class='bx bx-dollar'></i>
                    </button>
                </a>
            </div>
            <div>
                <small class="badge bg-label-primary">Created At: {{ format_date($commande->created_at) }}</small>


            </div>

            <div style="display: flex; justify-content: center; align-items: center; height: 100%;" class="mt-4">
                <a href="javascript:void(0);" class="mr-4"  data-bs-toggle="modal" data-bs-target="#commandeModal">
                    <button type="button" class="btn btn-info btn-sm"
                        data-id="{{ $commande->id }}"
                        data-bs-toggle="tooltip"
                        data-bs-placement="left"
                        title="{{ __('View Details') }}">
                        <i class="bx bx-expand"></i>
                    </button>
                </a>
            </div>

        </div>
    </div>
</div>
