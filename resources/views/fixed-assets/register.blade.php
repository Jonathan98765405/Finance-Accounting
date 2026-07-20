@extends('layouts.app')

@section('title', 'Asset Registration')
@section('active', 'Fixed Assets')

@section('page-title','Fixed Assets')
@section('page-title-heading','Fixed Assets')
@section('page-subtitle','Manage company fixed assets.')

@section('content')

    {{-- Header --}}
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-3xl font-bold" style="color:#173A66;">Fixed Assets</h1>
            <p class="text-gray-500 mt-1">Manage, track, and maintain all company assets.</p>
        </div>
        <div class="flex flex-col items-end gap-3">
            <a href="{{ url('/fixed-assets') }}"
               class="px-4 py-2 rounded-md border border-gray-300 text-sm font-medium text-gray-700 bg-white shadow-sm hover:bg-gray-50">
                Back to Asset List
            </a>
            <div class="flex gap-3">
                <button class="px-5 py-2 rounded-md text-white text-sm font-semibold shadow" style="background:#22B57A;">
                    Registration
                </button>
                <a href="{{ url('/fixed-assets/assignment') }}"
                   class="px-5 py-2 rounded-md text-white text-sm font-semibold shadow" style="background:#173A66;">
                    Assignment
                </a>
            </div>
        </div>
    </div>

    {{-- Sub-header --}}
    <div>
        <h2 class="text-lg font-bold" style="color:#173A66;">Asset Registration</h2>
        <p class="text-gray-500 text-sm mt-0.5">Register a new fixed asset or update and existing asset record</p>
    </div>

    @if (session('success'))
        <div class="rounded-md p-3" style="background:#D6F5DF;color:#16A34A;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-md p-3" style="background:#FEE2E2;color:#DC2626;">
            <strong>May mga kulang o maling laman sa form:</strong>
            <ul class="list-disc ml-5 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/fixed-assets/register') }}" method="POST" class="grid grid-cols-3 gap-5 items-start">
        @csrf

        {{-- ============ LEFT COLUMN (form) ============ --}}
        <div class="col-span-2 space-y-5">

            {{-- Asset Information --}}
            <div class="bg-white rounded-lg border border-gray-200 p-5">
                <h3 class="flex items-center gap-2 font-bold mb-4" style="color:#173A66;">
                    <i class="fa-solid fa-file-pen"></i> Asset Information
                </h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Asset ID</label>
                        <input type="text" value="{{ $nextTag }}" disabled
                               class="w-full px-3 py-2 rounded-md border border-gray-200 bg-gray-100 text-sm text-gray-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Asset Name <span class="text-red-500">*</span></label>
                        <input type="text" name="asset_name" value="{{ old('asset_name') }}" placeholder="Enter asset name" required
                               class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Category <span class="text-red-500">*</span></label>
                        <select name="category_id" required
                                class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option value="">Select category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->category_id }}" {{ old('category_id') == $cat->category_id ? 'selected' : '' }}>
                                    {{ $cat->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Serial Number</label>
                        <input type="text" placeholder="Enter serial number"
                               class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Purchase Date <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="date" name="acquisition_date" value="{{ old('acquisition_date') }}" required
                                   class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Purchase Cost <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="acquisition_cost" value="{{ old('acquisition_cost') }}" placeholder="Enter purchase cost" required
                               class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Supplier</label>
                        <select class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option>Select Supplier</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Warranty</label>
                        <input type="text" placeholder="Enter warranty in year"
                               class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>
                </div>
            </div>

            {{-- Assignment Information --}}
            <div class="bg-white rounded-lg border border-gray-200 p-5">
                <h3 class="flex items-center gap-2 font-bold mb-4" style="color:#173A66;">
                    <i class="fa-solid fa-users"></i> Assignment Information
                </h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Department</label>
                        <select class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option>Select department</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Location</label>
                        <input type="text" name="location" value="{{ old('location') }}" placeholder="Enter location"
                               class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Assigned To</label>
                        <select class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option>Select employee</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                        <select name="status" class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option value="active">Active</option>
                            <option value="under_maintenance">Under Maintenance</option>
                            <option value="disposed">Disposed</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Condition</label>
                        <select class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option>Select condition</option>
                            <option>New</option>
                            <option>Good</option>
                            <option>Fair</option>
                            <option>Poor</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Description / Notes --}}
            <div class="bg-white rounded-lg border border-gray-200 p-5">
                <h3 class="flex items-center gap-2 font-bold mb-4" style="color:#173A66;">
                    <i class="fa-solid fa-file-lines"></i> Description / Notes
                </h3>
                <textarea rows="3" placeholder="Enter description or notes about this asset (optional)"
                          class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100"></textarea>
            </div>

            {{-- Action buttons --}}
            <div class="flex items-center justify-between">
                
                <div class="flex gap-3">
                    <a href="{{ url('/fixed-assets') }}"
                       class="px-5 py-2.5 rounded-md text-sm font-semibold border border-gray-300 text-gray-700 bg-white">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-md text-sm font-semibold text-white shadow" style="background:#22B57A;">
                        Save Asset
                    </button>
                </div>
            </div>
        </div>

        {{-- ============ RIGHT COLUMN (preview) ============ --}}
        <div class="col-span-1">
            <div class="bg-white rounded-lg border border-gray-200 p-5 sticky top-5">
                <h3 class="flex items-center gap-2 font-bold mb-4" style="color:#173A66;">
                    <i class="fa-solid fa-eye"></i> Asset Preview
                </h3>

                <div class="w-full h-40 rounded-md flex items-center justify-center mb-4" style="background:#F3F5F9;">
                    <i class="fa-solid fa-laptop text-6xl text-gray-300"></i>
                </div>

                @php
                    $preview = [
                        ['label' => 'Asset ID', 'value' => $nextTag],
                        ['label' => 'Asset Name', 'value' => 'Fill out the form'],
                        ['label' => 'Category', 'value' => '-'],
                        ['label' => 'Status', 'value' => 'Active', 'badge' => true],
                    ];
                @endphp
                <ul class="text-sm space-y-2.5">
                    @foreach ($preview as $row)
                        <li class="flex items-center justify-between gap-2">
                            <span class="text-gray-500">{{ $row['label'] }}</span>
                            @if (!empty($row['badge']))
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:#D6F5DF;color:#16A34A;">
                                    {{ $row['value'] }}
                                </span>
                            @else
                                <span class="font-medium text-gray-700 text-right">{{ $row['value'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>

                <div class="mt-5 pt-4 border-t border-gray-100">
                    <a href="#" class="text-sm font-medium" style="color:#3B82F6;">QR Code</a>
                    <div class="mt-3 flex items-center justify-center">
                        <div class="w-32 h-32 flex items-center justify-center border border-gray-200 rounded-md">
                            <i class="fa-solid fa-qrcode text-6xl text-gray-800"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection