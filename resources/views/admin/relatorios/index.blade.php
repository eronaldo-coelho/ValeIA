<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - ValeIA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
                    <h1 class="text-2xl font-bold text-slate-800">Relatório de Reembolsos</h1>
                    <p class="text-slate-500">Resumo financeiro por funcionário e tipo de vale.</p>
                </div>
            </header>

            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 mb-6">
                <form action="{{ route('admin.relatorios.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="w-full md:w-auto">
                        <label class="block text-xs font-medium text-slate-500 mb-1">Data Início</label>
                        <input type="date" name="inicio" value="{{ $inicio }}" class="bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500 w-full">
                    </div>
                    <div class="w-full md:w-auto">
                        <label class="block text-xs font-medium text-slate-500 mb-1">Data Fim</label>
                        <input type="date" name="fim" value="{{ $fim }}" class="bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500 w-full">
                    </div>
                    <div class="flex gap-2 w-full md:w-auto">
                        <button type="submit" class="bg-slate-800 text-white px-4 py-2.5 rounded-lg text-sm font-bold hover:bg-slate-700 transition flex-1 md:flex-none">
                            Filtrar
                        </button>
                        <a href="{{ route('admin.relatorios.pdf', ['inicio' => $inicio, 'fim' => $fim]) }}" target="_blank" class="bg-brand-600 text-white px-4 py-2.5 rounded-lg text-sm font-bold hover:bg-brand-700 transition flex items-center gap-2 flex-1 md:flex-none justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Exportar PDF
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500">
                            <tr>
                                <th class="px-6 py-4 min-w-[200px]">Funcionário</th>
                                @foreach($vales as $vale)
                                    <th class="px-6 py-4 text-right whitespace-nowrap">{{ $vale->nome }}</th>
                                @endforeach
                                <th class="px-6 py-4 text-right font-bold text-slate-800 bg-slate-100">Total Reembolso</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($dados as $linha)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.relatorios.show', ['id' => $linha['funcionario']->id, 'inicio' => $inicio, 'fim' => $fim]) }}" class="font-medium text-brand-600 hover:underline">
                                        {{ $linha['funcionario']->nome }}
                                    </a>
                                    <p class="text-xs text-slate-400">{{ $linha['funcionario']->cargo }}</p>
                                </td>
                                @foreach($vales as $vale)
                                    <td class="px-6 py-4 text-right text-slate-600">
                                        R$ {{ number_format($linha['valores_por_vale'][$vale->id], 2, ',', '.') }}
                                    </td>
                                @endforeach
                                <td class="px-6 py-4 text-right font-bold text-slate-900 bg-slate-50">
                                    R$ {{ number_format($linha['total_funcionario'], 2, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-800 text-white font-bold">
                            <tr>
                                <td class="px-6 py-4">TOTAIS GERAIS</td>
                                @foreach($vales as $vale)
                                    <td class="px-6 py-4 text-right">
                                        R$ {{ number_format($totaisPorVale[$vale->id], 2, ',', '.') }}
                                    </td>
                                @endforeach
                                <td class="px-6 py-4 text-right bg-slate-900 text-brand-400">
                                    R$ {{ number_format($totalGeral, 2, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>