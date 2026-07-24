<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;

class CustomerApiController extends Controller
{
    /**
     * GET /api/v1/customers
     */
    public function index()
    {
        return response()->json(
            Customer::orderBy('name')->get()
        );
    }

    /**
     * GET /api/v1/customers/{customer}
     */
    public function show(Customer $customer)
    {
        return response()->json(
            $customer->load('salesInvoices')
        );
    }
}