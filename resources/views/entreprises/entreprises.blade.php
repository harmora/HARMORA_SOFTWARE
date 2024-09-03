@extends('layout')
@section('title')
<?= get_label('entreprises', 'Entreprises') ?>
@endsection
@php
$visibleColumns = getUserPreferences('Entreprises');
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
                        <?= get_label('entreprises', 'Entreprises') ?>
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{url('/entreprises/create')}}"><button type="button" class="btn btn-sm btn-primary " data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_entreprise', 'Create entreprise') ?>"><i class='bx bx-plus'></i></button></a>
        </div>
    </div>
    @if (is_countable($entreprises) && count($entreprises) > 0)
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <select class="form-select" id="forme_juridique_filter" aria-label="Default select example">
                        <option value=""><?= get_label('select_form', 'Select Form juridique') ?></option>
                        <option value="1">{{get_label('sarl','SARL')}}</option>
                        <option value="2">{{get_label('sarl_au','SARL AU')}}</option>
                        <option value="3">{{get_label('sa','SA')}}</option>
                        <option value="4">{{get_label('snc','SNC')}}</option>
                        <option value="5">{{get_label('scs','SCS')}}</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <input type="hidden" id="data_type" value="entreprises">
                <input type="hidden" id="save_column_visibility">
                <table id="table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/entreprises/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                    <thead>
                        <tr>
                            {{-- <th data-checkbox="true"></th> --}}
                            <th data-field="id" data-visible="{{ (in_array('id', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('id', 'ID') ?></th>
                            <th data-field="profile" data-visible="{{ (in_array('profile', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}"><?= get_label('entreprises', 'Entreprises') ?></th>
                            <th data-field="formej" data-visible="{{ (in_array('formej', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" ><?= get_label('forme_juridique', 'Forme juridique') ?></th>
                            <th data-field="city" data-visible="{{ (in_array('city', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('city', 'City') ?></th>
                            <th data-field="country" data-visible="{{ (in_array('assigned', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}"><?= get_label('country', 'Country') ?></th>
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
    $type = 'Clients'; ?>
    <x-empty-state-card :type="$type" />
    @endif
</div>
<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
    var label_projects = '<?= get_label('projects', 'Projects') ?>';
    var label_tasks = '<?= get_label('tasks', 'Tasks') ?>';
</script>
<script src="{{asset('assets/js/pages/entreprises.js')}}">
</script>
@endsection
