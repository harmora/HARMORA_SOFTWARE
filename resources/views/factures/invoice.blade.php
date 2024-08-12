@extends('layout')
@section('title')
<?= get_label('factures', 'Factures') ?>
@endsection
@php
$visibleColumns = getUserPreferences('factures');
@endphp
@section('content')
<div class="facture-container">
    <div class="header">
        <div class="company-info">
            <h2>Company Name</h2>
            <p>Address Line 1</p>
            <p>Address Line 2</p>
            <p>Phone: +212 6XX XXX XXX</p>
            <p>Email: company@example.com</p>
            <p>ICE: 0012345678</p>
        </div>
    </div>

    <div class="facture-info">
        <h2>Facture #{{ $facture->id }}</h2>
        <p class="date">Date: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    </div>

    <div class="client-info">
        <h4>Client Information:</h4>
        <p><strong>{{ $facture->client->name }}</strong></p>
        <p>{{ $facture->client->address }}</p>
        <p>Phone: {{ $facture->client->phone }}</p>
        <p>Email: {{ $facture->client->email }}</p>
        <p>ICE: {{ $facture->client->ice }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Quantité</th>
                <th>Prix Unitaire (MAD)</th>
                <th>Total (MAD)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($facture->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p>Sous-total: {{ number_format($facture->subtotal, 2) }} MAD</p>
        <p>TVA (20%): {{ number_format($facture->tva, 2) }} MAD</p>
        <p class="total">Total: {{ number_format($facture->total, 2) }} MAD</p>
    </div>

    <div class="footer">
        <p>Merci pour votre confiance.</p>
    </div>
</div>

</body>
</html>
@endsection