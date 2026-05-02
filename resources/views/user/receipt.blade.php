
@extends('layouts.receipt')

@section('title', 'Transaction Receipt')

@section('content')

<div class="receipt-header">
    <div class="brand-logo">
        <i class="bi bi-shield-check"></i>
    </div>
    <div class="status-badge">
        <i class="bi bi-check-circle-fill"></i>
        <span>Transaction Successful</span>
    </div>
    <h2>Payment Receipt</h2>
</div>

<div class="amount-display">
    <div class="amount-label">Amount Paid</div>
    <div class="amount-value">₦{{ number_format($transaction->amount, 2) }}</div>
</div>

<div class="info-grid">
    <div class="info-row">
        <div class="info-label">Reference Number</div>
        <div class="info-value">{{ strtoupper($transaction->referenceId ?? $transaction->trx ?? 'N/A') }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Service Type</div>
        <div class="info-value">{{ strtoupper($transaction->service_type ?? 'N/A') }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Description</div>
        <div class="info-value">{{ $transaction->service_description ?? 'Transaction successful' }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Date & Time</div>
        <div class="info-value">{{ $transaction->created_at->format('M d, Y • g:i A') }}</div>
    </div>
    <div class="info-row">
        <div class="info-label">Payment Status</div>
        <div class="info-value" style="color: var(--success-color);">{{ strtoupper($transaction->status ?? 'SUCCESS') }}</div>
    </div>
</div>

@endsection




