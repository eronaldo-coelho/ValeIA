<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ValeIA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { 50: '#ecfdf5', 100: '#d1fae5', 500: '#10b981', 600: '#059669', 800: '#065f46', 900: '#064e3b' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-600" x-data="{ sidebarOpen: false }">

    @include('admin.partials.mobile-header')

    <div class="flex h-screen overflow-hidden pt-16 md:pt-0">
        
        @include('admin.partials.sidebar')

        <main class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-8" @click="sidebarOpen = false">
            <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Visão Geral</h1>
                    <p class="text-slate-500">Acompanhe os gastos em tempo real.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="bg-white p-2.5 rounded-lg border border-slate-200 text-slate-500 hover:text-brand-600 hover:border-brand-200 transition shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </button>
                    <a href="{{ route('admin.criar-nota.index') }}" class="bg-brand-600 hover:bg-brand-800 text-white px-4 py-2.5 rounded-lg font-semibold shadow-lg shadow-brand-500/20 flex items-center gap-2 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Nova Nota Manual
                    </a>
                </div>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-50 p-3 rounded-lg text-brand-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Total Gasto (Mês)</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $stats['total_gasto'] }}</h3>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-orange-50 p-3 rounded-lg text-orange-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Notas Pendentes</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $stats['notas_pendentes'] }}</h3>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-50 p-3 rounded-lg text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Funcionários Ativos</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $stats['funcionarios_ativos'] }}</h3>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-purple-50 p-3 rounded-lg text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Saldo Disponível (Estimado)</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $stats['saldo_restante'] }}</h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 lg:col-span-2">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Fluxo de Despesas (Últimos 7 dias)</h3>
                    <div id="expenseChart" class="w-full h-80"></div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Top Vales</h3>
                    <div id="categoryChart" class="w-full h-64 flex justify-center"></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-800">Notas Recentes (Feed)</h3>
                    <a href="{{ route('admin.notas.index') }}" class="text-sm text-brand-600 font-semibold hover:underline">Ver todas</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Funcionário</th>
                                <th class="px-6 py-4">Tipo</th>
                                <th class="px-6 py-4">Data</th>
                                <th class="px-6 py-4">Valor</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($recent_receipts as $receipt)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $receipt['id'] }}</td>
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                                        {{ substr($receipt['user'], 0, 2) }}
                                    </div>
                                    {{ $receipt['user'] }}
                                </td>
                                <td class="px-6 py-4">{{ $receipt['type'] }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $receipt['date'] }}</td>
                                <td class="px-6 py-4 font-bold text-slate-800">{{ $receipt['amount'] }}</td>
                                <td class="px-6 py-4">
                                    @if($receipt['status'] == 'approved')
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Aprovado</span>
                                    @elseif($receipt['status'] == 'pending')
                                        <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-bold">Pendente</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">Rejeitado</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.notas.edit', str_replace('#NB-', '', $receipt['id'])) }}" class="text-slate-400 hover:text-brand-600 inline-block p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        const fluxoLabels = @json($chartLabels);
        const fluxoData = @json($chartData);
        
        const catLabels = @json($catLabels);
        const catData = @json($catSeries);

        var optionsArea = {
            series: [{ name: 'Gastos', data: fluxoData }],
            chart: { height: 320, type: 'area', toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
            colors: ['#10b981'],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            xaxis: { 
                categories: fluxoLabels, 
                labels: { style: { colors: '#64748b' } } 
            },
            yaxis: { 
                labels: { 
                    style: { colors: '#64748b' }, 
                    formatter: (value) => { 
                        return value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                    } 
                } 
            },
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.1, stops: [0, 90, 100] } },
            tooltip: { theme: 'light' }
        };
        var chartArea = new ApexCharts(document.querySelector("#expenseChart"), optionsArea);
        chartArea.render();

        var optionsDonut = {
            series: catData,
            labels: catLabels,
            chart: { type: 'donut', height: 280, fontFamily: 'Inter, sans-serif' },
            colors: ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6'],
            legend: { position: 'bottom' },
            dataLabels: { enabled: false },
            plotOptions: { 
                pie: { 
                    donut: { 
                        size: '70%', 
                        labels: { 
                            show: true, 
                            total: { 
                                show: true, 
                                label: 'Total', 
                                formatter: function (w) { 
                                    const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    return total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                                } 
                            } 
                        } 
                    } 
                } 
            }
        };
        var chartDonut = new ApexCharts(document.querySelector("#categoryChart"), optionsDonut);
        chartDonut.render();
    </script>
</body>
</html>