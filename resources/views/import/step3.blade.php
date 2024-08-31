@extends('layout')

@section('content')
<div class="container-fluid mt-3">
    <div class="card">
        <div class="card-header text-center">
            <h4>Review and Confirm Data</h4>
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
                                        <th>{{ ucfirst($dbColumn) }}</th>
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
@endsection
