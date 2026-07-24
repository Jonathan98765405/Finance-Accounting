<?php

namespace App\Http\Controllers;

use App\Services\SalesSyncService;
use Illuminate\Http\Request;

class SalesSyncController extends Controller
{
    protected SalesSyncService $syncService;

    public function __construct(SalesSyncService $syncService)
    {
        $this->syncService = $syncService;
    }

    public function sync(Request $request)
    {
        try {
            $result = $this->syncService->syncAll();

            return response()->json([
                'success' => true,
                'message' => "Na-sync: {$result['customers']} customers, {$result['invoices']} invoices.",
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage(),
            ], 500);

        }
    }
}