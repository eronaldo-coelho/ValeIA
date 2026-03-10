<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extrato de Pagamentos - ValeIA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { 50: '#ecfdf5', 100: '#d1fae5', 500: '#10b981', 600: '#059669', 800: '#065f46' }
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
            <header class="flex items-center gap-4 mb-8">
                <a href="{{ route('admin.pagamentos.index') }}" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Extrato de Pagamentos</h1>
                    <p class="text-slate-500">Histórico de {{ $funcionario->nome }}</p>
                </div>
            </header>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold">Total Pago</p>
                        <p class="text-2xl font-bold text-slate-800">R$ {{ number_format($pagamentos->sum('valor'), 2, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-500 uppercase font-bold">Último Pagamento</p>
                        <p class="text-sm font-medium text-slate-700">
                            {{ $pagamentos->first() ? \Carbon\Carbon::parse($pagamentos->first()->data_pagamento)->format('d/m/Y') : '-' }}
                        </p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-white text-xs uppercase font-semibold text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Data</th>
                                <th class="px-6 py-4">Comprovante</th>
                                <th class="px-6 py-4">Método</th>
                                <th class="px-6 py-4">Referente a</th>
                                <th class="px-6 py-4 text-right">Valor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($pagamentos as $pg)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($pg->data_pagamento)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($pg->imagem)
                                        <button @click="modalImage = '{{ $pg->imagem }}'" class="flex items-center gap-1 text-brand-600 hover:text-brand-800 text-xs font-bold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            Ver Imagem
                                        </button>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 uppercase text-xs font-bold text-slate-500">
                                    {{ $pg->forma_pagamento }}
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    {{ $pg->nomes_vales }}
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-slate-800">
                                    R$ {{ number_format($pg->valor, 2, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <div x-show="modalImage" class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-sm p-4" x-transition.opacity style="display: none;">
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