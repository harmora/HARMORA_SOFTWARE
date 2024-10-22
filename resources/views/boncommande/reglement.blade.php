@php
use App\Models\Workspace;
$auth_user = getAuthenticatedUser();
$roles = \Spatie\Permission\Models\Role::where('name', '!=', 'admin')->get();
@endphp

@extends('layout')

@section('title', get_label('order_payment', 'Order Payment'))

@section('content')
<div class="container-fluid my-5 mt-4">

    <!-- Breadcrumb Navigation -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <nav aria-label="breadcrumb" class="mb-0">
            <ol class="breadcrumb breadcrumb-style1 mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ url('/home') }}">{{ get_label('home', 'Home') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/orders') }}">{{ get_label('orders', 'Orders') }}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{ get_label('order_payment', 'Order Payment') }}
                </li>
            </ol>
        </nav>
    </div>

    <!-- Combined Status and Amounts Card -->
    <div class="card mb-4 shadow-sm" style="border-radius: 0.5rem; background-color: #f8f9fa;">
        <div class="card-body text-center">
            <h4 class="card-title text-primary">{{ get_label('payment_status', 'Payment Status') }}</h4>
            <p class="lead mb-3">
                <span class="badge {{ $invoice->payment_status == 'paid' ? 'bg-success' : ($invoice->payment_status == 'partial' ? 'bg-warning' : 'bg-danger') }}">
                    {{ ucfirst($invoice->payment_status) }}
                </span>
            </p>
            <hr>
            <h5 class="card-title text-dark">{{ get_label('total_amount', 'Total Amount') }}</h5>
            <p class="lead mb-1">
                <strong>{{ number_format($invoice->total_amount, 2) }} MAD</strong>
            </p>
            <h5 class="card-title text-dark">{{ get_label('montant_paye', 'Paid Amount') }}</h5>
            <p class="lead mb-1">
                <strong>{{ number_format($invoice->total_paid ?? 0, 2) }} MAD</strong>
            </p>
            <h5 class="card-title text-dark">{{ get_label('montant_restant', 'Remaining Amount') }}</h5>
            <p class="lead mb-1">
                <strong>{{ number_format($invoice->total_amount - $invoice->total_paid, 2) }} MAD</strong>
            </p>
        </div>
    </div>

    <!-- Input for Payment -->
    <div class="card shadow-sm" style="border-radius: 0.5rem; background-color: #e9ecef;">
        <div class="card-body">
            <h4 class="card-title text-center mb-4 text-dark">{{ get_label('payment_entry', 'Enter Payment') }}</h4>
           {{-- ---------------------------------------------------- --}}
            <form action="{{ route('commandes.store_reglement', $invoice->id) }}" method="POST" class="form-submit-event">
                @csrf
                <input type="hidden" name="redirect_url" value="/commandes/draggable">
                <div class="mb-3">
                    <label for="montant_paye" class="form-label text-dark">{{ get_label('montant_paye', 'Payment Amount') }} (MAD)</label>
                    <input type="number" class="form-control" id="montant_paye" name="montant_paye" step="0.01"
                           placeholder="{{ get_label('enter_amount', 'Enter the payment amount') }}" 
                           required
                           @if($invoice->total_amount - $invoice->total_paid == 0) readonly @endif>
                </div>
                
                <div class="mb-3">
                    <label for="payment_type" class="form-label text-dark">{{ get_label('payment_type', 'Payment Type') }}</label>
                    <select class="form-select" id="payment_type" name="payment_type" required
                            @if($invoice->total_amount - $invoice->total_paid == 0) disabled @endif>
                        <option value="virement">{{ get_label('virement', 'Virement') }}</option>
                        <option value="cheque">{{ get_label('cheque', 'Chèque') }}</option>
                        <option value="espece">{{ get_label('cash', 'Espèce') }}</option>
                    </select>
                </div>
                
                @if($invoice->total_amount - $invoice->total_paid > 0)
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary" id="submit_btn">{{ get_label('submit_payment', 'Submit Payment') }}</button>
                </div>
                @else
                <div class="alert alert-success" style="display: flex; justify-content: center; align-items: center;" role="alert">
                    {{ get_label('payment_completed', 'Payment Completed') }}
                </div>
                @endif
            </form>
        </div>
    </div>
    <div class="card mb-4 shadow-sm" style="border-radius: 0.5rem; background-color: #f8f9fa;">
        <div class="card-body">
            @if($previousReglements->isNotEmpty())
                <div id="previous-reglements" class="mt-4">
                    <h5 class="text-center">{{ get_label('previous_reglements', 'Previous Règlements') }}</h5>
                    @foreach($previousReglements as $reglement)
                        <div class="reglement-entry mb-3">
                            <p class="text-black">{{ get_label('reglement', 'Règlement') }} #{{ $loop->iteration }}</p>
                            <p>{{ get_label('date', 'Date') }}: {{ $reglement->date }}</p>
                            <p>{{ get_label('amount', 'Amount') }}:{{ get_label($reglement->amount_payed , $reglement->amount_payed ) }}</p>
                            <p>{{ get_label('payment_method', 'Payment Method') }}:{{ get_label($reglement->mode_virement, $reglement->mode_virement) }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
