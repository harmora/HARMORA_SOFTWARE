@php
use App\Models\Workspace;
$auth_user = getAuthenticatedUser();
$roles = \Spatie\Permission\Models\Role::where('name', '!=', 'admin')->get();
@endphp


{{-- <!-- add disp MODAL  -->   add langs !!!!!!!!!!!1 --}}
<div class="modal fade" id="createreservationmodal" tabindex="-1" role="dialog" aria-labelledby="createreservationmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ get_label('add_reservation', 'Add Reservation') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form class=" form-submit-event" action="{{ url('/disponibility/store') }}" method="POST">
                <input type="hidden" name="dnr">
                <input type="hidden" name="table" value="disponibilities">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3">
                            <label for="activity_name" class="form-label">{{ get_label('activity_name', 'Activity Name') }} <span class="asterisk">*</span></label>
                            <input class="form-control" type="text" name="activity_name" placeholder="{{ get_label('please_enter_activity_name', 'Please enter activity name') }}" value="{{ old('activity_name') }}">
                            @if ($errors->has('activity_name'))
                                <span class="text-danger">{{ $errors->first('activity_name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="details" class="form-label">{{ get_label('description', 'Description') }}</label>
                            <textarea class="form-control" name="details" placeholder="{{ get_label('please_enter_description', 'Please enter description') }}">{{ old('details') }}</textarea>
                            @if ($errors->has('details'))
                                <span class="text-danger">{{ $errors->first('details') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="start_date_event">{{ get_label('starts_at', 'Starts at') }} <span class="asterisk">*</span></label>
                            <input type="date" id="start_date_event" name="start_date_event" class="form-control" value="{{ old('start_date_event') }}">
                            @if ($errors->has('start_date_event'))
                                <span class="text-danger">{{ $errors->first('start_date_event') }}</span>
                            @endif
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="start_time">{{ get_label('time', 'Time') }} <span class="asterisk">*</span></label>
                            <input type="time" id="start_time" name="start_time" class="form-control" value="{{ old('start_time') }}">
                            @if ($errors->has('start_time'))
                                <span class="text-danger">{{ $errors->first('start_time') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="end_date_event">{{ get_label('ends_at', 'Ends at') }} <span class="asterisk">*</span></label>
                            <input type="date" id="end_date_event" name="end_date_event" class="form-control" value="{{ old('end_date_event') }}">
                            @if ($errors->has('end_date_event'))
                                <span class="text-danger">{{ $errors->first('end_date_event') }}</span>
                            @endif
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="end_time">{{ get_label('time', 'Time') }} <span class="asterisk">*</span></label>
                            <input type="time" id="end_time" name="end_time" class="form-control" value="{{ old('end_time') }}">
                            @if ($errors->has('end_time'))
                                <span class="text-danger">{{ $errors->first('end_time') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="alert alert-primary alert-dismissible" role="alert">
                        {{ get_label('Reservations added here will appear automatically in the calendar', 'Reservations added here will appear automatically in the calendar') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ get_label('close', 'Close') }}</button>
                    <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('create', 'Create') ?></label></button>

                </div>
            </form>
        </div>
    </div>
</div>















@if (Request::is('projects') || Request::is('projects/*') || Request::is('commandes') || Request::is('commandes/*') || Request::is('status/manage') || Request::is('users') || Request::is('clients'))
<div class="modal fade" id="create_status_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content form-submit-event" action="{{url('/status/store')}}" method="POST">
            <input type="hidden" name="dnr">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_status', 'Create status') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="nameBasic" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('color', 'Color') ?> <span class="asterisk">*</span></label>
                        <select class="form-select select-bg-label-primary" id="color" name="color">
                            <option class="badge bg-label-primary" value="primary" {{ old('color') == "primary" ? "selected" : "" }}>
                                <?= get_label('primary', 'Primary') ?>
                            </option>
                            <option class="badge bg-label-secondary" value="secondary" {{ old('color') == "secondary" ? "selected" : "" }}><?= get_label('secondary', 'Secondary') ?></option>
                            <option class="badge bg-label-success" value="success" {{ old('color') == "success" ? "selected" : "" }}><?= get_label('success', 'Success') ?></option>
                            <option class="badge bg-label-danger" value="danger" {{ old('color') == "danger" ? "selected" : "" }}><?= get_label('danger', 'Danger') ?></option>
                            <option class="badge bg-label-warning" value="warning" {{ old('color') == "warning" ? "selected" : "" }}><?= get_label('warning', 'Warning') ?></option>
                            <option class="badge bg-label-info" value="info" {{ old('color') == "info" ? "selected" : "" }}><?= get_label('info', 'Info') ?></option>
                            <option class="badge bg-label-dark" value="dark" {{ old('color') == "dark" ? "selected" : "" }}><?= get_label('dark', 'Dark') ?></option>
                        </select>
                    </div>
                </div>
                @if (isAdminOrHasAllDataAccess())
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label"><?= get_label('roles_can_set_status', 'Roles Can Set the Status') ?> <i class='bx bx-info-circle text-primary' data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" title="" data-bs-original-title="{{get_label('roles_can_set_status_info', 'Including Admin and Roles with All Data Access Permission, Users/Clients Under Selected Role(s) Will Have Permission to Set This Status.')}}"></i></label>
                        <div class="input-group">
                            <select class="form-control js-example-basic-multiple" name="role_ids[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                @isset($roles)
                                @foreach($roles as $role)
                                <option value="{{$role->id}}">{{ucfirst($role->name)}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?></label>
                </button>
                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('create', 'Create') ?></label></button>
            </div>
        </form>
    </div>
</div>
@endif
@if (Request::is('status/manage'))
<div class="modal fade" id="edit_status_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{url('/status/update')}}" class="modal-content form-submit-event" method="POST">
            <input type="hidden" name="id" id="status_id">
            <input type="hidden" name="dnr">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_status', 'Update status') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="status_title" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" required />
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('color', 'Color') ?> <span class="asterisk">*</span></label>
                        <select class="form-select select-bg-label-primary" id="status_color" name="color" required>
                            <option class="badge bg-label-primary" value="primary">
                                <?= get_label('primary', 'Primary') ?>
                            </option>
                            <option class="badge bg-label-secondary" value="secondary"><?= get_label('secondary', 'Secondary') ?></option>
                            <option class="badge bg-label-success" value="success"><?= get_label('success', 'Success') ?></option>
                            <option class="badge bg-label-danger" value="danger"><?= get_label('danger', 'Danger') ?></option>
                            <option class="badge bg-label-warning" value="warning"><?= get_label('warning', 'Warning') ?></option>
                            <option class="badge bg-label-info" value="info"><?= get_label('info', 'Info') ?></option>
                            <option class="badge bg-label-dark" value="dark"><?= get_label('dark', 'Dark') ?></option>
                        </select>
                    </div>
                </div>
                @if (isAdminOrHasAllDataAccess())
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label"><?= get_label('roles_can_set_status', 'Roles Can Set the Status') ?> <i class='bx bx-info-circle text-primary' data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" title="" data-bs-original-title="{{get_label('roles_can_set_status_info', 'Including Admin and Roles with All Data Access Permission, Users/Clients Under Selected Role(s) Will Have Permission to Set This Status.')}}"></i></label>
                        <div class="input-group">
                            <select class="form-control js-example-basic-multiple" name="role_ids[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                @isset($roles)
                                @foreach($roles as $role)
                                <option value="{{$role->id}}">{{ucfirst($role->name)}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?></label>
                </button>
                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('update', 'Update') ?></label></button>
            </div>
        </form>
    </div>
</div>
@endif
@if (Request::is('projects') || Request::is('projects/*') || Request::is('commandes') || Request::is('commandes/*') || Request::is('priority/manage') || Request::is('users') || Request::is('clients'))
<div class="modal fade" id="create_priority_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content form-submit-event" action="{{url('/priority/store')}}" method="POST">
            <input type="hidden" name="dnr">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_priority', 'Create Priority') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="nameBasic" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('color', 'Color') ?> <span class="asterisk">*</span></label>
                        <select class="form-select select-bg-label-primary" id="color" name="color">
                            <option class="badge bg-label-primary" value="primary" {{ old('color') == "primary" ? "selected" : "" }}>
                                <?= get_label('primary', 'Primary') ?>
                            </option>
                            <option class="badge bg-label-secondary" value="secondary" {{ old('color') == "secondary" ? "selected" : "" }}><?= get_label('secondary', 'Secondary') ?></option>
                            <option class="badge bg-label-success" value="success" {{ old('color') == "success" ? "selected" : "" }}><?= get_label('success', 'Success') ?></option>
                            <option class="badge bg-label-danger" value="danger" {{ old('color') == "danger" ? "selected" : "" }}><?= get_label('danger', 'Danger') ?></option>
                            <option class="badge bg-label-warning" value="warning" {{ old('color') == "warning" ? "selected" : "" }}><?= get_label('warning', 'Warning') ?></option>
                            <option class="badge bg-label-info" value="info" {{ old('color') == "info" ? "selected" : "" }}><?= get_label('info', 'Info') ?></option>
                            <option class="badge bg-label-dark" value="dark" {{ old('color') == "dark" ? "selected" : "" }}><?= get_label('dark', 'Dark') ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?></label>
                </button>
                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('create', 'Create') ?></label></button>
            </div>
        </form>
    </div>
</div>
@endif
@if (Request::is('priority/manage'))
<div class="modal fade" id="edit_priority_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{url('priority/update')}}" class="modal-content form-submit-event" method="POST">
            <input type="hidden" name="id" id="priority_id">
            <input type="hidden" name="dnr">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_priority', 'Update Priority') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="priority_title" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" required />
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('color', 'Color') ?> <span class="asterisk">*</span></label>
                        <select class="form-select select-bg-label-primary" id="priority_color" name="color" required>
                            <option class="badge bg-label-primary" value="primary">
                                <?= get_label('primary', 'Primary') ?>
                            </option>
                            <option class="badge bg-label-secondary" value="secondary"><?= get_label('secondary', 'Secondary') ?></option>
                            <option class="badge bg-label-success" value="success"><?= get_label('success', 'Success') ?></option>
                            <option class="badge bg-label-danger" value="danger"><?= get_label('danger', 'Danger') ?></option>
                            <option class="badge bg-label-warning" value="warning"><?= get_label('warning', 'Warning') ?></option>
                            <option class="badge bg-label-info" value="info"><?= get_label('info', 'Info') ?></option>
                            <option class="badge bg-label-dark" value="dark"><?= get_label('dark', 'Dark') ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?></label>
                </button>
                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('update', 'Update') ?></label></button>
            </div>
        </form>
    </div>
</div>
@endif

@if (Request::is('home') || Request::is('todos'))
<div class="modal fade" id="create_todo_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content form-submit-event" action="{{url('/todos/store')}}" method="POST">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_todo', 'Create todo') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('priority', 'Priority') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" name="priority">
                            <option value="low" {{ old('priority') == "low" ? "selected" : "" }}><?= get_label('low', 'Low') ?></option>
                            <option value="medium" {{ old('priority') == "medium" ? "selected" : "" }}><?= get_label('medium', 'Medium') ?></option>
                            <option value="high" {{ old('priority') == "high" ? "selected" : "" }}><?= get_label('high', 'High') ?></option>
                        </select>
                    </div>
                </div>
                <label for="description" class="form-label"><?= get_label('description', 'Description') ?></label>
                <textarea class="form-control" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="edit_todo_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{url('/todos/update')}}" class="modal-content form-submit-event" method="POST">
            <input type="hidden" name="id" id="todo_id">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_todo', 'Update todo') ?></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="todo_title" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('priority', 'Priority') ?> <span class="asterisk">*</span></label>
                        <select class="form-select" id="todo_priority" name="priority">
                            <option value="low"><?= get_label('low', 'Low') ?></option>
                            <option value="medium"><?= get_label('medium', 'Medium') ?></option>
                            <option value="high"><?= get_label('high', 'High') ?></option>
                        </select>
                    </div>
                </div>
                <label for="description" class="form-label"><?= get_label('description', 'Description') ?></label>
                <textarea class="form-control" id="todo_description" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('update', 'Update') ?></span></button>
            </div>
        </form>
    </div>
</div>
@endif
<div class="modal fade" id="default_language_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel2"><?= get_label('confirm', 'Confirm!') ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?= get_label('set_primary_lang_alert', 'Are you want to set as your primary language?') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-primary" id="confirm"><?= get_label('yes', 'Yes') ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="set_default_view_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel2"><?= get_label('confirm', 'Confirm!') ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?= get_label('set_default_view_alert', 'Are You Want to Set as Default View?') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-primary" id="confirm"><?= get_label('yes', 'Yes') ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmSaveColumnVisibility" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel2"><?= get_label('confirm', 'Confirm!') ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?= get_label('save_column_visibility_alert', 'Are You Want to Save Column Visibility?') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-primary" id="confirm"><?= get_label('yes', 'Yes') ?></button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="create_language_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <form class="modal-content form-submit-event" action="{{url('/settings/languages/store')}}" method="POST">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_language', 'Create language') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="name" placeholder="For Example: English" />
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('code', 'Code') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="code" placeholder="For Example: en" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="edit_language_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <form class="modal-content form-submit-event" action="{{url('/settings/languages/update')}}" method="POST">
            <input type="hidden" name="id" id="language_id">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_language', 'Update language') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" name="name" id="language_title" placeholder="For Example: English" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('update', 'Update') ?></button>
            </div>
        </form>
    </div>
</div>


@if (Request::is('notes'))
<div class="modal fade" id="create_note_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content form-submit-event" action="{{url('/notes/store')}}" method="POST">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_note', 'Create note') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="nameBasic" class="form-control" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('description', 'Description') ?></label>
                        <textarea class="form-control description" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('color', 'Color') ?> <span class="asterisk">*</span></label>
                        <select class="form-select select-bg-label-success" name="color">
                            <option class="badge bg-label-success" value="info" {{ old('color') == "info" ? "selected" : "" }}><?= get_label('green', 'Green') ?></option>
                            <option class="badge bg-label-warning" value="warning" {{ old('color') == "warning" ? "selected" : "" }}><?= get_label('yellow', 'Yellow') ?></option>
                            <option class="badge bg-label-danger" value="danger" {{ old('color') == "danger" ? "selected" : "" }}><?= get_label('red', 'Red') ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?></label>
                </button>
                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('create', 'Create') ?></label></button>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="edit_note_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content form-submit-event" action="{{url('/notes/update')}}" method="POST">
            <input type="hidden" name="id" id="note_id">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_note', 'Update note') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" id="note_title" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('description', 'Description') ?></label>
                        <textarea class="form-control description" id="note_description" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label"><?= get_label('color', 'Color') ?> <span class="asterisk">*</span></label>
                        <select class="form-select select-bg-label-success" id="note_color" name="color">
                            <option class="badge bg-label-info" value="info" {{ old('color') == "info" ? "selected" : "" }}><?= get_label('green', 'Green') ?></option>
                            <option class="badge bg-label-warning" value="warning" {{ old('color') == "warning" ? "selected" : "" }}><?= get_label('yellow', 'Yellow') ?></option>
                            <option class="badge bg-label-danger" value="danger" {{ old('color') == "danger" ? "selected" : "" }}><?= get_label('red', 'Red') ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?></label>
                </button>
                <button type="submit" class="btn btn-primary" id="submit_btn"><?= get_label('update', 'Update') ?></label></button>
            </div>
        </form>
    </div>
</div>
@endif
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel2"><?= get_label('warning', 'Warning!') ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?= get_label('delete_account_alert', 'Are you sure you want to delete your account?') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <form id="formAccountDeactivation" action="/account/destroy/{{getAuthenticatedUser()->id}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><?= get_label('yes', 'Yes') ?></button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2"><?= get_label('warning', 'Warning!') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> '</button>
            </div>
            <div class="modal-body">
                <p><?= get_label('delete_alert', 'Are you sure you want to delete?') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-danger" id="confirmDelete"><?= get_label('yes', 'Yes') ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmDeleteSelectedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2"><?= get_label('warning', 'Warning!') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> '</button>
            </div>
            <div class="modal-body">
                <p><?= get_label('delete_selected_alert', 'Are you sure you want to delete selected record(s)?') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-danger" id="confirmDeleteSelections"><?= get_label('yes', 'Yes') ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="duplicateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel2"><?= get_label('warning', 'Warning!') ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?= get_label('duplicate_warning', 'Are you sure you want to duplicate?') ?></p>
                <div id="titleDiv" class="d-none"><label class="form-label"><?= get_label('update_title', 'Update Title') ?></label><input type="text" class="form-control" id="updateTitle" placeholder="<?= get_label('enter_title_duplicate', 'Enter Title For Item Being Duplicated') ?>"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-primary" id="confirmDuplicate"><?= get_label('yes', 'Yes') ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="timerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel2"><?= get_label('time_tracker', 'Time tracker') ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="stopwatch">
                    <div class="stopwatch_time">
                        <input type="text" name="hour" id="hour" value="00" class="form-control stopwatch_time_input" readonly>
                        <div class="stopwatch_time_lable"><?= get_label('hours', 'Hours') ?></div>
                    </div>
                    <div class="stopwatch_time">
                        <input type="text" name="minute" id="minute" value="00" class="form-control stopwatch_time_input" readonly>
                        <div class="stopwatch_time_lable"><?= get_label('minutes', 'Minutes') ?></div>
                    </div>
                    <div class="stopwatch_time">
                        <input type="text" name="second" id="second" value="00" class="form-control stopwatch_time_input" readonly>
                        <div class="stopwatch_time_lable"><?= get_label('second', 'Second') ?></div>
                    </div>
                </div>
                <div class="selectgroup selectgroup-pills d-flex justify-content-around mt-3">
                    <label class="selectgroup-item">
                        <span class="selectgroup-button selectgroup-button-icon" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('start', 'Start') ?>" id="start" onclick="startTimer()"><i class="bx bx-play"></i></span>
                    </label>
                    <label class="selectgroup-item">
                        <span class="selectgroup-button selectgroup-button-icon" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('stop', 'Stop') ?>" id="end" onclick="stopTimer()"><i class="bx bx-stop"></i></span>
                    </label>
                    <label class="selectgroup-item">
                        <span class="selectgroup-button selectgroup-button-icon" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('pause', 'Pause') ?>" id="pause" onclick="pauseTimer()"><i class="bx bx-pause"></i></span>
                    </label>
                </div>
                <div class="form-group mb-0 mt-3">
                    <label class="label"><?= get_label('message', 'Message') ?>:</label>
                    <textarea class="form-control" id="time_tracker_message" placeholder="<?= get_label('please_enter_your_message', 'Please enter your message') ?>" name="message"></textarea>
                </div>

                <div class="modal-footer justify-content-center">
                    <a href="/time-tracker" class="btn btn-primary"><i class="bx bxs-time"></i> <?= get_label('view_timesheet', 'View timesheet') ?></a>
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
<div class="modal fade" id="stopTimerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2"><?= get_label('warning', 'Warning!') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> '</button>
            </div>
            <div class="modal-body">
                <p><?= get_label('stop_timer_alert', 'Are you sure you want to stop the timer?') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-danger" id="confirmStop"><?= get_label('yes', 'Yes') ?></button>
            </div>
        </div>
    </div>
</div>´


<div class="modal fade" id="restore_default_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel2"><?= get_label('confirm', 'Confirm!') ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?= get_label('confirm_restore_default_template', 'Are you sure you want to restore default template?') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-primary" id="confirmRestoreDefault"><?= get_label('yes', 'Yes') ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="sms_instuction_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('sms_gateway_configuration', 'Sms Gateway Configuration') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul>
                    <li>Read and follow instructions carefully while configuration sms gateway setting </li>
                    <li class="my-4">Firstly open your sms gateway account . You can find api keys in your account -> API keys & credentials -> create api key </li>
                    <li class="my-4">After create key you can see here Account sid and auth token </li>
                    <div class="simplelightbox-gallery">
                        <a href="{{asset('storage/images/base_url_and_params.png')}}" target="_blank">
                            <img src="{{asset('storage/images/base_url_and_params.png')}}" class="w-100">
                        </a>
                    </div>
                    <li class="my-4">For Base url Messaging -> Send an SMS</li>
                    <div class="simplelightbox-gallery">
                        <a href="{{asset('storage/images/api_key_and_token.png')}}" target="_blank">
                            <img src="{{asset('storage/images/api_key_and_token.png')}}" class="w-100">
                        </a>
                    </div>
                    <li class="my-4">check this for admin panel settings</li>
                    <div class="simplelightbox-gallery">
                        <a href="{{asset('storage/images/sms_gateway_1.png')}}" target="_blank">
                            <img src="{{asset('storage/images/sms_gateway_1.png')}}" class="w-100">
                        </a>
                    </div>
                    <div class="simplelightbox-gallery">
                        <a href="{{asset('storage/images/sms_gateway_2.png')}}" target="_blank">
                            <img src="{{asset('storage/images/sms_gateway_2.png')}}" class="w-100">
                        </a>
                    </div>
                    <li class="my-4"><b>Make sure you entered valid data as per instructions before proceed</b></li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="whatsapp_instuction_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('whatsapp_configuration', 'WhatsApp Configuration') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul>
                    <li class="mb-2">You can find your <b>Account SID</b> and <b>Auth Token</b> on the Twilio Console dashboard page.</li>
                    <li class="mb-2"><b>From Number:</b> To get a test <b>From Number</b>, log in to your Twilio Console and go to <b>Messaging > Try it out > Send a WhatsApp message</b> and follow the instructions. If you want to use <b>your own number</b> as the <b>From Number</b>, go to <b>Messaging > Senders > WhatsApp senders</b> and follow the instructions.</li>
                    <li class="mb-2"><b>Feel free to reach out to us if you encounter any difficulties.</b></li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="permission_instuction_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('permission_settings_instructions', 'Permission Settings Instructions') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul>
                    <li class="mb-2"><b>{{get_label('all_data_access', 'All Data Access')}}:</b> If this option is selected, users or clients assigned to this role will have unrestricted access to all data, without any specific restrictions or limitations.</li>
                    <li class="mb-2"><b>{{get_label('allocated_data_access', 'Allocated Data Access')}}:</b> If this option is selected, users or clients assigned to this role will have restricted access to data based on specific assignments and restrictions.</li>
                    <li class="mb-2"><b>{{get_label('create_permission', 'Create Permission')}}:</b> This determines whether users or clients assigned to this role can create new records. For example, if the create permission is enabled for projects, users or clients in this role will be able to create new projects; otherwise, they won’t have this ability.</li>
                    <li class="mb-2"><b>{{get_label('manage_permission', 'Manage Permission')}}:</b> This determines whether users or clients assigned to this role can access and interact with specific modules. For instance, if the manage permission is enabled for projects, users or clients in this role will be able to view projects however create, edit, or delete depending on the specific permissions granted. If the manage permission is disabled for projects, users or clients in this role won’t be able to view or interact with projects in any way.</li>
                    <li class="mb-2"><b>{{get_label('edit_permission', 'Edit Permission')}}:</b> This determines whether users or clients assigned to this role can edit current records. For example, if the edit permission is enabled for projects, users or clients in this role will be able to edit current projects; otherwise, they won’t have this ability.</li>
                    <li><b>{{get_label('delete_permission', 'Delete Permission')}}:</b> This determines whether users or clients assigned to this role can delete current records. For example, if the delete permission is enabled for projects, users or clients in this role will be able to delete current projects; otherwise, they won’t have this ability.</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
            </div>
        </div>
    </div>
</div>

@if (Request::is('commandes') || Request::is('commandes/draggable') || Request::is('projects/commandes/draggable/*') || Request::is('projects/commandes/list/*') || Request::is('commandes/information/*') || Request::is('home') || Request::is('users/profile/*') || Request::is('clients/profile/*') || Request::is('projects/information/*') || Request::is('users') || Request::is('clients'))
<div class="modal fade" id="edit_commande_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="/commandes/update" class="form-submit-event modal-content" method="POST">
            <input type="hidden" name="id" id="id">
            @if (!Request::is('projects/commandes/draggable/*') && !Request::is('commandes/draggable') && !Request::is('commandes/information/*'))
            <input type="hidden" name="dnr">
            <input type="hidden" name="table" value="commande_table">
            @endif
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_commande', 'Update Commande') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label for="title" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="title" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" value="{{ old('title') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="status"><?= get_label('status', 'Status') ?> <span class="asterisk">*</span></label>
                        <div class="input-group">
                            <select class="form-select statusDropdown" name="status_id" id="commande_status_id">
                                @isset($statuses)
                                @foreach($statuses as $status)
                                <option value="{{$status->id}}" data-color="{{$status->color}}" {{ old('status') == $status->id ? "selected" : "" }}>{{$status->title}} ({{$status->color}})</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" class="openCreateStatusModal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_status', 'Create status') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/status/manage" target="_blank"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_statuses', 'Manage statuses') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label"><?= get_label('priority', 'Priority') ?></label>
                        <div class="input-group">
                            <select class="form-select" name="priority_id" id="priority_id">
                                @isset($priorities)
                                @foreach($priorities as $priority)
                                <option value="{{$priority->id}}" class="badge bg-label-{{$priority->color}}" {{ old('priority') == $priority->id ? "selected" : "" }}>{{$priority->title}} ({{$priority->color}})</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" class="openCreatePriorityModal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_priority', 'Create Priority') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/priority/manage" target="_blank"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_priorities', 'Manage Priorities') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="start_date"><?= get_label('starts_at', 'Starts at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="update_start_date" name="start_date" class="form-control" value="">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="due_date"><?= get_label('ends_at', 'Ends at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="update_end_date" name="due_date" class="form-control" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label for="project_title" class="form-label"><?= get_label('project', 'Project') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="update_project_title" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label class="form-label" for="user_id"><?= get_label('select_users', 'Select users') ?> <span id="commande_update_users_associated_with_project"></span></label>
                        <div class="input-group">
                            <select class="form-control js-example-basic-multiple" name="user_id[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label for="description" class="form-label"><?= get_label('description', 'Description') ?></label>
                        <textarea class="form-control description" id="commande_description" rows="5" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('note', 'Note') ?></label>
                        <textarea class="form-control" name="note" rows="3" id="commandeNote" placeholder="<?= get_label('optional_note', 'Optional Note') ?>"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('update', 'Update') ?></button>
            </div>
        </form>
    </div>
</div>
@endif
<div class="modal fade" id="confirmUpdateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel2"><?= get_label('confirm', 'Confirm!') ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?= get_label('confirm_update_status', 'Do You Want to Update the Status?') ?></p>
                <textarea class="form-control" id="statusNote" placeholder="<?= get_label('optional_note', 'Optional Note') ?>"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="declineUpdateStatus" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-primary" id="confirmUpdateStatus"><?= get_label('yes', 'Yes') ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmUpdatePriorityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel2"><?= get_label('confirm', 'Confirm!') ?></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?= get_label('confirm_update_priority', 'Do You Want to Update the Priority?') ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="declineUpdatePriority" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" class="btn btn-primary" id="confirmUpdatePriority"><?= get_label('yes', 'Yes') ?></button>
            </div>
        </div>
    </div>
</div>
@if (Request::is('projects') || Request::is('projects/list') || Request::is('home') || Request::is('users/profile/*') || Request::is('clients/profile/*'))
<div class="modal fade" id="create_project_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="/projects/store" class="form-submit-event modal-content" method="POST">
            @if (!Request::is('projects'))
            <input type="hidden" name="dnr">
            <input type="hidden" name="table" value="projects_table">
            @endif
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('create_project', 'Create Project') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="title" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" value="{{ old('title') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="status"><?= get_label('status', 'Status') ?> <span class="asterisk">*</span></label>
                        <div class="input-group">
                            <select class="form-control statusDropdown" name="status_id">
                                @isset($statuses)
                                @foreach($statuses as $status)
                                @if (canSetStatus($status))
                                <option value="{{$status->id}}" data-color="{{$status->color}}" {{ old('status') == $status->id ? "selected" : "" }}>{{$status->title}} ({{$status->color}})</option>
                                @endif
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" class="openCreateStatusModal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_status', 'Create status') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/status/manage" target="_blank"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_statuses', 'Manage statuses') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label"><?= get_label('priority', 'Priority') ?></label>
                        <div class="input-group">
                            <select class="form-select" name="priority_id">
                                @isset($priorities)
                                @foreach($priorities as $priority)
                                <option value="{{$priority->id}}" class="badge bg-label-{{$priority->color}}" {{ old('priority') == $priority->id ? "selected" : "" }}>{{$priority->title}} ({{$priority->color}})</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" class="openCreatePriorityModal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_priority', 'Create Priority') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/priority/manage" target="_blank"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_priorities', 'Manage Priorities') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="budget" class="form-label"><?= get_label('budget', 'Budget') ?></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">{{$general_settings['currency_symbol']}}</span>
                            <input class="form-control" type="text" id="budget" name="budget" placeholder="<?= get_label('please_enter_budget', 'Please enter budget') ?>" value="{{ old('budget') }}">
                        </div>
                        <span class="text-danger error-message"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="start_date"><?= get_label('starts_at', 'Starts at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="start_date" name="start_date" class="form-control" value="">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="due_date"><?= get_label('ends_at', 'Ends at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="end_date" name="end_date" class="form-control" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="">
                            <?= get_label('commande_accessibility', 'Commande Accessibility') ?>
                            <i class='bx bx-info-circle text-primary' data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="" data-bs-original-title="<b>{{get_label('assigned_users','Assigned Users')}}:</b> {{get_label('assigned_users_info','You Will Need to Manually Select Commande Users When Creating Commandes Under This Project.')}} <br><b>{{get_label('project_users','Project Users')}}:</b> {{get_label('project_users_info','When Creating Commandes Under This Project, the Commande Users Selection Will Be Automatically Filled With Project Users.')}}" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                        </label>
                        <div class="input-group">
                            <select class="form-select" name="commande_accessibility">
                                <option value="assigned_users"><?= get_label('assigned_users', 'Assigned Users') ?></option>
                                <option value="project_users"><?= get_label('project_users', 'Project Users') ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label class="form-label" for="user_id"><?= get_label('select_users', 'Select users') ?></label>
                        <div class="input-group">
                            <select class="form-control js-example-basic-multiple" name="user_id[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                @isset($toSelectProjectUsers)
                                @foreach($toSelectProjectUsers as $user)
                                <?php $selected = $user->id == getAuthenticatedUser()->id ? "selected" : "" ?>
                                <option value="{{$user->id}}" {{ (collect(old('user_id'))->contains($user->id)) ? 'selected':'' }} <?= $selected ?>>{{$user->first_name}} {{$user->last_name}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label class="form-label" for="client_id"><?= get_label('select_clients', 'Select clients') ?></label>
                        <div class="input-group">
                            <select class="form-control js-example-basic-multiple" name="client_id[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                @isset($toSelectProjectClients)
                                @foreach ($toSelectProjectClients as $client)
                                <?php $selected = $client->id == getAuthenticatedUser()->id && $auth_user->hasRole('client') ? "selected" : "" ?>
                                <option value="{{$client->id}}" {{ (collect(old('client_id'))->contains($client->id)) ? 'selected':'' }} <?= $selected ?>>{{$client->first_name}} {{$client->last_name}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label class="form-label" for=""><?= get_label('select_tags', 'Select tags') ?></label>
                        <div class="input-group">
                            <select class="form-control tagsDropdown" name="tag_ids[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                @isset($tags)
                                @foreach($tags as $tag)
                                <option value="{{$tag->id}}" data-color="{{$tag->color}}" {{ (collect(old('tag_ids'))->contains($tag->id)) ? 'selected':'' }}>{{$tag->title}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" class="openCreateTagModal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_tag', 'Create tag') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/tags/manage"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_tags', 'Manage tags') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label for="description" class="form-label"><?= get_label('description', 'Description') ?></label>
                        <textarea class="form-control description" rows="5" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('note', 'Note') ?></label>
                        <textarea class="form-control" name="note" rows="3" placeholder="<?= get_label('optional_note', 'Optional Note') ?>"></textarea>
                    </div>
                </div>
                <div class="alert alert-primary" role="alert">
                    <?= get_label('you_will_be_project_participant_automatically', 'You will be project participant automatically.') ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
            </div>
        </form>
    </div>
</div>
@endif
@if (Request::is('projects') || Request::is('projects/list') || Request::is('projects/information/*') || Request::is('home') || Request::is('users/profile/*') || Request::is('clients/profile/*') || Request::is('users') || Request::is('clients'))
<div class="modal fade" id="edit_project_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="/projects/update" class="form-submit-event modal-content" method="POST">
            <input type="hidden" name="id" id="project_id">
            @if (!Request::is('projects') && !Request::is('projects/information/*'))
            <input type="hidden" name="dnr">
            <input type="hidden" name="table" value="projects_table">
            @endif
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><?= get_label('update_project', 'Update Project') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="title" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" name="title" id="project_title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" value="{{ old('title') }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="status"><?= get_label('status', 'Status') ?> <span class="asterisk">*</span></label>
                        <div class="input-group">
                            <select class="form-control statusDropdown" name="status_id" id="project_status_id">
                                @isset($statuses)
                                @foreach($statuses as $status)
                                <option value="{{$status->id}}" data-color="{{$status->color}}" {{ old('status') == $status->id ? "selected" : "" }}>{{$status->title}} ({{$status->color}})</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" class="openCreateStatusModal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_status', 'Create status') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/status/manage" target="_blank"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_statuses', 'Manage statuses') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label"><?= get_label('priority', 'Priority') ?></label>
                        <div class="input-group">
                            <select class="form-select" name="priority_id" id="project_priority_id">
                                @isset($priorities)
                                @foreach($priorities as $priority)
                                <option value="{{$priority->id}}" class="badge bg-label-{{$priority->color}}" {{ old('priority') == $priority->id ? "selected" : "" }}>{{$priority->title}} ({{$priority->color}})</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" class="openCreatePriorityModal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_priority', 'Create Priority') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/priority/manage" target="_blank"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_priorities', 'Manage Priorities') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="budget" class="form-label"><?= get_label('budget', 'Budget') ?></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">{{$general_settings['currency_symbol']}}</span>
                            <input class="form-control" type="text" id="project_budget" name="budget" placeholder="<?= get_label('please_enter_budget', 'Please enter budget') ?>" value="{{ old('budget') }}">
                        </div>
                        <span class="text-danger error-message"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="start_date"><?= get_label('starts_at', 'Starts at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="update_start_date" name="start_date" class="form-control" value="">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="due_date"><?= get_label('ends_at', 'Ends at') ?> <span class="asterisk">*</span></label>
                        <input type="text" id="update_end_date" name="end_date" class="form-control" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="">
                            <?= get_label('commande_accessibility', 'Commande Accessibility') ?>
                            <i class='bx bx-info-circle text-primary' data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="" data-bs-original-title="<b>{{get_label('assigned_users', 'Assigned Users')}}:</b> {{get_label('assigned_users_info','You Will Need to Manually Select Commande Users When Creating Commandes Under This Project.')}}<br><b>{{get_label('project_users', 'Project Users')}}:</b> {{get_label('project_users_info','When Creating Commandes Under This Project, the Commande Users Selection Will Be Automatically Filled With Project Users.')}}" data-bs-toggle="tooltip" data-bs-placement="top"></i>
                        </label>
                        <div class="input-group">
                            <select class="form-select" name="commande_accessibility" id="commande_accessibility">
                                <option value="assigned_users"><?= get_label('assigned_users', 'Assigned Users') ?></option>
                                <option value="project_users"><?= get_label('project_users', 'Project Users') ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label class="form-label" for="user_id"><?= get_label('select_users', 'Select users') ?></label>
                        <div class="input-group">
                            <select class="form-control js-example-basic-multiple" name="user_id[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                @isset($toSelectProjectUsers)
                                @foreach($toSelectProjectUsers as $user)
                                <?php $selected = $user->id == getAuthenticatedUser()->id ? "selected" : "" ?>
                                <option value="{{$user->id}}" {{ (collect(old('user_id'))->contains($user->id)) ? 'selected':'' }} <?= $selected ?>>{{$user->first_name}} {{$user->last_name}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label class="form-label" for="client_id"><?= get_label('select_clients', 'Select clients') ?></label>
                        <div class="input-group">
                            <select class="form-control js-example-basic-multiple" name="client_id[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                @isset($toSelectProjectClients)
                                @foreach ($toSelectProjectClients as $client)
                                <?php $selected = $client->id == getAuthenticatedUser()->id && $auth_user->hasRole('client') ? "selected" : "" ?>
                                <option value="{{$client->id}}" {{ (collect(old('client_id'))->contains($client->id)) ? 'selected':'' }} <?= $selected ?>>{{$client->first_name}} {{$client->last_name}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label class="form-label" for=""><?= get_label('select_tags', 'Select tags') ?></label>
                        <div class="input-group">
                            <select class="form-control tagsDropdown" name="tag_ids[]" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                @isset($tags)
                                @foreach($tags as $tag)
                                <option value="{{$tag->id}}" data-color="{{$tag->color}}" {{ (collect(old('tag_ids'))->contains($tag->id)) ? 'selected':'' }}>{{$tag->title}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="javascript:void(0);" class="openCreateTagModal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_tag', 'Create tag') ?>"><i class="bx bx-plus"></i></button></a>
                            <a href="/tags/manage"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('manage_tags', 'Manage tags') ?>"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label for="description" class="form-label"><?= get_label('description', 'Description') ?></label>
                        <textarea class="form-control description" rows="5" name="description" id="project_description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label class="form-label"><?= get_label('note', 'Note') ?></label>
                        <textarea class="form-control" name="note" id="projectNote" rows="3" placeholder="<?= get_label('optional_note', 'Optional Note') ?>"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <?= get_label('close', 'Close') ?>
                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('update', 'Update') ?></button>
            </div>
        </form>
    </div>
</div>
@endif
@if (Request::is('projects') || Request::is('projects/list') || Request::is('home') || Request::is('users/profile/*') || Request::is('clients/profile/*') || Request::is('commandes') || Request::is('commandes/draggable') || Request::is('projects/information/*'))
<div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><span id="typePlaceholder"></span> <?= get_label('quick_view', 'Quick View') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5 id="quickViewTitlePlaceholder" class="text-muted"></h5>
                <div class="nav-align-top">
                    <ul class="nav nav-tabs" role="tablist">

                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-quick-view-users" aria-controls="navs-top-quick-view-users">
                                <i class="menu-icon tf-icons bx bx-group text-primary"></i><?= get_label('users', 'Users') ?>
                            </button>
                        </li>


                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-quick-view-clients" aria-controls="navs-top-quick-view-clients">
                                <i class="menu-icon tf-icons bx bx-group text-warning"></i><?= get_label('clients', 'Clients') ?>
                            </button>
                        </li>

                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-quick-view-description" aria-controls="navs-top-quick-view-description">
                                <i class="menu-icon tf-icons bx bx-notepad text-success"></i><?= get_label('description', 'Description') ?>
                            </button>
                        </li>
                    </ul>
                    <input type="hidden" id="type">
                    <input type="hidden" id="typeId">
                    <div class="tab-content">

                        <div class="tab-pane fade active show" id="navs-top-quick-view-users" role="tabpanel">
                            <div class="table-responsive text-nowrap">
                                <!-- <input type="hidden" id="data_type" value="users">
                                <input type="hidden" id="data_table" value="usersTable"> -->
                                <table id="usersTable" data-toggle="table" data-loading-template="loadingTemplate" data-url="/users/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParamsUsersClients">
                                    <thead>
                                        <tr>
                                            <th data-checkbox="true"></th>
                                            <th data-sortable="true" data-field="id"><?= get_label('id', 'ID') ?></th>
                                            <th data-formatter="userFormatter" data-sortable="true" data-field="first_name"><?= get_label('users', 'Users') ?></th>
                                            <th data-field="role"><?= get_label('role', 'Role') ?></th>
                                            <th data-field="phone" data-sortable="true" data-visible="false"><?= get_label('phone_number', 'Phone number') ?></th>
                                            <th data-field="assigned"><?= get_label('assigned', 'Assigned') ?></th>
                                            <th data-sortable="true" data-field="created_at" data-visible="false"><?= get_label('created_at', 'Created at') ?></th>
                                            <th data-sortable="true" data-field="updated_at" data-visible="false"><?= get_label('updated_at', 'Updated at') ?></th>
                                            {{-- <th data-formatter="actionFormatterUsers"><?= get_label('actions', 'Actions') ?></th> --}}
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>


                        <div class="tab-pane fade " id="navs-top-quick-view-clients" role="tabpanel">
                            <div class="table-responsive text-nowrap">
                                <!-- <input type="hidden" id="data_type" value="clients">
                            <input type="hidden" id="data_table" value="clientsTable"> -->
                                <table id="clientsTable" data-toggle="table" data-loading-template="loadingTemplate" data-url="/clients/list" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParamsUsersClients">
                                    <thead>
                                        <tr>
                                            <th data-checkbox="true"></th>
                                            <th data-sortable="true" data-field="id"><?= get_label('id', 'ID') ?></th>
                                            <th data-formatter="clientFormatter" data-sortable="true"><?= get_label('client', 'Client') ?></th>
                                            <th data-field="company" data-sortable="true" data-visible="false"><?= get_label('company', 'Company') ?></th>
                                            <th data-field="phone" data-sortable="true" data-visible="false"><?= get_label('phone_number', 'Phone number') ?></th>
                                            <th data-field="assigned"><?= get_label('assigned', 'Assigned') ?></th>
                                            <th data-sortable="true" data-field="created_at" data-visible="false"><?= get_label('created_at', 'Created at') ?></th>
                                            <th data-sortable="true" data-field="updated_at" data-visible="false"><?= get_label('updated_at', 'Updated at') ?></th>
                                            {{--<th data-formatter="actionFormatterClients"><?= get_label('actions', 'Actions') ?></th> --}}
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="navs-top-quick-view-description" role="tabpanel">
                            <p class="pt-3" id="quickViewDescPlaceholder"></p>
                        </div>
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
@endif
<div class="modal fade" id="createWorkspaceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{get_label('create_workspace', 'Create Workspace')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('/workspaces/store')}}" class="form-submit-event" method="POST">
                <input type="hidden" name="dnr">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                            <input class="form-control" type="text" id="title" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" value="{{ old('title') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            @include('partials.select', ['label' => get_label('select_users', 'Select users'), 'name' => 'user_ids[]', 'items' => $toSelectWorkspaceUsers??[], 'authUserId' => $auth_user->id])
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            @include('partials.select', ['label' => get_label('select_clients', 'Select clients'), 'name' => 'client_ids[]', 'items' => $toSelectWorkspaceClients??[], 'authUserId' => $auth_user->id])
                        </div>
                    </div>
                    @if(isAdminOrHasAllDataAccess())
                    <div class="row">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <label class="form-check-label" for="primaryWorkspace">
                                    <input class="form-check-input" type="checkbox" name="primaryWorkspace" id="primaryWorkspace">
                                    <?= get_label('primary_workspace', 'Primary Workspace') ?>?
                                </label>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="alert alert-primary alert-dismissible" role="alert">
                        <?= get_label('you_will_be_workspace_participant_automatically', 'You will be workspace participant automatically.') ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= get_label('close', 'Close') ?></button>
                    <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('create', 'Create') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editWorkspaceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{get_label('update_workspace', 'Update Workspace')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('/workspaces/update')}}" class="form-submit-event" method="POST">
                <input type="hidden" name="id" id="workspace_id">
                <input type="hidden" name="dnr">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                            <input class="form-control" type="text" name="title" id="workspace_title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>" value="{{ old('title') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            @include('partials.select', ['label' => get_label('select_users', 'Select users'), 'name' => 'user_ids[]', 'items' => $toSelectWorkspaceUsers??[], 'authUserId' => $auth_user->id])
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            @include('partials.select', ['label' => get_label('select_clients', 'Select clients'), 'name' => 'client_ids[]', 'items' => $toSelectWorkspaceClients??[], 'authUserId' => $auth_user->id])
                        </div>
                    </div>
                    @if(isAdminOrHasAllDataAccess())
                    <div class="row">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <label class="form-check-label" for="updatePrimaryWorkspace">
                                    <input class="form-check-input" type="checkbox" name="primaryWorkspace" id="updatePrimaryWorkspace">
                                    <?= get_label('primary_workspace', 'Primary Workspace') ?>?
                                </label>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= get_label('close', 'Close') ?></button>
                    <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('update', 'Update') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
@if (Request::is('meetings'))
<div class="modal fade" id="createMeetingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{get_label('create_meeting', 'Create Meeting')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('/meetings/store')}}" class="form-submit-event" method="POST">
                <input type="hidden" name="dnr">
                <input type="hidden" name="table" value="meetings_table">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                            <input class="form-control" type="text" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for=""><?= get_label('starts_at', 'Starts at') ?> <span class="asterisk">*</span></label>
                            <input type="text" id="start_date" name="start_date" class="form-control" value="">
                        </div>
                        <div class="mb-3 col-md-2">
                            <label class="form-label" for=""><?= get_label('time', 'Time') ?> <span class="asterisk">*</span></label>
                            <input type="time" name="start_time" class="form-control">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="end_date_time"><?= get_label('ends_at', 'Ends at') ?> <span class="asterisk">*</span></label>
                            <input type="text" id="end_date" name="end_date" class="form-control" value="">
                        </div>
                        <div class="mb-3 col-md-2">
                            <label class="form-label" for=""><?= get_label('time', 'Time') ?> <span class="asterisk">*</span></label>
                            <input type="time" name="end_time" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            @include('partials.select', ['label' => get_label('select_users', 'Select users'), 'name' => 'user_ids[]', 'items' => $users??[], 'authUserId' => $auth_user->id])
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            @include('partials.select', ['label' => get_label('select_clients', 'Select clients'), 'name' => 'client_ids[]', 'items' => $clients??[], 'authUserId' => $auth_user->id, 'for' => 'clients'])
                        </div>
                    </div>
                    <div class="alert alert-primary alert-dismissible" role="alert">
                        <?= get_label('you_will_be_meeting_participant_automatically', 'You will be meeting participant automatically.') ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= get_label('close', 'Close') ?></button>
                    <button type="submit" id="submit_btn" class="btn btn-primary me-2"><?= get_label('create', 'Create') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editMeetingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{get_label('update_meeting', 'Update Meeting')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('/meetings/update')}}" class="form-submit-event" method="POST">
                <input type="hidden" name="dnr">
                <input type="hidden" name="id" id="meeting_id">
                <input type="hidden" name="table" value="meetings_table">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label"><?= get_label('title', 'Title') ?> <span class="asterisk">*</span></label>
                            <input class="form-control" type="text" id="meeting_title" name="title" placeholder="<?= get_label('please_enter_title', 'Please enter title') ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for=""><?= get_label('starts_at', 'Starts at') ?> <span class="asterisk">*</span></label>
                            <input type="text" id="update_start_date" name="start_date" class="form-control" value="">
                        </div>
                        <div class="mb-3 col-md-2">
                            <label class="form-label" for=""><?= get_label('time', 'Time') ?> <span class="asterisk">*</span></label>
                            <input type="time" id="meeting_start_time" name="start_time" class="form-control">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="end_date_time"><?= get_label('ends_at', 'Ends at') ?> <span class="asterisk">*</span></label>
                            <input type="text" id="update_end_date" name="end_date" class="form-control" value="">
                        </div>
                        <div class="mb-3 col-md-2">
                            <label class="form-label" for=""><?= get_label('time', 'Time') ?> <span class="asterisk">*</span></label>
                            <input type="time" id="meeting_end_time" name="end_time" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            @include('partials.select', ['label' => get_label('select_users', 'Select users'), 'name' => 'user_ids[]', 'items' => $users??[], 'authUserId' => $auth_user->id])
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            @include('partials.select', ['label' => get_label('select_clients', 'Select clients'), 'name' => 'client_ids[]', 'items' => $clients??[], 'authUserId' => $auth_user->id, 'for' => 'clients'])
                        </div>
                    </div>
                    <div class="alert alert-primary alert-dismissible" role="alert">
                        <?= get_label('you_will_be_meeting_participant_automatically', 'You will be meeting participant automatically.') ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= get_label('close', 'Close') ?></button>
                    <button type="submit" id="submit_btn" class="btn btn-primary"><?= get_label('update', 'Update') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@if (Request::is('users') || Request::is('clients') || Request::is('projects/list') || Request::is('projects') || Request::is('commandes') || Request::is('commandes/draggable'))
<div class="modal fade" id="viewAssignedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1"><span id="userPlaceholder"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="nav-align-top">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-view-assigned-projects" aria-controls="navs-top-view-assigned-projects">
                                <i class="menu-icon tf-icons bx bx-briefcase-alt-2 text-success"></i><?= get_label('projects', 'Projects') ?>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-view-assigned-commandes" aria-controls="navs-top-view-assigned-commandes">
                                <i class="menu-icon tf-icons bx bx-commande text-primary"></i><?= get_label('commandes', 'Commandes') ?>
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="navs-top-view-assigned-projects" role="tabpanel">

                        </div>
                        <div class="tab-pane fade" id="navs-top-view-assigned-commandes" role="tabpanel">

                        </div>
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
@endif


@if (Request::is('commandes') || Request::is('commandes/draggable') || Request::is('products/information/*') || Request::is('commandes/draggable/*') || Request::is('commandes/list/*')  || Request::is('users/profile/*') || Request::is('clients/profile/*'))
    <div class="modal fade" id="create_commande_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="/commandes/store" class="form-submit-event modal-content" method="POST">
                @if (!Request::is('commandes/draggable/*') && !Request::is('commandes/draggable') && !Request::is('products/information/*'))
                    <input type="hidden" name="dnr">
                    <input type="hidden" name="table" value="commande_table">
                @endif

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">{{ get_label('create_commande', 'Create Commande') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="title" class="form-label">{{ get_label('title', 'Title') }} <span class="asterisk">*</span></label>
                            <input class="form-control" type="text" name="title" placeholder="{{ get_label('please_enter_title', 'Please enter title') }}" value="{{ old('title') }}">
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="status">{{ get_label('status', 'Status') }}</label>
                            <div class="input-group">
                                <select class="form-select" id="status" name="status">
                                    <option value="">{{ get_label('select_status', 'Select status') }}</option>
                                    <option value="pending">{{ get_label('pending', 'Pending') }}</option>
                                    <option value="completed">{{ get_label('completed', 'Completed') }}</option>
                                    <option value="cancelled">{{ get_label('cancelled', 'Cancelled') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 col-md-12">
                            <label for="total_amount" class="form-label">{{ get_label('total_amount', 'Total Amount') }}</label>
                            <input class="form-control" type="number" name="total_amount" placeholder="{{ get_label('please_enter_total_amount', 'Please enter total amount') }}" value="{{ old('total_amount') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="start_date">{{ get_label('starts_at', 'Starts at') }} <span class="asterisk">*</span></label>
                            <input type="text" id="commande_start_date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="due_date">{{ get_label('ends_at', 'Ends at') }} <span class="asterisk">*</span></label>
                            <input type="text" id="commande_end_date" name="due_date" class="form-control" value="{{ old('due_date') }}">
                        </div>
                    </div>

                    <!-- <div class="mb-3">
                        <label class="form-label" for="product_ids">Select Products</label>
                        <div class="input-group">
                            <select class="form-control" name="product_ids[]" multiple>
                                <option value=""></option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> -->


                    <div class="mb-3">
                        <label class="form-select" for="product_id">{{ get_label('select_product', 'Select Product') }}</label>
                        <div class="input-group">
                            <select class="form-control" name="product_id">
                                <option value=""></option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>



                    <div class="mb-3">
                        <label class="form-select" for="client_id">{{ get_label('select_client', 'Select Client') }}</label>
                        <div class="input-group">
                            <select class="form-control" name="client_id">
                                <option value=""></option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->first_name }} {{ $client->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-select" for="user_id">{{ get_label('select_user', 'Select User') }}</label>
                        <div class="input-group">
                            <select class="form-control" name="user_id">
                                <option value=""></option>
                                @if(isset($users) && $users->count() > 0)
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                    @endforeach
                                @else
                                    <option value="">No users available</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3">
                            <label for="description" class="form-label">{{ get_label('description', 'Description') }}</label>
                            <textarea class="form-control description" rows="5" name="description" placeholder="{{ get_label('please_enter_description', 'Please enter description') }}">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3">
                            <label class="form-label">{{ get_label('note', 'Note') }}</label>
                            <textarea class="form-control" name="note" rows="3" placeholder="{{ get_label('optional_note', 'Optional Note') }}"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        {{ get_label('close', 'Close') }}
                    </button>
                    <button type="submit" id="submit_btn" class="btn btn-primary">{{ get_label('create', 'Create') }}</button>
                </div>
            </form>
        </div>
    </div>
@endif




