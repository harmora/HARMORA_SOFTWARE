@extends('layout')

@section('title')
<?= get_label('fimport_ournisseurs', 'Import Fournisseurs') ?>
@endsection

@section('content')
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('import.step1') }}" method="POST" enctype="multipart/form-data">
                        @csrf
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

@section('scripts')
<script>
    document.getElementById('fileInput').addEventListener('change', function(e) {
        var fileName = e.target.files[0].name;
        document.getElementById('fileNameDisplay').textContent = fileName;
    });
</script>
@endsection