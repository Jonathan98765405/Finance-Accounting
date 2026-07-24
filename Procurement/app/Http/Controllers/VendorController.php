<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::latest()->paginate(15);
        return view('vendors.index', compact('vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:vendors,code',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'payment_terms' => 'required|string',
        ]);

        Vendor::create($validated);
        return redirect()->back()->with('success', 'Vendor created successfully.');
    }
}