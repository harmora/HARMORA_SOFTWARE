@extends('layout')
@section('title')
<?= get_label('commandes', 'Commandes') ?>
@endsection

@php
$visibleColumns = getUserPreferences('commandes');
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
                        <?= get_label('commandes', 'Commandes') ?>
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{url('/commandes/create')}}"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('add_commande', 'Add Commande') ?>"><i class='bx bx-plus'></i></button></a>

            <a href="{{url('/commandes/draggable')}}">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" title="<?= get_label('draggable', 'Draggable') ?>">
                    <i class="bx bxs-dashboard"></i>
                </button>
            </a>
        </div>
    </div>

    <div class="row my-3">


        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class="menu-icon tf-icons bx bx-check-circle bx-md text-success"></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1"><?= get_label('completed_commande', 'Completed Commandes') ?></span>
                    <h3 class="card-title mb-2" id="completed-count">0</h3>
                    <a href="#" class="text-success fw-semibold">
                        <small><i class="bx bx-right-arrow-alt"></i> <?= get_label('view_more', 'View more') ?></small>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class="menu-icon tf-icons bx bx-hourglass bx-md text-warning"></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1"><?= get_label('pending_commande', 'Pending Commandes') ?></span>
                    <h3 class="card-title mb-2" id="pending-count">0</h3>
                    <a href="#" class="text-warning fw-semibold">
                        <small><i class="bx bx-right-arrow-alt"></i> <?= get_label('view_more', 'View more') ?></small>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <i class="menu-icon tf-icons bx bx-x-circle bx-md text-danger"></i>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1"><?= get_label('canceled_commande', 'Canceled Commandes') ?></span>
                    <h3 class="card-title mb-2" id="canceled-count">0</h3>
                    <a href="#" class="text-danger fw-semibold">
                        <small><i class="bx bx-right-arrow-alt"></i> <?= get_label('view_more', 'View more') ?></small>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @if (is_countable($commandes) && count($commandes) > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <input type="hidden" id="data_type" value="commandes">
                <input type="hidden" id="save_column_visibility">
                <table id="table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/commandes/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-field="id" data-visible="{{ (in_array('id', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('id', 'ID') ?></th>
                            <th data-field="title" data-visible="{{ (in_array('title', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" ><?= get_label('title', 'Title') ?></th>
                            <th data-field="client" data-visible="{{ (in_array('client', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">{{ get_label('client', 'Client') }}</th>
                            {{-- <th data-field="description" data-visible="{{ (in_array('description', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" >></th> --}}
                            <th data-field="products" data-visible="{{ (in_array('products', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}"><?= get_label('products', 'Products') ?></th>
                            <th data-field="tva" data-visible="{{ (in_array('tva', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">
                                <?= get_label('tva', 'TVA') ?>
                            </th>
                            <th data-field="status" data-visible="{{ (in_array('status', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('status', 'Status') ?></th>
                            <th data-field="total_amount" data-visible="{{ (in_array('total_amount', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true" ><?= get_label('total_amount', 'Total Amount') ?></th>
                            <th data-field="start_date" data-visible="{{ (in_array('start_date', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('start_date', 'Start Date') ?></th>
                            <th data-field="added_by" data-visible="{{ (in_array('added_by', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('added_by', 'Added By') ?></th>
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
    $type = 'Commandes'; ?>
    <x-empty-state-card :type="$type" />
    @endif
</div>



<!-- Commande Details Modal -->
<div class="modal fade" id="commandeModal" tabindex="-1" role="dialog" aria-labelledby="commandeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <h5 class="modal-title text-info">{{ get_label('view_commande', 'View Commande') }}</h5>
                    <div>
                        <a class="me-2">
                            <button type="button" class="btn btn-sm btn-secondary">
                                 {{ get_label('create devis', 'Create Devis') }} <i class='bx bx-file'></i>
                            </button>
                        </a>

                        <a >
                            <button type="button" class="btn btn-sm btn-primary" >
                               {{ get_label('create facture', 'Create Facture') }} <i class='bx bx-dollar'></i>
                            </button>
                        </a>
                    </div>
                </div>


                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>

                    <div class="row">
                        <div>
                            <label for="statusSelect">Update Status</label>
                            <select class="form-select select-bg-label-{{ "warning" }} mb-3" id="statusSelect"  data-type="commande" data-reload="true">
                                <option value="pending" class="badge bg-label-light text-black" {{ 'pending' == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" class="badge bg-label-light text-black" {{0 == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" class="badge bg-label-light text-black" {{ 0 == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="id" class="form-label">{{ get_label('id', 'ID') }}</label>
                            <input style="background-color: #ffffff !important;" type="text" id="id" class="form-control" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="title" class="form-label">{{ get_label('title', 'Title') }}</label>
                            <input style="background-color: #ffffff !important;" type="text" id="title" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="description" class="form-label">{{ get_label('description', 'Description') }}</label>
                            <textarea style="background-color: #ffffff !important;" id="description" class="form-control" rows="3" readonly></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="start_date" class="form-label">{{ get_label('start_date', 'Start Date') }}</label>
                            <input style="background-color: #ffffff !important;" type="date" id="start_date" class="form-control" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="due_date" class="form-label">{{ get_label('due_date', 'Due Date') }}</label>
                            <input style="background-color: #ffffff !important;" type="date" id="due_date" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="total_amount" class="form-label text-danger">{{ get_label('total_amount', 'Total Amount') }}</label>
                            <input style="background-color: #ffffff !important;" type="text" id="total_amount" class="form-control" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="status" class="form-label">{{ get_label('commande status', 'Commande Status') }}</label>
                            <input style="background-color: #ffffff !important;" type="text" id="status" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="client" class="form-label">{{ get_label('client', 'Client') }}</label>
                            <div id="client" class="form-control" style="background-color: #ffffff !important; padding: 10px;" readonly>
                                <!-- Client info will be populated here -->
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="added_by" class="form-label">{{ get_label('added_by', 'Added By') }}</label>
                            <div id="added_by" class="form-control" style="background-color: #ffffff !important; padding: 10px;" readonly>
                                <!-- Added by info will be populated here -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-12">
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

        // Event delegation for buttons that open the modal
        document.addEventListener('click', function (event) {
            if (event.target.closest('button[data-id]')) {
                var button = event.target.closest('button[data-id]');
                var id = button.dataset.id; // Get the ID from data-id attribute

                if (id) {
                    // Construct the URL for fetching the data
                    var url = "{{ url('commandes/getforaffiche') }}/" + id;

                    // Fetch the commande details from the server
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            // Populate the modal fields
                            modalBody.querySelector('#id').value = data.id;
                            modalBody.querySelector('#title').value = data.title;
                            modalBody.querySelector('#description').value = data.description;
                            modalBody.querySelector('#start_date').value = data.start_date;
                            modalBody.querySelector('#due_date').value = data.due_date;
                            modalBody.querySelector('#total_amount').value = data.total_amount;
                            modalBody.querySelector('#status').value = data.status;

                            // Populate client info
                            var clientInfo = `<div class="avatar avatar-md pull-up" title="${data.client.name}">
                                <img src="${data.client.picture_url}" alt="Avatar" class="rounded-circle">
                            </div>
                            <p>${data.client.name}</p>
                            <p>${data.client.denomenation}</p>`;
                            modalBody.querySelector('#client').innerHTML = clientInfo;

                            // Populate added by info
                            var addedByInfo = `<div class="avatar avatar-md pull-up" title="${data.added_by.name}">
                                <img src="${data.added_by.picture_url}" alt="Avatar" class="rounded-circle">
                            </div>
                            <p>${data.added_by.name}</p>`;
                            modalBody.querySelector('#added_by').innerHTML = addedByInfo;

                            // Populate products info
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
                        })
                        .catch(error => console.error('Error fetching data:', error));
                }
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    function updateCounters() {
        fetch('/commandes/counter')
            .then(response => response.json())
            .then(data => {
                document.getElementById('pending-count').innerText = data.pending;
                document.getElementById('completed-count').innerText = data.completed;
                document.getElementById('canceled-count').innerText = data.canceled;
            })
            .catch(error => console.error('Error fetching counter data:', error));
    }

    // Initial fetch to update counters
    updateCounters();

    // Add event listener to refresh counters on table refresh
    const tableElement = document.getElementById('table');
    tableElement.addEventListener('post-body.bs.table', function () {
        updateCounters();
    });
});

</script>


<script src="{{asset('assets/js/pages/commandes.js')}}"></script>
@endsection
