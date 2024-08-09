@extends('layout')

@section('title')
<?= get_label('update_user', 'Update user') ?>
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
                    <li class="breadcrumb-item">
                        <a href="{{ url('/achats') }}">{{ get_label('achats', 'Achats') }}</a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{ get_label('update', 'Update') }}
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('/achats/update/' . $achat->id) }}" method="POST" class="form-submit-event" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <input type="hidden" name="redirect_url" value="/achats">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="fournisseur_id" class="form-label">{{ get_label('fournisseur', 'Fournisseur') }}<span class="asterisk">*</span></label>
                        <select class="form-select" id="fournisseur_id" name="fournisseur_id" required>
                            <option value="">{{ get_label('select_fournisseur', 'Select Fournisseur') }}</option>
                            @foreach ($fournisseurs as $fournisseur)
                                <option value="{{ $fournisseur->id }}" {{ $achat->fournisseur_id == $fournisseur->id ? 'selected' : '' }}>{{ $fournisseur->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="type_achat" class="form-label">{{ get_label('type', 'Type') }} <span class="asterisk">*</span></label>
                        <select class="form-select" id="type_achat" name="type_achat">
                            <option value="materielle/produits" {{ $achat->type_achat == 'materielle/produits' ? 'selected' : '' }}>{{ get_label('materielle_produits', 'Materielle/Produits') }}</option>
                            <option value="recherche/developpement" {{ $achat->type_achat == 'recherche/developpement' ? 'selected' : '' }}>{{ get_label('recherche_developpement', 'Recherche/Developpement') }}</option>
                            <option value="investissements" {{ $achat->type_achat == 'investissements' ? 'selected' : '' }}>{{ get_label('investissements', 'Investissements') }}</option>
                            <option value="salaires/avantages sociaux" {{ $achat->type_achat == 'salaires/avantages sociaux' ? 'selected' : '' }}>{{ get_label('salaires_avantages_sociaux', 'Salaires/Avantages Sociaux') }}</option>
                            <option value="mainetenances/amélioration" {{ $achat->type_achat == 'mainetenances/amélioration' ? 'selected' : '' }}>{{ get_label('mainetenances_amélioration', 'Mainetenances/Amélioration') }}</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="montant" class="form-label">{{ get_label('montant', 'Montant') }} <span class="asterisk">*</span></label>
                        <input class="form-control" type="number" id="montant" name="montant" step="0.01" placeholder="{{ get_label('please_enter_montant', 'Please enter montant') }}" value="{{ old('montant', $achat->montant) }}" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="status_payement" class="form-label">{{ get_label('status_payement', 'Payment Status') }} <span class="asterisk">*</span></label>
                        <select class="form-select" id="status_payement" name="status_payement" required>
                            <option value="">{{ get_label('select_status', 'Select Status') }}</option>
                            <option value="paid" {{ old('status_payement', $achat->status_payement) == 'paid' ? 'selected' : '' }}>{{ get_label('paid', 'Paid') }}</option>
                            <option value="unpaid" {{ old('status_payement', $achat->status_payement) == 'unpaid' ? 'selected' : '' }}>{{ get_label('unpaid', 'Unpaid') }}</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="tva" class="form-label">{{ get_label('tva', 'TVA') }}</label>
                        <input class="form-control" type="number" id="tva" name="tva" step="0.1" placeholder="{{ get_label('please_enter_tva', 'Please enter TVA') }}" value="{{ old('tva', $achat->tva) }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="facture" class="form-label">{{ get_label('facture', 'Facture') }}</label>
                        <input class="form-control" type="file" id="facture" name="facture" placeholder="{{ get_label('please_enter_facture', 'Please enter invoice number') }}" value="{{ old('facture') }}">
                        @if ($achat->facture)
                            <a href="{{ asset('storage/' . $achat->facture) }}" target="_blank">{{ get_label('current_facture', 'Current Facture') }}</a>
                        @endif
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="date_paiement" class="form-label">{{ get_label('date_paiement', 'Payment Date') }}</label>
                        <input class="form-control" type="date" id="date_paiement" name="date_paiement" value="{{ old('date_paiement', $achat->date_paiement) }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="date_limit" class="form-label">{{ get_label('date_limit', 'Payment Due Date') }}</label>
                        <input class="form-control" type="date" id="date_limit" name="date_limit" value="{{ old('date_limit', $achat->date_limit) }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="reference" class="form-label">{{ get_label('reference', 'Reference') }}</label>
                        <input class="form-control" type="text" id="reference" name="reference" placeholder="{{ get_label('please_enter_reference', 'Please enter reference') }}" value="{{ old('reference', $achat->reference) }}">
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2" id="submit_btn">{{ get_label('update', 'Update') }}</button>
                    <a href="{{ url('/achats') }}" class="btn btn-outline-secondary">{{ get_label('cancel', 'Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
