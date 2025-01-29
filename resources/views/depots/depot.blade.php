@extends('layout')

@section('title')
    <?= get_label('depots', 'Depots') ?>
@endsection

@php
    $visibleColumns = getUserPreferences('depots');
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
                            <?= get_label('Depots', 'Depots') ?>
                        </li>
                    </ol>
                </nav>
            </div>

            <div>
                <button type="button" id="add_depot_btn" class="btn btn-sm btn-primary">{{ get_label('add_new_depot', 'Add New Depot') }}</button>
            </div>
        </div>

        @if (is_countable($depots) && count($depots) > 0)
        <div class="card">
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <input type="hidden" id="data_type" value="depots">
                    <input type="hidden" id="save_column_visibility">
                    <table id="table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/depots/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                        <thead>
                            <tr>
                                <th data-field="name" data-visible="{{ (in_array('name', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('name', 'Name') ?></th>
                                <th data-field="address" data-visible="{{ (in_array('address', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('address', 'Address') ?></th>
                                <th data-field="city" data-visible="{{ (in_array('city', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('city', 'City') ?></th>
                                <th data-field="country" data-visible="{{ (in_array('country', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true"><?= get_label('country', 'Country') ?></th>
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
        <?php $type = 'Depots'; ?>
        <x-empty-state-card :type="$type" />
        @endif
    </div>

    <!-- Depot Details Modal -->
    <div class="modal fade mt-5" id="depotModal" tabindex="-1" role="dialog" aria-labelledby="depotModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex w-100 justify-content-between align-items-center">
                        <h5 class="modal-title text-info">{{ get_label('view_depot', 'View Depot') }}</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">{{ get_label('name', 'Name') }}</label>
                                <input style="background-color: #ffffff !important;" type="text" id="name" class="form-control" readonly>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="address" class="form-label">{{ get_label('address', 'Address') }}</label>
                                <input style="background-color: #ffffff !important;" type="text" id="address" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="city" class="form-label">{{ get_label('city', 'City') }}</label>
                                <input style="background-color: #ffffff !important;" type="text" id="city" class="form-control" readonly>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="country" class="form-label">{{ get_label('country', 'Country') }}</label>
                                <input style="background-color: #ffffff !important;" type="text" id="country" class="form-control" readonly>
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
        document.addEventListener('DOMContentLoaded', function () {
            var modalElement = document.getElementById('depotModal');
            var modalBody = modalElement.querySelector('.modal-body');

            document.addEventListener('click', function (event) {
                var target = event.target.closest('[data-id]');

                if (target) {
                    var id = target.dataset.id;

                    if (id) {
                        var url = "{{ url('depots/getdepot') }}/" + id;

                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                modalBody.querySelector('#name').value = data.name ?? '--';
                                modalBody.querySelector('#address').value = data.address ?? '--';
                                modalBody.querySelector('#city').value = data.city ?? '--';
                                modalBody.querySelector('#country').value = data.country ?? '--';
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
    </script>

    <script src="{{asset('assets/js/pages/depots.js')}}"></script>
@endsection