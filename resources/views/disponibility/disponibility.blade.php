@extends('layout')
@section('title')
<?= get_label('disponibility', 'Disponibility') ?>
@endsection
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
                        <?= get_label('disponibility', 'Disponibility') ?>
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#createreservationmodal"><button type="button" class="btn btn-sm btn-primary " data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('add_reservation', 'Add Reservation') ?>"><i class='bx bx-plus'></i></button></a>
            {{-- action_create_meetings --}}
        </div>
    </div>
    <div class="nav-align-top">

                <!-- Content for the "Upcoming birthdays" tab -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">

                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-upcoming-birthdays-list" aria-controls="navs-top-upcoming-birthdays-list" aria-selected="true">
                            <i class="menu-icon tf-icons bx bx-menu text-primary"></i>  <?= get_label('list', 'List') ?>
                        </button>
                    </li>
                    <li class="nav-item">

                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-upcoming-birthdays-calendar" aria-controls="navs-top-upcoming-birthdays-calendar" aria-selected="false">
                            <i class="menu-icon tf-icons bx bx-calendar text-info"></i>  <?= get_label('calendar', 'Calendar') ?>
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="navs-top-upcoming-birthdays-list" role="tabpanel">
                        @if (is_countable($disponibilities) && count($disponibilities) > 0)
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive text-nowrap">
                                    <input type="hidden" id="data_type" value="disponibilities">
                                    <input type="hidden" id="save_column_visibility">
                                    <table id="disponibilities" data-toggle="table" data-loading-template="loadingTemplate" data-url="/disponibilities/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                                        <thead>
                                            <tr>
                                                <th data-field="id" data-visible="{{ (in_array('id', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}" data-sortable="true">ID</th>
                                                <th data-field="activity_name" data-visible="{{ (in_array('activity_name', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">Activity Name</th>
                                                <th data-field="details" data-visible="{{ (in_array('details', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">Details</th>
                                                <th data-field="start_date_time" data-visible="{{ (in_array('start_date_time', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">Start Date Time</th>
                                                <th data-field="end_date_time" data-visible="{{ (in_array('end_date_time', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">End Date Time</th>
                                                <th data-field="created_at" data-visible="{{ (in_array('created_at', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true">Created At</th>
                                                <th data-field="updated_at" data-visible="{{ (in_array('updated_at', $visibleColumns)) ? 'true' : 'false' }}" data-sortable="true">Updated At</th>
                                                <th data-field="actions" data-visible="{{ (in_array('actions', $visibleColumns) || empty($visibleColumns)) ? 'true' : 'false' }}">Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @else
                        <x-empty-state-card type="Disponibilities" />
                        @endif
                    </div>
                    <div class="tab-pane fade" id="navs-top-upcoming-birthdays-calendar" role="tabpanel">
                        <!-- Content for the "Calendar" tab under "Upcoming birthdays" -->
                        <div id="upcomingBirthdaysCalendar"></div>
                    </div>
                </div>

    </div>



    <!-- show disp MODAL  -->
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-info">{{ get_label('view_disponibility', 'View Disponibility') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="mb-3">
                                    <label  for="namedisp" class="form-label">{{ get_label('activity_name', 'Activity Name') }}</label>
                                    <input  style="background-color: #ffffff !important; "  type="text" id="namedisp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="descdisp" class="form-label">{{ get_label('description', 'Description') }}</label>
                                    <textarea style="background-color: #ffffff !important; "  id="descdisp" class="form-control" rows="3" readonly></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="start_date" class="form-label">{{ get_label('start_date', 'Start Date') }}</label>
                                    <input style="background-color: #ffffff !important; "  type="date" id="start_date" class="form-control" readonly>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="start_time" class="form-label">{{ get_label('start_time', 'Start Time') }}</label>
                                    <input style="background-color: #ffffff !important; "  type="time" id="start_time" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="end_date" class="form-label">{{ get_label('end_date', 'End Date') }}</label>
                                    <input  style="background-color: #ffffff !important; "  type="date" id="end_date" class="form-control" readonly>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="end_time" class="form-label">{{ get_label('end_time', 'End Time') }}</label>
                                    <input style="background-color: #ffffff !important; "  type="time" id="end_time" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="state" class="form-label">{{ get_label('state', 'State') }}</label>
                                    <input  style="background-color: #ffffff !important; "  type="text" id="state" class="form-control" readonly>
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

    <script src="{{asset('assets/js/pages/disponibilities.js')}}"></script>


</div>
@endsection


