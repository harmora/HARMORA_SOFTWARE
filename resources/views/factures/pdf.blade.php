<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $facture->id }}</title>
    <style>
        /* Include the same CSS styles from the previous view here */
    </style>
</head>
<body>
    @include('factures.invoice', ['facture' => $facture])
</body>
</html>
