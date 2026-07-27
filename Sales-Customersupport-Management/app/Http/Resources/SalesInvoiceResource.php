<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesInvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_no' => $this->invoice_no,
            'customer_id' => $this->customer_id,
            'billing_address' => $this->billing_address,
            'billing_email' => $this->billing_email,
            'billing_phone' => $this->billing_phone,
            'invoice_date' => $this->invoice_date?->format('M d, Y'),
            'due_date' => $this->due_date?->format('M d, Y'),
            'payment_terms' => $this->payment_terms,
            'total_amount' => $this->total_amount,
            'balance' => $this->balance,
            'subtotal' => $this->subtotal,
            'tax_amount' => $this->tax_amount,
            'discount_amount' => $this->discount_amount,
            'payment_status' => $this->payment_status,
            'created_at' => $this->created_at?->format('M d, Y h:i A'),
            'updated_at' => $this->updated_at?->format('M d, Y h:i A'),
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'items' => SalesInvoiceItemResource::collection($this->whenLoaded('items')),
        ];
    }
}