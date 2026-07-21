@extends('layouts.app')

@section('page-title', 'Budget Forecasting')
@section('page-title-heading', 'Budget Forecasting')
@section('page-subtitle', 'Comprehensive financial planning and live forecasting of budget')

@push('styles')

garcia 2:59

    <!-- FontAwesome for the specific icons used in the budget design -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .modal-overlay {
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(2px);
        }
        input::placeholder { color: #94A3B8; }
        
        @media print {
            .no-print-modal, .modal-overlay, .flex.space-x-3 { display: none !important; }
        }
    </style>
@endpush

@section('content')
    <!-- Action Buttons -->
    <div class="flex space-x-3 mb-6">
        <button onclick="editBudget()" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-500 font-semibold rounded-lg transition text-sm">Edit Budget / Actuals</button>
        <button onclick="generateReport()" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-500 font-semibold rounded-lg transition text-sm">Generate Report</button>
    </div>

    <!-- METRIC CARDS OVERVIEW -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-card flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-[#52B788] text-lg"><i class="fa-solid fa-dollar-sign"></i></div>
                <span class="text-xs text-blue-600 font-semibold"><i class="fa-solid fa-arrow-trend-up"></i> 8.5%</span>
            </div>
            <div class="mt-4">
                <p class="text-xs uppercase font-bold tracking-wider text-gray-400">Total Yearly Budget</p>
                <h3 id="cardTotalBudget" class="text-xl font-bold text-gray-900 mt-0.5">$0.00</h3>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-card flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-[#23387E] text-lg"><i class="fa-solid fa-chart-line"></i></div>
                <span class="text-xs text-blue-600 font-semibold"><i class="fa-solid fa-arrow-trend-up"></i> 8%</span>
            </div>
            <div class="mt-4">
                <p class="text-xs uppercase font-bold tracking-wider text-gray-400">Total Budget Spent</p>
                <h3 id="cardTotalActual" class="text-xl font-bold text-gray-900 mt-0.5">$0.00</h3>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-card flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-500 text-lg"><i class="fa-solid fa-wallet"></i></div>
                <span class="text-xs text-blue-600 font-semibold"><i class="fa-solid fa-arrow-trend-up"></i> 8.5%</span>
            </div>
            <div class="mt-4">
                <p class="text-xs uppercase font-bold tracking-wider text-gray-400">Remaining Budget</p>
                <h3 id="cardTotalVariance" class="text-xl font-bold text-gray-900 mt-0.5">$0.00</h3>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-card flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-500 text-lg"><i class="fa-solid fa-chart-bar"></i></div>
                <span class="text-xs text-red-500 font-semibold"><i class="fa-solid fa-triangle-exclamation"></i> 8.5% Overdue</span>
            </div>
            <div class="mt-4">
                <p class="text-xs uppercase font-bold tracking-wider text-gray-400">Forecast Accuracy</p>
                <h3 class="text-xl font-bold text-gray-900 mt-0.5">94.8%</h3>
                <span class="text-[10px] text-gray-400">Last 6 Months</span>
            </div>
        </div>
    </div>

    <!-- VISUALIZATION GRAPH CHART CANVAS ELEMENTS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-card">
            <div class="flex justify-between items-center mb-2">
                <div>
                    <span class="text-xs uppercase font-bold text-gray-400 tracking-wider">Budget</span>
                    <p class="text-[10px] text-gray-400 -mt-1">Yearly Budget (USD)</p>
                </div>
                <span class="text-xs text-gray-500 font-medium">This Year <i class="fa-solid fa-chevron-up text-[10px] ml-1"></i></span>
            </div>
            <div class="h-48">
                <canvas id="budgetChart"></canvas>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-card">
            <div class="flex justify-between items-center mb-2">
                <div>
                    <span class="text-xs uppercase font-bold text-gray-400 tracking-wider">Actual</span>
                    <p class="text-[10px] text-gray-400 -mt-1">Actual Spend (USD)</p>
                </div>
                <span class="text-xs text-gray-500 font-medium">This Year <i class="fa-solid fa-chevron-up text-[10px] ml-1"></i></span>
            </div>
            <div class="h-48">
                <canvas id="actualChart"></canvas>
            </div>
        </div>
    </div>

    <!-- COMPARISON TABULAR SUMMARY -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-card overflow-hidden">
        <div class="p-4 border-b border-gray-50">
            <h3 class="font-bold text-gray-700 text-base">Budget vs Actual Comparison</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="bg-amber-50/40 text-gray-500 font-semibold border-b border-gray-100 text-xs uppercase tracking-wider">
                        <th class="p-3.5 pl-6">Category</th>
                        <th class="p-3.5">Budget</th>
                        <th class="p-3.5">Actual</th>
                        <th class="p-3.5">Variance</th>
                        <th class="p-3.5 pr-6">Variance %</th>
                    </tr>
                </thead>
                <tbody id="comparisonTableBody" class="divide-y divide-gray-100 font-medium text-gray-700">
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL 1: EDIT DATA INPUT SYSTEM -->
    <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center modal-overlay hidden no-print-modal">
        <div class="bg-white rounded-[40px] shadow-2xl w-[500px] p-8 border border-gray-100">
            <h2 class="text-center text-[#23387E] text-3xl font-extrabold mb-4 tracking-wide uppercase">Edit Ledger Figures</h2>
            
            <div class="flex justify-center mb-6">
                <div class="bg-gray-100 p-1 rounded-full flex relative w-72 border border-gray-200">
                    <button onclick="setEditMode('budget')" id="btn-mode-budget" class="flex-1 py-1.5 text-xs font-bold rounded-full transition bg-[#23387E] text-white">
                        Edit Budget
                    </button>
                    <button onclick="setEditMode('actual')" id="btn-mode-actual" class="flex-1 py-1.5 text-xs font-bold rounded-full transition text-gray-500 hover:text-gray-800">
                        Edit Actual Spent
                    </button>
                </div>
            </div>

            <div class="space-y-4 px-4">
                <div>
                    <label id="lbl-marketing" class="text-[#23387E] font-bold text-sm block mb-1">Marketing Budget</label>
                    <input id="input-marketing" type="number" placeholder="Input Figures" class="w-full border-2 border-[#23387E] rounded-xl px-4 py-2 focus:outline-none placeholder-gray-400">
                </div>
                <div>
                    <label id="lbl-operations" class="text-[#23387E] font-bold text-sm block mb-1">Operations Budget</label>
                    <input id="input-operations" type="number" placeholder="Input Figures" class="w-full border-2 border-[#23387E] rounded-xl px-4 py-2 focus:outline-none placeholder-gray-400">
                </div>
                <div>
                    <label id="lbl-sales" class="text-[#23387E] font-bold text-sm block mb-1">Sales Budget</label>
                    <input id="input-sales" type="number" placeholder="Input Figures" class="w-full border-2 border-[#23387E] rounded-xl px-4 py-2 focus:outline-none placeholder-gray-400">
                </div>
                <div>
                    <label id="lbl-technology" class="text-[#23387E] font-bold text-sm block mb-1">Technologies Budget</label>
                    <input id="input-technology" type="number" placeholder="Input Figures" class="w-full border-2 border-[#23387E] rounded-xl px-4 py-2 focus:outline-none placeholder-gray-400">
                </div>
                <div>
                    <label id="lbl-human-resources" class="text-[#23387E] font-bold text-sm block mb-1">Human Resources Budget</label>
                    <input id="input-human-resources" type="number" placeholder="Input Figures" class="w-full border-2 border-[#23387E] rounded-xl px-4 py-2 focus:outline-none placeholder-gray-400">
                </div>
                <div>
                    <label id="lbl-finance" class="text-[#23387E] font-bold text-sm block mb-1">Finance Budget</label>
                    <input id="input-finance" type="number" placeholder="Input Figures" class="w-full border-2 border-[#23387E] rounded-xl px-4 py-2 focus:outline-none placeholder-gray-400">
                </div>
            </div>
            <div class="flex justify-center space-x-4 mt-8">
                <button onclick="saveBudgetEdits()" class="bg-[#52B788] text-black font-bold px-10 py-2.5 rounded-xl border border-gray-300 hover:bg-[#45a076] transition">Confirm</button>
                <button onclick="closeCustomModal('editModal')" class="bg-white text-black font-bold px-10 py-2.5 rounded-xl border-2 border-[#23387E] hover:bg-gray-50 transition">Back</button>
            </div>
        </div>
    </div>

    <!-- MODAL 2: GENERATE AND EXPORT REPORT PANEL -->
    <div id="reportModal" class="fixed inset-0 z-50 flex items-center justify-center modal-overlay hidden no-print-modal">
        <div class="bg-white rounded-[40px] shadow-2xl w-[500px] p-8 border border-gray-100 relative">
            <button onclick="closeCustomModal('reportModal')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-700">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
            <div class="mb-6">
                <h2 class="text-gray-900 text-3xl font-extrabold tracking-tight">GENERATE REPORT</h2>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-1">Budget Forecasting > Generate Report</p>
            </div>
            
            <div class="space-y-4 px-2">
                <div class="relative">
                    <select class="w-full bg-[#E2E8F0] rounded-full px-6 py-3 appearance-none focus:outline-none text-gray-600 text-sm font-medium">
                        <option>select year for report</option>
                        <option value="2026">2026</option>
                    </select>
                    <i class="fa-solid fa-caret-down absolute right-6 top-4 text-gray-500"></i>
                </div>

                <div class="flex items-center justify-center space-x-6 py-2 select-none">
                    <i onclick="stepDate(-1)" class="fa-solid fa-chevron-left text-lg cursor-pointer hover:text-gray-600 active:scale-95 transition"></i>
                    
                    <select id="reportMonthSelect" onchange="onDateSelectChange()" class="bg-transparent font-bold text-2xl appearance-none focus:outline-none border-2 border-gray-300 px-4 rounded-xl cursor-pointer">
                        <option value="0">Jan</option>
                        <option value="1">Feb</option>
                        <option value="2">Mar</option>
                        <option value="3">Apr</option>
                        <option value="4">May</option>
                        <option value="5">Jun</option>
                        <option value="6">Jul</option>
                        <option value="7">Aug</option>
                        <option value="8" selected>Sep</option>
                        <option value="9">Oct</option>
                        <option value="10">Nov</option>
                        <option value="11">Dec</option>
                    </select>
                    
                    <select id="reportYearSelect" onchange="onDateSelectChange()" class="bg-transparent font-bold text-2xl appearance-none focus:outline-none border-2 border-gray-300 px-4 rounded-xl cursor-pointer">
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026" selected>2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                    </select>
                    
                    <i onclick="stepDate(1)" class="fa-solid fa-chevron-right text-lg cursor-pointer hover:text-gray-600 active:scale-95 transition"></i>
                </div>

                <div class="relative bg-gray-300/70 rounded-2xl pb-1">
                    <div onclick="toggleCustomDropdown('categoryDropdownList', 'categoryDropdownIcon')" class="w-full bg-[#E2E8F0] rounded-full px-6 py-3 flex justify-between items-center cursor-pointer text-gray-800 text-sm font-bold uppercase select-none">
                        <span id="selectedCategoryHeader">Marketing Category</span>
                        <i id="categoryDropdownIcon" class="fa-solid fa-caret-down text-gray-500 transition-transform"></i>
                    </div>
                    
                    <div id="categoryDropdownList" class="hidden px-4 pt-2 pb-1 space-y-1 max-h-48 overflow-y-auto">
                        <div onclick="selectCategoryItem('Marketing Category')" class="px-4 py-1.5 hover:bg-gray-400/30 rounded-lg cursor-pointer text-xs text-gray-700 font-bold uppercase">Marketing Category</div>
                        <div onclick="selectCategoryItem('Operations Category')" class="px-4 py-1.5 hover:bg-gray-400/30 rounded-lg cursor-pointer text-xs text-gray-700 font-bold uppercase">Operations Category</div>
                        <div onclick="selectCategoryItem('Sales Category')" class="px-4 py-1.5 hover:bg-gray-400/30 rounded-lg cursor-pointer text-xs text-gray-700 font-bold uppercase">Sales Category</div>
                        <div onclick="selectCategoryItem('Technologies Category')" class="px-4 py-1.5 hover:bg-gray-400/30 rounded-lg cursor-pointer text-xs text-gray-700 font-bold uppercase">Technologies Category</div>
                        <div onclick="selectCategoryItem('Human Resources Category')" class="px-4 py-1.5 hover:bg-gray-400/30 rounded-lg cursor-pointer text-xs text-gray-700 font-bold uppercase">Human Resources Category</div>
                        <div onclick="selectCategoryItem('Finances Category')" class="px-4 py-1.5 hover:bg-gray-400/30 rounded-lg cursor-pointer text-xs text-gray-700 font-bold uppercase">Finances Category</div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2 px-2">
                    <div class="w-4 h-4 rounded-full bg-[#52B788] flex items-center justify-center"><i class="fa-solid fa-check text-[10px] text-white"></i></div>
                    <span class="text-[10px] font-bold text-gray-600">Select all the categories</span>
                </div>

                <div class="relative bg-gray-300/70 rounded-2xl pb-1">
                    <select id="exportFormatSelect" class="w-full bg-[#E2E8F0] rounded-full px-6 py-3 appearance-none focus:outline-none text-gray-800 text-sm font-bold uppercase">
                        <option value="">Export as (e.g PDF)</option>
                        <option value="pdf">PDF Document</option>
                        <option value="csv">CSV Spreadsheet</option>
                    </select>
                    <i class="fa-solid fa-caret-down absolute right-6 top-4 text-gray-500"></i>
                </div>

                <div class="px-2">
                    <label class="block text-xs font-bold text-gray-800 mb-1">Input email for</label>
                    <input type="email" id="reportEmailInput" value="akosijusthineleigh@gmail.com" class="w-full bg-[#E2E8F0] rounded-full px-6 py-3 focus:outline-none text-gray-800 text-sm">
                </div>
            </div>

            <div class="flex justify-center mt-10">
                <button onclick="triggerEmailAnimation('reportModal')" class="bg-[#52B788] text-black font-bold px-16 py-2.5 rounded-xl border border-gray-400 shadow-md hover:bg-[#45a076] text-lg transition">Confirm</button>
            </div>
        </div>
    </div>

    <!-- NOTIFICATION OVERLAYS TOASTS -->
    <div id="successOverlay" class="fixed inset-0 z-[60] flex items-center justify-center modal-overlay hidden no-print-modal">
        <div class="bg-white rounded-xl shadow-2xl px-16 py-10 flex items-center space-x-8 border border-gray-200">
            <div class="w-24 h-24 bg-[#52B788] rounded-full flex items-center justify-center shrink-0">
                <i class="fa-solid fa-check text-5xl text-white"></i>
            </div>
            <h2 class="text-2xl font-black text-gray-800 tracking-wider">LIST HAS BEEN UPDATED</h2>
        </div>
    </div>

    <div id="emailOverlay" class="fixed inset-0 z-[60] flex items-center justify-center modal-overlay hidden no-print-modal">
        <div class="bg-white rounded-xl shadow-2xl px-16 py-10 flex items-center space-x-8 border border-gray-200">
            <div class="w-24 h-24 bg-[#52B788] rounded-full flex items-center justify-center shrink-0">
                <i class="fa-solid fa-check text-5xl text-white"></i>
            </div>
            <h2 class="text-2xl font-black text-gray-800 tracking-wider">EMAIL HAS BEEN SENT</h2>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let budgetChartInstance = null;
        let actualChartInstance = null;
        let currentEditMode = 'budget';

        let financialData = {
            'marketing': { name: 'Marketing', budget: 1200000, actual: 1024692 },
            'operations': { name: 'Operations', budget: 2750000, actual: 2654321 },
            'sales': { name: 'Sales', budget: 2300000, actual: 2123456 },
            'technology': { name: 'Technology', budget: 1950000, actual: 1876543 },
            'human-resources': { name: 'Human Resources', budget: 1550000, actual: 1543210 },
            'finance': { name: 'Finance', budget: 3250000, actual: 3123456 }
        };

        const formatCurrency = (val) => '$' + val.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        const formatPercent = (val) => val.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '%';

        async function loadFinancialData() {
            try {
                const response = await fetch('/budgets'); 
                if (response.ok) {
                    const data = await response.json();
                    if (data && Object.keys(data).length > 0) {
                        financialData = data;
                    }
                }
            } catch (error) {
                console.warn('Backend database fetch unavailable. Defaulting to local memory engine.', error);
            }
            renderCalculatedMetrics();
        }

        async function pushFinancialDataUpdate() {
            const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
            const token = csrfTokenElement ? csrfTokenElement.getAttribute('content') : '';

            try {
                const response = await fetch('/budgets/update', { 
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        mode: currentEditMode,
                        records: financialData
                    })
                });
                
                if (!response.ok) {
                    console.error('Failed to commit modifications to the database server.');
                }
            } catch (error) {
                console.error('Network failure connecting to the database controller.', error);
            }
        }

        function renderCalculatedMetrics() {
            const tbody = document.getElementById('comparisonTableBody');
            if (!tbody) return;
            tbody.innerHTML = '';

            let totalBudget = 0;
            let totalActual = 0;

            Object.keys(financialData).forEach(key => {
                const item = financialData[key];
                const variance = item.budget - item.actual;
                const variancePercent = item.budget !== 0 ? (variance / item.budget) * 100 : 0;

                totalBudget += item.budget;
                totalActual += item.actual;

                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50/70 transition';
                tr.innerHTML = `
                    <td class="p-3.5 pl-6">${item.name}</td>
                    <td class="p-3.5 font-bold text-gray-900">${formatCurrency(item.budget)}</td>
                    <td class="p-3.5">${formatCurrency(item.actual)}</td>
                    <td class="p-3.5 text-emerald-600">${formatCurrency(variance)}</td>
                    <td class="p-3.5 pr-6 text-emerald-600 bg-emerald-50/20 font-bold">${formatPercent(variancePercent)}</td>
                `;
                tbody.appendChild(tr);
            });

            const totalVariance = totalBudget - totalActual;
            const totalVariancePercent = totalBudget !== 0 ? (totalVariance / totalBudget) * 100 : 0;

            const totalRow = document.createElement('tr');
            totalRow.className = 'bg-gray-50 font-bold text-gray-900 border-t-2 border-gray-100';
            totalRow.innerHTML = `
                <td class="p-4 pl-6">Total</td>
                <td class="p-4">${formatCurrency(totalBudget)}</td>
                <td class="p-4">${formatCurrency(totalActual)}</td>
                <td class="p-4 text-emerald-600">${formatCurrency(totalVariance)}</td>
                <td class="p-4 pr-6 text-emerald-600 bg-emerald-50/40 font-extrabold">${formatPercent(totalVariancePercent)}</td>
            `;
            tbody.appendChild(totalRow);

            document.getElementById('cardTotalBudget').innerText = formatCurrency(totalBudget);
            document.getElementById('cardTotalActual').innerText = formatCurrency(totalActual);
            document.getElementById('cardTotalVariance').innerText = formatCurrency(totalVariance);

            if (budgetChartInstance) {
                const monthlyBaseB = totalBudget / 12;
                budgetChartInstance.data.datasets[0].data = [
                    monthlyBaseB * 0.85, monthlyBaseB * 1.10, monthlyBaseB * 0.90, monthlyBaseB * 1.15,
                    monthlyBaseB * 1.30, monthlyBaseB * 0.95, monthlyBaseB * 0.90, monthlyBaseB * 1.12,
                    monthlyBaseB * 1.15, monthlyBaseB * 1.05, monthlyBaseB * 1.10, monthlyBaseB * 1.13
                ];
                budgetChartInstance.update();
            }

            if (actualChartInstance) {
                const monthlyBaseA = totalActual / 12;
                actualChartInstance.data.datasets[0].data = [
                    monthlyBaseA * 0.70, monthlyBaseA * 1.20, monthlyBaseA * 1.10, monthlyBaseA * 0.85,
                    monthlyBaseA * 1.25, monthlyBaseA * 1.35, monthlyBaseA * 0.95, monthlyBaseA * 1.05,
                    monthlyBaseA * 1.15, monthlyBaseA * 0.75, monthlyBaseA * 1.00, monthlyBaseA * 1.30
                ];
                actualChartInstance.update();
            }
        }

        function setEditMode(mode) {
            currentEditMode = mode;
            const btnBudget = document.getElementById('btn-mode-budget');
            const btnActual = document.getElementById('btn-mode-actual');
            
            if (mode === 'budget') {
                btnBudget.className = "flex-1 py-1.5 text-xs font-bold rounded-full transition bg-[#23387E] text-white";
                btnActual.className = "flex-1 py-1.5 text-xs font-bold rounded-full transition text-gray-500 hover:text-gray-800";
            } else {
                btnActual.className = "flex-1 py-1.5 text-xs font-bold rounded-full transition bg-[#23387E] text-white";
                btnBudget.className = "flex-1 py-1.5 text-xs font-bold rounded-full transition text-gray-500 hover:text-gray-800";
            }
            updateModalFields();
        }

        function updateModalFields() {
            Object.keys(financialData).forEach(key => {
                const label = document.getElementById(`lbl-${key}`);
                const input = document.getElementById(`input-${key}`);
                const item = financialData[key];
                
                if (input && label) {
                    input.value = ''; 
                    if (currentEditMode === 'budget') {
                        label.innerText = `${item.name} Budget`;
                        input.placeholder = item.budget;
                    } else {
                        label.innerText = `${item.name} Actual Spent`;
                        input.placeholder = item.actual;
                    }
                }
            });
        }

        async function saveBudgetEdits() {
            Object.keys(financialData).forEach(key => {
                const inputElement = document.getElementById(`input-${key}`);
                if (inputElement && inputElement.value.trim() !== '') {
                    const value = parseFloat(inputElement.value) || 0;
                    if (currentEditMode === 'budget') {
                        financialData[key].budget = value;
                    } else {
                        financialData[key].actual = value;
                    }
                }
            });

            renderCalculatedMetrics();
            await pushFinancialDataUpdate();
            triggerSuccessAnimation('editModal');
        }

        function stepDate(direction) {
            const monthSelect = document.getElementById('reportMonthSelect');
            const yearSelect = document.getElementById('reportYearSelect');
            
            let currentMonth = parseInt(monthSelect.value);
            let currentYear = parseInt(yearSelect.value);
            
            currentMonth += direction;
            
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            } else if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            
            const yearOption = yearSelect.querySelector(`option[value="${currentYear}"]`);
            if (yearOption) {
                yearSelect.value = currentYear;
                monthSelect.value = currentMonth;
                onDateSelectChange();
            } else {
                if (direction > 0) monthSelect.value = 11;
                if (direction < 0) monthSelect.value = 0;
            }
        }

        function onDateSelectChange() {
            const m = document.getElementById('reportMonthSelect').value;
            const y = document.getElementById('reportYearSelect').value;
            console.log(`Targeting Report Scope: Month Index ${m}, Year ${y}`);
        }

        function toggleCustomDropdown(listId, iconId) {
            const dropdownList = document.getElementById(listId);
            const dropdownIcon = document.getElementById(iconId);
            
            if (dropdownList.classList.contains('hidden')) {
                dropdownList.classList.remove('hidden');
                dropdownIcon.className = 'fa-solid fa-caret-up text-gray-500 transition-transform';
            } else {
                dropdownList.classList.add('hidden');
                dropdownIcon.className = 'fa-solid fa-caret-down text-gray-500 transition-transform';
            }
        }

        function selectCategoryItem(itemName) {
            document.getElementById('selectedCategoryHeader').innerText = itemName;
            document.getElementById('categoryDropdownList').classList.add('hidden');
            document.getElementById('categoryDropdownIcon').className = 'fa-solid fa-caret-down text-gray-500 transition-transform';
        }

        function executeDataExport(formatType) {
            if (!formatType) return;
            
            if (formatType === 'csv') {
                let csvRows = ['Category,Budget,Actual,Variance,Variance %'];
                
                Object.keys(financialData).forEach(key => {
                    const item = financialData[key];
                    const variance = item.budget - item.actual;
                    const variancePercent = item.budget !== 0 ? (variance / item.budget) * 100 : 0;
                    csvRows.push(`"${item.name}",${item.budget},${item.actual},${variance},${variancePercent.toFixed(2)}%`);
                });

                const csvString = csvRows.join('\n');
                const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement("a");
                link.href = URL.createObjectURL(blob);
                link.setAttribute("download", `financial_report_${new Date().toISOString().slice(0,10)}.csv`);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else if (formatType === 'pdf') {
                window.print();
            }
        }

        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    grid: { color: '#E2E8F0', drawBorder: false },
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
                            if (value >= 1000) return (value / 1000).toFixed(0) + 'k';
                            return value;
                        },
                        color: '#94A3B8'
                    }
                },
                x: { grid: { display: false }, ticks: { color: '#94A3B8' } }
            }
        };

        window.addEventListener('DOMContentLoaded', () => {
            budgetChartInstance = new Chart(document.getElementById('budgetChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        data: [], 
                        borderColor: '#003399',
                        backgroundColor: '#003399',
                        borderWidth: 3,
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: '#003399'
                    }]
                },
                options: chartOptions
            });

            actualChartInstance = new Chart(document.getElementById('actualChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        data: [], 
                        borderColor: '#3CB371',
                        backgroundColor: '#3CB371',
                        borderWidth: 3,
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: '#3CB371'
                    }]
                },
                options: chartOptions
            });

            loadFinancialData();
        });

        function editBudget() {
            setEditMode('budget');
            openCustomModal('editModal');
        }

        function generateReport() {
            openCustomModal('reportModal');
        }

        function openCustomModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeCustomModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function triggerSuccessAnimation(activeModalId) {
            closeCustomModal(activeModalId);
            const successPopup = document.getElementById('successOverlay');
            successPopup.classList.remove('hidden');
            setTimeout(() => {
                successPopup.classList.add('hidden');
                
                // Fallback using the layout's AppUI Toast if available
                if (typeof AppUI !== 'undefined') AppUI.showToast('List has been updated', 'success');
            }, 2000);
        }

        function triggerEmailAnimation(activeModalId) {
            const emailInput = document.getElementById('reportEmailInput');
            const formatSelect = document.getElementById('exportFormatSelect');

            if (emailInput && !emailInput.value.trim().includes('@')) {
                emailInput.style.border = '2px solid #EF4444';
                return;
            } else if (emailInput) {
                emailInput.style.border = 'none';
            }

            if (formatSelect && formatSelect.value) {
                executeDataExport(formatSelect.value);
            }

            closeCustomModal(activeModalId);
            const emailPopup = document.getElementById('emailOverlay');
            emailPopup.classList.remove('hidden');
            setTimeout(() => {
                emailPopup.classList.add('hidden');
                
                // Fallback using the layout's AppUI Toast if available
                if (typeof AppUI !== 'undefined') AppUI.showToast('Email has been sent', 'success');
            }, 2000);
        }
    </script>
@endpush