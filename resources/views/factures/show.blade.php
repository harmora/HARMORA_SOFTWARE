<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $facture->id }}</title>
</head>
<body>
    @include('factures.invoice', ['facture' => $facture])
    <a href="{{ route('factures.download', $facture->id) }}">Download PDF</a>
</body>
</html>
