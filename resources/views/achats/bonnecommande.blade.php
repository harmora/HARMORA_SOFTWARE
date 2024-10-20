
@extends('layout')
@section('title')
<?= get_label('bon_de_commande', 'Bon de Commande') ?>
@endsection
@php
$visibleColumns = getUserPreferences('BonDeCommande');
@endphp
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{url('/home')}}"><?= get_label('home', 'Home') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= get_label('bon_de_commande', 'Bon de Commande') ?>
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{url('/bondecommande/create')}}"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_bon_de_commande', 'Create Bon de Commande') ?>"><i class='bx bx-plus'></i></button></a>
        </div>
    </div>

    @if (is_countable($bonDeCommandes) && count($bonDeCommandes) > 0)
    <div class="card">
        <div class="card-body">

            <div class="col-md-4 mb-3 text-white">
                <a
                {{-- href="{{ route('achats.bonne') }}" --}}
                 class="btn btn-primary">
                    <i class="bx bx-box"></i> <?= get_label('Validated achat', ' Validated achat') ?>
                </a>
            </div>
             <!-- Add Filters Section -->
    <div class="row mb-4">
        <!-- Fournisseur Filter -->
        <div class="col-md-4 mb-3">
            <select class="form-select" id="fournisseur_filter" aria-label="Default select example">
                <option value=""><?= get_label('select_fournisseur', 'Select Fournisseur') ?></option>
                @foreach($fournisseurs as $fournisseur)
                <option value="{{ $fournisseur->id }}">{{ $fournisseur->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Type Achat Filter -->
        <div class="col-md-4 mb-3">
            <select class="form-select" id="type_achat_filter" aria-label="Default select example">
                <option value=""><?= get_label('select_type_achat', 'Select Type Achat') ?></option>
                @foreach($typesAchat as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <!-- Status Filter -->
        <div class="col-md-4 mb-3">
            <select class="form-select" id="status_filter" aria-label="Default select example">
                <option value=""><?= get_label('select_status', 'Select Status') ?></option>
                <option value="pending"><?= get_label('pending', 'Pending') ?></option>
                <option value="approved"><?= get_label('approved', 'Approved') ?></option>
                <option value="rejected"><?= get_label('rejected', 'Rejected') ?></option>
            </select>
        </div>
    </div>
            <div class="table-responsive text-nowrap">
                <input type="hidden" id="data_type" value="bon_de_commande">
                <table id="table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/boncommande/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-field="manage" data-visible="{{ (in_array('manage', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" ><?= get_label('bon_de_commande', 'bon de commande') ?></th>

                            <th data-field="reference" data-visible="{{ (in_array('reference', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('reference', 'Reference') ?></th>

                            <th data-field="status" data-visible="{{ (in_array('status', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('status', 'Statut') ?></th>
                            <th data-field="fournisseur" data-visible="{{ (in_array('fournisseur_id', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('fournisseur', 'Fournisseur') ?></th>
                            <th data-field="devis" data-visible="{{ (in_array('devis', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('devis', 'Devis') ?></th>

                            <th data-field="type_achat" data-visible="{{ (in_array('type_achat', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">{{ get_label('type_achat', 'Type Achat') }}</th>
                            <th data-field="tva" data-visible="{{ (in_array('tva', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('tva', 'TVA') ?></th>
                            <th data-field="montant" data-visible="{{ (in_array('montant', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('montant tcc', 'Montant TCC') ?></th>
                            <th data-field="products" data-visible="{{ (in_array('products', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('products', 'Products') ?></th>
                            <th data-field="date_commande" data-visible="{{ (in_array('date_commande', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('date_commande', 'Date de Commande') ?></th>
                            <th data-field="created_at" data-visible="{{ (in_array('created_at', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('created_at', 'Created at') ?></th>
                            <th data-field="updated_at" data-visible="{{ (in_array('updated_at', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('updated_at', 'Updated at') ?></th>
                          <!-- New column for products -->
                            <th data-field="actions" data-visible="{{ (in_array('actions', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}"><?= get_label('actions', 'Actions') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @else
    <?php
    $type = 'bon_de_commande'; ?>
    <x-empty-state-card :type="$type" />
    @endif
</div>
<script src="{{asset('assets/js/pages/boncommande.js')}}"></script>
@endsection
