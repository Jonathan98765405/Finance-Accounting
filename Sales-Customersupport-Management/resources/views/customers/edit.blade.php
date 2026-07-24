@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')

    <div class="mb-6">
        <a href="{{ route('customers.index') }}" class="text-sm text-slate-500 hover:text-slate-700">← Back to Customers</a>
        <h1 class="text-2xl font-bold text-slate-900 mt-2">Edit Customer</h1>
        <p class="text-sm text-slate-500 mt-1">{{ $customer->display_id }} — {{ $customer->name }}</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 max-w-xl">
        <form method="POST" action="{{ route('customers.update', $customer) }}" class="space-y-5">
            @csrf
            @method('PUT')
            @include('customers._form', ['customer' => $customer])

            <div class="pt-2">
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white text-sm font-medium rounded-lg px-4 py-2.5 transition">
                    Update Customer
                </button>
            </div>
        </form>
    </div>

@endsection