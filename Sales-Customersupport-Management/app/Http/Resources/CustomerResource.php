<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'display_id' => $this->display_id,
            'name' => $this->name,
            'address' => $this->address,
            'email' => $this->email,
            'contact_no' => $this->contact_no,
            'status' => $this->status,
            'created_at' => $this->created_at?->format('M d, Y h:i A'),
            'updated_at' => $this->updated_at?->format('M d, Y h:i A'),
            'sales_invoices' => $this->whenLoaded('salesInvoices', function () {
                return $this->salesInvoices->map(fn ($invoice) => [
                    'id' => $invoice->id,
                    'invoice_no' => $invoice->invoice_no,
                    'invoice_date' => $invoice->invoice_date?->format('M d, Y'),
                    'due_date' => $invoice->due_date?->format('M d, Y'),
                    'total_amount' => (float) $invoice->total_amount,
                    'balance' => (float) $invoice->balance,
                    'payment_status' => $invoice->payment_status,
                ]);
            }),
        ];
    }
}