<!DOCTYPE html>
<html>
<head>
    <title>Bon de Commande #{{ $bonCommande->id }}</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .bon-commande-container {
            width: 100%;
            max-width: 800px;
            margin: 10px auto;
            background-color: #ffffff;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .bon-commande-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .bon-commande-header p {
            font-size: 18px;
        }
        .entrepriselogo {
            max-width: 220px;
            max-height: 120px;
            margin-bottom: 10px;
        }
        .bon-commande-header h3 {
            font-size: 30px;
            color: #000000;
            margin: 0;
        }
        .bon-commande-details {
            margin-bottom: 30px;
        }
        .bon-commande-details p {
            margin: 5px 0;
            font-size: 14px;
        }
        .bon-commande-details strong {
            color: #000000;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 12px;
        }
        .products-table th, .products-table td {
            border: 1px solid #e0e0e0;
            padding: 10px;
            text-align: left;
        }
        .products-table th {
            background-color: #006fe4;
            color: #ffffff;
            font-weight: bold;
        }
        .products-table td {
            background-color: #f9f9f9;
        }
        .total-amount {
            text-align: right;
            font-size: 14px;
            margin-right: 16px;
            font-weight: bold;
            color: #000000;
        }
        .total-amount span {
            color: #000204;
        }
        .signatures {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signatures div {
            text-align: center;
            width: 45%;
        }
        .signatures div p {
            margin: 0;
            font-size: 14px;
            color: #333;
            border-top: 1px solid #333;
            padding-top: 5px;
        }
        footer {
            text-align: center;
            border-top: 1px solid #e0e0e0;
            padding-top: 15px;
            font-size: 12px;
            color: #383838;
        }
    </style>
</head>
<body>

    <div class="bon-commande-container">
        <div style="display: flex; text-align: center; padding-top: 8px;">{{ date('d/m/Y') }}</div>

        <table style="width: 100%; border-collapse: collapse; font-size: 12px !important;">
            <tr>
                <td style="width: 50%; vertical-align: middle;">
                    <img src="{{ $entreprise->photo ? 'storage/' . $entreprise->photo : 'storage/photos/no-image.jpg' }}" class="entrepriselogo" alt="Logo">
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <div style="margin-right: 26px">
                        <div><b>{{ $entreprise->denomination }}</b></div>
                        <div style="font-size: 14px">{{ $entreprise->address }} {{ $entreprise->city }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="bon-commande-header">
            <h3>{{ get_label('bon_commande', 'Bon de Commande') }}</h3>
            <p style="color: black !important"><strong>{{ get_label('reference', 'Reference :') }} :</strong> {{"BON_COMMANDE_".$bonCommande->reference}}</p>
        </div>

        <div class="bon-commande-details">
            <p><strong>{{ get_label('fournisseur', 'Fournisseur') }} :</strong> {{$bonCommande->fournisseur->name}}</p>
            <p><strong>{{ get_label('date_commande', 'Date de Commande') }} :</strong> {{ date('d/m/Y', strtotime($bonCommande->date_commande)) }}</p>
        </div>

        <h4>{{ get_label('produits', 'Produits') }} :</h4>
        <table class="products-table">
            <thead>
                <tr>
                    <th>{{ get_label('product_name', 'Nom du produit') }}</th>
                    <th>{{ get_label('quantity', 'Quantité') }}</th>
                    <th>{{ get_label('price_ht', 'Prix HT (MAD)') }}</th>
                    <th>{{ get_label('total_product_ht', 'Total produit HT (MAD)') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bonCommande->products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->pivot->quantity }}</td>
                        <td>{{ $product->pivot->price }}</td>
                        <td>{{ number_format($product->pivot->quantity * $product->pivot->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-amount">
            {{ get_label('total_ht', 'Total HT') }} : <span>{{ number_format($bonCommande->montant_ht, 2) }} MAD</span>
        </div>

        <div class="total-amount">
            {{ get_label('total_tva', 'Total TVA') }} : <span>{{ number_format($bonCommande->montant - $bonCommande->montant_ht, 2) }} MAD</span>
        </div>

        <div class="total-amount">
            {{ get_label('total_ttc', 'Total TTC') }} : <span>{{ number_format($bonCommande->montant, 2) }} MAD</span>
        </div>

        <div class="signatures">
            <div>
                <p style="margin-bottom: 140px">{{ get_label('authorized_signature', 'Signature autorisée') }}</p>
            </div>
        </div>

        <footer>
            <p>&copy; {{ date('Y') }} {{ $entreprise->denomination }} - {{ $entreprise->address }} - {{ $entreprise->city }}</p>
        </footer>
    </div>
</body>
</html>
