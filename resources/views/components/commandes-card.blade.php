<!-- commandes -->
@php
$flag = (Request::segment(1) == 'home' ||
Request::segment(1) == 'users' ||
Request::segment(1) == 'clients' ||
isset($viewAssigned) && $viewAssigned == 1 ||
(Request::segment(1) == 'products' && Request::segment(2) == 'information' && Request::segment(3) != null)) ? 0 : 1;
$visibleColumns = getUserPreferences('commandes');
@endphp
@if (isset($commandes) && $commandes->count() > 0 || (isset($emptyState) && $emptyState == 0))

<div class="<?= $flag == 1 ? 'card ' : '' ?>mt-2">
    @endif
    @if ($flag == 1 && ((isset($commandes) && $commandes->count() > 0 || (isset($emptyState) && $emptyState == 0))))
    <div class="card-body">
        @endif
        {{$slot}}
        @if (isset($commandes) && $commandes->count() > 0 || (isset($emptyState) && $emptyState == 0))
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="input-group input-group-merge">
                    <input type="text" id="commande_start_date_between" name="commande_start_date_between" class="form-control" placeholder="<?= get_label('start_date_between', 'Start date between') ?>" autocomplete="off">
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="input-group input-group-merge">
                    <input type="text" id="commande_end_date_between" name="commande_end_date_between" class="form-control" placeholder="<?= get_label('end_date_between', 'End date between') ?>" autocomplete="off">
                </div>
            </div>

            @if(isAdminOrHasAllDataAccess() && !isset($viewAssigned))
            @if(explode('_',$id)[0] !='client' && explode('_',$id)[0] !='user')
            <div class="col-md-4 mb-3">
                <select class="form-control js-example-basic-multiple" id="commandes_user_filter" name="user_ids[]" multiple="multiple" data-placeholder="<?= get_label('select_users', 'Select Users') ?>">
                    @foreach ($users as $user)
                    <option value="{{$user->id}}">{{$user->first_name.' '.$user->last_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <select class="form-control js-example-basic-multiple" id="commandes_client_filter" name="client_ids[]" multiple="multiple" data-placeholder="<?= get_label('select_clients', 'Select Clients') ?>">>
                    @foreach ($clients as $client)
                    <option value="{{$client->id}}">{{$client->first_name.' '.$client->last_name}}</option>
                    @endforeach
                </select>
            </div>
            @endif
            @endif
            <div class="col-md-4 mb-3">
                <select class="form-control" id="commande_status_filter" name="status_ids[]" multiple="multiple" data-placeholder="<?= get_label('select_statuses', 'Select Statuses') ?>">
                    @foreach ($statuses as $status)
                    @php
                    $selected = (request()->has('status') && request()->status == $status->id) ? 'selected' : '';
                    @endphp
                    <option value="{{ $status->id }}" {{ $selected }}>{{ $status->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <select class="form-control" id="commande_priority_filter" name="priority_ids[]" multiple="multiple" data-placeholder="<?= get_label('select_priorities', 'Select Priorities') ?>">
                    @php
                    $selected = (request()->has('priority') && request()->priority == $priority->id) ? 'selected' : '';
                    @endphp
                    @foreach ($priorities as $priority)
                    <option value="{{ $priority->id }}" {{ $selected }}>{{ $priority->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <input type="hidden" name="commande_start_date_from" id="commande_start_date_from">
        <input type="hidden" name="commande_start_date_to" id="commande_start_date_to">
        <input type="hidden" name="commande_end_date_from" id="commande_end_date_from">
        <input type="hidden" name="commande_end_date_to" id="commande_end_date_to">
        <div class="table-responsive text-nowrap">
            <input type="hidden" id="data_type" value="commandes">
            <input type="hidden" id="data_table" value="commande_table">
            <input type="hidden" id="save_column_visibility">
            <table id="commande_table" data-toggle="table" data-loading-template="loadingTemplate" data-url="{{ isset($viewAssigned) && $viewAssigned == 1 ? '' : (!empty($id) ? '/commandes/list/' . $id : '/commandes/list') }}" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParamsCommandes">
                <thead>
                    <tr>
                        <th data-checkbox="true"></th>
                        <th data-field="id" data-visible="{{ (in_array('id', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true">{{ get_label('id', 'ID') }}</th>
                        <th data-field="title" data-visible="{{ (in_array('title', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true">{{ get_label('commande', 'Commande') }}</th>
                        <th data-field="product_id" data-visible="{{ (in_array('product_id', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true">{{ get_label('product', 'Product') }}</th>
                        <th data-field="users" data-visible="{{ (in_array('users', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">{{ get_label('users', 'Users') }}</th>
                        <th data-field="clients" data-visible="{{ (in_array('clients', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">{{ get_label('clients', 'Clients') }}</th>
                        <th data-field="status" class="status-column" data-visible="{{ (in_array('status_id', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true">{{ get_label('status', 'Status') }}</th>
                        <th data-field="priority_id" class="priority-column" data-visible="{{ (in_array('priority_id', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true">{{ get_label('priority', 'Priority') }}</th>
                        <th data-field="start_date" data-visible="{{ (in_array('start_date', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true">{{ get_label('starts_at', 'Starts at') }}</th>
                        <th data-field="end_date" data-visible="{{ (in_array('end_date', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true">{{ get_label('ends_at', 'Ends at') }}</th>
                        <th data-field="created_at" data-visible="{{ (in_array('created_at', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('created_at', 'Created at') ?></th>
                        <th data-field="updated_at" data-visible="{{ (in_array('updated_at', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('updated_at', 'Updated at') ?></th>
                        <th data-field="actions" data-visible="{{ (in_array('actions', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">{{ get_label('actions', 'Actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
        @else
        @if(!isset($emptyState) || $emptyState != 0)
        <?php
        $type = 'Commandes';
        ?>
        <x-empty-state-card :type="$type" />
        @endif
        @endif
        @if ($flag == 1 && ((isset($commandes) && $commandes->count() > 0 || (isset($emptyState) && $emptyState == 0))))

    </div>
    @endif
    @if (isset($commandes) && $commandes->count() > 0 || (isset($emptyState) && $emptyState == 0))
    </div>
@endif
<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
    var label_duplicate = '<?= get_label('duplicate', 'Duplicate') ?>';
    var label_not_assigned = '<?= get_label('not_assigned', 'Not assigned') ?>';
    var add_favorite = '<?= get_label('add_favorite', 'Click to mark as favorite') ?>';
    var remove_favorite = '<?= get_label('remove_favorite', 'Click to remove from favorite') ?>';
    var id = '<?= $id??'' ?>';
</script>
<script src="{{asset('assets/js/pages/commandes.js')}}"></script>