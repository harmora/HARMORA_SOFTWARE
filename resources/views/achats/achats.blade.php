@extends('layout')
@section('title')
<?= get_label('achats', 'Achats') ?>
@endsection
@php
$visibleColumns = getUserPreferences('Achats');
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
                        <?= get_label('achats', 'Achats') ?>
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{url('/achats/create')}}"><button type="button" class="btn btn-sm btn-primary " data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_achat', 'Create achat') ?>"><i class='bx bx-plus'></i></button></a>
        </div>
    </div>
    @if (is_countable($entreprises) && count($entreprises) > 0)
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <select class="form-select" id="type_achat_filter" aria-label="Default select example">
                        <option value=""><?= get_label('select_status', 'Select status') ?></option>
                        <option value="1">{{get_label('active','Active')}}</option>
                        <option value="2">{{get_label('deactive','Deactive')}}</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <input type="hidden" id="data_type" value="entreprises">
                <input type="hidden" id="save_column_visibility">
                <table id="table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/achats/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                    <thead>
                        <tr>
                            {{-- <th data-checkbox="true"></th> --}}
                            <th data-field="id" data-visible="{{ (in_array('id', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('id', 'ID') ?></th>
                            <th data-field="profile" data-visible="{{ (in_array('profile', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}"><?= get_label('achats', 'Achats') ?></th>
                            <th data-field="type_achat" data-visible="{{ (in_array('type_achat', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" ><?= get_label('type', 'Type') ?></th>
                            <th data-field="montant" data-visible="{{ (in_array('montant', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('montant', 'Montant') ?></th>
                            <th data-field="status_payement" data-visible="{{ (in_array('status_payement', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('status', 'Statut') ?></th>
                            <th data-field="tva" data-visible="{{ (in_array('tva', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}"><?= get_label('tva', 'tva') ?></th>
                            <th data-field="facture" data-visible="{{ (in_array('created_at', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('facture_bon', 'Facture/Bon') ?></th>
                            <th data-field="date_paiement" data-visible="{{ (in_array('date_paiement', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('date_paiement', 'Date de paiement') ?></th>
                            <th data-field="date_limit" data-visible="{{ (in_array('date_limit', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('date_limit', 'Date limite de paiement') ?></th>
                            <th data-field="reference" data-visible="{{ (in_array('reference', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('reference', 'N°Cheque/Reference') ?></th>
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
    $type = 'achats'; ?>
    <x-empty-state-card :type="$type" />
    @endif
</div>
<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
    var label_projects = '<?= get_label('projects', 'Projects') ?>';
    var label_tasks = '<?= get_label('tasks', 'Tasks') ?>';
</script>
{{-- <script src="{{asset('assets/js/pages/entreprises.js')}}"> --}}
</script>
@endsection
