@extends('layout')
@section('title')
<?= get_label('factures', 'Factures') ?>
@endsection
@php
$visibleColumns = getUserPreferences('Factures');
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
                        <?= get_label('factures', 'Factures') ?>
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{url('/commandes/create')}}">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_facture', 'Create facture') ?>">
                    <i class='bx bx-plus'></i>
                </button>
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5><?= get_label('factures_list', 'Factures List') ?></h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><?= get_label('invoice_number', 'Invoice Number') ?></th>
                        <th><?= get_label('commande_name', 'Commande Name') ?></th>
                        <th><?= get_label('actions', 'Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($factures as $facture)
                        <tr>
                            <td>{{ $facture->invoice_number }}</td>
                            <td>{{ $facture->commande_name }}</td>
                            <td>
                                <a href="{{ url('/factures/' . $facture->id) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= get_label('view', 'View') ?>">
                                <i class='bx bx-info-circle text-info' data-bs-toggle="tooltip" data-bs-placement="right"></i>
                                </a>
                                <!-- <a href="{{ route('commandes.edit', $facture->id) }}" > -->
                                <!-- <a href="{{ url('/commandes' . $facture->commande_id . '/edit') }}">
                                    <i class='menu-icon tf-icons bx bx-edit text-primary'></i> <?= get_label('update', 'Update') ?>
                                </a> -->
                                <a href="{{ route('commandes.edit', $facture->commande_id) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" data-bs-placement="top">
    <i class='bx bx-edit text-primary'></i> <?= get_label('edit_commande', 'Edit Commande') ?>
</a>


                                <form action="{{ url('/factures/' . $facture->id) }}" method="POST" class="delete-facture-form" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger delete-facture-btn" data-id="{{ $facture->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= get_label('delete', 'Delete') ?>">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
    var label_projects = '<?= get_label('projects', 'Projects') ?>';
    var label_tasks = '<?= get_label('tasks', 'Tasks') ?>';

    document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-facture-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const factureId = this.getAttribute('data-id');
            const form = this.closest('form');

            if (confirm('Are you sure you want to delete this facture?')) {
                fetch(`{{ url('/factures') }}/${factureId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Facture deleted successfully.');
                        location.reload(); // Refresh the page
                    } else {
                        alert('Failed to delete the facture. Please try again.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
});

</script>
@endsection
