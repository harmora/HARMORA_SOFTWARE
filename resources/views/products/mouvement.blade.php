@extends('layout')
@section('title')
    <?= get_label('stock_movement', 'Stock Movement') ?>
@endsection
@php
    $visibleColumns = getUserPreferences('movements');
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
                        <?= get_label('stock_movement', 'Stock Movement') ?>
                    </li>
                </ol>
            </nav>
        </div>

    </div>
    @if (is_countable($movements) && count($movements) > 0)
    <div class="card">
        <div class="card-body">
            <div class="row">

                <div class="col-md-4 mb-3">
                    <a href="/products" class="btn btn-primary">
                        <i class="bx bx-box"></i> <?= get_label('my stock', 'My Stock') ?>
                    </a>

            </div>

                <div class="col-md-4 mb-3">
                    <select class="form-select" id="type_filter" aria-label="Default select example">
                        <option value=""><?= get_label('select_type', 'Select Type') ?></option>

                        <option value="1"><?= get_label('incoming', 'Incoming') ?></option>
                        <option value="2"><?= get_label('outgoing', 'Outgoing') ?></option>

                    </select>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <input type="hidden" id="data_type" value="movements">
                <input type="hidden" id="save_column_visibility">
                <table id="table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/mouvements/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                    <thead>
                        <tr>

                            <th data-field="type" data-visible="{{ (in_array('type', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('type', 'Type') ?></th>
                            <th data-field="reference" data-visible="{{ (in_array('reference', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}"><?= get_label('reference', 'Reference') ?></th>
                            <th data-field="description" data-visible="{{ (in_array('description', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">{{ get_label('description', 'Description') }}</th>
                            <th data-field="quantity" data-visible="{{ (in_array('quantity', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">{{ get_label('quantity', 'Quantity') }}</th>
                            <th data-field="batch_number" data-visible="{{ (in_array('batch_number', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">{{ get_label('batch_number', 'Batch Number') }}</th>
                            <th data-field="departure" data-visible="{{ (in_array('departure', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">{{ get_label('departure', 'Departure') }}</th>
                            <th data-field="arrival" data-visible="{{ (in_array('arrival', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">{{ get_label('arrival', 'Arrival') }}</th>
                            <th data-field="reason" data-visible="{{ (in_array('reason', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">{{ get_label('reason', 'Reason') }}</th>
                            <th data-field="movement_date" data-visible="{{ (in_array('movement_date', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true">{{ get_label('movement_date', 'Movement Date') }}</th>
                            <th data-field="delivery_date" data-visible="{{ (in_array('delivery_date', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true">{{ get_label('delivery_date', 'Delivery Date') }}</th>
                            <th data-field="user" data-visible="{{ (in_array('user', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">{{ get_label('user', 'User') }}</th>
                            <th data-field="actions" data-visible="{{ (in_array('actions', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">{{ get_label('actions', 'Actions') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @else
    <?php
    $type = 'Movements'; ?>
    <x-empty-state-card :type="$type" />
    @endif
</div>
<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
</script>
<script src="{{asset('assets/js/pages/movements.js')}}"></script>
@endsection
