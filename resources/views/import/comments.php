@extends('layout')
@section('title')
<?= get_label('fimport_ournisseurs', 'Import Fournisseurs') ?>
@endsection
@php
$visibleColumns = getUserPreferences('fournisseurs');
@endphp
@section('content')
<div class="container-fluid mt-3">
    <form action="{{ route('import.step1') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="   " name="file" required>
        <button type="submit">Proceed to Next Step</button>
    </form>
</div>
@endsection

<!-- step2 -->
@extends('layout')
@section('title')
<?= get_label('fimport_ournisseurs', 'Import Fournisseurs') ?>
@endsection
@section('content')
<div class="container-fluid mt-3">
    <form action="{{ route('import.step2') }}" method="POST">
        @csrf
        <input type="hidden" name="path" value="{{ $path }}">
        @foreach($data as $index => $heading)
            <label>{{ $heading }}</label>
            <select name="mappings[{{ $index }}]" required>
                <option value="">-- Select DB Column --</option>
                <option value="name">Name</option>
                <option value="email">Email</option>
                <option value="phone">Phone</option>
                <option value="city">City</option>
                <option value="country">country</option>
            </select>
            <br>
        @endforeach
        <button type="submit">Next</button>
    </form>
</div>
@endsection
<!-- ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->