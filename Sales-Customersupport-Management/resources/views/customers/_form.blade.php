{{-- Shared fields for create.blade.php and edit.blade.php --}}

<div>
    <label class="block text-sm font-medium text-slate-700 mb-1.5" for="name">Customer Name</label>
    <input
        id="name" name="name" type="text"
        value="{{ old('name', $customer->name ?? '') }}"
        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500"
    >
    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700 mb-1.5" for="address">Address</label>
    <textarea
        id="address" name="address" rows="2"
        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500"
    >{{ old('address', $customer->address ?? '') }}</textarea>
    @error('address') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700 mb-1.5" for="email">Email</label>
    <input
        id="email" name="email" type="email"
        value="{{ old('email', $customer->email ?? '') }}"
        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500"
    >
    @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700 mb-1.5" for="contact_no">Contact No.</label>
    <input
        id="contact_no" name="contact_no" type="text"
        value="{{ old('contact_no', $customer->contact_no ?? '') }}"
        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500"
    >
    @error('contact_no') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700 mb-1.5" for="status">Status</label>
    <select id="status" name="status" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500">
        @php $currentStatus = old('status', $customer->status ?? 'active'); @endphp
        <option value="active" @selected($currentStatus === 'active')>Active</option>
        <option value="inactive" @selected($currentStatus === 'inactive')>Inactive</option>
    </select>
    @error('status') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
</div>