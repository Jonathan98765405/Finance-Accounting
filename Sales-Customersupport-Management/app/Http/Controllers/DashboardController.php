<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\SalesInvoice;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth();

        $stats = [
            [
                'label' => 'Total Customers',
                'value' => number_format(Customer::count()),
                'change' => '+' . Customer::where('created_at', '>=', $startOfMonth)->count() . ' this month',
                'icon' => 'users',
                'color' => 'blue',
            ],
            [
                'label' => 'Total Invoices',
                'value' => number_format(SalesInvoice::count()),
                'change' => '+' . SalesInvoice::where('created_at', '>=', $startOfMonth)->count() . ' this month',
                'icon' => 'document',
                'color' => 'green',
            ],
            [
                'label' => 'Total Sales',
                'value' => '₱' . number_format((float) SalesInvoice::sum('total_amount'), 2),
                'change' => '+' . number_format((float) SalesInvoice::where('created_at', '>=', $startOfMonth)->sum('total_amount'), 2) . ' this month',
                'icon' => 'peso',
                'color' => 'amber',
            ],
            [
                'label' => 'Paid Invoices',
                'value' => number_format(SalesInvoice::where('payment_status', 'Paid')->count()),
                'change' => '+' . SalesInvoice::where('payment_status', 'Paid')->where('created_at', '>=', $startOfMonth)->count() . ' this month',
                'icon' => 'check',
                'color' => 'purple',
            ],
        ];

        $invoices = SalesInvoice::with('customer')
            ->orderByDesc('invoice_date')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        $user = [
            'name' => 'Juan Dela Cruz',
            'role' => 'Administrator',
        ];

        $dateRange = Carbon::now()->startOfWeek()->format('F j, Y') . ' - ' . Carbon::now()->endOfWeek()->format('F j, Y');

        return view('dashboard', compact('stats', 'invoices', 'user', 'dateRange'));
    }
}