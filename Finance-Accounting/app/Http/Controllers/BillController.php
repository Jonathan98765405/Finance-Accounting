<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming data from the Procurement app
        $validated = $request->validate([
            'po_number' => 'required|string|unique:bills,po_number',
            'vendor_id' => 'required',
            'total_amount' => 'required|numeric',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric',
            'items.*.unit_price' => 'required|numeric',
            'items.*.total_price' => 'required|numeric',
        ]);

        // Create the AP Bill/Invoice in the Finance database
        $bill = Bill::create([
            'po_number' => $validated['po_number'],
            'vendor_id' => $validated['vendor_id'],
            'total_amount' => $validated['total_amount'],
            'status' => 'pending_payment',
        ]);

        foreach ($validated['items'] as $item) {
            $bill->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price'],
            ]);
        }

        return response()->json([
            'message' => 'Successfully synced to AP',
            'bill_id' => $bill->id
        ], 200);
    }
}