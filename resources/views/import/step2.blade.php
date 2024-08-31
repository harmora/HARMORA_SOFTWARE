@extends('layout')

@section('content')
<div class="container-fluid mt-3">
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
</style>
@endsection
