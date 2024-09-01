@extends('layout')

@section('title', 'Commandes - Draggable')

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

            <a href="{{url('/commandes')}}">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" title="<?= get_label('list', 'list') ?>">
                    <i class="bx bx-menu"></i>
                </button>
            </a>
        </div>
    </div>

    @if ($total_commandes > 0)
    <div class="alert alert-primary alert-dismissible" role="alert">
        Drag and drop to update commande status!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="d-flex card flex-row" style="overflow-x: scroll; overflow-y: hidden;">
        @foreach(['pending', 'completed', 'cancelled'] as $status)
            <div class="my-4 mx-2" style="min-width: 390px; max-width: 390px;">
                <h4 class="fw-bold mx-4 my-2">{{ ucfirst($status) }}</h4>
                <div class="row m-2 d-flex flex-column" id="{{ $status }}" style="height: 100%" data-status="{{ $status }}">
                    @forelse ($commandesByStatus[$status] ?? [] as $commande)
                        <x-kanban :commande="$commande" />
                    @empty


                        <div class="alert alert-secondary" role="alert">
                            <p>No commandes in this status.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

    @else
        <x-empty-state-card type="Commandes" />
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
    var statusArray = @json(['pending', 'completed', 'cancelled']);
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


<script src="{{ asset('assets/js/pages/commande-board.js') }}"></script>
@endsection
