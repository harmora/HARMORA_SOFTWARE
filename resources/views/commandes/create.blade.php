<div class="modal fade show" id="create_task_modal" tabindex="-1" style="display: block; padding-left: 0px;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form action="https://taskify.taskhub.company/tasks/store" class="form-submit-event modal-content" method="POST">
                        <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Create Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <input type="hidden" name="_token" value="eJJt2myaoQD7kxaDWeND0sMkwjqPHBaKdhF6HTDi" autocomplete="off">            <div class="modal-body">
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label for="title" class="form-label">Title <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" name="title" placeholder="Please Enter Title" value="">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="status">Status <span class="asterisk">*</span></label>
                        <select class="form-select statusDropdown select2-hidden-accessible" name="status_id" data-select2-id="select2-data-22-jn4t" tabindex="-1" aria-hidden="true">
                                                                                                                <option value="0" data-color="danger" selected="" data-select2-id="select2-data-24-0dv9">Default (danger)</option>
                                                                                                                <option value="1" data-color="primary">Started (primary)</option>
                                                                                                                <option value="2" data-color="info">On Going (info)</option>
                                                                                                                <option value="59" data-color="warning">In Review (warning)</option>
                                                                                                            </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="select2-data-23-cvrj" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-status_id-34-container" aria-controls="select2-status_id-34-container"><span class="select2-selection__rendered" id="select2-status_id-34-container" role="textbox" aria-readonly="true" title="Default (danger)"><span class="badge bg-label-danger">Default (danger)</span></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                        <div class="mt-2">
                            <a href="javascript:void(0);" class="openCreateStatusModal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" Create Status"><i class="bx bx-plus"></i></button></a>
                            <a href="https://taskify.taskhub.company/status/manage" target="_blank"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Manage Statuses"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Priority</label>
                        <select class="form-select bg-label-secondary" name="priority_id">
                                                                                    <option value="0" class="badge bg-label-secondary" selected="">Default (secondary)</option>
                                                                                </select>
                        <div class="mt-2">
                            <a href="javascript:void(0);" class="openCreatePriorityModal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" Create priority"><i class="bx bx-plus"></i></button></a>
                            <a href="https://taskify.taskhub.company/priority/manage" target="_blank"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Manage Priorities"><i class="bx bx-list-ul"></i></button></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                                            <div class="mb-3">
                            <label class="form-label" for="user_id">Select Project <span class="asterisk">*</span></label>
                            <select class="form-control selectTaskProject select2-hidden-accessible" name="project" data-placeholder="Type to Search" data-select2-id="select2-data-27-qog0" tabindex="-1" aria-hidden="true">
                                <option value="" data-select2-id="select2-data-29-6v9e"></option>
                                                                                                
                                                                                            </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="select2-data-28-u492" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-project-tx-container" aria-controls="select2-project-tx-container"><span class="select2-selection__rendered" id="select2-project-tx-container" role="textbox" aria-readonly="true" title="Type to Search"><span class="select2-selection__placeholder">Type to Search</span></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                        </div>
                                    </div>
                <div class="row" id="selectTaskUsers">
                    <div class="mb-3">
                        <label class="form-label" for="user_id">Select Users <span id="users_associated_with_project"></span></label>
                        <select class="form-control js-example-basic-multiple select2-hidden-accessible" name="user_id[]" multiple="" data-placeholder="Type to Search" data-select2-id="select2-data-4-i908" tabindex="-1" aria-hidden="true">
                                                    </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="select2-data-5-cx5y" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--multiple" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="-1" aria-disabled="false"><ul class="select2-selection__rendered" id="select2-user_id-5u-container"></ul><span class="select2-search select2-search--inline"><textarea class="select2-search__field" type="search" tabindex="0" autocorrect="off" autocapitalize="none" spellcheck="false" role="searchbox" aria-autocomplete="list" autocomplete="off" aria-label="Search" aria-describedby="select2-user_id-5u-container" placeholder="Type to Search" style="width: 100%;"></textarea></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="start_date">Starts At</label>
                        <input type="text" id="task_start_date" name="start_date" class="form-control" placeholder="Please Select" autocomplete="off">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="due_date">Ends At</label>
                        <input type="text" id="task_end_date" name="due_date" class="form-control" placeholder="Please Select" autocomplete="off">
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control description" rows="5" name="description" placeholder="Please Enter Description" id="mce_0" style="display: none;" aria-hidden="true"></textarea><div role="application" class="tox tox-tinymce" aria-disabled="false" style="visibility: hidden; height: 250px;"><div class="tox-editor-container"><div data-alloy-vertical-dir="toptobottom" class="tox-editor-header"><div role="group" class="tox-toolbar-overlord" aria-disabled="false"><div role="group" class="tox-toolbar__primary"><div title="" role="toolbar" data-alloy-tabstop="true" tabindex="-1" class="tox-toolbar__group"><button aria-label="Insert/edit link" title="Insert/edit link" type="button" tabindex="-1" class="tox-tbtn" aria-disabled="false" aria-pressed="false" style="width: 34px;"><span class="tox-icon tox-tbtn__icon-wrap"><svg width="24" height="24" focusable="false"><path d="M6.2 12.3a1 1 0 0 1 1.4 1.4l-2 2a2 2 0 1 0 2.6 2.8l4.8-4.8a1 1 0 0 0 0-1.4 1 1 0 1 1 1.4-1.3 2.9 2.9 0 0 1 0 4L9.6 20a3.9 3.9 0 0 1-5.5-5.5l2-2Zm11.6-.6a1 1 0 0 1-1.4-1.4l2-2a2 2 0 1 0-2.6-2.8L11 10.3a1 1 0 0 0 0 1.4A1 1 0 1 1 9.6 13a2.9 2.9 0 0 1 0-4L14.4 4a3.9 3.9 0 0 1 5.5 5.5l-2 2Z" fill-rule="nonzero"></path></svg></span></button></div><div title="" role="toolbar" data-alloy-tabstop="true" tabindex="-1" class="tox-toolbar__group"><button aria-label="Undo" title="Undo" type="button" tabindex="-1" class="tox-tbtn tox-tbtn--disabled" aria-disabled="true" style="width: 34px;"><span class="tox-icon tox-tbtn__icon-wrap"><svg width="24" height="24" focusable="false"><path d="M6.4 8H12c3.7 0 6.2 2 6.8 5.1.6 2.7-.4 5.6-2.3 6.8a1 1 0 0 1-1-1.8c1.1-.6 1.8-2.7 1.4-4.6-.5-2.1-2.1-3.5-4.9-3.5H6.4l3.3 3.3a1 1 0 1 1-1.4 1.4l-5-5a1 1 0 0 1 0-1.4l5-5a1 1 0 0 1 1.4 1.4L6.4 8Z" fill-rule="nonzero"></path></svg></span></button><button aria-label="Redo" title="Redo" type="button" tabindex="-1" class="tox-tbtn tox-tbtn--disabled" aria-disabled="true" style="width: 34px;"><span class="tox-icon tox-tbtn__icon-wrap"><svg width="24" height="24" focusable="false"><path d="M17.6 10H12c-2.8 0-4.4 1.4-4.9 3.5-.4 2 .3 4 1.4 4.6a1 1 0 1 1-1 1.8c-2-1.2-2.9-4.1-2.3-6.8.6-3 3-5.1 6.8-5.1h5.6l-3.3-3.3a1 1 0 1 1 1.4-1.4l5 5a1 1 0 0 1 0 1.4l-5 5a1 1 0 0 1-1.4-1.4l3.3-3.3Z" fill-rule="nonzero"></path></svg></span></button></div><div title="" role="toolbar" data-alloy-tabstop="true" tabindex="-1" class="tox-toolbar__group"><button title="Blocks" aria-label="Blocks" aria-haspopup="true" type="button" tabindex="-1" unselectable="on" class="tox-tbtn tox-tbtn--select tox-tbtn--bespoke" aria-expanded="false" style="user-select: none; width: auto;"><span class="tox-tbtn__select-label">Paragraph</span><div class="tox-tbtn__select-chevron"><svg width="10" height="10" focusable="false"><path d="M8.7 2.2c.3-.3.8-.3 1 0 .4.4.4.9 0 1.2L5.7 7.8c-.3.3-.9.3-1.2 0L.2 3.4a.8.8 0 0 1 0-1.2c.3-.3.8-.3 1.1 0L5 6l3.7-3.8Z" fill-rule="nonzero"></path></svg></div></button></div><div role="toolbar" data-alloy-tabstop="true" tabindex="-1" class="tox-toolbar__group"><button aria-label="Reveal or hide additional toolbar items" title="Reveal or hide additional toolbar items" aria-haspopup="true" type="button" tabindex="-1" data-alloy-tabstop="true" class="tox-tbtn" aria-expanded="false"><span class="tox-icon tox-tbtn__icon-wrap"><svg width="24" height="24" focusable="false"><path d="M6 10a2 2 0 0 0-2 2c0 1.1.9 2 2 2a2 2 0 0 0 2-2 2 2 0 0 0-2-2Zm12 0a2 2 0 0 0-2 2c0 1.1.9 2 2 2a2 2 0 0 0 2-2 2 2 0 0 0-2-2Zm-6 0a2 2 0 0 0-2 2c0 1.1.9 2 2 2a2 2 0 0 0 2-2 2 2 0 0 0-2-2Z" fill-rule="nonzero"></path></svg></span></button></div></div></div><div class="tox-anchorbar"></div></div><div class="tox-sidebar-wrap"><div class="tox-edit-area"><iframe id="mce_0_ifr" frameborder="0" allowtransparency="true" title="Rich Text Area" class="tox-edit-area__iframe" srcdoc="<!DOCTYPE html><html><head><meta http-equiv=&quot;Content-Type&quot; content=&quot;text/html; charset=UTF-8&quot; /></head><body id=&quot;tinymce&quot; class=&quot;mce-content-body &quot; data-id=&quot;mce_0&quot; aria-label=&quot;Rich Text Area. Press ALT-0 for help.&quot;><br></body></html>"></iframe></div><div role="presentation" class="tox-sidebar"><div data-alloy-tabstop="true" tabindex="-1" class="tox-sidebar__slider tox-sidebar--sliding-closed" style="width: 0px;"><div class="tox-sidebar__pane-container"></div></div></div></div><div class="tox-bottom-anchorbar"></div><div class="tox-statusbar"><div class="tox-statusbar__text-container tox-statusbar__text-container-3-cols tox-statusbar__text-container--flex-start"><div role="navigation" data-alloy-tabstop="true" class="tox-statusbar__path" aria-disabled="false"></div><div class="tox-statusbar__help-text">Press Alt+0 for help</div><div class="tox-statusbar__right-container"><button type="button" tabindex="-1" data-alloy-tabstop="true" class="tox-statusbar__wordcount">0 words</button><span class="tox-statusbar__branding"><a href="https://www.tiny.cloud/powered-by-tiny?utm_campaign=editor_referral&amp;utm_medium=poweredby&amp;utm_source=tinymce&amp;utm_content=v6" rel="noopener" target="_blank" aria-label="Powered by Tiny" tabindex="-1"><svg width="50px" height="16px" viewBox="0 0 50 16" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" clip-rule="evenodd" d="M10.143 0c2.608.015 5.186 2.178 5.186 5.331 0 0 .077 3.812-.084 4.87-.361 2.41-2.164 4.074-4.65 4.496-1.453.284-2.523.49-3.212.623-.373.071-.634.122-.785.152-.184.038-.997.145-1.35.145-2.732 0-5.21-2.04-5.248-5.33 0 0 0-3.514.03-4.442.093-2.4 1.758-4.342 4.926-4.963 0 0 3.875-.752 4.036-.782.368-.07.775-.1 1.15-.1Zm1.826 2.8L5.83 3.989v2.393l-2.455.475v5.968l6.137-1.189V9.243l2.456-.476V2.8ZM5.83 6.382l3.682-.713v3.574l-3.682.713V6.382Zm27.173-1.64-.084-1.066h-2.226v9.132h2.456V7.743c-.008-1.151.998-2.064 2.149-2.072 1.15-.008 1.987.92 1.995 2.072v5.065h2.455V7.359c-.015-2.18-1.657-3.929-3.837-3.913a3.993 3.993 0 0 0-2.908 1.296Zm-6.3-4.266L29.16 0v2.387l-2.456.475V.476Zm0 3.2v9.132h2.456V3.676h-2.456Zm18.179 11.787L49.11 3.676H46.58l-1.612 4.527-.46 1.382-.384-1.382-1.611-4.527H39.98l3.3 9.132L42.15 16l2.732-.537ZM22.867 9.738c0 .752.568 1.075.921 1.075.353 0 .668-.047.998-.154l.537 1.765c-.23.154-.92.537-2.225.537-1.305 0-2.655-.997-2.686-2.686a136.877 136.877 0 0 1 0-4.374H18.8V3.676h1.612v-1.98l2.455-.476v2.456h2.302V5.9h-2.302v3.837Z"></path>
</svg></a></span></div></div><div title="Resize" aria-label="Press the Up and Down arrow keys to resize the editor." data-alloy-tabstop="true" tabindex="-1" class="tox-statusbar__resize-handle"><svg width="10" height="10" focusable="false"><g fill-rule="nonzero"><path d="M8.1 1.1A.5.5 0 1 1 9 2l-7 7A.5.5 0 1 1 1 8l7-7ZM8.1 5.1A.5.5 0 1 1 9 6l-3 3A.5.5 0 1 1 5 8l3-3Z"></path></g></svg></div></div></div><div aria-hidden="true" class="tox-view-wrap" style="display: none;"><div class="tox-view-wrap__slot-container"></div></div><div aria-hidden="true" class="tox-throbber" style="display: none;"></div></div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label class="form-label">Note</label>
                        <textarea class="form-control" name="note" rows="3" placeholder="Optional Note"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close                </button>
                <button type="submit" id="submit_btn" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</div>