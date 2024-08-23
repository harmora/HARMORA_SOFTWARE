@extends('layout')
@section('title')
<?= get_label('packs', 'Packs') ?>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/home') }}">{{ get_label('home', 'Home') }}</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= get_label('packs', 'Packs') ?>
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <!-- Button trigger modal -->



            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#createPackModal">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_pack', 'Create Pack') ?>">
                    <i class='bx bx-plus'></i>
                </button>
            </a>

        </div>
    </div>

    @if (is_countable($packs) && count($packs) > 0)
    <div class="row">
        @foreach ($packs as $pack)
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="{{ asset('storage/' . $pack->photo) }}" class="card-img-top" alt="{{ $pack->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $pack->name }}</h5>
                    <p class="card-text">{{ $pack->description }}</p>
                    <p class="card-text"><strong>{{ get_label('number_of_accounts', 'Number of Accounts') }}:</strong> {{ $pack->number_of_accounts }}</p>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ url('/packs/edit/' . $pack->id) }}" class="btn btn-warning btn-sm">
                        <i class='bx bx-edit'></i> <?= get_label('update', 'Update') ?>
                    </a>
                    <form action="{{ url('/packs/destroy/' . $pack->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this pack?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class='bx bx-trash'></i> <?= get_label('delete', 'Delete') ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <?php $type = 'Packs'; ?>
    <x-empty-state-card :type="$type" />
    @endif
</div>

<!-- Modal -->
<div class="modal fade" id="createPackModal" tabindex="-1" aria-labelledby="createPackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPackModalLabel"><?= get_label('create_pack', 'Create Pack') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form  class="form-submit-event" action="{{ url('/packs/store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ get_label('name', 'Name') }}</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">{{ get_label('description', 'Description') }}</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="number_of_accounts" class="form-label">{{ get_label('number_of_accounts', 'Number of Accounts') }}</label>
                        <input type="number" class="form-control" id="number_of_accounts" name="number_of_accounts" required>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">{{ get_label('photo', 'Photo') }}</label>
                        <input type="file" class="form-control" id="photo" name="photo" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ get_label('close', 'Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ get_label('save', 'Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
</script>
<script src="{{ asset('assets/js/pages/packs.js') }}"></script>
@endsection
