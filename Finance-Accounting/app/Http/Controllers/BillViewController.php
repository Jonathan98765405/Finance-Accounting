<?php

namespace App\Http\Controllers;

use App\Models\Bill;

class BillViewController extends Controller
{
    public function index()
    {
        $bills = Bill::with('items')->latest()->paginate(15);

        return view('bills.index', compact('bills'));
    }

    public function show(Bill $bill)
    {
        $bill->load('items');

        return view('bills.show', compact('bill'));
    }
}