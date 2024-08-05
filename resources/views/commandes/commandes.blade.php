@extends('layout')
@section('title')
<?= get_label('commandes', 'Commandes') ?> - <?= get_label('list_view', 'List view') ?>
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
                    @isset($product->id)
                    <li class="breadcrumb-item">
                        <a href="{{url('/'.getUserPreferences('products', 'default_view'))}}"><?= get_label('products', 'Products') ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{url('/products/information/'.$product->id)}}">{{$product->title}}</a>
                    </li>
                    @endisset
                    <li class="breadcrumb-item active"><?= get_label('commandes', 'Commandes') ?></li>
                </ol>
            </nav>
        </div>
        <div>
            @php
            $commandeDefaultView = getUserPreferences('commandes', 'default_view');
            @endphp
            @if (!$commandeDefaultView || $commandeDefaultView === 'commandes')
            <span class="badge bg-primary"><?= get_label('default_view', 'Default View') ?></span>
            @else
            <a href="javascript:void(0);"><span class="badge bg-secondary" id="set-default-view" data-type="commandes" data-view="list"><?= get_label('set_as_default_view', 'Set as Default View') ?></span></a>
            @endif
        </div>
        <div>
            @php
            $productId = isset($product->id) ? $product->id : (request()->has('product') ? request('product') : '');
            $url = isset($product->id) || request()->has('product') ? '/products/commandes/draggable/' . $productId : '/commandes/draggable';
            if (request()->has('status')) {
            $url .= '?status=' . request()->status;
            }
            @endphp
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_commande_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_commande', 'Create commande') ?>"><i class="bx bx-plus"></i></button></a>
            <a href="{{ $url }}"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('draggable', 'Draggable') ?>"><i class="bx bxs-dashboard"></i></button></a>
            
        </div>
    </div>
    <?php
    $id = isset($product->id) ? 'product_' . $product->id : '';
    ?>

</div>
@endsection
