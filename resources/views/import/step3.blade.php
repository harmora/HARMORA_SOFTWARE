@extends('layout')
@section('title')
<?= get_label('import', 'Import') ?>
@endsection
@section('content')
<!-- Progress Bar -->
<div class="progress-container">
    <ul class="progressbar">
        <li class="completed"><i class="fas fa-check-circle"></i><?= get_label('upload_file', 'upload file') ?></li>
        <li class="completed"><i class="fas fa-check-circle"></i> <?= get_label('map_columns', 'map columns') ?></li>
        <li class="active"><?= get_label('import_data', 'import data') ?></li>
    </ul>
</div>
<div class="container-fluid">
    <div class="card">
        <div class="card-header text-center">
            <h4><?= get_label('review_confirm_data', 'Review and Confirm Data') ?></h4>
        </div>
        <div class="card-body">
            <form action="{{ route('import.save') }}" method="POST">
                @csrf
                <input type="hidden" name="path" value="{{ $path }}">
                <input type="hidden" name="mappings" value="{{ json_encode($mappings) }}">
                <input type="hidden" name="save_columns" value="{{ json_encode($saveColumns) }}">
                <input type="hidden" name="table" value="{{ $table }}">


                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="thead-dark">
                            <tr>
                                @foreach($mappings as $dbColumn => $excelIndex)
                                    @if(in_array($dbColumn, $saveColumns))
                                        <th><?= get_label($dbColumn, $dbColumn) ?></th>
                                    @endif
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rows as $rowIndex => $row)
                                @if(!empty(array_filter($row)))
                                    <tr>
                                        @foreach($mappings as $dbColumn => $excelIndex)
                                            @if(in_array($dbColumn, $saveColumns))
                                                <td>
                                                    {{ $row[$excelIndex] ?? '' }}
                                                    <input type="hidden" name="data[{{ $rowIndex }}][{{ $dbColumn }}]" value="{{ $row[$excelIndex] ?? '' }}">
                                                </td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success">Save to Database</button>
                </div>
            </form>
        </div>
    </div>
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
    
    .progressbar li.active + li::after, .progressbar li.completed + li::after {
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
