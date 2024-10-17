@props(['item', 'type'])

@php
$user = getAuthenticatedUser();
@endphp

<div class="card m-2 shadow" data-item-id="{{ $item->id }}" data-item-type="{{ $type }}">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h6 class="card-title">
                <a href="{{ url("/{$type}s/information/" . $item->id) }}" target="_blank">
                    <strong>{{ $item->title ?? $item->number ?? 'N/A' }}</strong>
                </a>
            </h6>
            <div class="d-flex align-items-center">



                {{-- <a href="javascript:void(0);" class="quick-view" data-id="{{ $item->id }}" data-type="commande">
                    <i class='bx bx-info-circle text-info' data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Quick View"></i>
                </a> --}}

                <div class="input-group">
                    <a href="javascript:void(0);" class="mx-2" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class='bx bx-cog'></i>
                    </a>
                    <ul class="dropdown-menu">
                        @if($type === 'devis')
                        <a href="{{ route('commandes.editeditdevis', $item->id) }}">
                            <li class="dropdown-item">
                                <i class='menu-icon tf-icons bx bx-edit text-primary'></i> <?= get_label('update', 'Update') ?>
                            </li>
                        </a>
                        @endif
                        @if($type === 'facture')
                            <a href="{{ route('commandes.bonliv', $item->id) }}">
                                <li class="dropdown-item">
                                    <i class='menu-icon tf-icons bx bx-package text-primary'></i> <?= get_label('bonlib', 'Bon livraision') ?>
                                </li>
                            </a>
                        @endif
                        @if($type === 'devis')
                            <a href="javascript:void(0);" class="delete" data-reload="true" data-type="devise" data-id="{{ $item->id }}">
                        @elseif($type === 'facture')
                            <a href="javascript:void(0);" class="delete" data-reload="true" data-type="facture" data-id="{{ $item->id }}">
                        @elseif($type === 'bon_livraison')
                            <a href="javascript:void(0);" class="delete" data-reload="true" data-type="bon_livraison" data-id="{{ $item->id }}">
                        @endif
                                <li class="dropdown-item">
                                    <i class='menu-icon tf-icons bx bx-trash text-danger'></i> <?= get_label('delete', 'Delete') ?>
                                </li>
                            </a>
                    </ul>
                </div>


            </div>
        </div>
        <div class="card-subtitle text-muted mb-3">{{ $item->description ?? 'No description' }}</div>
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="card-subtitle mb-3">
                    <span class="text-danger"><?= get_label('Total Amount :', 'Total Amount :') ?></span>
                    {{ $item->total_amount ?? 'N/A' }}
                </div>
            </div>
        </div>
        <div class="d-flex flex-column">


            <div class="mb-3" style="display: flex; justify-content: center; align-items: center;">
                @if ($type === 'devis')
                <a class="me-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#devisModal-{{ $item->id }}">
                       {{ get_label('devis', 'Devis') }} <i class='bx bx-file'></i>
                    </button>
                </a>
                @elseif ($type === 'facture')
                <a class="me-2">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#factureModal-{{ $item->id }}">
                       {{ get_label('facture', 'Facture') }} <i class='bx bx-dollar'></i>
                    </button>
                </a>
                @elseif ($type === 'bon_livraison')
                <a class="me-2">
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#bonLivraisonModal-{{ $item->id }}">
                       {{ get_label('bon_livraison', 'Bon Livraison') }} <i class='bx bx-truck'></i>
                    </button>
                @endif
{{-- 
                @if ($item->status == "completed")
                <a class="me-2">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#factureModal-{{ $item->id }}">
                       {{ get_label('facture', 'Facture') }} <i class='bx bx-dollar'></i>
                    </button>
                </a>
                @endif --}}
            </div>
            <div>
                <small class="badge bg-label-primary mb-1"> <?= get_label('Created At:', 'Created At:') ?> {{ format_date($item->start_date) }}</small>

                @if ($item->status == "completed")
              <small class="badge bg-label-dark"> <?= get_label('validated At:', 'validated At:') ?>{{ format_date($item->due_date) }}</small>
                @endif

                @if ($item->status == "cancelled")
                <small class="badge bg-label-danger"> <?= get_label('Canceled At:', 'Canceled At:') ?> {{ format_date($item->due_date) }}</small>
                  @endif



            </div>

            {{-- <div style="display: flex; justify-content: center; align-items: center; height: 100%;" class="mt-4">
                <a href="javascript:void(0);" class="mr-4"  data-bs-toggle="modal" data-bs-target="#commandeModal">
                    <button type="button" class="btn btn-info btn-sm"
                        data-id="{{ $item->id }}"
                        data-bs-toggle="tooltip"
                        data-bs-placement="left"
                        title=" <?= get_label('View Details', 'View Details') ?>">
                        <i class="bx bx-expand"></i>
                    </button>
                </a>
            </div> --}}

        </div>
    </div>
</div>


<!-- Devis Modal -->
<div class="modal fade" id="devisModal-{{ $item->id }}" tabindex="-1" aria-labelledby="devisModalLabel-{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <h1 class="modal-title text-primary" id="devisModalLabel-{{ $item->id }}"> <?= get_label('Devis', 'Devis') ?></h1>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img src="{{ $item->user->entreprise->photo ? asset('storage/' . $item->user->entreprise->photo) : asset('storage/photos/no-image.jpg') }}"
                         alt="Entreprise Logo"
                         class="img-fluid rounded-circle"
                         style="max-width: 100px;">
                </div>

                <div class="row">
                    <div class="col-md-12" >
                        <labe ><strong> <?= get_label('Title:', 'Title:') ?></strong></label>
                        <span >{{ $item->title }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label ><strong><?= get_label('Description:', 'Description:') ?></strong></label>
                        <span >{{ $item->description }}</span>
                    </div>
                </div>

                @if ($item->client && $item->client->first_name != null)
                <div class="row mb-3">
                        <div class="col-md-12">
                            <label ><strong><?= get_label('Client:', 'Client:') ?></strong></label>
                            <span >{{ $item->client->first_name .' '.$item->client->last_name }}</span>
                        </div>
                    </div>
                @elseif($item->client && $item->client->denomenation != null)
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label ><strong><?= get_label('Denomination:', 'Denomination:') ?></strong></label>
                            <span >{{ $item->client->denomenation }}</span>
                        </div>
                    </div>
    
                @endif


                <div class="row mb-3">
                    <div class="col-md-12">
                        <h6><?= get_label('Products:', 'Products:') ?></h6>
                        <ul class="list-group">
                            @foreach ($item->products as $product)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $product->name }}</span>
                                    <span>{{ $product->pivot->quantity }} x {{ $product->pivot->price }} MAD</span>
                                </li>
                            @endforeach
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span >---</span>
                                {{-- TVA --}}
                                <span> <span class="text-danger mr-2"><?= get_label('Total HT :', 'Total HT :') ?> </span>    {{ number_format($item->total_amount / (1 + 20 / 100), 2) }}  MAD</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span >--- <b> <?= get_label('TVA :', 'TVA :') ?></b>{{ 20}}%</span>
                                {{-- TVA --}}
                                <span> <span class="text-danger mr-2"><?= get_label('total TTC :', 'total TTC :') ?> </span>{{ $item->total_amount }} MAD</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger" id="generatePdfButton-{{ $item->id }}"> <?= get_label('Devis [PDF]', 'Devis [PDF]') ?></button>
                @if($item->status == 'pending')
                    <a href="{{route('commandes.editeditdevis', $item->id)}}"><button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('add_devis', 'Add Devise') ?>"><?= get_label('proced-to-confirmation', 'Proceed to confirmation   ') ?></button></a>
                @elseif ($item->status == 'validated')
                    <span class="btn btn-success" style="cursor: not-allowed; opacity: 0.65;">
                        {{ __('Validated') }}
                    </span>
                @endif    
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= get_label('Close', 'Close') ?></button>
            </div>
        </div>
    </div>
</div>



{{-- @if ($item->status = 'validated')
 --}}
<!-- Facture Modal -->
<div class="modal fade" id="factureModal-{{ $item->id }}" tabindex="-1" aria-labelledby="factureModalLabel-{{ $item->id }}" aria-hidden="true" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <h1 class="modal-title text-primary" id="factureModalLabel-{{ $item->id }}"><?= get_label('Facture', 'Facture') ?></h1>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img src="{{ $item->user->entreprise->photo ? asset('storage/' . $item->user->entreprise->photo) : asset('storage/photos/no-image.jpg') }}"
                         alt="Client Photo"
                         class="img-fluid rounded-circle"
                         style="max-width: 100px;">
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label><strong><?= get_label('Title:', 'Title:') ?></strong></label>
                        <span>{{ $item->title }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label><strong><?= get_label('Description:', 'Description:') ?></strong></label>
                        <span>{{ $item->description }}</span>
                    </div>
                </div>


                <div class="row mb-3">
                    <div class="col-md-12">
                        @if ($item->client && $item->client->first_name)
                        <label><strong><?= get_label('Client:', 'Client:') ?></strong></label>
                        <span>{{ $item->client->first_name . ' ' . $item->client->last_name }}</span><br>
                        @endif

                        @if ($item->client && $item->client->denomenation)
                        <label><strong><?= get_label('Denomination:', 'Denomination:') ?></strong></label>
                        <span>{{ $item->client->denomenation }}</span><br>
                        @endif
                        @if($item->client)
                        <label><strong><?= get_label('Email:', 'Email:') ?></strong></label>
                        <span>{{ $item->client->email }}</span><br>
                        <label><strong><?= get_label('Phone:', 'Phone:') ?></strong></label>
                        <span>{{ $item->client->phone }}</span><br>
                        @endif
                    </div>
                </div>



                <div class="row mb-3">
                    <div class="col-md-12">
                        <h6><?= get_label('Products:', 'Products:') ?></h6>
                        <ul class="list-group">
                            @foreach ($item->products as $product)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $product->name }}</span>
                                    <span>{{ $product->pivot->quantity }} x {{ $product->pivot->price }} MAD</span>
                                </li>
                            @endforeach
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span >---</span>
                                <span><span class="text-danger mr-2"><?= get_label('Total HT:', 'Total HT:') ?></span>{{ number_format($item->total_amount / (1 + $item->tva / 100), 2) }} MAD</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>--- <b><?= get_label('TVA', 'TVA') ?>:</b> {{ $item->tva }}%</span>
                                <span><span class="text-danger mr-2"><?= get_label('Total TTC:', 'Total TTC:') ?></span>{{ $item->total_amount }} MAD</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger" id="generatefactureButton-{{ $item->id }}"><?= get_label('Creer Facture [PDF]', 'Creer Facture [PDF]') ?></button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= get_label('Close', 'Close') ?></button>
            </div>
        </div>
    </div>
</div>
{{-- @endif --}}
<!-- bon livraision Modal -->
{{-- <div class="modal fade" id="bonLivraisonModal-{{ $item->id }}" tabindex="-1" aria-labelledby="bonLivraisonModalLabel-{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <h1 class="modal-title text-primary" id="bonLivraisonModalLabel-{{ $item->id }}"> <?= get_label('Bon Livraison', 'Bon Livraison') ?></h1>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img src="{{ $item->user->entreprise->photo ? asset('storage/' . $item->user->entreprise->photo) : asset('storage/photos/no-image.jpg') }}"
                         alt="Entreprise Logo"
                         class="img-fluid rounded-circle"
                         style="max-width: 100px;">
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label ><strong><?= get_label('Title:', 'Title:') ?></strong></label>
                        <span >{{ $item->title }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label ><strong><?= get_label('Description:', 'Description:') ?></strong></label>
                        <span >{{ $item->description }}</span>
                    </div>
                </div>

                @if ($item->client && $item->client->first_name != null)
                <div class="row mb-3">
                        <div class="col-md-12">
                            <label ><strong><?= get_label('Client:', 'Client:') ?></strong></label>
                            <span >{{ $item->client->first_name .' '.$item->client->last_name }}</span>
                        </div>
                    </div>
                @elseif($item->client && $item->client->denomenation != null)
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label ><strong><?= get_label('Denomination:', 'Denomination:') ?></strong></label>
                            <span >{{ $item->client->denomenation }}</span>
                        </div>
                    </div>
                @endif
                @if($item->client)
                        <label><strong><?= get_label('Email:', 'Email:') ?></strong></label>
                        <span>{{ $item->client->email }}</span><br>
                        <label><strong><?= get_label('Phone:', 'Phone:') ?></strong></label>
                        <span>{{ $item->client->phone }}</span><br>
                @endif --}}







<script>
document.getElementById('generatePdfButton-{{ $item->id }}').addEventListener('click', function() {
    window.open("{{ route('devis.pdf', $item->id) }}", '_blank');
});

</script>


<script>
    document.getElementById('generatefactureButton-{{ $item->id }}').addEventListener('click', function() {
        window.open("{{ route('facture.pdf', $item->id) }}", '_blank');
    });
    </script>
