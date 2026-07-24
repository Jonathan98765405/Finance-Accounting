@extends('layouts.app')

@section('page-title', 'Edit Journal Entry')
@section('page-title-heading', 'Edit Journal Entry')
@section('page-subtitle', 'Update an existing financial transaction.')

@section('content')

@if ($errors->any())
    <div class="bg-brand-red/10 border border-brand-red/30 text-brand-red p-4 rounded-xl mb-6">
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="max-w-3xl bg-white rounded-2xl shadow-card border border-slate-200 p-8">
    <form action="{{ route('ledger.update', $entry->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label class="block mb-2 text-sm font-medium">Date</label>
        <input type="date" name="entry_date" value="{{ old('entry_date', \Carbon\Carbon::parse($entry->entry_date)->format('Y-m-d')) }}"
            class="border border-slate-200 p-3 rounded-xl w-full mb-4">

        <label class="block mb-2 text-sm font-medium">Reference</label>
        <input type="text" name="reference" value="{{ old('reference', $entry->reference) }}"
            class="border border-slate-200 p-3 rounded-xl w-full mb-4">

        <label class="block mb-2 text-sm font-medium">Description</label>
        <input type="text" name="description" value="{{ old('description', $entry->description) }}"
            class="border border-slate-200 p-3 rounded-xl w-full mb-6">

        <button type="submit" class="bg-navy hover:bg-navy-700 text-white px-6 py-3 rounded-xl shadow-card">
            Update Entry
        </button>
        <a href="{{ route('ledger.index') }}" class="ml-3 text-slate-600 hover:underline">
            Cancel
        </a>
    </form>
</div>

@endsection