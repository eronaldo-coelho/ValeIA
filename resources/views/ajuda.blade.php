<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Central de Ajuda | ValeIA</title>
    <meta name="description" content="Suporte técnico, dúvidas financeiras e documentação da plataforma ValeIA.">
    
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="theme-color" content="#064e3b">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Alpine.js para interatividade leve -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Schema Markup para FAQ (SEO Sênior) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [{
        "@type": "Question",
        "name": "Como funciona a leitura de notas fiscais?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Utilizamos OCR proprietário para extrair CNPJ, data e valores. A validação é feita em tempo real."
        }
      }, {
        "@type": "Question",
        "name": "O sistema detecta bebidas alcoólicas?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Sim, nossa IA analisa item a item e bloqueia produtos proibidos pela política da empresa."
        }
      }]
    }
    </script>
</head>
<body class="font-sans text-slate-900 bg-slate-50 antialiased selection:bg-brand-900 selection:text-white">

    <!-- Header Padrão (Consistente com a Landing Page) -->
    <header 
        x-data="{ mobileMenuOpen: false, scrolled: false }" 
        @scroll.window="scrolled = (window.pageYOffset > 20)"
        :class="{ 'bg-brand-900/95 backdrop-blur-md shadow-lg': scrolled, 'bg-brand-900': !scrolled }"
        class="fixed w-full top-0 z-50 border-b border-brand-800 transition-all duration-300"
    >
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-[88px]">
                
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="outline-none focus:ring-2 focus:ring-brand-500 rounded-sm">
                        <!-- Logo Grande (68px) conforme solicitado -->
                        <img class="h-[68px] w-auto object-contain brightness-0 invert" 
                             src="{{ asset('imagens/logo.png') }}" 
                             alt="Logo ValeIA" 
                             width="220" 
                             height="68">
                    </a>
                </div>

                <nav class="hidden md:flex space-x-10 items-center">
                    <a href="/#funcionalidades" class="text-[15px] font-medium text-brand-50 hover:text-white transition-colors">Funcionalidades</a>
                    <a href="/#planos" class="text-[15px] font-medium text-brand-50 hover:text-white transition-colors">Planos</a>
                    <a href="/ajuda" class="text-[15px] font-bold text-white border-b-2 border-brand-400 pb-1">Suporte</a>
                </nav>

                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-white hover:text-brand-200 transition-colors">Entrar</a>
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-brand-950 transition-all bg-white rounded-md hover:bg-brand-50 shadow-sm hover:shadow-md">
                        Acessar Painel
                    </a>
                </div>

                <!-- Mobile Trigger -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="text-brand-100 hover:text-white p-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Menu Mobile -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden bg-brand-900 border-t border-brand-800 absolute w-full left-0 shadow-xl">
            <div class="px-5 pt-6 pb-8 space-y-4">
                <a href="/" class="block text-base font-medium text-brand-50 hover:text-white">Home</a>
                <a href="/docs" class="block text-base font-medium text-brand-50 hover:text-white">Documentação API</a>
                <hr class="border-brand-800">
                <a href="{{ route('login') }}" class="block w-full py-3 text-center text-base font-bold text-brand-900 bg-white rounded-md">Login</a>
            </div>
        </div>
    </header>

    <main class="pt-[88px]">
        
        <!-- Hero de Ajuda -->
        <section class="bg-brand-900 relative overflow-hidden pb-32 pt-16">
            <!-- Padrão sutil de fundo (CSS puro, sem imagem externa para performance) -->
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 30px 30px;"></div>
            
            <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
                <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-6 tracking-tight">Central de Suporte</h1>
                <p class="text-lg text-brand-100 max-w-2xl mx-auto font-medium">
                    Está com dificuldades no fechamento ou na integração? Escolha um canal abaixo para atendimento prioritário.
                </p>
            </div>
        </section>

        <!-- Cards de Contato (Elevados sobre o Hero) -->
        <div class="max-w-5xl mx-auto px-4 -mt-20 relative z-20">
            <div class="grid md:grid-cols-2 gap-6">
                
                <!-- WhatsApp (Principal Canal) -->
                <a href="https://wa.me/5513982038196" target="_blank" class="group bg-white p-8 rounded-xl shadow-xl border border-slate-100 hover:border-green-200 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-start gap-6">
                        <div class="w-14 h-14 bg-green-100 text-green-600 rounded-lg flex items-center justify-center shrink-0 group-hover:bg-green-600 group-hover:text-white transition-colors">
                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 mb-1">WhatsApp</h3>
                            <p class="text-slate-500 text-sm mb-3">Suporte em tempo real para gestores.</p>
                            <div class="text-green-600 font-bold text-sm uppercase tracking-wide flex items-center gap-1 group-hover:gap-2 transition-all">
                                Iniciar Conversa <span>&rarr;</span>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- E-mail (Canal Formal) -->
                <a href="mailto:suporte@valeia.com.br" class="group bg-white p-8 rounded-xl shadow-xl border border-slate-100 hover:border-blue-200 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-start gap-6">
                        <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 mb-1">E-mail / Ticket</h3>
                            <p class="text-slate-500 text-sm mb-3">Para envio de arquivos em lote e financeiro.</p>
                            <div class="text-blue-600 font-bold text-sm uppercase tracking-wide flex items-center gap-1 group-hover:gap-2 transition-all">
                                Abrir Chamado <span>&rarr;</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- FAQ: Implementação com Alpine.js (Padrão Laravel Moderno) -->
        <section class="max-w-3xl mx-auto px-4 py-24">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900">Perguntas Frequentes</h2>
                <p class="mt-3 text-slate-500">Tópicos mais acessados na nossa base de conhecimento.</p>
            </div>

            <!-- Accordion Wrapper -->
            <div x-data="{ active: null }" class="space-y-4">
                
                <!-- Item 1 -->
                <div class="bg-white border border-slate-200 rounded-lg overflow-hidden transition-all duration-200"
                     :class="active === 1 ? 'border-brand-300 shadow-md' : 'hover:border-slate-300'">
                    <button @click="active = (active === 1 ? null : 1)" class="w-full p-5 flex justify-between items-center text-left focus:outline-none">
                        <span class="text-lg font-bold text-slate-800">Como funciona a leitura automática (OCR)?</span>
                        <svg class="w-5 h-5 text-slate-400 transform transition-transform duration-200" 
                             :class="active === 1 ? 'rotate-180 text-brand-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="active === 1" x-collapse x-cloak>
                        <div class="px-5 pb-6 pt-0 text-slate-600 leading-relaxed border-t border-slate-100 mt-2">
                            A tecnologia da ValeIA identifica o CNPJ do emissor, a data, o valor total e descritivo dos itens. O sistema cruza esses dados com as regras que você configurou (ex: horário permitido, tipo de estabelecimento) e já sugere a aprovação ou reprovação para o gestor.
                        </div>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="bg-white border border-slate-200 rounded-lg overflow-hidden transition-all duration-200"
                     :class="active === 2 ? 'border-brand-300 shadow-md' : 'hover:border-slate-300'">
                    <button @click="active = (active === 2 ? null : 2)" class="w-full p-5 flex justify-between items-center text-left focus:outline-none">
                        <span class="text-lg font-bold text-slate-800">O sistema bloqueia bebidas alcoólicas?</span>
                        <svg class="w-5 h-5 text-slate-400 transform transition-transform duration-200" 
                             :class="active === 2 ? 'rotate-180 text-brand-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="active === 2" x-collapse x-cloak>
                        <div class="px-5 pb-6 pt-0 text-slate-600 leading-relaxed border-t border-slate-100 mt-2">
                            Sim. Nossa IA lê linha por linha da nota fiscal. Se encontrar termos como "cerveja", "vinho" ou "caipirinha" (e variantes), a nota é sinalizada com um alerta de <strong>Compliance</strong>. Você pode configurar para reprovar automaticamente ou apenas avisar.
                        </div>
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="bg-white border border-slate-200 rounded-lg overflow-hidden transition-all duration-200"
                     :class="active === 3 ? 'border-brand-300 shadow-md' : 'hover:border-slate-300'">
                    <button @click="active = (active === 3 ? null : 3)" class="w-full p-5 flex justify-between items-center text-left focus:outline-none">
                        <span class="text-lg font-bold text-slate-800">É possível integrar com o sistema contábil (ERP)?</span>
                        <svg class="w-5 h-5 text-slate-400 transform transition-transform duration-200" 
                             :class="active === 3 ? 'rotate-180 text-brand-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="active === 3" x-collapse x-cloak>
                        <div class="px-5 pb-6 pt-0 text-slate-600 leading-relaxed border-t border-slate-100 mt-2">
                            Sim. Disponibilizamos uma API RESTful completa para desenvolvedores. Além disso, para times financeiros, o sistema exporta relatórios em Excel e PDF já formatados para facilitar a conciliação no seu ERP atual.
                        </div>
                    </div>
                </div>

                <!-- Item 4 -->
                <div class="bg-white border border-slate-200 rounded-lg overflow-hidden transition-all duration-200"
                     :class="active === 4 ? 'border-brand-300 shadow-md' : 'hover:border-slate-300'">
                    <button @click="active = (active === 4 ? null : 4)" class="w-full p-5 flex justify-between items-center text-left focus:outline-none">
                        <span class="text-lg font-bold text-slate-800">Como funciona o Trial (Teste Grátis)?</span>
                        <svg class="w-5 h-5 text-slate-400 transform transition-transform duration-200" 
                             :class="active === 4 ? 'rotate-180 text-brand-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="active === 4" x-collapse x-cloak>
                        <div class="px-5 pb-6 pt-0 text-slate-600 leading-relaxed border-t border-slate-100 mt-2">
                            Você tem 15 dias de acesso irrestrito a todas as funcionalidades do plano Enterprise. Não pedimos cartão de crédito no cadastro. Se gostar, você gera o boleto/pix dentro do painel para continuar usando.
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200 pt-16 pb-8">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                    <div class="col-span-1">
                        <img class="h-6 w-auto opacity-60 mb-6 grayscale" src="{{ asset('imagens/logo.png') }}" alt="ValeIA" width="100" height="24">
                        <p class="text-sm text-slate-500 leading-relaxed max-w-xs">
                            Gestão inteligente de reembolsos corporativos.
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider mb-4">Produto</h4>
                        <ul class="space-y-3 text-sm text-slate-600">
                            <li><a href="/#funcionalidades" class="hover:text-brand-700 transition-colors">Funcionalidades</a></li>
                            <li><a href="/#planos" class="hover:text-brand-700 transition-colors">Planos</a></li>
                            <li><a href="/admin/api-tokens" class="hover:text-brand-700 transition-colors">Integrações</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider mb-4">Suporte</h4>
                        <ul class="space-y-3 text-sm text-slate-600">
                            <li><a href="/ajuda" class="hover:text-brand-700 transition-colors">Central de Ajuda</a></li>
                            <li><a href="/docs" class="hover:text-brand-700 transition-colors">Documentação</a></li>
                            <li><a href="/status" class="hover:text-brand-700 transition-colors">Status do Sistema</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider mb-4">Legal</h4>
                        <ul class="space-y-3 text-sm text-slate-600">
                            <li><a href="/termos-de-uso" class="hover:text-brand-700 transition-colors">Termos de Uso</a></li>
                            <li><a href="/termos-de-privacidade" class="hover:text-brand-700 transition-colors">Privacidade</a></li>
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