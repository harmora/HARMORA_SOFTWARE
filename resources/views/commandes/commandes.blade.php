@extends('layout')
@section('title')
<?= get_label('commandes', 'Commandes') ?> - <?= get_label('list_view', 'List view') ?>
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
                    @isset($product->id)
                    <li class="breadcrumb-item">
                        <a href="{{url('/'.getUserPreferences('products', 'default_view'))}}"><?= get_label('products', 'Products') ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{url('/products/information/'.$product->id)}}">{{$product->title}}</a>
                    </li>
                    @endisset
                    <li class="breadcrumb-item active"><?= get_label('commandes', 'Commandes') ?></li>
                </ol>
            </nav>
        </div>
        <div>
            @php
            $commandeDefaultView = getUserPreferences('commandes', 'default_view');
            @endphp
            @if (!$commandeDefaultView || $commandeDefaultView === 'commandes')
            <span class="badge bg-primary"><?= get_label('default_view', 'Default View') ?></span>
            @else
            <a href="javascript:void(0);"><span class="badge bg-secondary" id="set-default-view" data-type="commandes" data-view="list"><?= get_label('set_as_default_view', 'Set as Default View') ?></span></a>
            @endif
        </div>
        <div>
            @php
            $productId = isset($product->id) ? $product->id : (request()->has('product') ? request('product') : '');
            $url = isset($product->id) || request()->has('product') ? '/products/commandes/draggable/' . $productId : '/commandes/draggable';
            if (request()->has('status')) {
            $url .= '?status=' . request()->status;
            }
            @endphp
            <a href="{{url('/commandes/create')}}"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_commande', 'Create commande') ?>"><i class='bx bx-plus'></i></button></a>
            <a href="{{ $url }}"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('draggable', 'Draggable') ?>"><i class="bx bxs-dashboard"></i></button></a>  
        </div>
    </div>


    @if (is_countable($commandes) && count($commandes) > 0)
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <select class="form-select" id="commande_status_filter" aria-label="Default select example">
                        <option value=""><?= get_label('select_status', 'Select status') ?></option>
                        <option value="pending">{{get_label('pending', 'Pending')}}</option>
                        <option value="completed">{{get_label('completed', 'Completed')}}</option>
                        <option value="cancelled">{{get_label('cancelled', 'Cancelled')}}</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <input type="hidden" id="data_type" value="commandes">
                <input type="hidden" id="save_column_visibility">
                <table id="table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/commandes/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th data-field="id" data-visible="{{ (in_array('id', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('id', 'ID') ?></th>
                            <th data-field="title" data-visible="{{ (in_array('title', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('title', 'Title') ?></th>
                            <th data-field="description" data-visible="{{ (in_array('description', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}"><?= get_label('description', 'Description') ?></th>
                            <th data-field="total_amount" data-visible="{{ (in_array('total_amount', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('total_amount', 'Total Amount') ?></th>
                            <th data-field="status" data-visible="{{ (in_array('status', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('status', 'Status') ?></th>
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

<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
    var label_title = '<?= get_label('title', 'Title') ?>';
    var label_description = '<?= get_label('description', 'Description') ?>';
</script>
<script src="{{asset('assets/js/pages/commandes.js')}}">
</script>


@endsection