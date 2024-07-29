@extends('layout')
@section('title')
<?= get_label('disponibility', 'Disponibility') ?>
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
                    <li class="breadcrumb-item active">
                        <?= get_label('disponibility', 'Disponibility') ?>
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#createreservationmodal"><button type="button" class="btn btn-sm btn-primary " data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('add_reservation', 'Add Reservation') ?>"><i class='bx bx-plus'></i></button></a>
            {{-- action_create_meetings --}}
        </div>
    </div>
    {{-- <x-meetings-card :meetings="$meetings" :users="$users" :clients="$clients" /> --}}
    <x-disponibility-card/>
</div>
@endsection


