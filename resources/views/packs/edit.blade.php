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
            <form action="{{ url('packs/update/'. $pack->id) }}" method="POST" class="form-submit-event">
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

                    <!-- Features Section -->
                    <div class="mb-3 col-md-12">
                        <label for="features" class="form-label"><?= get_label('select_features', 'Select Features') ?></label>
                        <div class="row">
                            @foreach ($features as $feature)
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="features[]" id="feature_{{ $feature->id }}" value="{{ $feature->id }}"
                                        @if (in_array($feature->id, $pack->features->pluck('id')->toArray())) checked @endif>
                                        <label class="form-check-label" for="feature_{{ $feature->id }}">
                                            {{ $feature->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
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
