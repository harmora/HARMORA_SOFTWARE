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

                <a href="/import">
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
                {{-- <div class="row">
                    <div class="col-md-4 mb-3">
                        <select class="form-select" id="fournisseur_status_filter" aria-label="Default select example">
                            <option value=""><?= get_label('select_status', 'Select status') ?></option>
                            <option value="Paris">{{get_label('paris','Paris')}}</option>
                            <option value="London">{{get_label('london','London')}}</option>
                        </select>
                    </div>
                </div> --}}
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
    
<!-- Commande Details Modal -->
<div class="modal fade mt-5" id="commandeModal" tabindex="-1" role="dialog" aria-labelledby="commandeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <h5 class="modal-title text-info">{{ get_label('view_fournisseur', 'View Fournisseur') }}</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="typeachat" class="form-label">{{ get_label('type', 'Type') }}</label>
                            <input style="background-color: #ffffff !important;" type="text" id="typeachat" class="form-control" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="supplier" class="form-label">{{ get_label('supplier', 'supplier') }}</label>
                            <input style="background-color: #ffffff !important;" type="text" id="supplier" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="ref" class="form-label">{{ get_label('reference', 'ref') }}</label>
                            <input style="background-color: #ffffff !important;" type="text" id="ref" class="form-control" readonly></input>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="amount" class="form-label">{{ get_label('amount', 'amount') }}</label>
                            <input style="background-color: #ffffff !important;" type="text" id="amount" class="form-control" readonly></input>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="amount_ht" class="form-label">{{ get_label('amount_ht', 'amount HT') }}</label>
                            <input style="background-color: #ffffff !important;" type="text" id="amount_ht" class="form-control" readonly></input>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="statuspayement" class="form-label">{{ get_label('Payment_Status', 'Payment Status') }}</label>
                            <input style="background-color: #ffffff !important;" type="text" id="statuspayement" class="form-control" readonly>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ get_label('close', 'Close') }}</button>
            </div>
        </div>
    </div>
</div>

    
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modalElement = document.getElementById('commandeModal');
    var modalBody = modalElement.querySelector('.modal-body');

    // Event delegation for elements that open the modal (buttons or images)
    document.addEventListener('click', function (event) {
        var target = event.target.closest('[data-id]'); // Capture both button and image clicks

        if (target) {
            var id = target.dataset.id; // Get the ID from data-id attribute

            if (id) {
                // Construct the URL for fetching the data
                var url = "{{ url('fournisseurs/getforaffiche') }}/" + id;

                // Fetch the commande details from the server
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        // Populate the modal fields
                        modalBody.querySelector('#typeachat').value = data.id??'--';
                        modalBody.querySelector('#supplier').value = data.name??'--';
                        modalBody.querySelector('#ref').value = data.email??'--';
                        modalBody.querySelector('#amount').value = data.phone??'--';
                        modalBody.querySelector('#statuspayement').value = data.country??'--';
                        modalBody.querySelector('#amount_ht').value = data.montant_ht??'--';
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }
        }
    });
});
</script>

    <script>
        var label_update = '<?= get_label('update', 'Update') ?>';
        var label_delete = '<?= get_label('delete', 'Delete') ?>';
    </script>

    </script>
        <script src="{{asset('assets/js/pages/fournisseurs.js')}}">
    </script>

@endsection




