@extends('layout')

@section('title')
<?= get_label('fimport_ournisseurs', 'Import Fournisseurs') ?>
@endsection

@section('content')
<div class="container-fluid mt-3">
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-pills nav-justified">
                <li class="nav-item">
                    <a class="nav-link active" href="#">1. Sélection du fichier</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">2. Configuration</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">3. Importation</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('import.step1') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="table">Select Table</label>
                            <select name="table" id="table" class="form-control" required>
                                <option value="">Choose a table</option>
                                <option value="fournisseurs">Fournisseurs</option>
                                <option value="achats">Achats</option>
                                <option value="products">Products</option>
                                <option value="clients">Clients</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fileInput" class="form-label">Choisir un fichier</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="fileInput" name="file" required>
                                <label class="input-group-text" for="fileInput">Parcourir</label>
                            </div>
                            <div id="fileNameDisplay" class="form-text mt-2">Aucun fichier choisi</div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Proceed to Next Step</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

