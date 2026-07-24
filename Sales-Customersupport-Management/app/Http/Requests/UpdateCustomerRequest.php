<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // $this->customer refers to the route-model-bound Customer instance
        // (route: customers.update -> {customer}), so the unique email check
        // ignores the record being edited.
        $customerId = $this->route('customer')->id ?? null;

        return [
            'name'       => ['required', 'string', 'max:255'],
            'address'    => ['nullable', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255', Rule::unique('customers', 'email')->ignore($customerId)],
            'contact_no' => ['nullable', 'string', 'max:255'],
            'status'     => ['required', 'in:active,inactive'],
        ];
    }
}