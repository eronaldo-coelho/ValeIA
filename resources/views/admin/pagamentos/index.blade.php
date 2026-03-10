<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamentos - ValeIA</title>
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
<body class="bg-slate-50 font-sans text-slate-600" x-data="{ sidebarOpen: false, modalImage: null }">

    @include('admin.partials.mobile-header')

    <div class="flex h-screen overflow-hidden pt-16 md:pt-0">
        @include('admin.partials.sidebar')

        <main class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-8" @click="sidebarOpen = false">
            
            <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Pagamentos Realizados</h1>
                    <p class="text-slate-500">Histórico de comprovantes e baixas de vales.</p>
                </div>
                <a href="{{ route('admin.pagamentos.create') }}" class="bg-brand-600 hover:bg-brand-800 text-white px-4 py-2.5 rounded-lg font-semibold shadow-lg shadow-brand-500/20 flex items-center gap-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Registrar Pagamento
                </a>
            </header>

            @if(session('success'))
                <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500">
                            <tr>
                                <th class="px-6 py-4">Comprovante</th>
                                <th class="px-6 py-4">Funcionário</th>
                                <th class="px-6 py-4">Forma Pagto</th>
                                <th class="px-6 py-4">Data</th>
                                <th class="px-6 py-4">Vales Pagos</th>
                                <th class="px-6 py-4">Valor</th>
                                <th class="px-6 py-4 text-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($pagamentos as $pagamento)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    @if($pagamento->imagem)
                                        <img src="{{ $pagamento->imagem }}" alt="Comprovante" class="w-10 h-10 object-cover rounded-lg cursor-pointer border border-slate-200 hover:scale-110 transition" @click="modalImage = '{{ $pagamento->imagem }}'">
                                    @else
                                        <span class="text-slate-400 text-xs italic">Sem img</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-900">
                                    {{ $pagamento->funcionario ? $pagamento->funcionario->nome : 'Removido' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-slate-100 rounded text-xs font-semibold text-slate-600 uppercase">
                                        {{ $pagamento->forma_pagamento }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($pagamento->data_pagamento)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-brand-700 max-w-xs truncate">
                                    {{ $pagamento->nomes_vales }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800">
                                    R$ {{ number_format($pagamento->valor, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($pagamento->funcionario)
                                        <a href="{{ route('admin.pagamentos.show', $pagamento->funcionario_id) }}" class="text-brand-600 hover:underline text-xs font-bold">Ver Histórico</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @if($pagamentos->isEmpty())
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-slate-400 italic">
                                    Nenhum pagamento registrado.
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <!-- Modal Imagem -->
        <div x-show="modalImage" class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-sm p-4 transition-opacity" x-transition.opacity style="display: none;">
            <div class="relative max-w-4xl w-full max-h-full">
                <button @click="modalImage = null" class="absolute -top-12 right-0 text-white hover:text-gray-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <img :src="modalImage" class="w-full h-auto max-h-[85vh] object-contain rounded-lg shadow-2xl">
            </div>
        </div>
    </div>
</body>
</html>