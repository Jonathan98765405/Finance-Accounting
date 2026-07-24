@extends('layouts.app')

@section('page-title', 'New Journal Entry')
@section('page-title-heading', 'New Journal Entry')
@section('page-subtitle', 'Create and record financial transactions.')

@section('content')

@if(session('error'))
    <div class="bg-brand-red/10 border border-brand-red/30 text-brand-red p-4 rounded-xl mb-6">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="bg-brand-red/10 border border-brand-red/30 text-brand-red p-4 rounded-xl mb-6">
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-2xl shadow-card border border-slate-200 p-8">
    <form action="{{ route('ledger.store') }}" method="POST">
        @csrf

        <h2 class="text-xl font-bold mb-5 text-navy">Journal Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div>
                <label class="block text-sm font-medium mb-2">Date</label>
                <input type="date" name="entry_date" value="{{ old('entry_date', date('Y-m-d')) }}"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Reference</label>
                <input type="text" name="reference" value="{{ old('reference') }}" placeholder="Example: JE-001"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Description</label>
                <input type="text" name="description" value="{{ old('description') }}" placeholder="Transaction description"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3" required>
            </div>
        </div>

        <div class="flex justify-between items-center mb-5">
            <h2 class="text-xl font-bold text-navy">Journal Lines</h2>
            <button type="button" onclick="addLine()" class="bg-brand-green hover:bg-brand-greenDark text-white px-5 py-2 rounded-xl">
                + Add Line
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-100 text-slate-600">
                        <th class="text-left p-4 rounded-l-xl">Account</th>
                        <th class="text-right p-4">Debit</th>
                        <th class="text-right p-4">Credit</th>
                        <th class="p-4">Action</th>
                    </tr>
                </thead>
                <tbody id="journalLines">
                    @for ($i = 0; $i < 1; $i++)
                        <tr class="journal-row border-b">
                            <td class="p-4">
                                <select name="account_id[]" class="account-select w-full border border-slate-200 rounded-xl px-3 py-2" required>
                                    <option value="">Select Account</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->account_code }} - {{ $account->account_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-4">
                                <input type="number" step="0.01" name="debit[]" class="debit w-full border border-slate-200 rounded-xl px-3 py-2 text-right" value="0" oninput="calculateTotal()">
                            </td>
                            <td class="p-4">
                                <input type="number" step="0.01" name="credit[]" class="credit w-full border border-slate-200 rounded-xl px-3 py-2 text-right" value="0" oninput="calculateTotal()">
                            </td>
                            <td class="p-4 text-center">
                                <button type="button" onclick="removeLine(this)" class="text-brand-red hover:opacity-75">
                                    <i data-lucide="trash-2" class="w-4 h-4 inline"></i>
                                </button>
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-slate-100 rounded-xl p-5">
                <p class="text-slate-500 text-sm">Total Debit</p>
                <h3 id="totalDebit" class="text-2xl font-bold text-brand-blue mt-2">₱0.00</h3>
            </div>
            <div class="bg-slate-100 rounded-xl p-5">
                <p class="text-slate-500 text-sm">Total Credit</p>
                <h3 id="totalCredit" class="text-2xl font-bold text-brand-red mt-2">₱0.00</h3>
            </div>
            <div class="bg-slate-100 rounded-xl p-5">
                <p class="text-slate-500 text-sm">Status</p>
                <h3 id="balanceStatus" class="text-2xl font-bold text-brand-red mt-2">Not Balanced</h3>
            </div>
        </div>

        <div class="mt-8 flex gap-3">
            <button type="submit" class="bg-navy hover:bg-navy-700 text-white px-6 py-3 rounded-xl shadow-card">
                Save Entry
            </button>
            <a href="{{ route('ledger.index') }}" class="bg-slate-200 hover:bg-slate-300 px-6 py-3 rounded-xl">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    function addLine() {
        let container = document.getElementById('journalLines');
        let row = document.querySelector('.journal-row').cloneNode(true);
        row.querySelectorAll('input').forEach(input => input.value = 0);
        row.querySelector('select').selectedIndex = 0;
        container.appendChild(row);
        lucide.createIcons();
    }

    function removeLine(button) {
        let rows = document.querySelectorAll('.journal-row');
        if (rows.length > 1) {
            button.closest('.journal-row').remove();
            calculateTotal();
        }
    }

    function calculateTotal() {
        let debits = document.querySelectorAll('.debit');
        let credits = document.querySelectorAll('.credit');
        let totalDebit = 0;
        let totalCredit = 0;
        debits.forEach(input => totalDebit += parseFloat(input.value) || 0);
        credits.forEach(input => totalCredit += parseFloat(input.value) || 0);

        document.getElementById('totalDebit').innerHTML = "₱" + totalDebit.toFixed(2);
        document.getElementById('totalCredit').innerHTML = "₱" + totalCredit.toFixed(2);

        let status = document.getElementById('balanceStatus');
        if (totalDebit === totalCredit && totalDebit > 0) {
            status.innerHTML = "Balanced ✓";
            status.classList.remove('text-brand-red');
            status.classList.add('text-brand-green');
        } else {
            status.innerHTML = "Not Balanced";
            status.classList.remove('text-brand-green');
            status.classList.add('text-brand-red');
        }
    }

    calculateTotal();
</script>
@endpush