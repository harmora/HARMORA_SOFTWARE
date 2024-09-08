@extends('layout')
@section('title')
<?= get_label('commandes', 'Commandes') ?>
@endsection

@php
$visibleColumns = getUserPreferences('commandes');
@endphp

@section('content')

<style>
    .progress-container {
        width: 100%;
        margin: 20px 0;
    }

    .progressbar {
        counter-reset: step;
        display: flex;
        justify-content: space-between;
        list-style: none;
        padding: 0;
        margin: 0;
        position: relative;
    }

    .progressbar li {
        text-align: center;
        position: relative;
        width: 33.33%;
        color: gray;
        text-transform: capitalize;
        z-index: 1;
    }

    .progressbar li::before {
        content: ''; /* Removed the counter content */
        width: 25px;
        height: 25px;
        border: 2px solid gray;
        display: block;
        text-align: center;
        margin: 0 auto 10px auto;
        border-radius: 50%;
        background-color: white;
        line-height: 25px;
        transition: all 0.3s ease; /* Smooth transition */
    }

    .progressbar li.active::before {
        width: 40px; /* Increase size for active step */
        height: 40px;
        line-height: 40px;
    }

    .progressbar li::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 2px;
        background-color: gray;
        top: 15px;
        left: 50%;
        z-index: -1;
        transform: translateX(-50%);
    }

    .progressbar li:first-child::after {
        width: 100%;
        left: 50%;
        transform: translateX(0);
    }

    .progressbar li:last-child::after {
        width: 50%;
        left: 0;
        transform: none;
    }

    /* Icons inside active steps */
    .progressbar li.active.pending::before {
        background-color: orange;
        border-color: orange;
        color: white;
        font-family: "FontAwesome"; /* Specify the font family for icons */
        content: "\f254"; /* Unicode for the pending (clock) icon */
        font-size: 18px; /* Adjust the font size for the icon */
    }

    .progressbar li.active.completed::before {
        background-color: green;
        border-color: green;
        color: white;
        font-family: "FontAwesome";
        content: "\f00c"; /* Unicode for the completed (check) icon */
        font-size: 18px;
    }

    .progressbar li.active.cancelled::before {
        background-color: red;
        border-color: red;
        color: white;
        font-family: "FontAwesome";
        content: "\f00d"; /* Unicode for the cancelled (cross) icon */
        font-size: 18px;
    }

    .progressbar li.active {
        color: black; /* Color for active status text */
        font-size: 20px;
        font-weight: bold;
    }


    </style>

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
                            <th data-field="id" data-visible="{{ (in_array('id', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}"></th>
                            <th data-field="title" data-visible="{{ (in_array('title', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" ><?= get_label('title', 'Title') ?></th>
                            <th data-field="client" data-visible="{{ (in_array('client', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">{{ get_label('client', 'Client') }}</th>
                            {{-- <th data-field="description" data-visible="{{ (in_array('description', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" >></th> --}}
                            <th data-field="products" data-visible="{{ (in_array('products', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}"><?= get_label('products', 'Products') ?></th>
                            <th data-field="tva" data-visible="{{ (in_array('tva', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">
                                <?= get_label('tva', 'TVA') ?>
                            </th>
                            <th data-field="status" data-visible="{{ (in_array('status', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('status', 'Status') ?></th>
                            <th data-field="total_amount" data-visible="{{ (in_array('total_amount', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true" ><?= get_label('total_amount', 'Total Amount') ?></th>

                            <th data-field="documents" data-visible="{{ (in_array('documents', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="false">
                                <?= get_label('estimates / invoices', 'Estimates / Invoices') ?>
                            </th>

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

                </div>


                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="loading-spinner" class="text-center" style="display: none;">
                    <div class="text-center">

                        <div class="spinner-grow text-warning" role="status">
                            <span class="sr-only"><?= get_label('loading ...', 'loading ...') ?></span>
                          </div>
                          <div class="spinner-grow text-success" role="status">
                            <span class="sr-only"><?= get_label('loading ...', 'loading ...') ?></span>
                          </div>
                          <div class="spinner-grow text-danger" role="status">
                            <span class="sr-only"><?= get_label('loading ...', 'loading ...') ?></span>
                          </div>

                    </div>

                    <small class="badge bg-label-dark"><?= get_label('loading ...', 'loading ...') ?></small>

                </div>

                <form>



                    <div class="row">


                        <div class="progress-container">
                            <ul class="progressbar">
                                <li class="pending active"><?= get_label('Pending', 'Pending') ?></li>
                                <li class="completed"><?= get_label('Completed', 'Completed') ?></li>
                                <li class="cancelled"><?= get_label('Cancelled', 'Cancelled') ?></li>
                            </ul>
                        </div>




                    </div>


                    <div class="row mb-3">
                        <div id="commande-status" style="display: flex; justify-content: center;">
                            <!-- Your content here -->
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
                            <input style="background-color: #ffffff !important;" type="text" id="starting_date" class="form-control" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="due_date" class="form-label">{{ get_label('due_date', 'Due Date') }}</label>
                            <input style="background-color: #ffffff !important;" type="text" id="dueing_date" class="form-control" readonly>
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
    var statusArray = @json(['pending', 'completed', 'cancelled']);
</script>


<script>




document.addEventListener('DOMContentLoaded', function () {
    var modalElement = document.getElementById('commandeModal');
    var modalBody = modalElement.querySelector('.modal-body');
    var spinner = modalBody.querySelector('#loading-spinner');
    var formContent = modalBody.querySelector('form');
    var statusContainer = modalBody.querySelector('#commande-status');




    // Event delegation for buttons that open the modal
    document.addEventListener('click', function (event) {
        if (event.target.closest('button[data-id]')) {
            var button = event.target.closest('button[data-id]');
            var id = button.dataset.id; // Get the ID from data-id attribute

            if (id) {
                // Show the spinner and hide the form content
                spinner.style.display = 'block';
                formContent.style.display = 'none';


                // Construct the URL for fetching the data
                var url = "{{ url('commandes/getforaffiche') }}/" + id;

                // Fetch the commande details from the server
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        // Hide the spinner and show the form content
                        spinner.style.display = 'none';
                        formContent.style.display = 'block';

                        // Populate the modal fields
                        modalBody.querySelector('#id').value = data.id;
                        modalBody.querySelector('#title').value = data.title;
                        modalBody.querySelector('#description').value = data.description;
                        modalBody.querySelector('#starting_date').value = data.start_date;
                        modalBody.querySelector('#dueing_date').value = data.due_date;
                        modalBody.querySelector('#total_amount').value = data.total_amount;
                        modalBody.querySelector('#status').value = data.status;

                        let statusContainer = document.getElementById('commande-status');

                        if (data.status === 'pending') {
                            statusContainer.innerHTML =
    '<a class="me-2"><button type="button" id="validate-commande" class="btn btn-success" data-id="' + data.id + '" onclick="updateCommandeStatus(' + data.id + ', \'completed\')"><?= get_label('Validate Commande', 'Validate Commande') ?></button></a>' +
    '<a><button type="button" id="cancel-commande" class="btn btn-danger" data-id="' + data.id + '" onclick="updateCommandeStatus(' + data.id + ', \'cancelled\')"><?= get_label('Cancel Commande', 'Cancel Commande') ?></button></a>';

            } else if (data.status === 'cancelled') {
                            statusContainer.innerHTML =
                                '<div class="badge bg-label-danger"><?= get_label('This commande was canceled', 'This commande was canceled') ?></div>';
                        } else if (data.status === 'completed') {
                            statusContainer.innerHTML =
                                '<div class="badge bg-label-success"><?= get_label('This commande was completed', 'This commande was completed') ?></div>';
                        }

                        const steps = document.querySelectorAll('.progressbar li');

                        steps.forEach(step => {
                            step.classList.remove('active', 'completed', 'cancelled');
                        });

                        if (data.status === 'pending') {
                            steps[0].classList.add('active');
                            steps[0].classList.add('pending');
                        } else if (data.status === 'completed') {
                            steps[1].classList.add('active');
                            steps[1].classList.add('completed');
                        } else if (data.status === 'cancelled') {
                            steps[2].classList.add('active');
                            steps[2].classList.add('cancelled');
                        }

                        var clientInfo = '<div class="avatar avatar-md pull-up" title="' + data.client.name + '">' +
                            '<img src="' + data.client.picture_url + '" alt="Avatar" class="rounded-circle"></div>' ;

                            if(data.client.denomenation)
                        {
                            clientInfo +=  '<p style="margin-bottom: 0 !IMPORTANT;">' + data.client.name + '</p>' + '<p class="badge bg-label-dark"> ' + data.client.denomenation + '</p>';
                        }
                        else
                        {
                            clientInfo +=  '<p>' + data.client.name + '</p>';
                        }


                        modalBody.querySelector('#client').innerHTML = clientInfo;

                        var addedByInfo = '<div class="avatar avatar-md pull-up" title="' + data.added_by.name + '">' +
                            '<img src="' + data.added_by.picture_url + '" alt="Avatar" class="rounded-circle"></div>' +
                            '<p>' + data.added_by.name + '</p>';
                        modalBody.querySelector('#added_by').innerHTML = addedByInfo;

                        var productsHtml = '';
                        data.products.forEach(product => {
                            productsHtml += '<div class="col-md-3 mb-3">' +
                                '<div class="card">' +
                                '<img src="' + product.picture_url + '" class="card-img-top" alt="' + product.name + '">' +
                                '<div class="card-body">' +
                                '<h5 class="card-title">' + product.name + '</h5>' +
                                '<p class="card-text">' + product.description + '</p>' +
                                '<p class="card-text"><strong><?= get_label('price', 'Price')?> : </strong>' + product.price + 'DH </p>' +
                                '</div></div></div>';
                        });
                        modalBody.querySelector('#products').innerHTML = productsHtml;

                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        // Optionally hide spinner and show an error message
                        spinner.style.display = 'none';
                        formContent.style.display = 'block';
                    });
            }
        }
    });
});



function updateCommandeStatus(id, status) {
    var url = "{{ url('commandes/updatestatus') }}/" + id; // Assuming you have a route for updating status

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Handle success: update the UI, show a message, etc.
            // Optionally reload the page or close the modal
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error updating status:', error);
    });
}

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

<script>
$(document).on('click', '[id^=generatePdfButton-]', function() {
    var url = $(this).data('url'); // Get the URL from the data-url attribute
    window.open(url, '_blank'); // Open the URL in a new tab
});

$(document).on('click', '[id^=generatefactureButton-]', function() {
    var url = $(this).data('url'); // Get the URL from the data-url attribute
    window.open(url, '_blank'); // Open the URL in a new tab
});

</script>


<script src="{{asset('assets/js/pages/commandes.js')}}"></script>
@endsection


