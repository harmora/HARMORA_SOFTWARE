@extends('layout')

@section('content')
<div class="container-fluid mt-3">
    <form action="{{ route('import.step2') }}" method="POST">
        @csrf
        <input type="hidden" name="path" value="{{ $path }}">

        <div class="row">
            @foreach($dbColumns as $dbColumn)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center d-flex flex-column justify-content-center align-items-center">
                        <div class="card-header w-100 d-flex justify-content-between align-items-center">
                            <h5 class="card-title flex-grow-1 text-center mb-0">{{ ucfirst($dbColumn) }}</h5>
                            <!-- Checkbox aligned to the right -->
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="save_columns[]" value="{{ $dbColumn }}" id="save_{{ $dbColumn }}">
                                <label class="form-check-label" for="save_{{ $dbColumn }}"></label>
                            </div>
                        </div>
                        <div class="card-body w-100">
                            <div class="form-group">
                                <select name="mappings[{{ $dbColumn }}]" id="mappings[{{ $dbColumn }}]" class="form-control text-center" required>
                                    <option value="">Select Excel Column</option>
                                    @foreach($headings as $index => $heading)
                                        <option value="{{ $index }}" class="text-center">{{ $heading }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Next</button>
        </div>
    </form>
</div>
@endsection

@section('styles')
<style>
    /* Center the text inside the select options */
    select.text-center option {
        text-align: center;
    }
    /* Ensure the title and checkbox are on the same line */
    .card-header {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
</style>
@endsection
