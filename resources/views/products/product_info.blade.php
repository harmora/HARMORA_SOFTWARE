@extends('layout')
@section('title')
<?= get_label('stock_info', 'Stock Info') ?>
@endsection
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{url('/home')}}"><?= get_label('home', 'Home') ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{url('/products')}}"><?= get_label('products', 'Products') ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <?= $product->name; ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <!-- Product Information -->
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <img src="{{$product->photo ? asset('storage/' . $product->photo) : asset('storage/photos/no-image.jpg')}}" alt="product-image" class="d-block rounded" height="200" width="200" id="uploadedImage" />
                        <h4 class="card-header fw-bold">{{ $product->name }}</h4>

                    </div>
                </div>
                <hr class="my-0" />
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label"><?= get_label('price', 'Price') ?></label>
                            <div class="input-group">
                                <input type="text" name="price" class="form-control" value="{{ $product->price }}" readonly>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="stock"><?= get_label('stock', 'Stock') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="stock" value="{{ $product->stock }}" readonly="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="category"><?= get_label('category', 'Category') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="category" value="{{ $product->category_name ?? '--' }}" readonly="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="stock"><?= get_label('stock deffective', 'Stock Deffective') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="stock" value="{{ $product->stock_defective }}" readonly="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label" for="description"><?= get_label('description', 'Description') ?></label>
                            <div class="input-group input-group-merge">
                                <textarea class="form-control" id="description"  readonly="">{{ $product->description ?? '--' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                    <!-- Depot Information -->
        <div class="card mb-4 shadow-sm" style="border-radius: 0.5rem; background-color: #f8f9fa;">
            <div class="card-header">
                <h5 class="card-title"><?= get_label('depot_info', 'Depot Info') ?></h5>
            </div>
            <div class="card-body">
                @if ($depots->isEmpty())
                    <p><?=  get_label('no_depot_info','No depot information available.')?></p>
                @else
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= get_label('depot_name', 'Depot Name') ?></th>
                                <th><?= get_label('quantity', 'Quantity') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($depots as $depot)
                                <tr>
                                    <td>{{ $depot['name'] }}</td>
                                    <td>{{ $depot['quantity'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>        

        </div>
    </div>
</div>
@endsection
