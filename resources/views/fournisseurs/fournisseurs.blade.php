@extends('layout')
@section('title')
<?= get_label('fournisseurs', 'Fournisseurs') ?>
@endsection
@php
$visibleColumns = getUserPreferences('fournisseurs');
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
                        <?= get_label('Suppliers', 'Fournisseurs') ?>
                    </li>
                </ol>
            </nav>
        </div>



        <div>

            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#importFileModal">
                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="{{ get_label('import_file', 'Import File') }}">
                   import Excel <i class="bx bx-file"></i>
                </button>
            </a>

            <a href="{{url('/fournisseurs/create')}}">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_fournisseur', 'Create fournisseur') ?>">
                    <i class='bx bx-plus'></i>
                </button>
            </a>


        </div>
    </div>
    @if (is_countable($fournisseurs) && count($fournisseurs) > 0)
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <select class="form-select" id="fournisseur_status_filter" aria-label="Default select example">
                        <option value=""><?= get_label('select_status', 'Select status') ?></option>
                        <option value="1">{{get_label('active','Active')}}</option>
                        <option value="0">{{get_label('inactive','Inactive')}}</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <input type="hidden" id="data_type" value="fournisseurs">
                <input type="hidden" id="save_column_visibility">
                <table id="table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/fournisseurs/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th data-field="id" data-visible="{{ (in_array('id', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('id', 'ID') ?></th>
                            <th data-field="profile" data-visible="{{ (in_array('profile', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}"><?= get_label('Suppliers', 'Fournisseurs') ?></th>
                            <th data-field="phone" data-visible="{{ (in_array('phone', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('phone_number', 'Phone number') ?></th>
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
    $type = 'Fournisseurs'; ?>
    <x-empty-state-card :type="$type" />
    @endif
</div>



<!-- Modal -->
<div class="modal fade" id="importFileModal" tabindex="-1" aria-labelledby="importFileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importFileModalLabel">{{ get_label('import_file', 'Import File') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('fournisseurs.import') }}" method="POST" enctype="multipart/form-data" class="form-submit-event">
                    @csrf

                    <!-- File input with label -->
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <!-- Hidden file input -->
                        <input type="file" name="import_file" id="import_file" class="form-control" accept=".xlsx, .xls">

                        <!-- File input preview (optional) -->
                        <button type="submit" class="btn btn-danger">
                            <i class='bx bx-upload'></i>
                        </button>
                    </div>

                    <!-- Instructions for allowed file types -->
                    <div class="ms-2">
                        <p class="text-muted mt-2">{{ get_label('allowed_xlsx_xls', 'Allowed XLSX or XLS.') }}</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';

    // JavaScript to trigger file input click on label click
    document.getElementById('select_file_button').addEventListener('click', function() {
        document.getElementById('import_file').click();
    });
</script>
@endsection




