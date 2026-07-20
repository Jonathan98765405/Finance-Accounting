@extends('layouts.app')
@section('page-title-heading', 'Payment Processing & GRA')
@section('page-subtitle', 'Process payments to suppliers and generate remittance advice for the payment made.')
@section('content')

<style>
/* ================= FAIL-SAFE LAYOUT ENGINE ================= */
.ap-page {
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    background-color: #f8fafc;
    min-height: 100vh;
    padding: 12px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* KPI Horizontal Grid System */
.ap-page .kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    width: 100%;
}

@media (max-width: 1200px) {
    .ap-page .kpi-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .ap-page .kpi-grid {
        grid-template-columns: 1fr;
    }
}

/* 2-Column Main Workspace Layout */
.ap-page .main-workspace {
    display: grid !important;
    grid-template-columns: 2fr 1fr !important;
    gap: 24px !important;
    width: 100% !important;
    align-items: start !important;
}

@media (max-width: 1100px) {
    .ap-page .main-workspace {
        grid-template-columns: 1fr !important;
    }
}

.ap-page .left-workspace-column,
.ap-page .right-workspace-column {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* ================= COMPONENT STYLES ================= */
.ap-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    box-shadow: 0 4px 18px rgba(20, 40, 90, .04);
    overflow: hidden;
}

.kpi-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 24px;
}

.kpi-icon {
    width: 54px;
    height: 54px;
    min-width: 54px;
    border-radius: 50%;
    background: #E7FAF0;
    color: #18B876;
    display: flex;
    align-items: center;
    justify-content: center;
}

.kpi-label {
    font-size: 12.5px;
    font-weight: 600;
    color: #94a3b8;
    margin-bottom: 2px;
}

.kpi-value {
    font-size: 24px;
    font-weight: 800;
    color: #1e293b;
    line-height: 1.2;
}

.kpi-sub {
    font-size: 11px;
    color: #94a3b8;
    font-weight: 500;
}

.section-eyebrow {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 22px;
}

.section-eyebrow .bar {
    width: 4px;
    height: 16px;
    border-radius: 2px;
    background: #18B876;
}

.section-eyebrow span {
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #18B876;
}

/* ================= ALERT / FLASH MESSAGE ================= */
.alert {
    padding: 14px 18px;
    border-radius: 12px;
    font-size: 13.5px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-success {
    background: #E7FAF0;
    color: #12805a;
    border: 1px solid #b9ecd6;
}

.alert-warning {
    background: #FFF6DD;
    color: #8a6d00;
    border: 1px solid #f5e3a8;
}

/* ================= FILTER ROW ENGINE (No Bootstrap required) ================= */
.ap-filters-row {
    display: flex !important;
    gap: 12px !important;
    margin-bottom: 20px !important;
    align-items: center !important;
    flex-wrap: wrap !important;
    width: 100% !important;
}

.ap-filter-item-search {
    flex: 2 1 250px !important;
    min-width: 200px !important;
}

.ap-filter-item-method {
    flex: 1.2 1 180px !important;
    min-width: 150px !important;
}

.ap-filter-item-priority {
    flex: 1 1 150px !important;
    min-width: 120px !important;
}

/* Modern Input & Select fields styling */
.form-control, .form-select {
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 13.5px;
    color: #334155;
    background-color: #f8fafc;
    transition: all 0.2s ease;
    width: 100%;
    box-sizing: border-box;
}

.form-control:focus, .form-select:focus {
    border-color: #18B876;
    box-shadow: 0 0 0 3px rgba(24, 184, 118, 0.15);
    background-color: #fff;
    outline: none;
}

/* Modern Table styling */
.table {
    width: 100%;
    border-collapse: collapse;
    table-layout: auto;
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

.priority-high { background: #FDECEC; color: #D93025; padding: 5px 12px; border-radius: 30px; font-size: 11px; font-weight: 600; display: inline-block; }
.priority-medium { background: #FFF6DD; color: #C88700; padding: 5px 12px; border-radius: 30px; font-size: 11px; font-weight: 600; display: inline-block; }
.priority-low { background: #EAFBF3; color: #18A566; padding: 5px 12px; border-radius: 30px; font-size: 11px; font-weight: 600; display: inline-block; }

.status-badge { padding: 6px 14px; border-radius: 30px; font-size: 12px; font-weight: 600; display: inline-block; }
.status-paid { background: #EAFBF3; color: #18A566; }

.ra-link {
    font-size: 12.5px !important;
    font-weight: 600 !important;
    color: #2354B8 !important;
    text-decoration: none !important;
    display: flex !important;
    align-items: center !important;
    gap: 6px !important;
    flex-wrap: wrap;
}
.ra-link:hover { color: #18449e !important; text-decoration: underline !important; }

.ra-link-wrapper {
    display: flex;
    flex-direction: column;
    width: 100%;
    gap: 4px;
}

.ra-sent-info {
    font-size: 10.5px;
    color: #94a3b8;
    margin-top: 2px;
    display: block;
    width: 100%;
}

/* Crisp circular Mail Buttons */
.mail-btn {
    width: 32px !important;
    height: 32px !important;
    min-width: 32px !important;
    border-radius: 50% !important;
    border: 1px solid #cbd5e1 !important;
    background-color: #ffffff !important;
    background: #ffffff !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.2s ease !important;
    color: #475569 !important;
    cursor: pointer !important;
    padding: 0 !important;
    outline: none !important;
    box-shadow: none !important;
}
.mail-btn:hover {
    background-color: #243f90 !important;
    background: #243f90 !important;
    color: #ffffff !important;
    border-color: #243f90 !important;
}
.mail-btn svg {
    fill: none !important;
    stroke: currentColor !important;
    width: 14px !important;
    height: 14px !important;
}

.activity-row {
    display: flex;
    align-items: center;
    padding: 14px 0;
    border-top: 1px solid #F1F3F8;
}
.activity-row:first-of-type { border-top: none; }

.activity-icon {
    width: 34px;
    height: 34px;
    min-width: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    margin-right: 12px;
}
.activity-icon.done { background: #E7FAF0; color: #18B876; }
.activity-icon.scheduled { background: #E9F0FF; color: #2354B8; }

.activity-title { font-size: 13px; color: #3D4658; margin-bottom: 1px; font-weight: 500; }
.activity-time { font-size: 11px; color: #AEB6C6; white-space: nowrap; margin-left: 10px; }

/* Green styling for primary success elements */
.btn-success {
    background-color: #18B876 !important;
    border-color: #18B876 !important;
    color: #fff !important;
    font-weight: 600 !important;
    font-size: 13.5px !important;
    padding: 10px 16px !important;
    border-radius: 8px !important;
    border: none !important;
    cursor: pointer !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    width: 100% !important;
    height: 42px !important;
    transition: background-color 0.2s !important;
    box-sizing: border-box !important;
}
.btn-success:hover:not(:disabled) {
    background-color: #149c63 !important;
}
.btn-success:disabled {
    background-color: #cbd5e1 !important;
    color: #94a3b8 !important;
    cursor: not-allowed !important;
}

.btn-outline-success {
    color: #18B876 !important;
    border: 1.5px solid #18B876 !important;
    background: transparent !important;
    padding: 6px 14px !important;
    border-radius: 8px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    transition: all 0.2s !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    white-space: nowrap !important;
}
.btn-outline-success:hover {
    background-color: #18B876 !important;
    color: #fff !important;
}

/* ================= VIEW ALL TRIGGER ================= */
.view-all-wrap {
    text-align: center;
    padding: 14px 0 2px;
    margin-top: 8px;
    border-top: 1px solid #f1f5f9;
}
.view-all-btn {
    background: none;
    border: none;
    color: #18B876;
    font-weight: 600;
    font-size: 13.5px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px;
}
.view-all-btn:hover {
    color: #149c63;
    text-decoration: underline;
}

/* ================= MODAL FALLBACK ENGINE ================= */
.modal {
    display: none !important;
    position: fixed !important;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.6) !important;
    backdrop-filter: blur(4px);
    z-index: 9999 !important;
    align-items: center;
    justify-content: center;
    box-sizing: border-box;
}
.modal.show {
    display: flex !important;
}
.modal-dialog {
    width: 100%;
    max-width: 500px;
    margin: 20px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    overflow: hidden;
    animation: modalScaleIn 0.2s ease-out;
    border: none;
    box-sizing: border-box;
}
.modal-dialog-lg {
    max-width: 960px;
}
@keyframes modalScaleIn {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-body {
    padding: 24px;
}
.modal-body-scroll {
    max-height: 65vh;
    overflow-y: auto;
}
.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    background-color: #f8fafc;
}
.btn-close {
    background: transparent;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #94a3b8;
}
.btn-close:hover { color: #475569; }
.btn-outline-secondary {
    background: #fff;
    border: 1px solid #cbd5e1;
    color: #475569;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 500;
    font-size: 13.5px;
    cursor: pointer;
}
.btn-outline-secondary:hover { background: #f8fafc; }
.btn-primary {
    background: #2354B8;
    color: #fff;
    border: none;
    padding: 8px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13.5px;
    cursor: pointer;
}
.btn-primary:hover { background: #1a4093; }

.d-none { display: none !important; }

/* Table responsive wrapper fix */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
</style>

<div class="ap-page">

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    <div class="kpi-grid">
        <div class="ap-card">
            <div class="kpi-card">
                <div class="kpi-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#18B876" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="9" y1="6" x2="20" y2="6"></line><line x1="9" y1="12" x2="20" y2="12"></line><line x1="9" y1="18" x2="20" y2="18"></line><polyline points="4 6 5 7 7 5"></polyline><polyline points="4 12 5 13 7 11"></polyline><polyline points="4 18 5 19 7 17"></polyline></svg>
                </div>
                <div>
                    <div class="kpi-label">Payments to Process</div>
                    <div class="kpi-value">₱{{ number_format($totalReady, 2) }}</div>
                    <div class="kpi-sub">{{ $totalReadyCount }} payment(s)</div>
                </div>
            </div>
        </div>

        <div class="ap-card">
            <div class="kpi-card">
                <div class="kpi-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#18B876" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <div>
                    <div class="kpi-label">Payments Processed</div>
                    <div class="kpi-value">₱{{ number_format($processedThisMonthTotal, 2) }}</div>
                    <div class="kpi-sub">{{ $processedThisMonthCount }} payment(s) this month</div>
                </div>
            </div>
        </div>

        <div class="ap-card">
            <div class="kpi-card">
                <div class="kpi-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#18B876" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <div>
                    <div class="kpi-label">Remittance Pending</div>
                    <div class="kpi-value">₱{{ number_format($pendingRemittanceTotal, 2) }}</div>
                    <div class="kpi-sub">{{ $pendingRemittanceCount }} not yet emailed</div>
                </div>
            </div>
        </div>

        <div class="ap-card">
            <div class="kpi-card">
                <div class="kpi-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#18B876" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                </div>
                <div>
                    <div class="kpi-label">Remittance Advice</div>
                    <div class="kpi-value">{{ $remittanceSentThisMonthCount }}</div>
                    <div class="kpi-sub">Sent this month</div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-workspace">

        <div class="left-workspace-column">

            @php
                // Ready-for-processing table shows 4 rows inline before "View All".
                $readyVisibleLimit = 4;
                // Payment history table shows 3 rows inline before "View All".
                $historyVisibleLimit = 3;
                $visibleReadyPayments = $readyPayments->take($readyVisibleLimit);
                $visiblePaidPayments = $paidPayments->take($historyVisibleLimit);
            @endphp

            <div class="ap-card">
                <div class="p-4">
                    <div class="section-eyebrow">
                        <span class="bar"></span>
                        <span>Approve Payments Ready For Processing</span>
                    </div>

                    <div class="ap-filters-row">
                        <div class="ap-filter-item-search">
                            <input type="text" id="readySearch" class="form-control" placeholder="Search supplier or invoice...">
                        </div>
                        <div class="ap-filter-item-method">
                            <select id="methodFilter" class="form-select">
                                <option value="">All Payment Methods</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Cash">Cash</option>
                            </select>
                        </div>
                        <div class="ap-filter-item-priority">
                            <select id="priorityFilterReady" class="form-select">
                                <option value="">All Priorities</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive border rounded-3">
                        <table class="table align-middle mb-0" id="readyTable">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Invoice No.</th>
                                    <th style="width: 20%;">Supplier</th>
                                    <th style="width: 15%;">Due Date</th>
                                    <th style="width: 15%; text-align: right;">Amount</th>
                                    <th style="width: 15%;">Method</th>
                                    <th style="width: 10%;">Priority</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($visibleReadyPayments as $payment)
                                    <tr data-search="{{ strtolower(($payment->invoice->supplier->name ?? '') . ' ' . ($payment->invoice->invoice_number ?? '')) }}"
                                        data-method="{{ $payment->payment_method }}"
                                        data-priority="{{ $payment->priority }}">
                                        <td class="fw-semibold text-primary">{{ $payment->invoice->invoice_number ?? '—' }}</td>
                                        <td>{{ $payment->invoice->supplier->name ?? '—' }}</td>
                                        <td>{{ $payment->invoice->due_date?->format('M d, Y') ?? '—' }}</td>
                                        <td class="text-end fw-semibold">₱{{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ $payment->payment_method }}</td>
                                        <td><span class="priority-{{ $payment->priority }}">{{ ucfirst($payment->priority) }}</span></td>
                                        <td style="text-align: center;">
                                            <button type="button"
                                                    class="btn-outline-success select-payment-btn"
                                                    data-id="{{ $payment->id }}"
                                                    data-action="{{ route('ap.payment.process', $payment) }}"
                                                    data-supplier="{{ $payment->invoice->supplier->name ?? '—' }}"
                                                    data-invoice="{{ $payment->invoice->invoice_number ?? '—' }}"
                                                    data-amount="₱{{ number_format($payment->amount, 2) }}"
                                                    data-method="{{ $payment->payment_method }}">
                                                Select
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted py-4">Nothing waiting to be processed.</td></tr>
                                @endforelse
                                <tr id="noReadyResults" style="display: none;"><td colspan="7" class="text-center text-muted py-4">No matching payments.</td></tr>
                            </tbody>
                        </table>
                    </div>

                    @if ($readyPayments->count() > $readyVisibleLimit)
                        <div class="view-all-wrap">
                            <button type="button" class="view-all-btn" id="openReadyViewAll">
                                View All ({{ $readyPayments->count() }} total)
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="ap-card">
                <div class="p-4">
                    <div class="section-eyebrow">
                        <span class="bar"></span>
                        <span>Payment History</span>
                    </div>

                    <div class="table-responsive border rounded-3">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Invoice No.</th>
                                    <th style="width: 18%;">Supplier</th>
                                    <th style="width: 15%;">Payment Date</th>
                                    <th style="width: 15%; text-align: right;">Amount</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 20%;">Remittance Advice</th>
                                    <th style="width: 7%; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($visiblePaidPayments as $payment)
                                    <tr>
                                        <td class="fw-semibold text-primary">{{ $payment->invoice->invoice_number ?? '—' }}</td>
                                        <td>{{ $payment->invoice->supplier->name ?? '—' }}</td>
                                        <td>{{ $payment->payment_date?->format('M d, Y') ?? '—' }}</td>
                                        <td class="text-end fw-semibold">₱{{ number_format($payment->amount, 2) }}</td>
                                        <td><span class="status-badge status-paid">Paid</span></td>
                                        <td>
                                            <div class="ra-link-wrapper">
                                                <a href="{{ route('ap.payment.remittance', $payment) }}" class="ra-link">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                                    <span>{{ $payment->remittance_number ?? 'Generate PDF' }}</span>
                                                </a>
                                                @if ($payment->remittance_sent_at)
                                                    <span class="ra-sent-info">
                                                        Sent {{ $payment->remittance_sent_at->diffForHumans() }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td style="text-align: center;">
                                            <button type="button"
                                                    class="mail-btn email-supplier-btn"
                                                    data-action="{{ route('ap.payment.remittance.email', $payment) }}"
                                                    data-to="{{ $payment->invoice->supplier->email ?? '' }}"
                                                    data-subject="Remittance Advice - Invoice {{ $payment->invoice->invoice_number ?? '' }}"
                                                    data-message="Please find attached the remittance advice for invoice {{ $payment->invoice->invoice_number ?? '' }}."
                                                    title="Email Supplier">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted py-4">No payments processed yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($paidPayments->count() > $historyVisibleLimit)
                        <div class="view-all-wrap">
                            <button type="button" class="view-all-btn" id="openHistoryViewAll">
                                View All ({{ $paidPayments->count() }} total)
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <div class="right-workspace-column">

            <div class="ap-card">
                <div class="p-4">
                    <div class="section-eyebrow">
                        <span class="bar"></span>
                        <span>Process Payment</span>
                    </div>

                    <form method="POST" action="" id="processForm">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Selected Payment</label>
                            <input type="text" class="form-control" id="selectedPaymentDisplay" readonly placeholder="Select a payment from the table">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Payment Method</label>
                            <select class="form-select" name="payment_method" id="processMethod" required>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Cash">Cash</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Payment Date</label>
                            <input type="date" class="form-control" name="payment_date" value="{{ now()->toDateString() }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Bank Account</label>
                            <input type="text" class="form-control" name="bank_account" placeholder="e.g. BDO - Operating (1231-2313)">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Reference / Batch No.</label>
                            <input type="text" class="form-control" name="reference_number" placeholder="Optional">
                        </div>

                        <button type="submit" class="btn btn-success" id="processSubmitBtn" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                            Process Payment
                        </button>
                    </form>
                </div>
            </div>

            <div class="ap-card">
                <div class="p-4">
                    <div class="section-eyebrow">
                        <span class="bar"></span>
                        <span>Recent Activities</span>
                    </div>

                    @forelse ($recentActivities as $activity)
                        <div class="activity-row">
                            <div class="activity-icon {{ $activity->status === 'scheduled' ? 'scheduled' : 'done' }}">
                                @if ($activity->type === 'remittance_sent')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                @elseif ($activity->type === 'payment_scheduled')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="activity-title">{{ $activity->description }}</div>
                            </div>
                            <div class="activity-time">{{ $activity->created_at->diffForHumans() }}</div>
                        </div>
                    @empty
                        <p class="text-muted mb-0 small">No recent activity.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</div>

{{-- ================= VIEW ALL: READY FOR PROCESSING ================= --}}
<div class="modal" id="viewAllReadyModal">
    <div class="modal-dialog modal-dialog-lg">
        <div class="modal-header">
            <h5 class="fw-bold text-primary mb-0" style="margin: 0; font-size: 18px;">All Payments Ready for Processing</h5>
            <button type="button" class="btn-close" id="closeReadyViewAllX">&times;</button>
        </div>

        <div class="modal-body modal-body-scroll">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Supplier</th>
                            <th>Due Date</th>
                            <th style="text-align: right;">Amount</th>
                            <th>Method</th>
                            <th>Priority</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($readyPayments as $payment)
                            <tr>
                                <td class="fw-semibold text-primary">{{ $payment->invoice->invoice_number ?? '—' }}</td>
                                <td>{{ $payment->invoice->supplier->name ?? '—' }}</td>
                                <td>{{ $payment->invoice->due_date?->format('M d, Y') ?? '—' }}</td>
                                <td class="text-end fw-semibold">₱{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->payment_method }}</td>
                                <td><span class="priority-{{ $payment->priority }}">{{ ucfirst($payment->priority) }}</span></td>
                                <td style="text-align: center;">
                                    <button type="button"
                                            class="btn-outline-success select-payment-btn"
                                            data-id="{{ $payment->id }}"
                                            data-action="{{ route('ap.payment.process', $payment) }}"
                                            data-supplier="{{ $payment->invoice->supplier->name ?? '—' }}"
                                            data-invoice="{{ $payment->invoice->invoice_number ?? '—' }}"
                                            data-amount="₱{{ number_format($payment->amount, 2) }}"
                                            data-method="{{ $payment->payment_method }}">
                                        Select
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">Nothing waiting to be processed.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn-outline-secondary" id="closeReadyViewAllBtn">Close</button>
        </div>
    </div>
</div>

{{-- ================= VIEW ALL: PAYMENT HISTORY ================= --}}
<div class="modal" id="viewAllHistoryModal">
    <div class="modal-dialog modal-dialog-lg">
        <div class="modal-header">
            <h5 class="fw-bold text-primary mb-0" style="margin: 0; font-size: 18px;">All Payment History</h5>
            <button type="button" class="btn-close" id="closeHistoryViewAllX">&times;</button>
        </div>

        <div class="modal-body modal-body-scroll">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Supplier</th>
                            <th>Payment Date</th>
                            <th style="text-align: right;">Amount</th>
                            <th>Status</th>
                            <th>Remittance Advice</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($paidPayments as $payment)
                            <tr>
                                <td class="fw-semibold text-primary">{{ $payment->invoice->invoice_number ?? '—' }}</td>
                                <td>{{ $payment->invoice->supplier->name ?? '—' }}</td>
                                <td>{{ $payment->payment_date?->format('M d, Y') ?? '—' }}</td>
                                <td class="text-end fw-semibold">₱{{ number_format($payment->amount, 2) }}</td>
                                <td><span class="status-badge status-paid">Paid</span></td>
                                <td>
                                    <div class="ra-link-wrapper">
                                        <a href="{{ route('ap.payment.remittance', $payment) }}" class="ra-link">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                            <span>{{ $payment->remittance_number ?? 'Generate PDF' }}</span>
                                        </a>
                                        @if ($payment->remittance_sent_at)
                                            <span class="ra-sent-info">
                                                Sent {{ $payment->remittance_sent_at->diffForHumans() }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <button type="button"
                                            class="mail-btn email-supplier-btn"
                                            data-action="{{ route('ap.payment.remittance.email', $payment) }}"
                                            data-to="{{ $payment->invoice->supplier->email ?? '' }}"
                                            data-subject="Remittance Advice - Invoice {{ $payment->invoice->invoice_number ?? '' }}"
                                            data-message="Please find attached the remittance advice for invoice {{ $payment->invoice->invoice_number ?? '' }}."
                                            title="Email Supplier">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">No payments processed yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn-outline-secondary" id="closeHistoryViewAllBtn">Close</button>
        </div>
    </div>
</div>

<div class="modal" id="emailSupplierModal">
    <div class="modal-dialog">
        <form method="POST" id="emailSupplierForm" action="">
            @csrf

            <div class="modal-header">
                <h5 class="fw-bold text-primary mb-0" style="margin: 0; font-size: 18px;">Email Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="mb-3" style="margin-bottom: 16px;">
                    <label class="form-label fw-semibold small" style="display: block; margin-bottom: 6px;">To</label>
                    <input type="text" class="form-control" id="emailTo" readonly>
                </div>

                <div class="mb-3" style="margin-bottom: 16px;">
                    <label class="form-label fw-semibold small" style="display: block; margin-bottom: 6px;">Subject</label>
                    <input type="text" class="form-control" name="subject" id="emailSubject" required>
                </div>

                <div class="mb-3" style="margin-bottom: 16px;">
                    <label class="form-label fw-semibold small" style="display: block; margin-bottom: 6px;">Message</label>
                    <textarea class="form-control" name="message" id="emailMessage" rows="5" required style="resize: vertical;"></textarea>
                </div>

                <div style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" checked disabled id="attachRemittance">
                    <label for="attachRemittance" class="small text-muted" style="font-size: 12.5px;">Attach Remittance Advice (PDF)</label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn-primary" style="display: flex; align-items: center; gap: 6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    Send Email
                </button>
            </div>
        </form>
    </div>
</div>

@if (session('remittance_sent'))
    <div class="modal show" id="emailSentModal">
        <div class="modal-dialog">
            <div class="modal-body p-4 text-center" style="padding: 32px; text-align: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#18B876" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 16px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <h5 class="fw-bold text-primary" style="font-size: 20px; margin-bottom: 8px;">Email Sent Successfully!</h5>
                <p class="text-muted mb-4" style="font-size: 14px; margin-bottom: 20px;">The Remittance Advice has been successfully emailed to your supplier.</p>

                <div class="text-start bg-light rounded-3 p-3 mb-3" style="font-size:13px; background: #f8fafc; border-radius: 8px; padding: 16px; text-align: left; margin-bottom: 24px; border: 1px solid #e2e8f0;">
                    <div style="margin-bottom:6px;"><strong>To:</strong> {{ session('remittance_sent')['to'] }}</div>
                    <div style="margin-bottom:6px;"><strong>Invoice:</strong> {{ session('remittance_sent')['invoice'] }}</div>
                    <div><strong>Attachment:</strong> {{ session('remittance_sent')['attachment'] }}</div>
                </div>

                <button type="button" class="btn-primary" data-bs-dismiss="modal" style="width: 100%;">Close Panel</button>
            </div>
        </div>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ---- Ready-to-process table: search + filters ----
    const searchInput = document.getElementById('readySearch');
    const methodFilter = document.getElementById('methodFilter');
    const priorityFilter = document.getElementById('priorityFilterReady');
    const readyRows = Array.from(document.querySelectorAll('#readyTable tbody tr[data-search]'));
    const noReadyResults = document.getElementById('noReadyResults');

    function applyReadyFilters() {
        const term = (searchInput?.value || '').toLowerCase().trim();
        const method = methodFilter?.value || '';
        const priority = priorityFilter?.value || '';
        let visible = 0;

        readyRows.forEach(function (row) {
            const matches = (!term || row.getAttribute('data-search').includes(term))
                && (!method || row.getAttribute('data-method') === method)
                && (!priority || row.getAttribute('data-priority') === priority);

            row.style.display = matches ? '' : 'none';
            if (matches) visible++;
        });

        if (noReadyResults) {
            noReadyResults.style.display = (visible === 0 && readyRows.length > 0) ? 'table-row' : 'none';
        }
    }

    [searchInput, methodFilter, priorityFilter].forEach(function (el) {
        if (el) el.addEventListener('input', applyReadyFilters);
    });

    // Run filters initially to guarantee a clean starting render state
    applyReadyFilters();

    // ---- Select a ready payment -> populate Process Payment panel ----
    const processForm = document.getElementById('processForm');
    const processSubmitBtn = document.getElementById('processSubmitBtn');
    const selectedPaymentDisplay = document.getElementById('selectedPaymentDisplay');
    const processMethod = document.getElementById('processMethod');

    function bindSelectPaymentButtons() {
        document.querySelectorAll('.select-payment-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                processForm.action = btn.getAttribute('data-action');
                selectedPaymentDisplay.value = btn.getAttribute('data-invoice') + ' — ' + btn.getAttribute('data-supplier') + ' (' + btn.getAttribute('data-amount') + ')';
                if (processMethod && btn.getAttribute('data-method')) {
                    processMethod.value = btn.getAttribute('data-method');
                }
                processSubmitBtn.disabled = false;

                // If this button lives inside the "View All" modal, close it
                // so the person can see the Process Payment panel update.
                if (btn.closest('#viewAllReadyModal')) {
                    closeReadyViewAll();
                }
            });
        });
    }
    bindSelectPaymentButtons();

    // ================= MODAL MANAGER (PURE VANILLA) =================
    const emailModal = document.getElementById('emailSupplierModal');
    const emailSupplierForm = document.getElementById('emailSupplierForm');
    const emailTo = document.getElementById('emailTo');
    const emailSubject = document.getElementById('emailSubject');
    const emailMessage = document.getElementById('emailMessage');

    function bindEmailSupplierButtons() {
        document.querySelectorAll('.email-supplier-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();

                // If launched from the "View All" history modal, close it first
                // so the email dialog isn't stacked on top of it.
                if (btn.closest('#viewAllHistoryModal')) {
                    closeHistoryViewAll();
                }

                emailSupplierForm.action = btn.getAttribute('data-action');
                emailTo.value = btn.getAttribute('data-to') || '';
                emailSubject.value = btn.getAttribute('data-subject') || '';
                emailMessage.value = btn.getAttribute('data-message') || '';

                emailModal.classList.add('show');
            });
        });
    }
    bindEmailSupplierButtons();

    // Unified closing logic for all custom overlay dialogues
    document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function (closeBtn) {
        closeBtn.addEventListener('click', function () {
            emailModal.classList.remove('show');
            const successModal = document.getElementById('emailSentModal');
            if (successModal) successModal.classList.remove('show');
        });
    });

    // Close on click outside bounds
    window.addEventListener('click', function (e) {
        if (e.target === emailModal) {
            emailModal.classList.remove('show');
        }
        const successModal = document.getElementById('emailSentModal');
        if (successModal && e.target === successModal) {
            successModal.classList.remove('show');
        }
    });

    // ================= VIEW ALL MODALS =================
    const viewAllReadyModal = document.getElementById('viewAllReadyModal');
    const viewAllHistoryModal = document.getElementById('viewAllHistoryModal');

    function openReadyViewAll() { viewAllReadyModal.classList.add('show'); }
    function closeReadyViewAll() { viewAllReadyModal.classList.remove('show'); }
    function openHistoryViewAll() { viewAllHistoryModal.classList.add('show'); }
    function closeHistoryViewAll() { viewAllHistoryModal.classList.remove('show'); }

    const openReadyBtn = document.getElementById('openReadyViewAll');
    const closeReadyX = document.getElementById('closeReadyViewAllX');
    const closeReadyBtn = document.getElementById('closeReadyViewAllBtn');

    if (openReadyBtn) openReadyBtn.addEventListener('click', openReadyViewAll);
    if (closeReadyX) closeReadyX.addEventListener('click', closeReadyViewAll);
    if (closeReadyBtn) closeReadyBtn.addEventListener('click', closeReadyViewAll);

    const openHistoryBtn = document.getElementById('openHistoryViewAll');
    const closeHistoryX = document.getElementById('closeHistoryViewAllX');
    const closeHistoryBtn = document.getElementById('closeHistoryViewAllBtn');

    if (openHistoryBtn) openHistoryBtn.addEventListener('click', openHistoryViewAll);
    if (closeHistoryX) closeHistoryX.addEventListener('click', closeHistoryViewAll);
    if (closeHistoryBtn) closeHistoryBtn.addEventListener('click', closeHistoryViewAll);

    // Click outside the dialog closes it
    window.addEventListener('click', function (e) {
        if (e.target === viewAllReadyModal) closeReadyViewAll();
        if (e.target === viewAllHistoryModal) closeHistoryViewAll();
    });

    // Esc key closes whichever view-all modal is open
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        if (viewAllReadyModal.classList.contains('show')) closeReadyViewAll();
        if (viewAllHistoryModal.classList.contains('show')) closeHistoryViewAll();
    });

});
</script>

@endsection