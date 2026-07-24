<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RequisitionController extends Controller
{
    public function index()
    {
        $requisitions = Requisition::with('user', 'items')->latest()->paginate(15);
        return view('requisitions.index', compact('requisitions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purpose' => 'nullable|string|max:500',
            'items'   => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.unit_price'  => 'required|numeric|min:0',
        ]);

        $requisition = DB::transaction(function () use ($validated) {
            $total = collect($validated['items'])->sum(
                fn ($item) => $item['quantity'] * $item['unit_price']
            );

            $requisition = Requisition::create([
                'requisition_number' => $this->generateRequisitionNumber(),
                'user_id'            => auth()->id(),
                'purpose'            => $validated['purpose'] ?? null,
                'status'             => 'pending_approval',
                'total_amount'       => $total,
            ]);

            foreach ($validated['items'] as $item) {
                $requisition->items()->create([
                    'description' => $item['description'],
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            return $requisition;
        });

        return redirect()->route('requisitions.index')->with('success', 'Requisition submitted.');
    }

    private function generateRequisitionNumber(): string
    {
        do {
            $number = 'PR-' . strtoupper(Str::random(8));
        } while (Requisition::where('requisition_number', $number)->exists());

        return $number;
    }
}