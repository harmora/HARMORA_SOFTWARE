@php
use App\Models\Workspace;
$auth_user = getAuthenticatedUser();
$roles = \Spatie\Permission\Models\Role::where('name', '!=', 'admin')->get();
@endphp
@extends('layout')
@section('title')
<?= get_label('edit_commande', 'Edit Commande') ?>
@endsection
@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-2 mt-2">
    <nav aria-label="breadcrumb" class="mb-0">
            <ol class="breadcrumb breadcrumb-style1 mb-0">
                <li class="breadcrumb-item">
                    <a href="{{url('/home')}}"><?= get_label('home', 'Home') ?></a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{url('/commandes')}}"><?= get_label('commandes', 'Commandes') ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <?= get_label('edit', 'Edit') ?>
                </li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
        <form action="{{ route('commandes.update', $commande->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $commande->title) }}" required>
            </div>
            <div class="mb-3">
    <label for="client_id" class="form-label">{{ get_label('select_client', 'Select Client') }}</label>
    <select class="form-control" name="client_id" required>
        <option value="">Select Client</option>
        @foreach($clients as $client)
            <option value="{{ $client->id }}" {{ old('client_id', $commande->client_id) == $client->id ? 'selected' : '' }}>{{ $client->first_name }} {{ $client->last_name }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="user_id" class="form-label">{{ get_label('select_user', 'Select User') }}</label>
    <select class="form-control" name="user_id">
        <option value="">Select User</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}" {{ old('user_id', $commande->user_id) == $user->id ? 'selected' : '' }}>{{ $user->first_name }} {{ $user->last_name }}</option>
        @endforeach
    </select>
</div>

            <div class="form-group">
                <label for="status">Status</label>
                <input type="text" name="status" class="form-control" value="{{ old('status', $commande->status) }}" required>
            </div>

            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $commande->due_date) }}" required>
            </div>

            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ old('stat_date', $commande->start_date) }}" required>
            </div>            

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control">{{ old('description', $commande->description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="products">Products</label>
                <div id="product-fields">
                    @foreach($commande->products as $product)
                        <div class="product-field">
                            <select name="products[{{ $loop->index }}][product_id]" class="form-control" required>
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                <!-- Add other products as options if needed -->
                            </select>
                            <input type="number" name="products[{{ $loop->index }}][quantity]" class="form-control" value="{{ $product->pivot->quantity }}" required>
                            <input type="number" name="products[{{ $loop->index }}][price]" class="form-control" value="{{ $product->pivot->price }}" required>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add-product">Add Product</button>
            </div>

            <!-- Add other fields as necessary -->

            <button type="submit" class="btn btn-primary">Update Commande</button>
        </form>
</div>
</div>
    </div>

@endsection

@section('scripts')
document.getElementById('add-product').addEventListener('click', function() {
    let productFields = document.getElementById('product-fields');
    let index = productFields.children.length;

    let newField = document.createElement('div');
    newField.classList.add('product-field');
    newField.innerHTML = `
        <select name="products[${index}][product_id]" class="form-control" required>
            <!-- Add product options here -->
        </select>
        <input type="number" name="products[${index}][quantity]" class="form-control" required>
        <input type="number" name="products[${index}][price]" class="form-control" required>
    `;

    productFields.appendChild(newField);
});

@endsection

