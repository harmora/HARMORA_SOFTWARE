{{-- <!DOCTYPE html>
<html>
<head>
    <title>Devis #{{ $commande->id }}</title>
    <style>

        body {
             font-family: 'Roboto', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .devis-container {
            width: 100%;
            max-width: 800px;
            margin: 10px auto;

            background-color: #ffffff;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .devis-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .entrepriselogo {
            max-width: 220px;
            max-height: 120px;
            margin-bottom: 10px;
        }
        .devis-header h3 {
            font-size: 36px;
            color: #006fe4;
            margin: 0;
        }
        .devis-details {
            margin-bottom: 30px;
        }
        .devis-details p {
            margin: 5px 0;
            font-size: 16px;
        }
        .devis-details strong {
            color: #006fe4;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
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
            color: #006fe4;

        }
        .total-amount span{
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


<div class="devis-container">
    <div style="display: flex; text-align: center; padding-top:8px;">{{ date('d/m/Y') }}</div>

    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 50%; vertical-align: middle;">
                <img src="{{ $entreprise->photo ? 'storage/' . $entreprise->photo : 'storage/photos/no-image.jpg' }}"  class="entrepriselogo" alt="Logo">


            </td>
            <td style="text-align: right; vertical-align: middle;">
                <div style="margin-right: 26px">
                    <div> <b>{{ $entreprise->denomination }}</b></div>
                <div style="font-size: 14px">{{ $entreprise->address }} {{ $entreprise->city }}</div>
            </div>
            </td>
        </tr>
    </table>

        <div class="devis-header">

            <h3>Devis</h3>
        </div>
        <div class="devis-details">
            <p><strong>Commande name:</strong> {{ $commande->title }}</p>
            <p><strong>Description:</strong> {{ $commande->description }}</p>
            <p><strong>Client:</strong> {{ $commande->client->first_name." ".$commande->client->last_name }}</p>

        </div>
        <h4>Products:</h4>
        <table class="products-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price HT (MAD)</th>
                    <th>Product Total HT (MAD)</th>
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
            Total HT: <span>{{ number_format($commande->total_amount, 2) }} MAD</span>
            </div>

        <div class="total-amount">

            Total TVA: <span>{{ number_format($commande->total_amount, 2) }} MAD</span>
                </div>
        <div class="total-amount">
                 Total TTC: <span>{{ number_format($commande->total_amount, 2) }} MAD</span>
        </div>

        <div class="signatures">
            <div>
                <p style="margin-bottom: 140px">Authorized Signature</p>
            </div>
        </div>

        <footer>
            <p>&copy; {{ date('Y') }} {{ $entreprise->denomination }} - {{ $entreprise->address }} - {{ $entreprise->city }}</p>
        </footer>

    </div>
</body>
</html> --}}



<!DOCTYPE html>
<html>
<head>
    <title>Devis #{{ $commande->id }}</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .devis-container {
            width: 100%;
            max-width: 800px;
            margin: 10px auto;
            background-color: #ffffff;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .devis-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .entrepriselogo {
            max-width: 220px;
            max-height: 120px;
            margin-bottom: 10px;
        }
        .devis-header h3 {
            font-size: 36px;
            color: #006fe4;
            margin: 0;
        }
        .devis-details {
            margin-bottom: 30px;
        }
        .devis-details p {
            margin: 5px 0;
            font-size: 16px;
        }
        .devis-details strong {
            color: #006fe4;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
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
            color: #006fe4;
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

    <div class="devis-container">
        <div style="display: flex; text-align: center; padding-top:8px;">{{ date('d/m/Y') }}</div>

        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; vertical-align: middle;">
                    <img src="{{ $entreprise->photo ? 'storage/' . $entreprise->photo : 'storage/photos/no-image.jpg' }}"  class="entrepriselogo" alt="Logo">
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <div style="margin-right: 26px">
                        <div> <b>{{ $entreprise->denomination }}</b></div>
                        <div style="font-size: 14px">{{ $entreprise->address }} {{ $entreprise->city }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="devis-header">
            <h3>Devis</h3>
        </div>

        <div class="devis-details">
            <p><strong>Nom de la commande :</strong> {{ $commande->title }}</p>
            <p><strong>Description :</strong> {{ $commande->description }}</p>
            <p><strong>Client :</strong> {{ $commande->client->first_name." ".$commande->client->last_name }}</p>
        </div>

        <h4>Produits :</h4>
        <table class="products-table">
            <thead>
                <tr>
                    <th>Nom du produit</th>
                    <th>Quantité</th>
                    <th>Prix HT (MAD)</th>
                    <th>Total produit HT (MAD)</th>
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
            Total HT : <span>{{ number_format($commande->total_amount, 2) }} MAD</span>
        </div>

        <div class="total-amount">
            Total TVA : <span>{{ number_format($commande->total_amount * 0.2, 2) }} MAD</span>
        </div>

        <div class="total-amount">
            Total TTC : <span>{{ number_format($commande->total_amount * 1.2, 2) }} MAD</span>
        </div>

        <div class="signatures">
            <div>
                <p style="margin-bottom: 140px">Signature autorisée</p>
            </div>
        </div>

        <footer>
            <p>&copy; {{ date('Y') }} {{ $entreprise->denomination }} - {{ $entreprise->address }} - {{ $entreprise->city }}</p>
        </footer>
    </div>
</body>
</html>

