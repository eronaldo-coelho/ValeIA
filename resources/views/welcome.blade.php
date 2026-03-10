<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>ValeIA | Gestão de Reembolsos</title>
    <meta name="description" content="O fim das planilhas de reembolso. Auditoria de notas fiscais via IA e integração contábil.">
    
    <!-- Meta tags essenciais apenas -->
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#064e3b">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph -->
    <meta property="og:locale" content="pt_BR">
    <meta property="og:type" content="website">
    <meta property="og:title" content="ValeIA - O fim da planilha de reembolso">
    <meta property="og:description" content="Auditoria fiscal automática para empresas.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('imagens/og-share.jpg') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Ajuste fino para o gradiente do hero não quebrar em telas ultra-wide */
        .hero-pattern {
            background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
            background-size: 24px 24px;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans text-slate-900 bg-slate-50 antialiased">

    <!-- Header Controlado via Alpine -->
    <header 
        x-data="{ menuAberto: false, scrollado: false }" 
        @scroll.window="scrollado = (window.pageYOffset > 10)"
        :class="scrollado ? 'bg-brand-900/95 backdrop-blur shadow-md py-2' : 'bg-brand-900 py-4'"
        class="fixed w-full top-0 z-50 border-b border-brand-800 transition-all duration-300"
    >
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                
                <!-- Logo Wrapper -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="block hover:opacity-90 transition-opacity">
                        <img class="h-[52px] md:h-[60px] w-auto object-contain brightness-0 invert" 
                             src="{{ asset('imagens/logo.png') }}" 
                             alt="ValeIA" 
                             fetchpriority="high">
                    </a>
                </div>

                <!-- Nav Principal -->
                <nav class="hidden md:flex gap-8 items-center">
                    <a href="#funcionalidades" class="text-sm font-medium text-brand-100 hover:text-white hover:underline decoration-brand-400 underline-offset-4 transition-all">Funcionalidades</a>
                    <a href="#planos" class="text-sm font-medium text-brand-100 hover:text-white hover:underline decoration-brand-400 underline-offset-4 transition-all">Planos</a>
                    <a href="/ajuda" class="text-sm font-medium text-brand-100 hover:text-white hover:underline decoration-brand-400 underline-offset-4 transition-all">Suporte</a>
                </nav>

                <!-- Actions -->
                <div class="hidden md:flex items-center gap-4">
                    <a href="{{ route('login') }}" class="text-sm font-bold text-white hover:text-brand-200 transition-colors">
                        Login
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-brand-900 bg-white rounded hover:bg-slate-100 transition-colors shadow-sm">
                        Criar Conta
                    </a>
                </div>

                <!-- Mobile Toggler -->
                <div class="md:hidden flex items-center">
                    <button 
                        @click="menuAberto = !menuAberto" 
                        type="button" 
                        class="text-brand-100 hover:text-white p-2 transition-colors focus:outline-none"
                        aria-label="Abrir menu"
                    >
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!menuAberto" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="menuAberto" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Dropdown Mobile -->
        <div 
            x-show="menuAberto" 
            x-transition.opacity.duration.200ms
            x-cloak
            class="md:hidden bg-brand-900 border-t border-brand-800 absolute w-full left-0 shadow-2xl"
        >
            <div class="p-4 space-y-3">
                <a href="#funcionalidades" @click="menuAberto = false" class="block px-4 py-3 text-brand-50 hover:bg-brand-800 rounded-lg transition-colors font-medium">Funcionalidades</a>
                <a href="#planos" @click="menuAberto = false" class="block px-4 py-3 text-brand-50 hover:bg-brand-800 rounded-lg transition-colors font-medium">Planos</a>
                <a href="/ajuda" class="block px-4 py-3 text-brand-50 hover:bg-brand-800 rounded-lg transition-colors font-medium">Ajuda</a>
                <div class="border-t border-brand-800 my-2"></div>
                <a href="{{ route('login') }}" class="block w-full text-center py-3 bg-white text-brand-900 font-bold rounded shadow-sm">
                    Acessar Sistema
                </a>
            </div>
        </div>
    </header>

    <!-- Espaçador para o header fixo -->
    <div class="h-[80px] md:h-[92px]"></div>

    <main>
        <!-- Hero: Foco em conversão -->
        <section class="relative bg-white border-b border-slate-200 overflow-hidden">
            <div class="absolute inset-0 hero-pattern opacity-40 pointer-events-none"></div>
            
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
                <div class="grid lg:grid-cols-12 gap-12 items-center">
                    
                    <div class="lg:col-span-5 text-center lg:text-left z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded bg-green-100 text-green-800 text-xs font-bold uppercase tracking-wide mb-6 border border-green-200">
                            <span class="w-2 h-2 rounded-full bg-green-600"></span>
                            Sistema 100% Online
                        </div>
                        
                        <h1 class="text-4xl md:text-5xl lg:text-[3.25rem] leading-tight font-extrabold text-slate-900 mb-6">
                            Sua empresa ainda perde tempo conferindo <span class="text-brand-700">reembolso</span>?
                        </h1>
                        
                        <p class="text-lg text-slate-600 mb-8 leading-relaxed font-medium">
                            Automatize a conferência de notas fiscais. A ValeIA lê o comprovante, valida o CNPJ e prepara o fechamento contábil. Sem planilha, sem dor de cabeça.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-3.5 text-base font-bold text-white bg-brand-700 rounded hover:bg-brand-800 transition-all shadow-lg shadow-brand-900/10">
                                Testar Agora
                            </a>
                            <a href="#funcionalidades" class="inline-flex items-center justify-center px-8 py-3.5 text-base font-bold text-slate-700 bg-white border border-slate-300 rounded hover:bg-slate-50 transition-all">
                                Ver como funciona
                            </a>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap justify-center lg:justify-start gap-6 text-sm font-semibold text-slate-500">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                Adeus Planilhas
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                Anti-fraude
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-7 relative">
                        <!-- Imagem com tratamento de sombra realista -->
                        <div class="bg-slate-900 rounded p-2 shadow-2xl ring-1 ring-slate-900/10">
                            <img src="{{ asset('imagens/dashboard.png') }}" 
                                 alt="Painel Administrativo" 
                                 class="block w-full h-auto rounded bg-slate-800 opacity-95 hover:opacity-100 transition-opacity duration-700"
                                 width="1200" 
                                 height="750" 
                                 loading="lazy">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Recursos (Grid Assimétrico - Mais humano) -->
        <section id="funcionalidades" class="py-20 bg-white">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-12 border-b border-slate-100 pb-6">
                    <h2 class="text-3xl font-bold text-slate-900">O que o sistema faz?</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <!-- Feature Principal (Destaque) -->
                    <div class="md:col-span-2 p-8 bg-brand-50 rounded border border-brand-100 relative overflow-hidden group">
                        <div class="relative z-10 flex flex-col md:flex-row gap-6 items-start">
                            <div class="p-3 bg-white text-brand-700 rounded shadow-sm shrink-0">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-brand-900 mb-2">OCR de Notas Fiscais</h3>
                                <p class="text-brand-800 leading-relaxed">
                                    Nossa IA lê a foto da nota (NF-e, NFC-e ou recibo manual). O sistema extrai data, CNPJ, itens e valores automaticamente. <br>
                                    <span class="text-sm font-semibold mt-2 block opacity-80">*Reduz em 90% o erro de digitação do financeiro.</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- WhatsApp -->
                    <div class="p-6 bg-white rounded border border-slate-200 hover:border-brand-300 transition-colors group">
                        <div class="w-10 h-10 bg-slate-50 text-slate-700 group-hover:bg-brand-50 group-hover:text-brand-700 rounded flex items-center justify-center mb-4 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Integração WhatsApp</h3>
                        <p class="text-slate-600 text-sm">Seu funcionário manda a foto da nota no Zap da empresa e o sistema processa na hora.</p>
                    </div>

                    <!-- Anti-fraude -->
                    <div class="p-6 bg-white rounded border border-slate-200 hover:border-brand-300 transition-colors group">
                        <div class="w-10 h-10 bg-slate-50 text-slate-700 group-hover:bg-brand-50 group-hover:text-brand-700 rounded flex items-center justify-center mb-4 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Anti-Fraude</h3>
                        <p class="text-slate-600 text-sm">Bloqueio automático de notas duplicadas ou com data muito antiga.</p>
                    </div>

                    <!-- Saldos -->
                    <div class="p-6 bg-white rounded border border-slate-200 hover:border-brand-300 transition-colors group">
                        <div class="w-10 h-10 bg-slate-50 text-slate-700 group-hover:bg-brand-50 group-hover:text-brand-700 rounded flex items-center justify-center mb-4 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Gestão de Saldos</h3>
                        <p class="text-slate-600 text-sm">Defina limites mensais (budget) por centro de custo ou colaborador.</p>
                    </div>

                    <!-- Relatórios -->
                    <div class="p-6 bg-slate-800 rounded text-white shadow-lg">
                        <div class="w-10 h-10 bg-slate-700 text-white rounded flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Relatórios PDF</h3>
                        <p class="text-slate-300 text-sm">Exportação pronta para a contabilidade. O sistema fecha o mês para você.</p>
                    </div>

                </div>
            </div>
        </section>

        <section id="planos" class="py-24 bg-slate-900">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-white">Quanto custa?</h2>
                    <p class="mt-4 text-slate-400">Sem taxa de implantação. Cancele quando quiser.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    @foreach($planos as $plano)
                        @php
                            $valor_final = $plano->valor;
                            if($plano->desconto > 0) {
                                $valor_final = $plano->valor - ($plano->valor * ($plano->desconto / 100));
                            }
                        @endphp

                        <!-- Lógica de destaque no Blade para evitar JS desnecessário -->
                        <div class="flex flex-col relative rounded p-8 {{ $loop->iteration == 2 ? 'bg-brand-900 ring-2 ring-brand-500 shadow-2xl scale-105 z-10' : 'bg-slate-800 border border-slate-700' }}">
                            
                            @if($loop->iteration == 2)
                                <div class="absolute -top-3 left-0 right-0 flex justify-center">
                                    <span class="bg-brand-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                        Mais Usado
                                    </span>
                                </div>
                            @endif

                            <h3 class="text-xl font-bold text-white">{{ $plano->nome }}</h3>
                            
                            <!-- Descrição curta -->
                            <p class="text-sm text-slate-400 mt-2 min-h-[40px] leading-snug">
                                {{ $plano->descricao[0] ?? '' }}
                            </p>

                            <div class="my-6 pb-6 border-b border-white/5">
                                <div class="flex items-baseline">
                                    <span class="text-3xl font-bold text-white">R$ {{ number_format($valor_final, 0, ',', '.') }}</span>
                                    <span class="ml-1 text-slate-500 text-xs">/mês</span>
                                </div>
                                @if($plano->desconto > 0)
                                    <p class="text-xs text-brand-400 font-medium mt-1">Economia de {{ $plano->desconto }}%</p>
                                @endif
                            </div>

                            <ul class="space-y-3 mb-8 flex-1">
                                @foreach(array_slice($plano->descricao, 1) as $item)
                                    <li class="flex items-start">
                                        <!-- Ícone SVG inline pra evitar request -->
                                        <svg class="w-4 h-4 text-brand-500 mr-3 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="text-sm text-slate-300">{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <a href="{{ route('login') }}" class="w-full inline-flex justify-center items-center py-3 px-4 rounded text-sm font-bold transition-colors {{ $loop->iteration == 2 ? 'bg-brand-600 hover:bg-brand-500 text-white' : 'bg-slate-700 hover:bg-slate-600 text-white' }}">
                                Selecionar
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <footer class="bg-white border-t border-slate-200 pt-16 pb-8">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                    <div class="col-span-1">
                        <img class="h-6 w-auto opacity-60 mb-6 grayscale" src="{{ asset('imagens/logo.png') }}" alt="ValeIA" width="100" height="24">
                        <p class="text-sm text-slate-500 leading-relaxed max-w-xs">
                            Tecnologia para facilitar a vida do departamento financeiro.
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider mb-4">Navegação</h4>
                        <ul class="space-y-3 text-sm text-slate-600">
                            <li><a href="#funcionalidades" class="hover:text-brand-700 hover:underline">Funcionalidades</a></li>
                            <li><a href="#planos" class="hover:text-brand-700 hover:underline">Planos</a></li>
                            <li><a href="/admin/api-tokens" class="hover:text-brand-700 hover:underline">Integrações</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider mb-4">Ajuda</h4>
                        <ul class="space-y-3 text-sm text-slate-600">
                            <li><a href="/ajuda" class="hover:text-brand-700 hover:underline">Central de Ajuda</a></li>
                            <li><a href="/docs" class="hover:text-brand-700 hover:underline">Documentação (API)</a></li>
                            <li><a href="/status" class="hover:text-brand-700 hover:underline">Status</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider mb-4">Legal</h4>
                        <ul class="space-y-3 text-sm text-slate-600">
                            <li><a href="/termos-de-uso" class="hover:text-brand-700 hover:underline">Termos de Uso</a></li>
                            <li><a href="/termos-de-privacidade" class="hover:text-brand-700 hover:underline">Privacidade</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-t border-slate-100 pt-8 flex justify-between items-center">
                    <p class="text-xs text-slate-400">&copy; {{ date('Y') }} ValeIA Tecnologia.</p>
                </div>
            </div>
        </footer>
    </main>
</body>
</html>