
@extends('layout')

@section('title')
<?= get_label('import', 'Import') ?>
@endsection

@section('content')
    <!-- Progress Bar -->
    <div class="progress-container">
        <ul class="progressbar">
            <li class="active"> <?= get_label('upload_file', 'upload file') ?></li>
            <li><?= get_label('map_columns', 'map columns') ?></li>
            <li><?= get_label('import_data', 'import data') ?></li>
        </ul>
    </div>
<div class="container-fluid ">

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('import.step1') }}" method="POST"  enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="table"><?= get_label('select_table', 'Select table') ?> </label>
                            <select name="table" id="table" class="form-control" >
                                <option value=""><?= get_label('select_table', 'Select table') ?></option>
                                <option value="fournisseurs"><?= get_label('Suppliers', 'Suppliers') ?></option>
                                {{-- <option value="achats">Achats</option> --}}
                                <option value="products"><?= get_label('products', 'products') ?></option>
                                <option value="clients"><?= get_label('clients', 'clients') ?></option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fileInput" class="form-label"><?= get_label('choix', 'selectt') ?></label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="fileInput" name="file" >
                                <label class="input-group-text" for="fileInput"><?= get_label('parcourir', 'parcourir') ?></label>
                            </div>
                            <div id="fileNameDisplay" class="form-text mt-2"><?= get_label('no_file_selected', 'No file selected') ?></div>

                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary"><?= get_label('proceed_next_step', 'Proceed to the next step') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.progress-container {
    width: 100%;
    margin: 20px 0;
}

.progressbar {
    counter-reset: step;
    display: flex;
    justify-content: space-between;
    list-style: none;
    padding: 0;
    margin: 0;
}

.progressbar li {
    text-align: center;
    position: relative;
    width: 100%;
    color: gray;
    text-transform: uppercase;
    font-size: 12px;
}

.progressbar li::before {
    counter-increment: step;
    content: counter(step);
    width: 30px;
    height: 30px;
    border: 2px solid gray;
    display: block;
    text-align: center;
    margin: 0 auto 10px auto;
    border-radius: 50%;
    background-color: white;
    line-height: 30px;
}

.progressbar li.active::before, .progressbar li.completed::before {
    border-color: green;
}

.progressbar li.completed::before {
    content: '\f00c'; /* FontAwesome check-circle */
    font-family: FontAwesome;
    color: white;
    background-color: green;
}

.progressbar li.active {
    color: green;
}

.progressbar li.active + li::after, .progressbar li.completed + li::after {
    background-color: gray;
}

.progressbar li::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 2px;
    background-color: gray;
    top: 15px;
    left: 0%;
    z-index: -1;
    transform: translateX(-50%);

}

.progressbar li:first-child::after {
    content: none;
}

</style>

@endsection
