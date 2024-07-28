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
                    <!-- Content for the "List" tab under "Upcoming birthdays" -->

                    {{-- <x-upcoming-birthdays-card :users="[]" /> --}}
                   <span class="info"><b>---------------> TABLE OF RESERVATIONS ADDED <----------------</b></span>
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

