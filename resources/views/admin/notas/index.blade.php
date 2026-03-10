<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas Fiscais - ValeIA</title>
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
                    <h1 class="text-2xl font-bold text-slate-800">Notas Fiscais</h1>
                    <p class="text-slate-500">Gerencie e audite os comprovantes enviados.</p>
                </div>
            </header>

            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 mb-6">
                <form action="{{ route('admin.notas.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Status</label>
                        <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500">
                            <option value="">Todos</option>
                            <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                            <option value="aprovado" {{ request('status') == 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                            <option value="reprovado" {{ request('status') == 'reprovado' ? 'selected' : '' }}>Reprovado</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Tipo de Vale</label>
                        <select name="vale_id" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500">
                            <option value="">Todos</option>
                            @foreach($vales as $vale)
                                <option value="{{ $vale->id }}" {{ request('vale_id') == $vale->id ? 'selected' : '' }}>{{ $vale->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Data</label>
                        <input type="date" name="data" value="{{ request('data') }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-slate-800 text-white py-2.5 rounded-lg text-sm font-bold hover:bg-slate-700 transition">
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
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
                                <th class="px-6 py-4 text-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($notas as $nota)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-medium text-slate-900">#NB-{{ $nota->id }}</td>
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 uppercase">
                                        {{ substr($nota->funcionario->nome ?? '?', 0, 2) }}
                                    </div>
                                    <span class="font-medium">{{ $nota->funcionario->nome ?? 'Desconhecido' }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $nota->vale ? $nota->vale->nome : ucfirst($nota->tipo) }}
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $nota->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800">
                                    R$ {{ number_format($nota->valor_total_nota, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($nota->status == 'aprovado')
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Aprovado</span>
                                    @elseif($nota->status == 'reprovado')
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">Rejeitado</span>
                                    @else
                                        <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-bold">Pendente</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.notas.edit', $nota->id) }}" class="text-slate-400 hover:text-brand-600 inline-block p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-100">
                    {{ $notas->withQueryString()->links() }}
                </div>
            </div>
        </main>
    </div>
</body>
</html>