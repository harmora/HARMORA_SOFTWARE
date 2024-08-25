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
            <a href="{{url('/factures/create')}}"><button type="button" class="btn btn-sm btn-primary " data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_facture', 'Create facture') ?>"><i class='bx bx-plus'></i></button></a>
        </div>
    </div>

    <div class="card">

    </div>

</div>
<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
    var label_projects = '<?= get_label('projects', 'Projects') ?>';
    var label_tasks = '<?= get_label('tasks', 'Tasks') ?>';
</script>
{{-- <script src="{{asset('assets/js/pages/entreprises.js')}}"> --}}
</script>
@endsection
