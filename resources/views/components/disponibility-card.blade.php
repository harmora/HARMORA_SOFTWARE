<div class="nav-align-top">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-upcoming-birthdays" aria-controls="navs-top-upcoming-birthdays" aria-selected="true">
                <i class="menu-icon tf-icons bx bx-calendar text-info"></i> <?= get_label('added_reservations', 'Reservations Added') ?>
            </button>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade active show" id="navs-top-upcoming-birthdays" role="tabpanel">
            <!-- Content for the "Upcoming birthdays" tab -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-upcoming-birthdays-list" aria-controls="navs-top-upcoming-birthdays-list" aria-selected="true">
                        <?= get_label('list', 'List') ?>
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-upcoming-birthdays-calendar" aria-controls="navs-top-upcoming-birthdays-calendar" aria-selected="false">
                        <?= get_label('calendar', 'Calendar') ?>
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
                                <table id="table" data-toggle="table" data-loading-template="loadingTemplate" data-url="/disponibilities/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParams">
                                    <thead>
                                        <tr>
                                            <th data-checkbox="true"></th>
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
    </div>
</div>



<!-- show disp MODAL  -->
<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title text-info" id="exampleModalLabel1"><span>Reservation Informations :</span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="nav-align-top">
                <div id="eventInfo">
                    <p ><strong><?=get_label('activity_name', 'Activity Name')?>:</strong> <span id="namedisp"></span></p>
                    <p ><strong><?=get_label('description','Description')?>:</strong> <span id="descdisp"></span> </p>

                    {{-- a ajouter en lang --}}
                    <p ><strong><?=get_label('Start Date','Start Date')?>:</strong> <span id="date_start">...</span> </p>
                    <p ><strong><?=get_label('end Date','End Date')?>:</strong> <span id="date_start">...</span> </p>
                    <p ><strong><?=get_label('State','State')?>:</strong> <span id="date_start">Already Done</span> </p>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                <?= get_label('close', 'Close') ?>
            </button>
        </div>
    </div>
    </div>
</div>

