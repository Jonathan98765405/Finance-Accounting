@extends('layouts.app')

@section('page-title', 'Finance & Accounting | Financial Reports')
@section('page-title-heading', 'Financial Reports')
@section('page-subtitle', 'Monitor financial performance and ensure regulatory compliance.')

@section('content')
    @include('financial-reports.header')

    @php
      $fmt = fn($n) => ($n < 0 ? '-₱' : '₱') . number_format(abs($n));

      $statusColor = fn($status) => match ($status) {
        'Filed' => 'text-brand-green',
        'Calculated' => 'text-navy-600',
        'Pending' => 'text-brand-orange',
        default => 'text-slate-400',
      };
    @endphp

    {{-- ================= CASH FLOW ================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
      <div class="bg-white rounded-2xl shadow-card p-5">
        <p class="text-sm font-bold text-brand-green">Operating</p>
        <p id="cf-summary-operating" class="text-2xl font-extrabold text-navy mt-1">₱2,570,000</p>
      </div>
      <div class="bg-white rounded-2xl shadow-card p-5">
        <p class="text-sm font-bold text-brand-orange">Investing</p>
        <p id="cf-summary-investing" class="text-2xl font-extrabold text-navy mt-1">-₱143,000</p>
      </div>
      <div class="bg-white rounded-2xl shadow-card p-5">
        <p class="text-sm font-bold text-navy-600">Financing</p>
        <p id="cf-summary-financing" class="text-2xl font-extrabold text-navy mt-1">-₱69,000</p>
      </div>
      <div class="bg-white rounded-2xl shadow-card p-5">
        <p class="text-sm font-bold text-brand-green">Net Cash Flow</p>
        <p id="cf-summary-net" class="text-2xl font-extrabold text-navy mt-1">₱388,000</p>
      </div>
    </div>

    {{-- ================= MONTHLY TABLE ================= --}}
    <div class="bg-white rounded-2xl shadow-card p-6 mb-6">
      <div class="flex items-center justify-between mb-4">
        <p class="text-sm font-bold text-navy">Cash Flow Breakdown</p>
        <div class="relative">
          <select id="cashflow-year-select"
            class="appearance-none flex items-center gap-1 text-sm font-medium text-slate-600 border border-slate-200 rounded-lg pl-3 pr-8 py-1.5 bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-navy-600/30">
            @foreach ($years as $y)
                <option value="{{ $y }}" @selected($y == $selectedYear)>{{ $y }}</option>
            @endforeach
          </select>
          <i data-lucide="chevron-down"
            class="w-4 h-4 text-slate-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="text-slate-400 text-left">
              <th class="pb-2">Month</th>
              <th class="pb-2">Operating</th>
              <th class="pb-2">Investing</th>
              <th class="pb-2">Financing</th>
              <th class="pb-2">Net</th>
              <th class="pb-2">Action</th>
            </tr>
          </thead>
          <tbody id="cashflow-monthly-body" class="divide-y divide-slate-100">
            {{-- Rendered by JS from CASHFLOW_BY_YEAR --}}
          </tbody>
        </table>
      </div>
    </div>

    {{-- ================= TAX SUMMARY ================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
      <div class="bg-white rounded-2xl shadow-card p-5">
        <p class="text-sm font-bold text-brand-orange">Total Tax Due</p>
        <p class="text-2xl font-extrabold text-navy mt-1">{{ $fmt($taxSummary['totalDue']) }}</p>
      </div>
      <div class="bg-white rounded-2xl shadow-card p-5">
        <p class="text-sm font-bold text-brand-green">Filed YTD</p>
        <p class="text-2xl font-extrabold text-navy mt-1">{{ $fmt($taxSummary['filedYtd']) }}</p>
      </div>
      <div class="bg-white rounded-2xl shadow-card p-5">
        <p class="text-sm font-bold text-slate-500">Pending Filings</p>
        <p class="text-2xl font-extrabold text-navy mt-1">{{ $taxSummary['pendingFilings'] }}</p>
      </div>
    </div>

    {{-- ================= TAX TABLE ================= --}}
    <div class="bg-white rounded-2xl shadow-card p-6 mb-6">
      <p class="text-sm font-bold text-navy mb-4">Tax Calculation</p>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="text-slate-400 text-left">
              <th class="pb-2">Type</th>
              <th class="pb-2">Rate</th>
              <th class="pb-2">Taxable</th>
              <th class="pb-2">Amount</th>
              <th class="pb-2">Deadline</th>
              <th class="pb-2">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @foreach ($taxCalculation as $tax)
              <tr>
                <td class="py-2">{{ $tax['type'] }}</td>
                <td class="py-2">{{ $tax['rate'] }}</td>
                <td class="py-2">{{ $fmt($tax['taxableAmount']) }}</td>
                <td class="py-2 font-semibold">{{ $fmt($tax['amountDue']) }}</td>
                <td class="py-2">{{ $tax['deadline'] }}</td>
                <td class="py-2">
                  <button type="button" class="js-tax-status font-semibold {{ $statusColor($tax['status']) }} hover:underline"
                    data-type="{{ $tax['type'] }}" data-rate="{{ $tax['rate'] }}" data-taxable="{{ $tax['taxableAmount'] }}"
                    data-amount="{{ $tax['amountDue'] }}" data-deadline="{{ $tax['deadline'] }}"
                    data-status="{{ $tax['status'] }}">
                    {{ $tax['status'] }}
                  </button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    {{-- ================= TAX CALENDAR (EDITABLE) ================= --}}
    <div class="bg-white rounded-2xl shadow-card p-6">
      <div class="flex items-center justify-between mb-4">
        <div>
          <p class="text-sm font-bold text-navy">Tax Calendar</p>
          <p class="text-xs text-slate-400 mt-0.5">
            <span id="tax-cal-due-count">0</span> upcoming &middot;
            <span id="tax-cal-overdue-count">0</span> overdue
          </p>
        </div>
        <button type="button" id="js-tax-cal-add"
          class="inline-flex items-center gap-1.5 rounded-xl bg-navy px-4 py-2 text-sm font-semibold text-white hover:bg-navy-700 transition">
          <i data-lucide="plus" class="w-4 h-4"></i>
          Add Filing
        </button>
      </div>

      <div id="tax-calendar-grid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 text-sm">
        {{-- Rendered by JS from TAX_CALENDAR_ITEMS --}}
      </div>

      <div id="tax-calendar-empty"
        class="hidden text-center py-10 text-sm text-slate-400 border border-dashed border-slate-200 rounded-xl">
        No filings yet. Click "Add Filing" to create one.
      </div>
    </div>
@endsection

@push('scripts')
    <script>
      const cfMonthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
      const cfMonthAbbr = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

      function cfFmt(n) {
        return (n < 0 ? '-₱' : '₱') + Math.abs(Math.round(n)).toLocaleString();
      }
      function cfFmtSigned(n) {
        return (n < 0 ? '-₱' : '+₱') + Math.abs(Math.round(n)).toLocaleString();
      }

      const CF_CURRENT_YEAR = {{ $selectedYear }};
      const CASHFLOW_BY_YEAR = { [CF_CURRENT_YEAR]: @json($cashflowMonthly) };
      const TAX_SUMMARY_BY_YEAR = { [CF_CURRENT_YEAR]: @json($taxSummary) };
      const TAX_CALCULATION_BY_YEAR = { [CF_CURRENT_YEAR]: @json($taxCalculation) };

      async function loadCashflowYear(year) {
        if (CASHFLOW_BY_YEAR[year]) return;

        const res = await fetch(`{{ url('/financial-reports/cashflow-tax/data') }}?year=${year}`, {
          headers: { 'Accept': 'application/json' }
        });
        if (!res.ok) return;
        const data = await res.json();
        CASHFLOW_BY_YEAR[year] = data.cashflowMonthly;
        TAX_SUMMARY_BY_YEAR[year] = data.taxSummary;
        TAX_CALCULATION_BY_YEAR[year] = data.taxCalculation;
      }

      function renderCashflowSummary(year) {
        const rows = CASHFLOW_BY_YEAR[year] || [];
        const totals = rows.reduce((acc, r) => {
          acc.operating += r.operating;
          acc.investing += r.investing;
          acc.financing += r.financing;
          acc.net += r.net;
          return acc;
        }, { operating: 0, investing: 0, financing: 0, net: 0 });

        document.getElementById('cf-summary-operating').textContent = cfFmt(totals.operating);
        document.getElementById('cf-summary-investing').textContent = cfFmt(totals.investing);
        document.getElementById('cf-summary-financing').textContent = cfFmt(totals.financing);
        document.getElementById('cf-summary-net').textContent = cfFmt(totals.net);
      }

      function renderCashflowTable(year) {
        const rows = CASHFLOW_BY_YEAR[year] || [];
        document.getElementById('cashflow-monthly-body').innerHTML = rows.map((r, i) => `
            <tr>
              <td class="py-2 font-semibold">${r.abbr}</td>
              <td class="py-2 text-brand-green">${cfFmtSigned(r.operating)}</td>
              <td class="py-2 text-brand-orange">${cfFmt(r.investing)}</td>
              <td class="py-2 text-navy-600">${cfFmt(r.financing)}</td>
              <td class="py-2 font-bold text-navy">${cfFmt(r.net)}</td>
              <td class="py-2">
                <button type="button" class="text-navy-600 font-medium hover:underline js-cashflow-view" data-year="${year}" data-index="${i}">View</button>
              </td>
            </tr>
          `).join('');
      }

      function openCashflowDetailModal(year, index) {
        const yearData = CASHFLOW_BY_YEAR[year];
        if (!yearData || !yearData[index]) return;
        const r = yearData[index];
        AppUI.openModal(`
            <h3 class="text-lg font-bold text-navy mb-1">${r.month} ${r.year} Cash Flow</h3>
            <p class="text-sm text-slate-500 mb-4">Full breakdown of operating, investing, and financing activity.</p>
            <div>
              <div class="flex items-center justify-between py-2 border-b border-slate-100">
                <span class="text-sm text-slate-500">Operating Activities</span>
                <span class="text-sm font-semibold text-brand-green">${cfFmtSigned(r.operating)}</span>
              </div>
              <div class="flex items-center justify-between py-2 border-b border-slate-100">
                <span class="text-sm text-slate-500">Investing Activities</span>
                <span class="text-sm font-semibold text-brand-orange">${cfFmt(r.investing)}</span>
              </div>
              <div class="flex items-center justify-between py-2 border-b border-slate-100">
                <span class="text-sm text-slate-500">Financing Activities</span>
                <span class="text-sm font-semibold text-navy-600">${cfFmt(r.financing)}</span>
              </div>
              <div class="flex items-center justify-between py-3">
                <span class="text-sm font-semibold text-navy">Net Cash Flow</span>
                <span class="text-base font-extrabold text-navy">${cfFmt(r.net)}</span>
              </div>
            </div>
            <div class="flex justify-end pt-3">
              <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy-700">Close</button>
            </div>
          `);
      }

      document.addEventListener('click', function (e) {
        const btn = e.target.closest('.js-cashflow-view');
        if (!btn) return;
        openCashflowDetailModal(btn.dataset.year, parseInt(btn.dataset.index, 10));
      });

      renderCashflowSummary(CF_CURRENT_YEAR);
      renderCashflowTable(CF_CURRENT_YEAR);

      document.getElementById('cashflow-year-select').addEventListener('change', async function () {
        const year = this.value;
        await loadCashflowYear(year);
        renderCashflowSummary(year);
        renderCashflowTable(year);
      });

      const TAX_CAL_TYPES = ['Income Tax', 'VAT / GST', 'Payroll Tax', 'Withholding Tax'];

      const TAX_STATUS_INFO = {
        Filed: {
          badge: 'bg-brand-green/10 text-brand-green',
          icon: 'circle-check-big',
          iconColor: 'text-brand-green',
          note: 'This filing has been submitted to the relevant tax authority and no further action is required at this time.',
        },
        Calculated: {
          badge: 'bg-navy-600/10 text-navy-600',
          icon: 'calculator',
          iconColor: 'text-navy-600',
          note: 'The amount due has been calculated based on current figures but has not yet been filed. Review before the deadline to avoid penalties.',
        },
        Pending: {
          badge: 'bg-brand-orange/10 text-brand-orange',
          icon: 'clock',
          iconColor: 'text-brand-orange',
          note: 'This filing is still pending — documentation or approval is outstanding. Filing before the deadline is recommended to avoid late fees.',
        },
      };

      function openTaxStatusModal(btn) {
        const type = btn.dataset.type;
        const rate = btn.dataset.rate;
        const taxable = parseFloat(btn.dataset.taxable);
        const amount = parseFloat(btn.dataset.amount);
        const deadline = btn.dataset.deadline;
        const status = btn.dataset.status;
        const info = TAX_STATUS_INFO[status] || TAX_STATUS_INFO.Pending;

        AppUI.openModal(`
            <div class="flex items-center gap-3 mb-1">
              <div class="flex h-10 w-10 items-center justify-center rounded-full ${info.badge}">
                <i data-lucide="${info.icon}" class="w-5 h-5 ${info.iconColor}"></i>
              </div>
              <div>
                <h3 class="text-lg font-bold text-navy leading-tight">${type}</h3>
                <span class="inline-block mt-0.5 rounded-full px-2.5 py-0.5 text-xs font-semibold ${info.badge}">${status}</span>
              </div>
            </div>
            <p class="text-sm text-slate-500 mt-3 mb-4">${info.note}</p>
            <div>
              <div class="flex items-center justify-between py-2 border-b border-slate-100">
                <span class="text-sm text-slate-500">Tax Rate</span>
                <span class="text-sm font-semibold text-navy">${rate}</span>
              </div>
              <div class="flex items-center justify-between py-2 border-b border-slate-100">
                <span class="text-sm text-slate-500">Taxable Amount</span>
                <span class="text-sm font-semibold text-navy">${cfFmt(taxable)}</span>
              </div>
              <div class="flex items-center justify-between py-2 border-b border-slate-100">
                <span class="text-sm text-slate-500">Amount Due</span>
                <span class="text-sm font-semibold text-navy">${cfFmt(amount)}</span>
              </div>
              <div class="flex items-center justify-between py-2">
                <span class="text-sm text-slate-500">Deadline</span>
                <span class="text-sm font-semibold text-navy">${deadline}</span>
              </div>
            </div>
            <div class="flex justify-end pt-4">
              <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy-700">Close</button>
            </div>
          `);
        lucide.createIcons();
      }

      document.addEventListener('click', function (e) {
        const btn = e.target.closest('.js-tax-status');
        if (!btn) return;
        openTaxStatusModal(btn);
      });

      let TAX_CALENDAR_ITEMS = @json($taxCalendar);
      const TAX_CAL_CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const TAX_CAL_STORE_URL = @json(route('financial-reports.tax-calendar.store'));
      const taxCalItemUrl = (id) => `${TAX_CAL_STORE_URL}/${id}`;

      async function taxCalApi(url, method, body) {
        const res = await fetch(url, {
          method,
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': TAX_CAL_CSRF,
          },
          body: body ? JSON.stringify(body) : undefined,
        });
        if (!res.ok) {
          const payload = await res.json().catch(() => ({}));
          throw new Error(payload.message || 'Request failed');
        }
        return res.status === 204 ? null : res.json();
      }

      function taxCalDaysUntil(dateStr) {
        const d = new Date(dateStr + 'T00:00:00');
        const t = new Date(new Date().toISOString().slice(0, 10) + 'T00:00:00');
        return Math.round((d - t) / 86400000);
      }

      function taxCalDateLabel(dateStr) {
        const d = new Date(dateStr + 'T00:00:00');
        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
      }

      function taxCalStatusMeta(item) {
        if (item.status === 'Filed') {
          return { label: 'Filed', badge: 'bg-brand-green/10 text-brand-green', border: 'border-slate-100' };
        }
        const days = taxCalDaysUntil(item.date);
        if (days < 0) {
          return { label: 'Overdue', badge: 'bg-red-100 text-red-600', border: 'border-red-100' };
        }
        if (days <= 14) {
          return { label: 'Due Soon', badge: 'bg-brand-orange/10 text-brand-orange', border: 'border-slate-100' };
        }
        return { label: 'Upcoming', badge: 'bg-navy-600/10 text-navy-600', border: 'border-slate-100' };
      }

      function renderTaxCalendar() {
        const grid = document.getElementById('tax-calendar-grid');
        const empty = document.getElementById('tax-calendar-empty');
        const sorted = [...TAX_CALENDAR_ITEMS].sort((a, b) => a.date.localeCompare(b.date));

        if (sorted.length === 0) {
          grid.innerHTML = '';
          grid.classList.add('hidden');
          empty.classList.remove('hidden');
        } else {
          grid.classList.remove('hidden');
          empty.classList.add('hidden');

          grid.innerHTML = sorted.map(item => {
            const meta = taxCalStatusMeta(item);
            return `
              <div class="group relative rounded-xl border ${meta.border} p-4 hover:shadow-sm transition">
                <div class="flex items-start justify-between gap-2">
                  <p class="text-xs text-slate-400">${taxCalDateLabel(item.date)}</p>
                  <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold ${meta.badge}">${meta.label}</span>
                </div>
                <p class="font-semibold text-navy mt-1.5">${escapeTaxCalHtml(item.label)}</p>
                <p class="text-lg font-extrabold text-navy mt-1">${cfFmt(item.amount)}</p>

                <div class="flex items-center gap-1 mt-3 opacity-0 group-hover:opacity-100 transition">
                  <button type="button" class="js-tax-cal-edit inline-flex items-center gap-1 rounded-lg border border-slate-200 px-2 py-1 text-xs font-medium text-slate-600 hover:border-navy-600 hover:text-navy-600" data-id="${item.id}">
                    <i data-lucide="pencil" class="w-3 h-3"></i> Edit
                  </button>
                  <button type="button" class="js-tax-cal-delete inline-flex items-center gap-1 rounded-lg border border-slate-200 px-2 py-1 text-xs font-medium text-slate-600 hover:border-red-400 hover:text-red-500" data-id="${item.id}">
                    <i data-lucide="trash-2" class="w-3 h-3"></i> Delete
                  </button>
                </div>
              </div>`;
          }).join('');
        }

        document.getElementById('tax-cal-due-count').textContent =
          TAX_CALENDAR_ITEMS.filter(i => i.status !== 'Filed').length;
        document.getElementById('tax-cal-overdue-count').textContent =
          TAX_CALENDAR_ITEMS.filter(i => i.status !== 'Filed' && taxCalDaysUntil(i.date) < 0).length;

        lucide.createIcons();
      }

      function escapeTaxCalHtml(s) {
        return String(s).replace(/[&<>"']/g, c => ({
          '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
        }[c]));
      }

      function openTaxCalFormModal(existing) {
        const isEdit = !!existing;
        const idVal = isEdit ? existing.id : '';
        const labelVal = isEdit ? existing.label : '';
        const amountVal = isEdit ? existing.amount : '';
        const dateVal = isEdit ? existing.date : new Date().toISOString().slice(0, 10);
        const statusVal = isEdit ? existing.status : 'Upcoming';

        AppUI.openModal(`
            <h3 class="text-lg font-bold text-navy mb-1">${isEdit ? 'Edit Filing' : 'Add Filing'}</h3>
            <p class="text-sm text-slate-500 mb-4">${isEdit ? 'Update the details for this tax obligation.' : 'Enter the details for the new tax obligation.'}</p>

            <input type="hidden" id="tc-form-id" value="${idVal}">

            <div class="mb-3">
              <label class="block text-xs font-semibold text-navy mb-1">Filing Type</label>
              <select id="tc-form-label" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30">
                <option value="" disabled ${!labelVal ? 'selected' : ''}>Select a tax type</option>
                ${TAX_CAL_TYPES.map(t => `<option value="${t}" ${labelVal === t ? 'selected' : ''}>${t}</option>`).join('')}
              </select>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-3">
              <div>
                <label class="block text-xs font-semibold text-navy mb-1">Amount Due</label>
                <input type="number" id="tc-form-amount" value="${amountVal}" min="0" step="1" placeholder="0"
                  class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30">
              </div>
              <div>
                <label class="block text-xs font-semibold text-navy mb-1">Deadline</label>
                <input type="date" id="tc-form-date" value="${dateVal}"
                  class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30">
              </div>
            </div>

            <div class="mb-4">
              <label class="block text-xs font-semibold text-navy mb-1">Status</label>
              <select id="tc-form-status" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30">
                <option value="Upcoming" ${statusVal === 'Upcoming' ? 'selected' : ''}>Upcoming</option>
                <option value="Filed" ${statusVal === 'Filed' ? 'selected' : ''}>Filed</option>
              </select>
            </div>

            <p id="tc-form-error" class="hidden text-xs text-red-500 mb-3">Please fill in all fields with valid values.</p>

            <div class="flex justify-end gap-2 pt-1">
              <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-navy border border-slate-200 hover:bg-slate-50">Cancel</button>
              <button type="button" id="tc-form-save" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy-700">Save Filing</button>
            </div>
          `);
        lucide.createIcons();

        document.getElementById('tc-form-save').addEventListener('click', async function () {
          const id = document.getElementById('tc-form-id').value;
          const label = document.getElementById('tc-form-label').value.trim();
          const amount = parseFloat(document.getElementById('tc-form-amount').value);
          const date = document.getElementById('tc-form-date').value;
          const status = document.getElementById('tc-form-status').value;
          const errorEl = document.getElementById('tc-form-error');
          const saveBtn = this;

          if (!label || isNaN(amount) || amount < 0 || !date) {
            errorEl.textContent = 'Please fill in all fields with valid values.';
            errorEl.classList.remove('hidden');
            return;
          }
          errorEl.classList.add('hidden');
          saveBtn.disabled = true;

          try {
            const payload = { label, amount, date, status };

            if (id) {
              const { item: saved } = await taxCalApi(taxCalItemUrl(id), 'PUT', payload);
              const item = TAX_CALENDAR_ITEMS.find(i => String(i.id) === String(id));
              if (item) Object.assign(item, saved);
            } else {
              const { item: saved } = await taxCalApi(TAX_CAL_STORE_URL, 'POST', payload);
              TAX_CALENDAR_ITEMS.push(saved);
            }

            renderTaxCalendar();
            AppUI.closeModal();
          } catch (err) {
            errorEl.textContent = "Couldn't save this filing. Please try again.";
            errorEl.classList.remove('hidden');
          } finally {
            saveBtn.disabled = false;
          }
        });
      }

      // Front-end UI protection interceptor added for dynamic initialization
      document.getElementById('js-tax-cal-add').addEventListener('click', function () {
        if (!AppUI.requirePermission()) return;
        openTaxCalFormModal(null);
      });

      // Front-end UI protection interceptor added for row events execution loops
      document.addEventListener('click', function (e) {
        const editBtn = e.target.closest('.js-tax-cal-edit');
        if (editBtn) {
          if (!AppUI.requirePermission()) return;
          const item = TAX_CALENDAR_ITEMS.find(i => String(i.id) === String(editBtn.dataset.id));
          if (item) openTaxCalFormModal(item);
          return;
        }

        const delBtn = e.target.closest('.js-tax-cal-delete');
        if (delBtn) {
          if (!AppUI.requirePermission()) return;
          const item = TAX_CALENDAR_ITEMS.find(i => String(i.id) === String(delBtn.dataset.id));
          AppUI.openModal(`
              <h3 class="text-lg font-bold text-navy mb-1">Delete Filing</h3>
              <p class="text-sm text-slate-500 mb-5">Are you sure you want to delete "${item ? escapeTaxCalHtml(item.label) : 'this filing'}"? This can't be undone.</p>
              <div class="flex justify-end gap-2">
                <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-navy border border-slate-200 hover:bg-slate-50">Cancel</button>
                <button type="button" id="tc-confirm-delete" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-red-500 hover:bg-red-600">Delete</button>
              </div>
            `);
          document.getElementById('tc-confirm-delete').addEventListener('click', async function () {
            const confirmBtn = this;
            confirmBtn.disabled = true;
            try {
              await taxCalApi(taxCalItemUrl(delBtn.dataset.id), 'DELETE');
              TAX_CALENDAR_ITEMS = TAX_CALENDAR_ITEMS.filter(i => String(i.id) !== String(delBtn.dataset.id));
              renderTaxCalendar();
              AppUI.closeModal();
            } catch (err) {
              confirmBtn.disabled = false;
            }
          });
        }
      });

      renderTaxCalendar();
    </script>
@endpush