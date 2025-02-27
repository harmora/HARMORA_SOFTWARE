@extends('layout')

@section('title', 'Commandes - Draggable')

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
                        <?= get_label('sales', 'Commandes') ?>
                    </li>
                </ol>
            </nav>
        </div>

        <div>
            {{-- <a href="{{url('/commandes/create')}}">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('add_commande', 'Add Commande') ?>">
                    <i class='bx bx-plus'></i>
                </button>
            </a> --}}
            <a href="{{url('/commandes/createDevise')}}"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('add_devis', 'Add Devise') ?>"><i class='bx bx-plus'></i></button></a>

            <a href="{{url('/commandes')}}">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" title="<?= get_label('list', 'list') ?>">
                    <i class="bx bx-menu"></i>
                </button>
            </a>
        </div>
    </div>

    @if ($total_items > 0)
    {{-- <div class="alert alert-primary alert-dismissible" role="alert">
        <?= get_label('commande_validation_notice', 'Once the Commande is validated, you can get its facture.') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?= get_label('close', 'Close') ?>"></button>
    </div> --}}
    <div class="alert alert-primary alert-dismissible" role="alert">
        <?= get_label('board_view_notice', 'This board displays Devis, Factures, and Bons de Livraison.') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?= get_label('close', 'Close') ?>"></button>
    </div>

    <div class="d-flex card flex-row" style="overflow-x: scroll; overflow-y: hidden;">
        @foreach(['devis' => $devises, 'facture' => $invoices, 'bon_livraison' => $bon_livraisions] as $type => $items)
        <div class="my-4 mx-2 w-100">
            <h4 class="fw-bold mx-4 my-2">
            @if ($type === 'devis')
                <i class="menu-icon tf-icons bx bx-file bx-md text-primary"></i>
                <?= get_label("devis", "Devis") ?>
            @elseif ($type === 'facture')
                <i class="menu-icon tf-icons bx bx-dollar bx-md text-success"></i>
                <?= get_label("facture", "Facture") ?>
            @elseif ($type === 'bon_livraison')
                <i class="menu-icon tf-icons bx bx-package bx-md text-warning"></i>
                <?= get_label("bon_liv", "Bon de Livraison") ?>
            @endif
            </h4>
    
            <div class="row m-2 d-flex flex-column" id="{{ $type }}" style="height: 100%" data-type="{{ $type }}">
                @forelse ($items as $item)
                    <x-kanban :item="$item" :type="$type" />
                @empty
                    <div class="alert alert-secondary" role="alert">
                        <?= get_label('no_items', 'No items in this category.') ?>
                    </div>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>
    @else
        <x-empty-state-card type="board_items" />
    @endif    
</div>

<!-- Commande Details Modal -->
<div class="modal fade" id="commandeModal" tabindex="-1" role="dialog" aria-labelledby="commandeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <h5 class="modal-title text-info"><?= get_label('view_commande', 'View Commande') ?></h5>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= get_label('close', 'Close') ?>"></button>
            </div>
            <div class="modal-body">
                <div id="loading-spinner" class="text-center" style="display: none;">
                    <div class="text-center">
                        <div class="spinner-grow text-warning" role="status">
                            <span class="sr-only"><?= get_label('loading', 'Loading') ?>...</span>
                        </div>
                        <div class="spinner-grow text-success" role="status">
                            <span class="sr-only"><?= get_label('loading', 'Loading') ?>...</span>
                        </div>
                        <div class="spinner-grow text-danger" role="status">
                            <span class="sr-only"><?= get_label('loading', 'Loading') ?>...</span>
                        </div>
                    </div>
                    <small class="badge bg-label-dark"><?= get_label('loading', 'loading') ?>...</small>
                </div>

                <form>
                    <div class="row">
                        <div class="progress-container">
                            <ul class="progressbar">
                                <li class="pending active"><?= get_label('pending', 'Pending') ?></li>
                                <li class="completed"><?= get_label('completed', 'Completed') ?></li>
                                <li class="cancelled"><?= get_label('cancelled', 'Cancelled') ?></li>
                            </ul>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div id="commande-status" style="display: flex; justify-content: center;">
                            <!-- Status content -->
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="id" class="form-label"><?= get_label('id', 'ID') ?></label>
                            <input style="background-color: #ffffff !important;" type="text" id="id" class="form-control" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="title" class="form-label"><?= get_label('title', 'Title') ?></label>
                            <input style="background-color: #ffffff !important;" type="text" id="title" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="description" class="form-label"><?= get_label('description', 'Description') ?></label>
                            <textarea style="background-color: #ffffff !important;" id="description" class="form-control" rows="3" readonly></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="start_date" class="form-label"><?= get_label('start_date', 'Start Date') ?></label>
                            <input style="background-color: #ffffff !important;" type="text" id="starting_date" class="form-control" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="due_date" class="form-label"><?= get_label('due_date', 'Due Date') ?></label>
                            <input style="background-color: #ffffff !important;" type="text" id="dueing_date" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="total_amount" class="form-label text-danger"><?= get_label('total_amount', 'Total Amount') ?></label>
                            <input style="background-color: #ffffff !important;" type="text" id="total_amount" class="form-control" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="status" class="form-label"><?= get_label('commande_status', 'Commande Status') ?></label>
                            <input style="background-color: #ffffff !important;" type="text" id="status" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="client" class="form-label"><?= get_label('client', 'Client') ?></label>
                            <div id="client" class="form-control" style="background-color: #ffffff !important; padding: 10px;" readonly>
                                <!-- Client info -->
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="added_by" class="form-label"><?= get_label('added_by', 'Added By') ?></label>
                            <div id="added_by" class="form-control" style="background-color: #ffffff !important; padding: 10px;" readonly>
                                <!-- Added by info -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="products" class="form-label"><?= get_label('products', 'Products') ?></label>
                            <div id="products" class="row">
                                <!-- Product entries -->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= get_label('close', 'Close') ?></button>
            </div>
        </div>
    </div>
</div>






<script>
    var typeArray = @json(['devis', 'facture', 'bon_livraison']);
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
    '<a class="me-2"><button type="button" id="validate-commande" class="btn btn-success" data-id="' + data.id + '" onclick="updateCommandeStatus(' + data.id + ', \'completed\')">Validate Commande</button></a>' +
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
                                '<p class="card-text"><strong>Price:</strong>' + product.price + 'DH </p>' +
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



<script src="{{ asset('assets/js/pages/commande-board.js') }}"></script>
@endsection
