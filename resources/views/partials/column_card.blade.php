<div class="col-md-4 mb-4">
    <div class="card h-100 text-center d-flex flex-column justify-content-center align-items-center position-relative">
        <div class="card-header w-100">
            <h5 class="card-title"><?= get_label($dbColumn, $dbColumn) ?>@if($isRequired)<span class="asterisk">*</span>@endif</h5>
            <!-- Checkbox in the top-right corner -->
            <div class="form-check position-absolute" style="top: 10px; right: 10px;">
                <input type="checkbox" class="form-check-input" name="save_columns[]" value="{{ $dbColumn }}" id="save_{{ $dbColumn }}" @if($isRequired) checked required @endif>
                <label class="form-check-label" for="save_{{ $dbColumn }}"></label>
            </div>
        </div>
        <div class="card-body w-100">
            <div class="form-group">
                <select name="mappings[{{ $dbColumn }}]" id="mappings[{{ $dbColumn }}]" class="form-control text-center" @if($isRequired) required @endif>
                    <option value=""><?= get_label('select_excel_column', 'chose Excel Colulmn') ?> </option>
                    @foreach($headings as $index => $heading)
                    <option value="{{ $index }}" class="text-center">{{ $heading }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
