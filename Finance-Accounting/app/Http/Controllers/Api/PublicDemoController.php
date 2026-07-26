<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AccountPayable\Invoice;
use Illuminate\Http\JsonResponse;

class PublicDemoController extends Controller
{
    /**
     * Public, no-auth demo endpoint — returns real Accounts Payable
     * invoice data as JSON. Visiting this URL directly in a browser
     * prints the JSON straight to the page, same as the
     * "sample/api/employee" example: no login, no token needed.
     *
     * This is separate from the secured /v1/ap/... routes (which still
     * require auth:sanctum) — it exists only for demo/presentation
     * purposes, so it deliberately exposes a small, safe slice of data.
     */
    public function invoices(): JsonResponse
    {
        $invoices = Invoice::with('supplier:id,name,email')
            ->select('id', 'invoice_number', 'supplier_id', 'invoice_date', 'due_date', 'total_amount', 'status')
            ->latest('id') // most recently created, not manually-entered invoice_date
            ->limit(20)
            ->get();

        return response()->json([
            'module' => 'Finance & Accounting - Accounts Payable',
            'count' => $invoices->count(),
            'data' => $invoices,
        ]);
    }
}