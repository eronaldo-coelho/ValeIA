<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Política de Privacidade | ValeIA</title>
    <meta name="description" content="Como tratamos seus dados, conformidade LGPD e segurança da informação na ValeIA.">
    
    <meta name="robots" content="noindex, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="theme-color" content="#ffffff">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-600 bg-slate-50 antialiased print:bg-white print:text-black">

    <nav class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200 print:hidden transition-all duration-300">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-[90px]">
                
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" title="Voltar para o início" class="opacity-100 hover:opacity-80 transition-opacity">
                        <img class="h-[64px] w-auto object-contain" 
                             src="{{ asset('imagens/logo.png') }}" 
                             alt="ValeIA" 
                             width="200" 
                             height="64">
                    </a>
                </div>

                <div class="flex items-center gap-4 md:gap-6">
                    <button onclick="window.print()" type="button" class="group flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-brand-700 transition-colors">
                        <svg class="w-5 h-5 group-hover:text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        <span class="hidden sm:inline">Imprimir</span>
                    </button>

                    <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white bg-brand-700 rounded-lg hover:bg-brand-800 shadow-sm transition-all">
                        Acessar Painel
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-12 md:py-16 px-4 sm:px-6 lg:px-8 print:p-0">
        <article class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-slate-200 p-8 md:p-12 print:shadow-none print:border-0 print:p-0">
            
            <header class="mb-10 pb-8 border-b border-slate-100 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">Política de Privacidade</h1>
                    <p class="mt-2 text-slate-500">Transparência sobre como tratamos seus dados.</p>
                </div>
                <div class="flex items-center gap-2 text-sm bg-slate-50 px-3 py-1.5 rounded-md border border-slate-100">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    <span class="text-slate-600 font-medium">Conforme LGPD (Lei 13.709/18)</span>
                </div>
            </header>

            <div class="space-y-10 text-base leading-relaxed text-slate-600 print:text-black print:text-sm">
                
                <section>
                    <p class="font-medium text-slate-800 mb-4">
                        A privacidade dos dados da sua empresa é a base do nosso negócio. Esta política descreve quais informações coletamos e como elas são utilizadas pela ValeIA Tecnologia.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span class="text-brand-600">1.</span> Dados Coletados
                    </h2>
                    <div class="space-y-4">
                        <p>Para operar a plataforma de auditoria, coletamos dois tipos de dados:</p>
                        
                        <div class="pl-4 border-l-4 border-brand-100 space-y-2">
                            <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">A. Dados Cadastrais</h3>
                            <p class="text-sm">Nome, CPF/CNPJ, E-mail e Telefone do administrador e dos colaboradores cadastrados no sistema.</p>
                        </div>

                        <div class="pl-4 border-l-4 border-brand-100 space-y-2">
                            <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">B. Dados de Auditoria (IA)</h3>
                            <p class="text-sm">Imagens de notas fiscais enviadas para reembolso. Nossa IA processa essas imagens para extrair: Data, CNPJ do Emissor, Itens Adquiridos e Valor Total.</p>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span class="text-brand-600">2.</span> Uso das Informações
                    </h2>
                    <ul class="list-disc pl-5 space-y-2 marker:text-brand-400">
                        <li><strong>Prestação do Serviço:</strong> Validar se a despesa está de acordo com as regras da sua empresa (horário, tipo de item, valor).</li>
                        <li><strong>Prevenção de Fraude:</strong> Detectar notas duplicadas que já foram reembolsadas anteriormente por outro funcionário.</li>
                        <li><strong>Comunicação:</strong> Envio de status de aprovação via WhatsApp (quando ativo).</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span class="text-brand-600">3.</span> Compartilhamento
                    </h2>
                    <p class="mb-3">
                        Não vendemos dados. O compartilhamento ocorre estritamente com provedores de infraestrutura necessários para o serviço funcionar:
                    </p>
                    <ul class="grid sm:grid-cols-2 gap-4 mt-4">
                        <li class="bg-slate-50 p-3 rounded border border-slate-100 text-sm">
                            <strong class="block text-slate-900 mb-1">Mercado Pago</strong>
                            Processamento de pagamentos (Boletos/Pix).
                        </li>
                        <li class="bg-slate-50 p-3 rounded border border-slate-100 text-sm">
                            <strong class="block text-slate-900 mb-1">Serviços de Cloud</strong>
                            Hospedagem segura e processamento de OCR.
                        </li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span class="text-brand-600">4.</span> Retenção e Exclusão
                    </h2>
                    <p class="mb-3">
                        4.1. Seus dados ficam ativos enquanto você for cliente.
                    </p>
                    <p>
                        4.2. Em caso de cancelamento, mantemos os dados em "quarentena" por 60 dias para permitir reativação. Após este prazo, imagens e dados sensíveis podem ser excluídos permanentemente para segurança. Dados fiscais de pagamento da assinatura são mantidos pelo prazo legal (5 anos).
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span class="text-brand-600">5.</span> Seus Direitos
                    </h2>
                    <p class="mb-3">
                        Você pode solicitar a exportação ou exclusão completa dos dados da sua empresa a qualquer momento através do painel de controle ou via suporte, ressalvadas as obrigações legais de guarda.
                    </p>
                </section>

            </div>

            <footer class="mt-16 pt-8 border-t border-slate-100 print:hidden">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm">
                    <p class="text-slate-500">
                        Encarregado de Dados (DPO): <a href="mailto:privacidade@valeia.com.br" class="text-brand-600 font-semibold hover:underline">privacidade@valeia.com.br</a>
                    </p>
                    <a href="/" class="text-slate-400 hover:text-slate-600 transition-colors">Voltar para a Home</a>
                </div>
            </footer>

        </article>
        
        <div class="mt-8 text-center print:hidden">
            <p class="text-xs text-slate-400">&copy; {{ date('Y') }} ValeIA Tecnologia.</p>
        </div>
    </main>
</body>
</html>