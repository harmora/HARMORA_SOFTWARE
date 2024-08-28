@extends('layout')
@section('title')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('import.step2') }}" method="POST" class="form-submit-event" enctype="multipart/form-data">
                <input type="hidden" name="redirect_url" value="/fournisseurs">
                @csrf
                @foreach($headings as $index => $heading)
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
    </div>
</div>
@endsection
