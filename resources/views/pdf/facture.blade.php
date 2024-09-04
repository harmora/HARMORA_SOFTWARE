<!DOCTYPE html>
<html>
<head>
    <title>Facture #{{ $commande->id }}</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .facture-container {
            width: 100%;
            max-width: 800px;
            margin: 10px auto;
            background-color: #ffffff;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .facture-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .entrepriselogo {
            max-width: 220px;
            max-height: 120px;
            margin-bottom: 10px;
        }
        .facture-header h3 {
            font-size: 30px;
            color: #000000;
            margin: 0;
        }
        .facture-details {
            margin-bottom: 30px;
        }
        .facture-details p {
            margin: 5px 0;
            font-size: 14px;
        }
        .facture-details strong {
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
            text-align: right;
            font-size: 14px;
            margin-right: 16px;
            font-weight: bold;
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
    <div style="display: flex; text-align: center;">
        <img src="storage/logos/default_full_logo.png" style="width: 170px; margin-top: 10px;" alt="Logo">
    </div>

    <div class="facture-container">
        <div style="display: flex; text-align: center; padding-top:8px;">{{ date('d/m/Y') }}</div>

        <table style="width: 100%; border-collapse: collapse; font-size:12px !important;">
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

        <div class="facture-header">
            <h3>{{ get_label('facture', 'Facture') }}</h3>
        </div>

        <div class="facture-details">
            <p><strong>{{ get_label('order_name', 'Nom de la commande') }} :</strong> {{ $commande->id  ." - ". $commande->title }}</p>
            <p><strong>{{ get_label('description', 'Description') }} :</strong> {{ $commande->description }}</p>

            @if ($commande->client->first_name)
                <p><strong>{{ get_label('client_name', 'Nom de client') }} :</strong> {{ $commande->client->first_name . ' ' . $commande->client->last_name }}</p>
            @endif

            @if ($commande->client->denomenation)
                <p><strong>{{ get_label('client_denomination', 'Dénomination de client') }} :</strong> {{ $commande->client->denomenation }}</p>
            @endif

            <p><strong>{{ get_label('email', 'Email') }} :</strong> {{ $commande->client->email }}</p>
            <p><strong>{{ get_label('phone', 'Phone') }} :</strong> {{ $commande->client->phone }}</p>
        </div>

        <h4>{{ get_label('products', 'Produits') }} :</h4>
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
                @foreach ($commande->products as $product)
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
            {{ get_label('total_ht', 'Total HT') }} : <span>{{ number_format($commande->total_amount / (1 + $commande->tva / 100), 2) }} MAD</span>
        </div>

        <div class="total-amount">
            {{ get_label('total_tva', 'Total TVA') }} : <span>{{ number_format($commande->total_amount - ($commande->total_amount / (1 + $commande->tva / 100)), 2) }} MAD</span>
        </div>

        <div class="total-amount">
            {{ get_label('total_ttc', 'Total TTC') }} : <span>{{ number_format($commande->total_amount, 2) }} MAD</span>
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
