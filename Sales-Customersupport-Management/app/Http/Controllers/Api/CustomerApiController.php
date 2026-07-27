<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;

class CustomerApiController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('name')->get();

        return CustomerResource::collection($customers);
    }

    public function show(Customer $customer)
    {
        $customer->load('salesInvoices');

        return new CustomerResource($customer);
    }
}