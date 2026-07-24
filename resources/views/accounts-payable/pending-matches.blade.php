@extends('layouts.app')
@section('page-title', 'Pending Three-Way Matches')
@section('page-title-heading', 'Pending Three-Way Matches')
@section('page-subtitle', 'Every verified invoice waiting on a Purchase Order / Goods Receipt match. Pick one to open its match page directly.')
@section('content')

<style>
    /* ================= PAGE STYLES ================= */
    .match-page {
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        background-color: #f8fafc;
        min-height: 100vh;
        padding: 12px;
    }

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 4px 18px rgba(20, 40, 90, .04);
        overflow: hidden;
    }

    .card-body {
        padding: 24px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        table-layout: auto;
        margin-bottom: 0;
    }

    .table thead {
        background-color: #f8fafc;
    }

    .table th {
        background-color: #f8fafc !important;
        color: #475569;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 16px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }

    .table td {
        padding: 14px 16px;
        font-size: 13.5px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        word-wrap: break-word;
        overflow-wrap: break-word;
        text-align: left;
    }

    .table td.text-end {
        text-align: right;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge.bg-success-subtle {
        background-color: #EAFBF3 !important;
        color: #18A566 !important;
    }

    .badge.bg-danger-subtle {
        background-color: #FDECEC !important;
        color: #D93025 !important;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .btn-outline-primary {
        color: #0d6efd;
        border: 1.5px solid #0d6efd;
        background: transparent;
    }

    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: #fff;
    }

    .btn i {
        font-size: 12px;
    }

    .alert {
        border: none;
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 20px;
        font-size: 13.5px;
    }

    .alert-success {
        background-color: #EAFBF3;
        color: #18A566;
    }

    .alert-warning {
        background-color: #FFF6DD;
        color: #C88700;
    }

    .empty-state {
        text-align: center;
        padding: 48px 16px;
        color: #94a3b8;
    }

    .empty-state p {
        font-size: 14px;
    }

    /* Table responsive */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    @media (max-width: 768px) {
        .table {
            font-size: 12px;
        }

        .table th,
        .table td {
            padding: 10px 12px;
        }

        .btn {
            padding: 6px 10px;
            font-size: 12px;
        }
    }
</style>

<div class="match-page">

    <!-- ================= ALERTS ================= -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    <!-- ================= MAIN CARD ================= -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Invoice Number</th>
                            <th style="width: 18%;">Supplier</th>
                            <th style="width: 13%;">Invoice Date</th>
                            <th style="width: 13%; text-align: right;">Amount</th>
                            <th style="width: 13%;">PO Status</th>
                            <th style="width: 13%;">GRN Status</th>
                            <th style="width: 15%; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($invoices as $invoice)
                            <tr>
                                <td class="fw-semibold text-primary">{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->supplier->name }}</td>
                                <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                                <td class="text-end">₱{{ number_format($invoice->total_amount, 2) }}</td>

                                <td>
                                    @if ($invoice->po_matched_live)
                                        <span class="badge bg-success-subtle">
                                            <i class="fa-solid fa-check"></i>
                                            Matched
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle">
                                            Not Matched
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if ($invoice->grn_matched_live)
                                        <span class="badge bg-success-subtle">
                                            <i class="fa-solid fa-check"></i>
                                            Matched
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle">
                                            Not Matched
                                        </span>
                                    @endif
                                </td>

                                <td style="text-align: center;">
                                    <a href="{{ route('ap.match', $invoice) }}" class="btn btn-outline-primary">
                                        <i class="fa-solid fa-arrow-right-to-bracket"></i>
                                        Open Match
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <p>No invoices are currently awaiting three-way match.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection