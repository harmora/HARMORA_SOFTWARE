<form action="{{ route('import.save') }}" method="POST">
    @csrf
    <table>
        <thead>
            <tr>
                @foreach($mappings as $db_column)
                    <th>{{ $db_column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    @foreach($mappings as $index => $db_column)
                        <td>{{ $row[$index] }}</td>
                        <input type="hidden" name="data[{{ $loop->parent->index }}][{{ $db_column }}]" value="{{ $row[$index] }}">
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    <button type="submit">Save</button>
</form>
