@extends('layout')
@section('title')
<?= get_label('import', 'Import') ?>
@endsection
@section('content')
<!-- Progress Bar -->
<div class="progress-container">
    <ul class="progressbar">
        <li class="completed"> <?= get_label('upload_file', 'upload file') ?></li>
        <li class="active"><?= get_label('map_columns', 'map columns') ?></li>
        <li><?= get_label('import_data', 'import data') ?></li>
    </ul>
</div>

<div class="container-fluid">
    <!-- Sheet Selection -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="form-group">
                <label for="sheet_selector"><?= get_label('select_sheet', 'Select Sheet') ?></label>
                <select class="form-control" id="sheet_selector" name="selected_sheet">
                    @foreach($sheets as $index => $sheetName)
                        <option value="{{ $index }}" {{ $selectedSheet == $index ? 'selected' : '' }}>
                            {{ $sheetName }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <form action="{{ route('import.step2') }}" method="POST" id="mapping_form">
        @csrf
        <input type="hidden" name="path" value="{{ $path }}">
        <input type="hidden" name="table" value="{{ $table }}">
        <input type="hidden" name="selected_sheet" id="selected_sheet_input" value="{{ $selectedSheet }}">

        <div class="row" id="mapping_container">
            @foreach(['requiredColumns' => $requiredColumns, 'dbColumns' => $dbColumns] as $type => $columns)
                @foreach($columns as $dbColumn)
                    @include('partials.column_card', [
                        'dbColumn' => $dbColumn,
                        'headings' => $headings,
                        'isRequired' => $type === 'requiredColumns'
                    ])
                @endforeach
            @endforeach
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary"><?= get_label('proceed_next_step', 'Proceed to the next step') ?></button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sheetSelector = document.getElementById('sheet_selector');
    const mappingForm = document.getElementById('mapping_form');
    const selectedSheetInput = document.getElementById('selected_sheet_input');
    const mappingContainer = document.getElementById('mapping_container');

    sheetSelector.addEventListener('change', function() {
        const selectedSheet = this.value;
        selectedSheetInput.value = selectedSheet;

        // Fetch headers for the selected sheet
        fetch(`{{ route('import.get-headers') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                path: '{{ $path }}',
                sheet: selectedSheet
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update all select elements with new headers
                const selects = document.querySelectorAll('.column-mapping-select');
                selects.forEach(select => {
                    const currentValue = select.value;
                    select.innerHTML = '<option value=""><?= get_label('select_column', 'Select Column') ?></option>';
                    
                    data.headings.forEach(heading => {
                        const option = document.createElement('option');
                        option.value = heading;
                        option.textContent = heading;
                        option.selected = heading === currentValue;
                        select.appendChild(option);
                    });
                });
            } else {
                alert(data.message || 'Error loading sheet headers');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading sheet headers');
        });
    });
});
</script>
@endpush

<style>
    .progress-container {
        width: 100%;
        margin: 20px 0;
    }
    
    .progressbar {
        counter-reset: step;
        display: flex;
        justify-content: space-between;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .progressbar li {
        text-align: center;
        position: relative;
        width: 100%;
        color: gray;
        text-transform: uppercase;
        font-size: 12px;
    }
    
    .progressbar li::before {
        counter-increment: step;
        content: counter(step);
        width: 30px;
        height: 30px;
        border: 2px solid gray;
        display: block;
        text-align: center;
        margin: 0 auto 10px auto;
        border-radius: 50%;
        background-color: white;
        line-height: 30px;
    }
    
    .progressbar li.active::before, .progressbar li.completed::before {
        border-color: green;
    }
    
    .progressbar li.completed::before {
        content: '\f00c';
        font-family: FontAwesome;
        color: white;
        background-color: green;
    }
    
    .progressbar li.active {
        color: green;
    }
    
    .progressbar li.completed + li::after {
        background-color: green;
    }
    
    .progressbar li::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 2px;
        background-color: gray;
        top: 15px;
        left: 0%;
        z-index: -1;
        transform: translateX(-50%);
    }
    
    .progressbar li:first-child::after {
        content: none;
    }
</style>
@endsection