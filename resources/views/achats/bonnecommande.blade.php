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
            <div class="table-responsive text-nowrap">
                <input type="hidden" id="data_type" value="bon_de_commande">
                <table id="table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/bon_de_commande/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-field="id" data-visible="{{ (in_array('id', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('id', 'ID') ?></th>
                            <th data-field="fournisseur_id" data-visible="{{ (in_array('fournisseur_id', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('fournisseur', 'Fournisseur') ?></th>
                            <th data-field="entreprise_id" data-visible="{{ (in_array('entreprise_id', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('entreprise', 'Entreprise') ?></th>
                            <th data-field="type_achat" data-visible="{{ (in_array('type_achat', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">{{ get_label('type_achat', 'Type Achat') }}</th>
                            <th data-field="montant" data-visible="{{ (in_array('montant', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('montant', 'Montant') ?></th>
                            <th data-field="tva" data-visible="{{ (in_array('tva', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">{{ get_label('tva', 'TVA') }}</th>
                            <th data-field="facture" data-visible="{{ (in_array('facture', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('facture', 'Facture') ?></th>
                            <th data-field="date_paiement" data-visible="{{ (in_array('date_paiement', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('date_paiement', 'Date de Paiement') ?></th>
                            <th data-field="date_limit" data-visible="{{ (in_array('date_limit', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('date_limit', 'Date Limite') ?></th>
                            <th data-field="reference" data-visible="{{ (in_array('reference', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('reference', 'Reference') ?></th>
                            <th data-field="status" data-visible="{{ (in_array('status', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('status', 'Statut') ?></th>
                            <th data-field="created_at" data-visible="{{ (in_array('created_at', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('created_at', 'Created at') ?></th>
                            <th data-field="updated_at" data-visible="{{ (in_array('updated_at', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('updated_at', 'Updated at') ?></th>
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
@endsection
