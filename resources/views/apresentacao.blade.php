<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>ValeIA Corporate | Apresentação Executiva</title>
    
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#064e3b">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .hero-pattern {
            background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="font-sans text-slate-900 bg-slate-50 antialiased">

    <!-- Header com Logo Ampliada -->
    <header class="bg-brand-900 py-8 border-b border-brand-800 shadow-sm">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 flex justify-center">
            <div class="flex-shrink-0">
                <!-- Tamanho aumentado significativamente conforme solicitado (h-24 mobile, h-32 desktop) -->
                <img class="h-24 md:h-32 w-auto object-contain brightness-0 invert" 
                     src="{{ asset('imagens/logo.png') }}" 
                     alt="ValeIA Logo" 
                     fetchpriority="high">
            </div>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="relative bg-white border-b border-slate-200 overflow-hidden">
            <div class="absolute inset-0 hero-pattern opacity-40 pointer-events-none"></div>
            
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
                <div class="grid lg:grid-cols-12 gap-12 items-center">
                    
                    <div class="lg:col-span-5 text-center lg:text-left z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded bg-brand-50 text-brand-800 text-xs font-bold uppercase tracking-wide mb-6 border border-brand-200">
                            <span class="w-2 h-2 rounded-full bg-brand-600"></span>
                            Solução Corporativa
                        </div>
                        
                        <h1 class="text-4xl md:text-5xl lg:text-[3.25rem] leading-tight font-extrabold text-slate-900 mb-6">
                            Gestão de despesas e <span class="text-brand-700">auditoria fiscal</span> automatizada.
                        </h1>
                        
                        <p class="text-lg text-slate-600 mb-8 leading-relaxed font-medium">
                            Centralize o processo de reembolso, elimine planilhas manuais e garanta compliance fiscal com tecnologia de leitura automática (OCR) e validação de CNPJ.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                            <a href="https://wa.me/5587981656116?text=Ol%C3%A1%2C%20gostaria%20de%20agendar%20uma%20apresenta%C3%A7%C3%A3o%20da%20ValeIA." target="_blank" class="inline-flex items-center justify-center px-8 py-3.5 text-base font-bold text-white bg-green-600 hover:bg-green-700 rounded shadow-lg transition-all gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                Falar no WhatsApp
                            </a>
                        </div>
                    </div>

                    <div class="lg:col-span-7 relative">
                        <div class="bg-slate-900 rounded p-2 shadow-2xl ring-1 ring-slate-900/10">
                            <img src="{{ asset('imagens/dashboard.png') }}" 
                                 alt="Painel de Controle Financeiro" 
                                 class="block w-full h-auto rounded bg-slate-800 opacity-95 hover:opacity-100 transition-opacity duration-700"
                                 width="1200" 
                                 height="750" 
                                 loading="lazy">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Funcionalidades -->
        <section class="py-20 bg-white">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-12 border-b border-slate-100 pb-6">
                    <h2 class="text-3xl font-bold text-slate-900">Capacidades da Plataforma</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <!-- Destaque OCR -->
                    <div class="md:col-span-2 p-8 bg-brand-50 rounded border border-brand-100 relative overflow-hidden group">
                        <div class="relative z-10 flex flex-col md:flex-row gap-6 items-start">
                            <div class="p-3 bg-white text-brand-700 rounded shadow-sm shrink-0">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-brand-900 mb-2">Processamento Inteligente (OCR)</h3>
                                <p class="text-brand-800 leading-relaxed">
                                    Nossa IA processa NF-e, NFC-e e recibos. Extraímos CNPJ, Data, Valor e Itens automaticamente, cruzando dados com a Receita Federal para garantir a validade do documento.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-white rounded border border-slate-200">
                        <div class="w-10 h-10 bg-slate-50 text-slate-700 rounded flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Envio via WhatsApp</h3>
                        <p class="text-slate-600 text-sm">Facilidade para a equipe de campo: basta enviar a foto da nota para o número do WhatsApp.</p>
                    </div>

                    <div class="p-6 bg-white rounded border border-slate-200">
                        <div class="w-10 h-10 bg-slate-50 text-slate-700 rounded flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Anti-Fraude</h3>
                        <p class="text-slate-600 text-sm">Bloqueio imediato de notas duplicadas ou fora da política de datas da empresa.</p>
                    </div>

                    <div class="p-6 bg-slate-800 rounded text-white shadow-lg">
                        <div class="w-10 h-10 bg-slate-700 text-white rounded flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Exportação Contábil</h3>
                        <p class="text-slate-300 text-sm">Relatórios estruturados prontos para importação no sistema contábil (ERP).</p>
                    </div>

                </div>
            </div>
        </section>

        <!-- Seção Auditoria -->
        <section class="py-24 bg-slate-50 border-y border-slate-200">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div class="order-2 lg:order-1 relative">
                         <div class="bg-white rounded-lg p-2 shadow-xl ring-1 ring-slate-200">
                            <img src="{{ asset('imagens/nota-fiscal.png') }}" 
                                 alt="Interface de Conferência de Nota Fiscal" 
                                 class="block w-full h-auto rounded bg-slate-50"
                                 loading="lazy">
                        </div>
                    </div>
                    
                    <div class="order-1 lg:order-2">
                        <h2 class="text-3xl font-bold text-slate-900 mb-6">Conferência e Auditoria Visual</h2>
                        <p class="text-lg text-slate-600 mb-6 leading-relaxed">
                            Otimize o tempo do time financeiro. O sistema apresenta o comprovante digitalizado ao lado dos dados extraídos para uma aprovação rápida e segura.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-brand-600 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-slate-700">Visualização lado-a-lado do comprovante.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-brand-600 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-slate-700">Detecção automática de itens não reembolsáveis.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-brand-600 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-slate-700">Fluxo de aprovação multinível personalizável.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Seção Integração -->
        <section class="py-24 bg-white">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-6">Integração ERP e Segurança de Dados</h2>
                        <p class="text-lg text-slate-600 mb-6 leading-relaxed">
                            A ValeIA foi construída com foco em interoperabilidade. Utilize nossa API RESTful para conectar o fluxo de reembolso diretamente ao seu sistema de gestão (SAP, Totvs, Oracle).
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-brand-600 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <span class="text-slate-700">Gestão granular de tokens de acesso.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-brand-600 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                <span class="text-slate-700">Permissões de leitura e escrita segregadas.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-brand-600 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                <span class="text-slate-700">Documentação técnica completa (Swagger).</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="relative">
                         <div class="bg-white rounded-lg p-2 shadow-xl ring-1 ring-slate-200">
                            <img src="{{ asset('imagens/api-integracao.png') }}" 
                                 alt="Painel de Integração API" 
                                 class="block w-full h-auto rounded bg-slate-50"
                                 loading="lazy">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Diferenciais -->
        <section class="py-16 bg-brand-900 border-y border-brand-800">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-4 gap-8 text-center md:text-left">
                    <div class="p-4">
                        <h4 class="text-brand-300 font-bold text-lg mb-2">Onboarding Rápido</h4>
                        <p class="text-slate-300 text-sm">Implantação imediata sem necessidade de instalação local.</p>
                    </div>
                    <div class="p-4">
                        <h4 class="text-brand-300 font-bold text-lg mb-2">SLA Garantido</h4>
                        <p class="text-slate-300 text-sm">Infraestrutura em nuvem escalável e redundante.</p>
                    </div>
                    <div class="p-4">
                        <h4 class="text-brand-300 font-bold text-lg mb-2">Suporte Expert</h4>
                        <p class="text-slate-300 text-sm">Atendimento direto com especialistas no produto.</p>
                    </div>
                    <div class="p-4">
                        <h4 class="text-brand-300 font-bold text-lg mb-2">Segurança</h4>
                        <p class="text-slate-300 text-sm">Dados criptografados e conformidade com a LGPD.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final Encerramento -->
        <section class="py-24 bg-white">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold text-slate-900 mb-6">Pronto para transformar sua gestão de despesas?</h2>
                <p class="text-lg text-slate-600 mb-8">
                    Entre em contato com nossa equipe comercial para uma demonstração personalizada.
                </p>
                <div class="inline-block bg-green-50 px-8 py-6 rounded-xl border border-green-100 shadow-sm">
                    <p class="text-sm text-green-800 uppercase tracking-widest font-bold mb-3">WhatsApp Comercial</p>
                    <a href="https://wa.me/5587981656116?text=Ol%C3%A1%2C%20gostaria%20de%20agendar%20uma%20apresenta%C3%A7%C3%A3o%20da%20ValeIA." target="_blank" class="flex items-center justify-center gap-2 text-2xl md:text-3xl font-bold text-green-600 hover:text-green-700 transition-colors">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        +55 (87) 98165-6116
                    </a>
                </div>
            </div>
        </section>

    </main>
</body>
</html>