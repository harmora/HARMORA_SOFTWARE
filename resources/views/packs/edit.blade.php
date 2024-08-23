@extends('layout')
@section('title')
    <?= get_label('edit_pack', 'Edit Pack') ?>
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
                        <a href="{{url('/packs')}}"><?= get_label('packs', 'Packs') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= get_label('edit', 'Edit') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('packs/update/'. $pack->id) }}" method="POST" class="form-submit-event" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect_url" value="/packs">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="name" class="form-label"><?= get_label('pack_name', 'Pack Name') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="text" id="name" name="name" placeholder="<?= get_label('please_enter_pack_name', 'Please enter pack name') ?>" value="{{ old('name', $pack->name) }}" >
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="description" class="form-label"><?= get_label('description', 'Description') ?></label>
                        <textarea class="form-control" id="description" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description')?>">{{ old('description', $pack->description) }}</textarea>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="number_of_account" class="form-label"><?= get_label('number_of_account', 'Number of Account') ?> <span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="number_of_accounts" name="number_of_accounts" placeholder="<?= get_label('please_enter_number_of_account', 'Please enter number of account') ?>" value="{{ old('number_of_account', $pack->number_of_accounts) }}" >
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="image" class="form-label"><?= get_label('pack_image', 'Pack Image') ?></label>
                        <div class="d-flex align-items-start align-items-sm-center gap-4 my-3">
                            <img src="{{ $pack->photo ? asset('storage/' . $pack->photo) : asset('storage/packs/default-image.jpg') }}" alt="pack-image" class="d-block rounded" height="100" width="100" id="uploadedImage" />
                            <div class="button-wrapper">
                                <div class="input-group d-flex">
                                    <input type="file" class="form-control" id="inputGroupFile02" name="image">
                                </div>
                                <p class="text-muted mt-2"><?= get_label('allowed_jpg_png', 'Allowed JPG or PNG.') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" id="submit_btn" class="btn btn-primary me-2"><?= get_label('update', 'Update') ?></button>
                    <a href="{{ url('/packs') }}" class="btn btn-outline-secondary"><?= get_label('cancel', 'Cancel') ?></a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
