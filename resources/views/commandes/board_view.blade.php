@extends('layout')

@section('title', 'Commandes - Draggable')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/home') }}">Home</a>
                    </li>
                    @if (isset($product->id))
                    <li class="breadcrumb-item">
                        <a href="{{ url('/' . getUserPreferences('products', 'default_view')) }}">Products</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ url('/products/information/' . $product->id) }}">{{ $product->title }}</a>
                    </li>
                    @endisset
                    <li class="breadcrumb-item active">
                        Commandes
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            @php
            $commandeDefaultView = getUserPreferences('commandes', 'default_view');
            @endphp
            @if ($commandeDefaultView === 'commandes/draggable')
            <span class="badge bg-primary">Default View</span>
            @else
            <a href="javascript:void(0);"><span class="badge bg-secondary" id="set-default-view" data-type="commandes" data-view="draggable">Set as Default View</span></a>
            @endif
        </div>
        <div>
            @php
            $url = isset($product->id) ? '/products/commandes/list/' . $product->id : '/commandes';
            if (request()->has('status')) {
                $url .= '?status=' . request()->status;
            }
            @endphp
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_commande_modal">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Create Commande">
                    <i class="bx bx-plus"></i>
                </button>
            </a>
            <a href="{{ $url }}">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="List View">
                    <i class="bx bx-list-ul"></i>
                </button>
            </a>
        </div>
    </div>
    @if ($total_commandes > 0)
    <div class="alert alert-primary alert-dismissible" role="alert">
        Drag and drop to update commande status!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div class="d-flex card flex-row" style="overflow-x: scroll; overflow-y: hidden;">
        @foreach ($statuses as $status)
        <div class="my-4" style="min-width: 300px; max-width: 300px;">
            <h4 class="fw-bold mx-4 my-2">{{ $status->title }}</h4>
            <div class="row m-2 d-flex flex-column" id="{{ $status->slug }}" style="height: 100%;" data-status="{{ $status->id }}">
                @foreach ($commandes as $commande)
                @if ($commande->status_id == $status->id)
                <x-kanban :commande="$commande" />
                @endif
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
    @else
    <x-empty-state-card type="Commandes" />
    @endif
</div>

<script>
    var statusArray = @json($statuses);
</script>
<script src="{{ asset('assets/js/pages/commande-board.js') }}"></script>
@endsection
