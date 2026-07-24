<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Requisition;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $orders = PurchaseOrder::with('vendor', 'creator')->latest()->paginate(15);
        return view('purchase_orders.index', compact('orders'));
    }

    public function create()
    {
        $vendors = Vendor::all();
        $requisitions = Requisition::where('status', 'approved')
            ->whereDoesntHave('purchaseOrder')
            ->get();

        $suggestedPoNumber = $this->generatePoNumber();

        return view('purchase_orders.create', compact('vendors', 'requisitions', 'suggestedPoNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id'       => 'required|exists:vendors,id',
            'requisition_id'  => 'nullable|exists:requisitions,id',
            'po_number'       => 'nullable|string|unique:purchase_orders,po_number',
            'items'           => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity'    => 'required|numeric|min:0.01',
            'items.*.unit_price'  => 'required|numeric|min:0',
        ]);

        $totalAmount = collect($validated['items'])->sum(
            fn ($item) => $item['quantity'] * $item['unit_price']
        );

        $po = DB::transaction(function () use ($validated, $totalAmount) {
            $po = PurchaseOrder::create([
                'po_number'      => $validated['po_number'] ?? $this->generatePoNumber(),
                'vendor_id'      => $validated['vendor_id'],
                'requisition_id' => $validated['requisition_id'] ?? null,
                'created_by'     => auth()->id(),
                'total_amount'   => $totalAmount,
                // Approval step removed — POs go straight to "approved" so
                // Sync to AP is available immediately after creation.
                'status'         => 'approved',
            ]);

            foreach ($validated['items'] as $item) {
                $po->items()->create([
                    'description' => $item['description'],
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            return $po;
        });

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order created and ready to sync.');
    }

    public function convertFromRequisition(Requisition $requisition, Request $request)
    {
        if ($requisition->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved requisitions can be converted to a Purchase Order.');
        }

        if ($requisition->purchaseOrder()->exists()) {
            return redirect()->back()->with('error', 'This requisition has already been converted to a Purchase Order.');
        }

        $request->validate(['vendor_id' => 'required|exists:vendors,id']);

        $po = DB::transaction(function () use ($requisition, $request) {
            $po = PurchaseOrder::create([
                'po_number'      => $this->generatePoNumber(),
                'vendor_id'      => $request->vendor_id,
                'requisition_id' => $requisition->id,
                'created_by'     => auth()->id(),
                'total_amount'   => $requisition->total_amount,
                // Approval step removed — see note in store() above.
                'status'         => 'approved',
            ]);

            foreach ($requisition->items as $item) {
                $po->items()->create([
                    'description' => $item->description,
                    'quantity'    => $item->quantity,
                    'unit_price'  => $item->unit_price,
                    'total_price' => $item->total_price,
                ]);
            }

            $requisition->update(['status' => 'ordered']);

            return $po;
        });

        return redirect()->route('purchase-orders.index')->with('success', 'PO created from requisition and ready to sync.');
    }

    // Handoff to external Accounts Payable (AP) app via Sanctum API
    public function syncToAP(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved POs can be sent to AP.');
        }

        if ($purchaseOrder->ap_synced_at) {
            return redirect()->back()->with('error', 'This PO has already been synced to AP.');
        }

        try {
            // Load the vendor so we can send its name/email/phone along with
            // the sync — this lets Finance-Accounting create a matching
            // Supplier record instead of falling back to a placeholder name.
            $vendor = $purchaseOrder->vendor;

            $response = Http::withToken(config('services.finance.token'))
                ->withHeaders(['Accept' => 'application/json'])
                ->timeout(15)
                ->post(config('services.finance.url') . '/purchase-orders/sync', [
                    'po_number'     => $purchaseOrder->po_number,
                    'vendor_id'     => $purchaseOrder->vendor_id,
                    'vendor_name'   => $vendor?->name,
                    'vendor_email'  => $vendor?->email,
                    'vendor_phone'  => $vendor?->phone,
                    'total_amount'  => $purchaseOrder->total_amount,
                    'items'         => $purchaseOrder->items->toArray(),
                ]);

            if ($response->successful()) {
                $purchaseOrder->update([
                    'status'        => 'sent_to_ap',
                    'ap_synced_at'  => now(),
                ]);

                return redirect()->back()->with('success', 'Purchase Order successfully synced to AP.');
            }

            return redirect()->back()->with('error', 'Finance app rejected the sync: ' . $response->json('message', $response->body()));

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return redirect()->back()->with('error', 'Could not connect to the Finance application.');
        } catch (\Exception $e) {
            report($e);
            return redirect()->back()->with('error', 'Unexpected error while syncing to AP.');
        }
    }

    private function generatePoNumber(): string
    {
        do {
            $number = 'PO-' . strtoupper(Str::random(8));
        } while (PurchaseOrder::where('po_number', $number)->exists());

        return $number;
    }
}