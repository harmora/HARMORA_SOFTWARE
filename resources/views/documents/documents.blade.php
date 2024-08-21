@extends('layout')
@section('title')
    <?= get_label('documents', 'Documents') ?>
@endsection
@php
    $visibleColumns = getUserPreferences('documents');
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
                        <?= get_label('documents', 'Documents') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" id="document_number_filter" placeholder="Facture">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Commande">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Client">
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <input type="hidden" id="data_type" value="documents">
                <input type="hidden" id="save_column_visibility">
                <table id="table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/documents/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="document_number" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-field="document_number" data-visible="{{ (in_array('document_number', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true">{{ get_label('document_number', 'Document Number') }}</th>
                            <th data-field="client" data-visible="{{ (in_array('client', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">{{ get_label('client', 'Client') }}</th>
                            <th data-field="total_price" data-visible="{{ (in_array('total_price', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true">{{ get_label('total_price', 'Total Price') }}</th>
                            <th data-field="remaining_amount" data-visible="{{ (in_array('remaining_amount', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true">{{ get_label('remaining_amount', 'Remaining Amount') }}</th>
                            <th data-field="created_by" data-visible="{{ (in_array('created_by', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">{{ get_label('created_by', 'Created By') }}</th>
                            {{-- <th data-field="creation_date" data-visible="{{ (in_array('creation_date', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true">{{ get_label('creation_date', 'Creation Date') }}</th> --}}
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
</script>
{{-- <script src="{{asset('assets/js/pages/documents.js')}}"></script> --}}
@endsection
