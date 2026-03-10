<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Individual - {{ $funcionario->nome }}</title>
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
                    <h1 class="text-2xl font-bold text-slate-800">{{ $funcionario->nome }}</h1>
                    <p class="text-slate-500">{{ $funcionario->cargo }} • CPF: {{ $funcionario->cpf }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.relatorios.index') }}" class="text-slate-500 hover:text-slate-800 font-semibold text-sm px-4">Voltar</a>
                    <a href="{{ route('admin.relatorios.funcionario.pdf', ['id' => $funcionario->id, 'inicio' => $inicio, 'fim' => $fim]) }}" target="_blank" class="bg-brand-600 text-white px-4 py-2.5 rounded-lg text-sm font-bold hover:bg-brand-700 transition flex items-center gap-2 shadow-lg shadow-brand-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Exportar PDF
                    </a>
                </div>
            </header>

            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 mb-6">
                <form action="{{ route('admin.relatorios.show', $funcionario->id) }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="w-full md:w-auto">
                        <label class="block text-xs font-medium text-slate-500 mb-1">Data Início</label>
                        <input type="date" name="inicio" value="{{ $inicio }}" class="bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500 w-full">
                    </div>
                    <div class="w-full md:w-auto">
                        <label class="block text-xs font-medium text-slate-500 mb-1">Data Fim</label>
                        <input type="date" name="fim" value="{{ $fim }}" class="bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500 w-full">
                    </div>
                    <button type="submit" class="w-full md:w-auto bg-slate-800 text-white px-6 py-2.5 rounded-lg text-sm font-bold hover:bg-slate-700 transition">
                        Atualizar Período
                    </button>
                </form>
            </div>

            <div class="space-y-6">
                @foreach($relatorioVales as $item)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">{{ $item['nome_vale'] }}</h3>
                                <p class="text-xs text-slate-500 uppercase tracking-wide">
                                    Limite: R$ {{ number_format($item['config']->valor, 2, ',', '.') }} / {{ $item['config']->periodicidade }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500">Total Gasto no Período</p>
                                <p class="text-xl font-bold text-brand-600">R$ {{ number_format($item['gasto'], 2, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="w-full bg-slate-200 rounded-full h-2.5 mb-1">
                            @php
                                $porcentagem = ($item['limite'] > 0) ? ($item['gasto'] / $item['limite']) * 100 : 0;
                                $corBarra = $porcentagem > 100 ? 'bg-red-500' : 'bg-brand-500';
                            @endphp
                            <div class="{{ $corBarra }} h-2.5 rounded-full transition-all duration-500" style="width: {{ min($porcentagem, 100) }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs font-medium text-slate-500">
                            <span>Utilizado: {{ number_format($porcentagem, 1) }}% do teto mensal estimado</span>
                            <span>Disponível (Est.): R$ {{ number_format($item['saldo'], 2, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-white text-xs uppercase font-semibold text-slate-500 border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-3">Data</th>
                                    <th class="px-6 py-3">Estabelecimento</th>
                                    <th class="px-6 py-3">Tipo</th>
                                    <th class="px-6 py-3 text-right">Valor</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($item['notas'] as $nota)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-3">{{ $nota->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-3">{{ $nota->estabelecimento ?? 'N/A' }}</td>
                                    <td class="px-6 py-3">{{ ucfirst($nota->tipo) }}</td>
                                    <td class="px-6 py-3 text-right font-medium text-slate-900">
                                        R$ {{ number_format($nota->valor_total_nota, 2, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-400 italic">
                                        Nenhuma despesa aprovada neste período para este vale.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach

                <div class="bg-slate-900 text-white p-6 rounded-2xl shadow-lg flex justify-between items-center">
                    <div>
                        <p class="text-slate-400 text-sm">Total a Reembolsar (Geral)</p>
                        <p class="text-xs text-slate-500">Soma de todos os vales no período selecionado</p>
                    </div>
                    <div class="text-3xl font-bold text-brand-400">
                        R$ {{ number_format($totalGastoGeral, 2, ',', '.') }}
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>