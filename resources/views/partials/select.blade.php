{{-- @php
    $isClient = isClient();
    $for = isset($for) && $for != '' ? $for : 'users';
@endphp
<label class="form-label" for="{{ $name }}">{{ $label }}</label>
<div class="input-group">
    <select class="form-control js-example-basic-multiple" name="{{ $name }}" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
        @foreach($items as $item)
            @php
                if ($isClient) {
                    $selected = (isset($authUserId) && $authUserId == $item->id && $for == 'clients') ? 'selected' : '';
                } else {
                    $selected = (isset($authUserId) && $authUserId == $item->id) ? 'selected' : '';
                }
            @endphp
            <option value="{{ $item->id }}" {{ $selected }}>{{ $item->first_name }} {{ $item->last_name }}</option>
        @endforeach
    </select>
</div> --}}

{{-- code modified is bellow original above  --}}
@php
    $isClient = isClient();
    $for = isset($for) && $for != '' ? $for : 'users';
@endphp
<label class="form-label" for="{{ $name }}">{{ $label }}</label>
<div class="input-group">
    <select class="form-control js-example-basic-multiple" name="{{ $name }}" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
        @foreach($items as $item)
            @php
                $selected = '';
                if ($for == 'clients' && $isClient) {
                    $selected = (isset($authUserId) && $authUserId == $item->id) ? 'selected' : '';
                } elseif ($for == 'suppliers') {
                    // Add your supplier-specific logic here
                    $selected = (isset($selectedSupplierId) && $selectedSupplierId == $item->id) ? 'selected' : '';
                } elseif ($for == 'products') {
                    // Add your product-specific logic here
                    $selected = (isset($selectedProductId) && $selectedProductId == $item->id) ? 'selected' : '';
                } else {
                    $selected = (isset($authUserId) && $authUserId == $item->id) ? 'selected' : '';
                }
            @endphp
            <option value="{{ $item->id }}" {{ $selected }}>
                @if($for == 'products' || $for == 'suppliers')
                    {{ $item->name }}
                @else
                    {{ $item->first_name }} {{ $item->last_name }}
                @endif
            </option>
        @endforeach
    </select>
</div>




{{-- @php
    $isClient = isClient();
    $for = isset($for) && $for != '' ? $for : 'users';
@endphp
<label class="form-label" for="{{ $name }}">{{ $label }}</label>
<div class="input-group">
    <select class="form-control js-example-basic-multiple" name="{{ $name }}" multiple="multiple" data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
        @foreach($items as $item)
            @php
                if ($isClient) {
                    $selected = (isset($authUserId) && $authUserId == $item->id && $for == 'clients') ? 'selected' : '';
                } 
                else {
                    $selected = (isset($authUserId) && $authUserId == $item->id) ? 'selected' : '';
                }
            @endphp
            <option value="{{ $item->id }}" {{ $selected }}>{{ $item->first_name }} {{ $item->last_name }}</option>
        @endforeach
    </select>
</div> --}}
