<?php

namespace App\Services;

use App\Models\GeneralLedger\Account;
use App\Models\GeneralLedger\Entry;
use App\Models\GeneralLedger\EntryLine;
use App\Models\AccountPayable\Invoice as ApInvoice;
use App\Models\AccountPayable\Payment as ApPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Unified General Ledger Service handling Accounts Payable, Accounts Receivable, and Fixed Assets.
 */
class GeneralLedgerService
{
    /**
     * Shared & AP/AR Account Codes
     */
    protected const CASH_ACCOUNT_CODE = '1000';
    protected const AR_ACCOUNT_CODE = '1100';
    protected const AP_LIABILITY_ACCOUNT_CODE = '2000';
    protected const REVENUE_ACCOUNT_CODE = '4000';
    protected const DEFAULT_EXPENSE_ACCOUNT_CODE = '5100';
    protected const DEFAULT_EXPENSE_ACCOUNT_NAME = 'Accounts Payable Purchases';

    /* =========================================================
     * CORE / GENERAL ENTRY METHODS
     * ========================================================= */

    /**
     * Create a balanced journal entry.
     * $lines = [['account_code' => '1500', 'debit' => 100, 'credit' => 0], ...]
     */
    public function postEntry(string $date, string $description, array $lines, string $refPrefix = 'JE', ?string $reference = null): Entry
    {
        $totalDebit = array_sum(array_column($lines, 'debit'));
        $totalCredit = array_sum(array_column($lines, 'credit'));

        if (round($totalDebit, 2) !== round($totalCredit, 2)) {
            throw new \RuntimeException("GL entry not balanced: debit {$totalDebit} vs credit {$totalCredit}");
        }

        return DB::transaction(function () use ($date, $description, $lines, $refPrefix, $reference) {
            // A deterministic reference (e.g. tied to an asset tag or invoice
            // number) lets us look the entry back up later to reverse it, and
            // makes re-posting idempotent instead of creating duplicates.
            if ($reference) {
                if ($existing = Entry::where('reference', $reference)->first()) {
                    return $existing;
                }
            } else {
                $reference = $refPrefix . '-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));
            }

            $entry = Entry::create([
                'entry_date'  => $date,
                'reference'   => $reference,
                'description' => $description,
                'status'      => 'Posted',
            ]);

            foreach ($lines as $line) {
                $account = Account::where('account_code', $line['account_code'])->firstOrFail();

                EntryLine::create([
                    'gl_entry_id'   => $entry->id,
                    'gl_account_id' => $account->id,
                    'debit'         => $line['debit'] ?? 0,
                    'credit'        => $line['credit'] ?? 0,
                ]);
            }

            return $entry;
        });
    }

    protected function accountId(string $code): int
    {
        $id = Account::where('account_code', $code)->value('id');

        if (! $id) {
            throw new \RuntimeException(
                "GL account with code {$code} was not found. Please add it to gl_accounts."
            );
        }

        return $id;
    }

    /**
     * Delete the entry (and its lines) matching an exact reference.
     * Returns true if an entry was found and removed.
     */
    public function reverseEntry(string $reference): bool
    {
        $entry = Entry::where('reference', $reference)->first();

        if (! $entry) {
            return false;
        }

        $entry->lines()->delete();
        $entry->delete();

        return true;
    }

    /**
     * Delete every entry (and its lines) whose reference matches a
     * SQL LIKE pattern, e.g. "FA-%-FA-2026-003%" to sweep up every
     * acquisition/depreciation/disposal entry ever posted for one asset.
     * Returns the number of entries removed.
     */
    public function reverseEntriesLike(string $referencePattern): int
    {
        $entries = Entry::where('reference', 'like', $referencePattern)->get();

        foreach ($entries as $entry) {
            $entry->lines()->delete();
            $entry->delete();
        }

        return $entries->count();
    }

    /* =========================================================
     * ACCOUNTS PAYABLE (AP) METHODS
     * ========================================================= */

    /**
     * Post the liability side of an invoice once it clears three-way match.
     */
    public function postInvoiceApproved(ApInvoice $invoice): Entry
    {
        $reference = "AP-INV-{$invoice->invoice_number}";

        if ($existing = Entry::where('reference', $reference)->first()) {
            return $existing;
        }

        return DB::transaction(function () use ($invoice, $reference) {
            $apAccount = $this->apLiabilityAccount();
            $expenseAccount = $this->defaultExpenseAccount();

            $entry = Entry::create([
                'entry_date' => $invoice->invoice_date,
                'reference' => $reference,
                'description' => "AP Invoice {$invoice->invoice_number} — {$invoice->supplier->name}",
                'status' => 'Posted',
            ]);

            $entry->lines()->create([
                'gl_account_id' => $expenseAccount->id,
                'debit' => $invoice->total_amount,
                'credit' => 0,
            ]);

            $entry->lines()->create([
                'gl_account_id' => $apAccount->id,
                'debit' => 0,
                'credit' => $invoice->total_amount,
            ]);

            return $entry;
        });
    }

    /**
     * Post the cash-settlement side once an AP payment is marked paid.
     */
    public function postPaymentCompleted(ApPayment $payment): Entry
    {
        $reference = "AP-PAY-{$payment->reference_number}";

        if ($existing = Entry::where('reference', $reference)->first()) {
            return $existing;
        }

        return DB::transaction(function () use ($payment, $reference) {
            $apAccount = $this->apLiabilityAccount();
            $cashAccount = $this->cashAccount();

            $entry = Entry::create([
                'entry_date' => $payment->payment_date ?? now()->toDateString(),
                'reference' => $reference,
                'description' => "Payment {$payment->reference_number} — Invoice {$payment->invoice->invoice_number} ({$payment->invoice->supplier->name})",
                'status' => 'Posted',
            ]);

            $entry->lines()->create([
                'gl_account_id' => $apAccount->id,
                'debit' => $payment->amount,
                'credit' => 0,
            ]);

            $entry->lines()->create([
                'gl_account_id' => $cashAccount->id,
                'debit' => 0,
                'credit' => $payment->amount,
            ]);

            return $entry;
        });
    }

    /**
     * Undo the liability entry posted when an invoice was approved.
     * Call this before/while deleting an AP invoice.
     */
    public function reverseInvoiceApproved(ApInvoice $invoice): bool
    {
        return $this->reverseEntry("AP-INV-{$invoice->invoice_number}");
    }

    /**
     * Undo the cash-settlement entry posted when a payment completed.
     * Call this before/while deleting an AP payment (or an invoice's
     * payments, when the invoice itself is deleted).
     */
    public function reversePaymentCompleted(ApPayment $payment): bool
    {
        return $this->reverseEntry("AP-PAY-{$payment->reference_number}");
    }

    protected function apLiabilityAccount(): Account
    {
        return Account::where('account_code', self::AP_LIABILITY_ACCOUNT_CODE)->firstOrFail();
    }

    protected function cashAccount(): Account
    {
        return Account::where('account_code', self::CASH_ACCOUNT_CODE)->firstOrFail();
    }

    protected function defaultExpenseAccount(): Account
    {
        return Account::firstOrCreate(
            ['account_code' => self::DEFAULT_EXPENSE_ACCOUNT_CODE],
            [
                'account_name' => self::DEFAULT_EXPENSE_ACCOUNT_NAME,
                'account_type' => 'Expense',
                'status' => 'Active',
            ]
        );
    }

    /* =========================================================
     * ACCOUNTS RECEIVABLE (AR) METHODS
     * ========================================================= */

    /**
     * Dr Accounts Receivable / Cr Sales Revenue — for a newly created invoice.
     */
    public function postInvoiceCreated($invoice): Entry
    {
        return DB::transaction(function () use ($invoice) {
            $entry = Entry::create([
                'entry_date'  => $invoice->invoice_date,
                'reference'   => $this->invoiceReference($invoice),
                'description' => 'AR Invoice ' . $invoice->invoice_number . ' - ' . optional($invoice->customer)->customer_name,
                'status'      => 'Posted',
            ]);

            EntryLine::create([
                'gl_entry_id'   => $entry->id,
                'gl_account_id' => $this->accountId(self::AR_ACCOUNT_CODE),
                'debit'         => $invoice->total,
                'credit'        => 0,
            ]);

            EntryLine::create([
                'gl_entry_id'   => $entry->id,
                'gl_account_id' => $this->accountId(self::REVENUE_ACCOUNT_CODE),
                'debit'         => 0,
                'credit'        => $invoice->total,
            ]);

            return $entry;
        });
    }

    /**
     * Invoice line items / total changed — drop the old entry for this
     * invoice and repost a fresh balanced one.
     */
    public function postInvoiceUpdated($invoice): Entry
    {
        return DB::transaction(function () use ($invoice) {
            $this->reverseInvoice($invoice);

            return $this->postInvoiceCreated($invoice);
        });
    }

    /**
     * Invoice deleted — remove its journal entry and lines entirely.
     */
    public function reverseInvoice($invoice): void
    {
        $this->reverseEntry($this->invoiceReference($invoice));
    }

    /**
     * Dr Cash on Hand / Cr Accounts Receivable — for a recorded payment.
     */
    public function postPaymentReceived($payment): Entry
    {
        return DB::transaction(function () use ($payment) {
            $entry = Entry::create([
                'entry_date'  => $payment->payment_date,
                'reference'   => 'AR-PAY-' . $payment->reference_no,
                'description' => 'AR Payment ' . $payment->reference_no . ' - ' . optional($payment->customer)->customer_name,
                'status'      => 'Posted',
            ]);

            EntryLine::create([
                'gl_entry_id'   => $entry->id,
                'gl_account_id' => $this->accountId(self::CASH_ACCOUNT_CODE),
                'debit'         => $payment->amount,
                'credit'        => 0,
            ]);

            EntryLine::create([
                'gl_entry_id'   => $entry->id,
                'gl_account_id' => $this->accountId(self::AR_ACCOUNT_CODE),
                'debit'         => 0,
                'credit'        => $payment->amount,
            ]);

            return $entry;
        });
    }

    protected function invoiceReference($invoice): string
    {
        return 'AR-INV-' . $invoice->invoice_number;
    }

    /* =========================================================
     * FIXED ASSETS METHODS
     * ========================================================= */

    /**
     * Debit Fixed Assets, Credit Cash on Hand.
     */
    public function postAssetAcquisition($asset): Entry
    {
        return $this->postEntry(
            $asset->acquisition_date->format('Y-m-d'),
            "Acquisition of asset {$asset->asset_tag} - {$asset->asset_name}",
            [
                ['account_code' => '1500', 'debit' => $asset->acquisition_cost, 'credit' => 0],
                ['account_code' => '1000', 'debit' => 0, 'credit' => $asset->acquisition_cost],
            ],
            'FA-ACQ',
            "FA-ACQ-{$asset->asset_tag}"
        );
    }

    /**
     * Debit Depreciation Expense, Credit Accumulated Depreciation.
     */
    public function postDepreciation($asset, float $expenseAmount, string $periodDate): Entry
    {
        return $this->postEntry(
            $periodDate,
            "Depreciation expense for asset {$asset->asset_tag} - {$asset->asset_name}",
            [
                ['account_code' => '5200', 'debit' => $expenseAmount, 'credit' => 0],
                ['account_code' => '1510', 'debit' => 0, 'credit' => $expenseAmount],
            ],
            'FA-DEP',
            'FA-DEP-' . $asset->asset_tag . '-' . str_replace('-', '', $periodDate)
        );
    }

    /**
     * Remove asset cost + accumulated depreciation from books,
     * record cash received (if any) and any gain/loss on disposal.
     */
    public function postDisposal($asset): Entry
    {
        $lines = [
            ['account_code' => '1510', 'debit' => $asset->accumulated_depreciation, 'credit' => 0],
            ['account_code' => '1500', 'debit' => 0, 'credit' => $asset->acquisition_cost],
        ];

        $cashReceived = $asset->disposal_value ?? 0;
        if ($cashReceived > 0) {
            $lines[] = ['account_code' => '1000', 'debit' => $cashReceived, 'credit' => 0];
        }

        $gainLoss = $asset->gain_loss ?? 0;
        if ($gainLoss > 0) {
            $lines[] = ['account_code' => '7000', 'debit' => 0, 'credit' => $gainLoss];
        } elseif ($gainLoss < 0) {
            $lines[] = ['account_code' => '7000', 'debit' => abs($gainLoss), 'credit' => 0];
        }

        return $this->postEntry(
            $asset->disposal_date->format('Y-m-d'),
            "Disposal of asset {$asset->asset_tag} - {$asset->asset_name} ({$asset->disposal_reason})",
            $lines,
            'FA-DISP',
            "FA-DISP-{$asset->asset_tag}"
        );
    }

    /**
     * Remove every GL entry ever posted for this asset — acquisition,
     * every depreciation period, and disposal (if any). Call this before
     * deleting a fixed asset so the GL doesn't keep orphaned balances
     * for an asset that no longer exists.
     */
    public function reverseAssetEntries($asset): int
    {
        return $this->reverseEntriesLike("FA-%-{$asset->asset_tag}%");
    }
}