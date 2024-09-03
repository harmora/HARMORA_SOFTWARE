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

<!-- Your form code for mapping columns goes here -->

<div class="container-fluid">
    <form action="{{ route('import.step2') }}" method="POST">
        @csrf
        <input type="hidden" name="path" value="{{ $path }}">
        <input type="hidden" name="table" value="{{ $table }}">

        <div class="row">
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
        content: '\f00c'; /* FontAwesome check-circle */
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
