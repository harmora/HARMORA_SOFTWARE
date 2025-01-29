@extends('layout')
@section('title')
<?= get_label('achats', 'Achats') ?>
@endsection
@php
$visibleColumns = getUserPreferences('achats');
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
    @if (is_countable($achats) && count($achats) > 0)
    <div class="card">
        <div class="card-body">
            <div class="row">

                <div class="col-md-4 mb-3 text-white">
                    <a
                    {{-- href="{{ route('achats.bonne') }}" --}}
                     class="btn btn-primary">
                        <i class="bx bx-box"></i> <?= get_label('Purchase_order', ' Purchase order') ?>
                    </a>
                </div>

                <div class="col-md-4 mb-3">
                    <select class="form-select" id="type_achat_filter" aria-label="Default select example">
                        <option value=""><?= get_label('select_status', 'Select status') ?></option>
                        <option value="Matériel/Produits"><?= get_label('Matériel/Produits', 'Materielle/Products') ?></option>
                        <option value="recherche/developpement"><?= get_label('recherche/developpement', 'Research/Development') ?></option>
                        <option value="investissements"><?= get_label('investissements', 'Investments') ?></option>
                        <option value="salaires/avantages sociaux"><?= get_label('salaires/avantages sociaux', 'Salaries/Social Benefits') ?></option>
                        <option value="mainetenances/amélioration"><?= get_label('mainetenances/amélioration', 'Maintenance/Improvement') ?></option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <select class="form-select" id="status_filter" aria-label="Default select example">
                        <option value=""><?= get_label('all', 'All') ?></option>
                        <option value="paid">{{get_label('paid','Paid')}}</option>
                        <option value="unpaid">{{get_label('unpaid','Unpaid')}}</option>
                        <option value="partial">{{get_label('partial','Partial')}}</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <input type="hidden" id="data_type" value="achats">
                <input type="hidden" id="save_column_visibility">
                <table id="table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/achats/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-field="profile" data-visible="{{ (in_array('profile', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}"><?= get_label('achats', 'Achats') ?></th>
                            <th data-field="reference" data-visible="{{ (in_array('reference', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('reference', 'Reference') ?></th>
                            <th data-field="fournisseur" data-visible="{{ (in_array('fournisseur', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('fournisseur', 'Fournisseur') ?></th> <!-- New Fournisseur Column -->
                            <th data-field="status_payement" data-visible="{{ (in_array('status_payement', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('status', 'Statut') ?></th>

                            <th data-field="type_achat" data-visible="{{ (in_array('type_achat', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" ><?= get_label('type', 'Type') ?></th>
                            <th data-field="date_achat" data-visible="{{ (in_array('date_achat', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('date_achat', 'Date d\'Achat') ?></th>
                            <th data-field="montant" data-visible="{{ (in_array('montant', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('montant', 'Montant') ?></th>
                            <th data-field="tva" data-visible="{{ (in_array('tva', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}"><?= get_label('tva', 'tva') ?></th>
                            {{-- <th data-field="date_limit" data-visible="{{ (in_array('date_limit', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('date_limit', 'Date limite de paiement') ?></th> --}}
                            {{-- <th data-field="date_paiement" data-visible="{{ (in_array('date_paiement', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('date_paiement', 'Date de paiement') ?></th> --}}
                            <th data-field="facture" data-visible="{{ (in_array('facture', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('facture', 'Facture') ?></th>
                            <th data-field="devis" data-visible="{{ (in_array('devis', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('devis', 'Devis') ?></th>
                            <th data-field="products" data-visible="{{ (in_array('products', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('products', 'Products') ?></th>
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


<!-- Commande Details Modal -->
<div class="modal fade" id="commandeModal" tabindex="-1" role="dialog" aria-labelledby="commandeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <h5 class="modal-title text-info">{{ get_label('view_achat', 'View Achat') }}</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>

                    {{-- <div class="row">
                        <div>
                            <label for="statusSelect">Update Status</label>
                            <select class="form-select select-bg-label-{{ "warning" }} mb-3" id="statusSelect"  data-type="commande" data-reload="true">
                                <option value="pending" class="badge bg-label-light text-black" {{ 'pending' == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" class="badge bg-label-light text-black" {{0 == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" class="badge bg-label-light text-black" {{ 0 == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                    </div> --}}


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
                    <div class="row" id="partialdisplay">
                        <div class="mb-3 col-md-6">
                            <label for="payant" class="form-label">{{ get_label('montant_payée', 'montant_payée') }}</label>
                            <input style="background-color: #ffffff !important;" type="text" id="payant" class="form-control" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="restant" class="form-label">{{ get_label('montant_restant', 'montant_restant') }}</label>
                            <input style="background-color: #ffffff !important;" type="text" id="restant" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="date_paiement" class="form-label"><?= get_label('date_paiement', 'Payment Date') ?></label>
                            <input style="background-color: #ffffff !important;" class="form-control" type="date" id="date_paiement" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="date_limit" class="form-label"><?= get_label('date_limit', 'Payment Due Date') ?></label>
                            <input style="background-color: #ffffff !important;" class="form-control" type="date" id="date_limit" readonly >
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6"  id="factureaffiche">
                            <label for="facture" class="form-label"><?= get_label('facture', 'Facture') ?></label>
                            <div id="facture" >
                                <!-- Product entries will be populated here -->
                            </div>
                        </div>
                        <div class="mb-3 col-md-6"  id="devisaffiche">
                            <label for="devis" class="form-label"><?= get_label('devis', 'Devis') ?></label>
                            <div id="devis" >
                                <!-- Product entries will be populated here -->
                            </div>
                        </div>
                    </div>
                    <div class="row" id="prodaffiche">
                        <div class="mb-3 col-md-12" >
                            <label for="products" class="form-label">{{ get_label('products', 'Products') }}</label>
                            <div id="products" class="row">
                                <!-- Product entries will be populated here -->
                            </div>
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
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modalElement = document.getElementById('commandeModal');
        var modalBody = modalElement.querySelector('.modal-body');
        // var prodaffiche = modalBody.querySelector('#prodaffiche');

        // Event delegation for buttons that open the modal
        document.addEventListener('click', function (event) {
            if (event.target.closest('button[data-id]')) {
                var button = event.target.closest('button[data-id]');
                var id = button.dataset.id; // Get the ID from data-id attribute

                if (id) {
                    // Construct the URL for fetching the data
                    var url = "{{ url('achats/getforaffiche') }}/" + id;

                    // Fetch the commande details from the server
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            // Populate the modal fields
                            modalBody.querySelector('#typeachat').value = data.type_achat;
                            modalBody.querySelector('#supplier').value = data.fournisseur;
                            modalBody.querySelector('#ref').value = data.reference;
                            modalBody.querySelector('#amount').value = data.montant;
                            modalBody.querySelector('#statuspayement').value = data.status_payement;
                            modalBody.querySelector('#date_paiement').value = data.date_paiement;
                            modalBody.querySelector('#date_limit').value = data.date_limit;
                            modalBody.querySelector('#amount_ht').value = data.montant_ht;
                            // modalBody.querySelector('#tva').value = data.tva;

                            if(data.status_payement=='partial'){
                                modalBody.querySelector('#restant').value = data.montant_restant;
                                modalBody.querySelector('#payant').value = data.montant_payée;
                                $('#partialdisplay').show();
                            }
                            else{
                                $('#partialdisplay').hide();
                            }

                            if(data.facture){
                                modalBody.querySelector('#facture').value = data.facture;
                                var factureHtml = '';
                                var fileExtension = data.facture.split('.').pop().toLowerCase(); // Get the file extension
                                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
                                    factureHtml += `<div class="card">
                                            <img src="${data.facture}" class="card-img-top" alt="facture" height="130" width="130">
                                        </div>`;
                                } else if (fileExtension === 'pdf') {
                                    factureHtml += `<div class="card">
                                            <embed src="${data.facture}" type="application/pdf" height="auto" width="100%" style="overflow:auto;" />
                                        </div>`;
                                } else {
                                    factureHtml += `<div class="col-md-3 mb-3">
                                        <div class="card">
                                            <p class="text-muted mt-2">File not supported.</p>
                                        </div>
                                    </div>`;
                                }
                                modalBody.querySelector('#facture').innerHTML = factureHtml;
                                $('#factureaffiche').show();
                            }
                            else{
                                $('#factureaffiche').hide();
                            }



                            if(data.devis){
                                modalBody.querySelector('#devis').value = data.devis;
                                var devisHtml = '';
                                var fileExtension2 = data.facture.split('.').pop().toLowerCase(); // Get the file extension
                                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension2)) {
                                    devisHtml += `<div class="card">
                                            <img src="${data.devis}" class="card-img-top" alt="facture" height="130" width="130">
                                        </div>`;
                                } else if (fileExtension2 === 'pdf') {
                                    devisHtml += `<div class="card">
                                            <embed src="${data.devis}" type="application/pdf" height="auto" width="100%" style="overflow:auto;" />
                                        </div>`;
                                } else {
                                    devisHtml += `<div class="col-md-3 mb-3">
                                        <div class="card">
                                            <p class="text-muted mt-2">File not supported.</p>
                                        </div>
                                    </div>`;
                                }
                                modalBody.querySelector('#devis').innerHTML = devisHtml;
                                $('#devisaffiche').show();
                            }
                            else{
                                $('#devisaffiche').hide();
                            }

                            // Populate products info
                            if (data.products && data.products.length > 0) {

                            var productsHtml = '';
                            data.products.forEach(product => {
                                productsHtml += `<div class="col-md-3 mb-3">
                                    <div class="card">
                                        <img src="${product.picture_url}" class="card-img-top" alt="${product.name}">
                                        <div class="card-body">
                                            <h5 class="card-title">${product.name}</h5>
                                            <p class="card-text">${product.description}</p>
                                            <p class="card-text"><strong>Price:</strong> $${product.price}</p>
                                        </div>
                                    </div>
                                </div>`;
                            });
                            modalBody.querySelector('#products').innerHTML = productsHtml;
                            $('#prodaffiche').show();
                            }
                            else {
                                $('#prodaffiche').hide();
                            }

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
    var label_projects = '<?= get_label('projects', 'Projects') ?>';
    var label_tasks = '<?= get_label('tasks', 'Tasks') ?>';
</script>
    <script src="{{asset('assets/js/pages/achats.js')}}"></script>
@endsection
