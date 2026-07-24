<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AccountPayable\PurchaseOrder as ApPurchaseOrder;
use App\Models\AccountPayable\Supplier;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming data from the Procurement app
        $validated = $request->validate([
            'po_number'            => 'required|string|unique:bills,po_number',
            'vendor_id'            => 'required',
            'vendor_name'          => 'nullable|string',
            'vendor_email'         => 'nullable|email',
            'vendor_phone'         => 'nullable|string',
            'total_amount'         => 'required|numeric',
            'items'                => 'required|array|min:1',
            'items.*.description'  => 'required|string',
            'items.*.quantity'     => 'required|numeric',
            'items.*.unit_price'   => 'required|numeric',
            'items.*.total_price'  => 'required|numeric',
        ]);

        [$bill, $apPurchaseOrder] = DB::transaction(function () use ($validated) {

            // 1. Create the AP Bill record (unchanged from before)
            $bill = Bill::create([
                'po_number'    => $validated['po_number'],
                'vendor_id'    => $validated['vendor_id'],
                'total_amount' => $validated['total_amount'],
                'status'       => 'pending_payment',
                'ap_synced_at' => now(),
            ]);

            foreach ($validated['items'] as $item) {
                $bill->items()->create([
                    'description' => $item['description'],
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $item['unit_price'],
                    'total_price' => $item['total_price'],
                ]);
            }

            // 2. Find or create a matching Supplier, so the synced PO shows
            //    up on the Accounts Payable > Purchase Orders page.
            $supplierName = $validated['vendor_name'] ?? ('External Vendor #' . $validated['vendor_id']);

            $supplier = Supplier::firstOrCreate(
                ['name' => $supplierName],
                [
                    'email' => $validated['vendor_email'] ?? null,
                    'phone' => $validated['vendor_phone'] ?? null,
                ]
            );

            // 3. Create the matching AP Purchase Order + items so it appears
            //    automatically on the existing Purchase Orders dashboard.
            $apPurchaseOrder = ApPurchaseOrder::create([
                'po_number'    => $validated['po_number'],
                'supplier_id'  => $supplier->id,
                'po_date'      => now(),
                'subtotal'     => $validated['total_amount'],
                'tax_amount'   => 0,
                'total_amount' => $validated['total_amount'],
                'status'       => 'pending',
            ]);

            foreach ($validated['items'] as $item) {
                // NOTE: App\Models\AccountPayable\PurchaseOrderItem's fillable
                // fields are 'description' and 'amount' (not 'item_description'/
                // 'total_price' as the older poStore() controller uses — that's
                // a pre-existing mismatch in that method, left alone here).
                $apPurchaseOrder->items()->create([
                    'description' => $item['description'],
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $item['unit_price'],
                    'amount'      => $item['total_price'],
                ]);
            }

            return [$bill, $apPurchaseOrder];
        });

        return response()->json([
            'message'                => 'Successfully synced to AP',
            'bill_id'                => $bill->id,
            'ap_purchase_order_id'   => $apPurchaseOrder->id,
        ], 200);
    }
}