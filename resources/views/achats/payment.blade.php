@extends('layout')

@section('title', get_label('achat_payment', 'Achat Payment'))

@section('content')
<div class="container-fluid my-5 mt-4"> <!-- Added mt-4 for top margin -->

    <!-- Breadcrumb Navigation -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <nav aria-label="breadcrumb" class="mb-0">
            <ol class="breadcrumb breadcrumb-style1 mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ url('/home') }}">{{ get_label('home', 'Home') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/achats') }}">{{ get_label('achats', 'Achats') }}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{ get_label('achat_payment', 'Achat Payment') }}
                </li>
            </ol>
        </nav>
    </div>

    <!-- Combined Status and Amounts Card -->
    <div class="card mb-4 shadow-sm" style="border-radius: 0.5rem; background-color: #f8f9fa;">
        <div class="card-body text-center">
            <h4 class="card-title text-primary">{{ get_label('payment_status', 'Payment Status') }}</h4>
            <p class="lead mb-3">
                <span class="badge {{ $achat->status_payement == 'paid' ? 'bg-success' : ($achat->status_payement == 'partial' ? 'bg-warning' : 'bg-danger') }}">
                    {{ ucfirst($achat->status_payement) }}
                </span>
            </p>
            <hr>
            <h5 class="card-title text-dark">{{ get_label('total_amount', 'Total Amount') }}</h5>
            <p class="lead mb-1">
                <strong>{{ number_format($achat->montant, 2) }} MAD</strong>
            </p>
            <h5 class="card-title text-dark">{{ get_label('montant_paye', 'Paid Amount') }}</h5>
            <p class="lead mb-1">
                <strong>{{ number_format($achat->montant_payée ?? 0, 2) }} MAD</strong>
            </p>
            <h5 class="card-title text-dark">{{ get_label('montant_restant', 'Remaining Amount') }}</h5>
            <p class="lead mb-1">
                <strong>{{ number_format($achat->montant_restant ?? $achat->montant, 2) }} MAD</strong>
            </p>
        </div>
    </div>

    <!-- Input for Payment -->
    <div class="card shadow-sm" style="border-radius: 0.5rem; background-color: #e9ecef;">
        <div class="card-body">
            <h4 class="card-title text-center mb-4 text-dark">{{ get_label('payment_entry', 'Enter Payment') }}</h4>
            <form action="{{ url('/achats/payment/' . $achat->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="montant_paye" class="form-label text-dark">{{ get_label('montant_paye', 'Payment Amount') }} (MAD)</label>
                    <input type="number" class="form-control" id="montant_paye" name="montant_paye" step="0.01" placeholder="{{ get_label('enter_amount', 'Enter the payment amount') }}" required>
                </div>
                <div class="mb-3">
                    <label for="payment_type" class="form-label text-dark">{{ get_label('payment_type', 'Payment Type') }}</label>
                    <select class="form-select" id="payment_type" name="payment_type" required>
                        <option value="Virement">{{ get_label('virement', 'Virement') }}</option>
                        <option value="Chèque">{{ get_label('cheque', 'Chèque') }}</option>
                        <option value="Espèce">{{ get_label('cash', 'Espèce') }}</option>
                    </select>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">{{ get_label('submit_payment', 'Submit Payment') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
