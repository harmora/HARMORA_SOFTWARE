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


            <div class="mb-3" style="display: flex; justify-content: center; align-items: center;">
                <a class="me-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#devisModal-{{ $commande->id }}">
                       {{ get_label('devis', 'Devis') }} <i class='bx bx-file'></i>
                    </button>
                </a>



                @if ($commande->status == "completed")
                <a >
                    <button type="button" class="btn btn-sm btn-primary" >
                       {{ get_label('facture', 'Facture') }} <i class='bx bx-dollar'></i>
                    </button>
                </a>
                @endif

            </div>
            <div>
                <small class="badge bg-label-primary mb-1">Created At: {{ format_date($commande->start_date) }}</small>

                @if ($commande->status == "completed")
              <small class="badge bg-label-dark">validated At: {{ format_date($commande->due_date) }}</small>
                @endif

                @if ($commande->status == "cancelled")
                <small class="badge bg-label-danger">Canceled At: {{ format_date($commande->due_date) }}</small>
                  @endif



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


<!-- Devis Modal -->
<div class="modal fade" id="devisModal-{{ $commande->id }}" tabindex="-1" aria-labelledby="devisModalLabel-{{ $commande->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <h5 class="modal-title text-info" id="devisModalLabel-{{ $commande->id }}">Devis</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img src="{{ $commande->user->entreprise->photo ? asset('storage/' . $commande->user->entreprise->photo) : asset('storage/photos/no-image.jpg') }}"
                         alt="Entreprise Logo"
                         class="img-fluid rounded-circle"
                         style="max-width: 100px;">
                </div>

                <div class="row mb-3">
                    <div class="col-md-12" >
                        <labe ><strong>Title:</strong></label>
                        <span >{{ $commande->title }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label ><strong>Description:</strong></label>
                        <span >{{ $commande->description }}</span>
                    </div>
                </div>


                <div class="row mb-3">
                    <div class="col-md-12">
                        <h6>Products:</h6>
                        <ul class="list-group">
                            @foreach ($commande->products as $product)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $product->name }}</span>
                                    <span>{{ $product->pivot->quantity }} x {{ $product->pivot->price }} MAD</span>
                                </li>
                            @endforeach
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span >---</span>
                                <span> <span class="text-danger mr-2">Total Amount : </span>{{ $commande->total_amount }} MAD</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger" id="generatePdfButton-{{ $commande->id }}"> Creer Devis [PDF]</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<script>
document.getElementById('generatePdfButton-{{ $commande->id }}').addEventListener('click', function() {
    window.open("{{ route('devis.pdf', $commande->id) }}", '_blank');
});

</script>
