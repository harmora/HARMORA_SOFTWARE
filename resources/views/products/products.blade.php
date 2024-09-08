@extends('layout')
@section('title')
<?= get_label('stock', 'Stock') ?>
@endsection
@php
$visibleColumns = getUserPreferences('products');
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
                        <?= get_label('stock', 'Stock') ?>
                    </li>
                </ol>
            </nav>
        </div>

        <div>
            <a href="/import">
                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="{{ get_label('import_file', 'Import File') }}">
                    <?= get_label('import Excel', 'import Excel') ?> <i class="bx bx-file"></i>
                </button>
            </a>
            <a href="{{url('/products/create')}}"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('add_stock', 'Add Stock') ?>"><i class='bx bx-plus'></i></button></a>
        </div>
    </div>
    @if (is_countable($products) && count($products) > 0)
    <div class="card">
        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">
                    <a href="{{ route('products.movements') }}" class="btn btn-primary">
                        <i class="bx bx-box"></i> <?= get_label('Stock_Mouvements', ' Stock Mouvements') ?>
                    </a>

            </div>

                <div class="col-md-4 mb-3">
                    <select class="form-select" id="category_filter" aria-label="Default select example">
                        <option value=""><?= get_label('select_status', 'Select status') ?></option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat->id ?>"><?= $cat->name_cat ?></option>
                         <?php endforeach; ?>

                    </select>
                </div>
                {{-- @isset($roles)
                <div class="col-md-4 mb-3">
                    <select class="form-control js-example-basic-multiple" id="user_roles_filter" multiple="multiple" data-placeholder="<?= get_label('select_roles', 'Select Roles') ?>">
                        @foreach ($roles as $role)
                        <option value="{{$role->id}}">{{ucfirst($role->name)}}</option>
                        @endforeach
                    </select>
                </div>
                @endisset --}}
            </div>
            <div class="table-responsive text-nowrap">
                <input type="hidden" id="data_type" value="products">
                <input type="hidden" id="save_column_visibility">
                <table id="table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/products/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                    <thead>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th data-field="id" data-visible="{{ (in_array('id', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('id', 'ID') ?></th>
                            <th data-field="profile" data-visible="{{ (in_array('profile', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}"><?= get_label('product', 'Product') ?></th>
                            <th data-field="name" data-visible="{{ (in_array('name', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" ><?= get_label('name', 'Name') ?></th>
                            <th data-field="price" data-visible="{{ (in_array('price', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('price', 'Price') ?></th>
                            <th data-field="category" data-visible="{{ (in_array('category', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}"><?= get_label('category', 'Category') ?> </th>
                            <th data-field="stock" data-visible="{{ (in_array('stock', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}"><?= get_label('stock', 'Stock') ?> </th>
                            <th data-field="stock_def" data-visible="{{ (in_array('stock_def', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}"><?= get_label('Stock deffective', 'Stock defective') ?> </th>
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
    $type = 'Users'; ?>
    <x-empty-state-card :type="$type" />
    @endif
</div>
<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';

</script>
<script src="{{asset('assets/js/pages/products.js')}}"></script>
@endsection
