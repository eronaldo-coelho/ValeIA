<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipos de Vales - ValeIA</title>
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
                    <h1 class="text-2xl font-bold text-slate-800">Tipos de Vales</h1>
                    <p class="text-slate-500">Gerencie os benefícios oferecidos pela empresa.</p>
                </div>
                <a href="{{ route('admin.vales.create') }}" class="bg-brand-600 hover:bg-brand-800 text-white px-4 py-2.5 rounded-lg font-semibold shadow-lg shadow-brand-500/20 flex items-center gap-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Novo Vale
                </a>
            </header>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500">
                            <tr>
                                <th class="px-6 py-4">Nome do Vale</th>
                                <th class="px-6 py-4 text-center">Funcionários Ativos</th>
                                <th class="px-6 py-4 text-right">Custo Mensal Estimado</th>
                                <th class="px-6 py-4 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($vales as $vale)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $vale->nome }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                                        {{ $vale->total_funcionarios }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-medium text-brand-600">
                                    R$ {{ number_format($vale->custo_mensal, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-2">
                                    @if($vale->admin_id == auth()->id())
                                        <a href="{{ route('admin.vales.edit', $vale->id) }}" class="text-slate-400 hover:text-brand-600 p-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('admin.vales.destroy', $vale->id) }}" method="POST" onsubmit="return confirm('Excluir este vale? Isso só é possível se nenhum funcionário estiver usando.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-red-600 p-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400 italic px-2">Padrão do Sistema</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>