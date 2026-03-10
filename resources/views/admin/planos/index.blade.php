<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Plano - ValeIA</title>
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
<body class="bg-slate-50 font-sans text-slate-600" x-data="{ sidebarOpen: false, paymentModalOpen: false, selectedMethod: 'pix' }">

    @include('admin.partials.mobile-header')

    <div class="flex h-screen overflow-hidden pt-16 md:pt-0">
        @include('admin.partials.sidebar')

        <main class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-8" @click="sidebarOpen = false">
            <header class="mb-8">
                <h1 class="text-2xl font-bold text-slate-800">Assinatura e Cobrança</h1>
                <p class="text-slate-500">Gerencie seu plano atual e método de pagamento.</p>
            </header>

            @if(session('success'))
                <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">Plano Atual</h2>
                            <p class="text-brand-600 font-semibold text-xl mt-1">{{ $currentPlan->nome }}</p>
                        </div>
                        <span class="px-3 py-1 {{ $daysToRenovation > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} text-xs font-bold rounded-full uppercase">
                            {{ $daysToRenovation > 0 ? 'Ativo' : 'Expirado' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl mb-6">
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-semibold">Tipo de Assinatura</p>
                            @if($isTrial)
                                <p class="text-slate-900 font-medium">Período de Teste Gratuito</p>
                            @else
                                <p class="text-slate-900 font-medium">Plano Mensal (Pré-pago)</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-semibold">Vencimento</p>
                            <p class="text-slate-900 font-medium">{{ $renovationDate->format('d/m/Y') }}</p>
                            
                            @if($daysToRenovation > 0)
                                <p class="text-sm text-brand-600 font-bold mt-1">Restam {{ (int)$daysToRenovation }} dias</p>
                            @else
                                <p class="text-sm text-red-600 font-bold mt-1">Renovação Necessária</p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="font-bold text-slate-800 mb-3">Incluso no seu plano:</h3>
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($currentPlan->descricao as $item)
                                <li class="flex items-start text-sm text-slate-600">
                                    <svg class="w-4 h-4 text-brand-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @if($canRenewEarly || $daysToRenovation <= 0)
                        <div class="mt-auto pt-6 border-t border-slate-100">
                            <div class="flex items-center justify-between bg-yellow-50 p-4 rounded-xl border border-yellow-100">
                                <div>
                                    <p class="text-sm font-bold text-yellow-800">
                                        {{ $daysToRenovation <= 0 ? 'Assinatura Expirada' : 'Adiantar Renovação' }}
                                    </p>
                                    <p class="text-xs text-yellow-600">Garanta mais 30 dias de acesso. O tempo é acumulativo.</p>
                                </div>
                                <button @click="paymentModalOpen = true" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-bold py-2 px-4 rounded-lg shadow-sm transition">
                                    {{ $daysToRenovation <= 0 ? 'Pagar Agora' : 'Renovar (+30 Dias)' }}
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="bg-brand-900 rounded-2xl p-6 text-white relative overflow-hidden flex flex-col justify-center">
                    <div class="relative z-10">
                        <h2 class="text-lg font-bold mb-2">Precisa de mais recursos?</h2>
                        <p class="text-brand-100 text-sm mb-6">Faça o upgrade agora e desbloqueie todo o potencial da plataforma.</p>
                        <a href="#outros-planos" class="inline-block bg-white text-brand-900 px-6 py-2 rounded-lg font-bold text-sm hover:bg-brand-50 transition shadow-lg">Ver Opções</a>
                    </div>
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-brand-500 rounded-full blur-3xl opacity-50"></div>
                </div>
            </div>

            <div class="border-t border-slate-200 my-8"></div>

            <h2 id="outros-planos" class="text-xl font-bold text-slate-800 mb-6">Disponíveis para Troca</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pb-10">
                @foreach($allPlans as $plan)
                    @if($plan->id !== $currentPlan->id)
                        @php
                            $precoFinal = $plan->valor;
                            if($plan->desconto > 0) {
                                $precoFinal = $plan->valor - ($plan->valor * ($plan->desconto / 100));
                            }
                        @endphp
                        
                        <div class="bg-white border border-slate-200 rounded-xl p-6 hover:border-brand-500 hover:shadow-lg transition relative flex flex-col">
                            <h3 class="font-bold text-lg text-slate-800">{{ $plan->nome }}</h3>
                            <p class="text-xs text-slate-400 mt-1 mb-4">{{ $plan->descricao[0] ?? '' }}</p>

                            <div class="mb-6">
                                @if($plan->desconto > 0)
                                    <span class="text-sm text-slate-400 line-through">R$ {{ number_format($plan->valor, 0, ',', '.') }}</span>
                                @endif
                                <div class="flex items-baseline gap-1">
                                    <span class="text-3xl font-extrabold text-slate-900">R$ {{ number_format($precoFinal, 0, ',', '.') }}</span>
                                    <span class="text-sm text-slate-500">/mês</span>
                                </div>
                            </div>

                            <ul class="space-y-3 mb-8 flex-1">
                                @foreach($plan->descricao as $item)
                                    <li class="flex items-start text-sm text-slate-600">
                                        <svg class="w-4 h-4 text-brand-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="leading-tight">{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <form action="{{ route('admin.planos.update') }}" method="POST" class="mt-auto">
                                @csrf
                                <input type="hidden" name="plano_id" value="{{ $plan->id }}">
                                
                                @if($canChangePlan)
                                    <button type="submit" class="w-full py-3 border-2 border-brand-500 text-brand-600 rounded-lg font-bold text-sm hover:bg-brand-500 hover:text-white transition uppercase tracking-wide">
                                        Mudar para este
                                    </button>
                                @else
                                    <button type="button" disabled class="w-full py-3 bg-slate-100 text-slate-400 rounded-lg font-bold text-sm cursor-not-allowed border-2 border-slate-200" title="Aguarde faltar 2 dias para o vencimento">
                                        Troca Indisponível
                                    </button>
                                    <p class="text-xs text-center text-slate-400 mt-2">Disponível em {{ $renovationDate->subDays(2)->format('d/m') }}</p>
                                @endif
                            </form>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- MODAL DE PAGAMENTO -->
            <div x-show="paymentModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="paymentModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" @click="paymentModalOpen = false" aria-hidden="true"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="paymentModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-xl leading-6 font-bold text-slate-900" id="modal-title">Renovar Assinatura</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-slate-500">
                                            Você está renovando o plano <strong>{{ $currentPlan->nome }}</strong>. O valor será de 
                                            @php
                                                $valorRenovacao = $currentPlan->valor;
                                                if($currentPlan->desconto > 0) {
                                                    $valorRenovacao = $currentPlan->valor - ($currentPlan->valor * ($currentPlan->desconto / 100));
                                                }
                                            @endphp
                                            <span class="font-bold text-slate-900">R$ {{ number_format($valorRenovacao, 2, ',', '.') }}</span>.
                                        </p>
                                    </div>

                                    <form action="{{ route('admin.planos.pagamento.gerar') }}" method="POST" id="renewForm" class="mt-6">
                                        @csrf
                                        <input type="hidden" name="plano_id" value="{{ $currentPlan->id }}">
                                        
                                        <div class="grid grid-cols-2 gap-4">
                                            <label class="cursor-pointer relative">
                                                <input type="radio" name="metodo" value="pix" x-model="selectedMethod" class="peer sr-only">
                                                <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-brand-500 peer-checked:bg-brand-50 hover:bg-slate-50 transition text-center h-full flex flex-col items-center justify-center">
                                                    <svg class="w-8 h-8 text-brand-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                                    <span class="font-bold text-slate-700 block">Pix</span>
                                                    <span class="text-xs text-brand-600 font-semibold">Aprovação Imediata</span>
                                                </div>
                                            </label>

                                            <label class="cursor-pointer relative">
                                                <input type="radio" name="metodo" value="bolbradesco" x-model="selectedMethod" class="peer sr-only">
                                                <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-brand-500 peer-checked:bg-brand-50 hover:bg-slate-50 transition text-center h-full flex flex-col items-center justify-center">
                                                    <svg class="w-8 h-8 text-slate-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    <span class="font-bold text-slate-700 block">Boleto</span>
                                                    <span class="text-xs text-slate-500">Até 3 dias úteis</span>
                                                </div>
                                            </label>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button form="renewForm" type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-3 bg-brand-600 text-base font-medium text-white hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Gerar Pagamento
                            </button>
                            <button type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-3 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="paymentModalOpen = false">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>