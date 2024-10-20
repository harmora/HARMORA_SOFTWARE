<div class="mb-3 col-md-6">
    <label for="type_achat" class="form-label"><?= get_label('type', 'Type') ?> <span class="asterisk">*</span></label>
    <select class="form-select" id="type_achat" name="type_achat">
        <option value="Matériel/Produits"><?= get_label('Matériel/Produits', 'Materielle/Products') ?></option>
        <option value="recherche/developpement"><?= get_label('recherche/developpement', 'Research/Development') ?></option>
        <option value="investissements"><?= get_label('investissements', 'Investments') ?></option>
        <option value="salaires/avantages sociaux"><?= get_label('salaires/avantages sociaux', 'Salaries/Social Benefits') ?></option>
        <option value="mainetenances/amélioration"><?= get_label('mainetenances/amélioration', 'Maintenance/Improvement') ?></option>
    </select>
</div>

<div class="mb-3 col-md-6">
    <label for="date_achat" class="form-label"><?= get_label('date_achat', 'Purchase Date') ?></label>
    <input class="form-control" type="date" id="date_achat" name="date_achat" value="{{ old('date_achat') }}">
</div>


<div class="mb-3 col-md-6">
    <label for="montant" class="form-label"><?= get_label('montant', 'Montant total') ?> <span class="asterisk">*</span></label>
<input class="form-control" type="number" id="montant" name="montant" step="0.01" placeholder="<?= get_label('please_enter_montant', 'Please enter montant') ?>" value="{{ old('montant') }}" required>
</div>


<div class="mb-3 col-md-6">
<label for="tva" class="form-label"><?= get_label('tva', 'TVA') ?><span class="asterisk">*</span>   </label>
<input class="form-control" type="number" id="tva" name="tva" step="0.1" placeholder="<?= get_label('please_enter_tva', 'Please enter TVA') ?>" value="{{ old('tva') }}">
</div>


<div class="mb-3 col-md-6">
    <label for="montant_ht" class="form-label"><?= get_label('montant_ht', 'Montant hors taxes') ?></label>
    <input class="form-control" type="number" id="montant_ht" name="montant_ht" placeholder="<?= get_label('please_enter_montant_ht', 'Please enter montant') ?>" value="{{ old('montant_ht') }}">
</div>





