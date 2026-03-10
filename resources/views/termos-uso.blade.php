<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Termos de Uso | ValeIA</title>
    <meta name="description" content="Regras de utilização, política de cancelamento e SLA da plataforma ValeIA.">
    
    <!-- Legal docs não precisam competir por SEO, mas devem ser indexáveis -->
    <meta name="robots" content="noindex, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="theme-color" content="#ffffff">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-600 bg-slate-50 antialiased print:bg-white print:text-black">

    <!-- Header Robusto (Mesma altura da Landing Page) -->
    <nav class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200 print:hidden transition-all duration-300">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-[90px]">
                
                <!-- Logo Grande -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" title="Voltar para o início" class="opacity-100 hover:opacity-80 transition-opacity">
                        <!-- Sem invert aqui pois o fundo é branco, mantendo tamanho grande -->
                        <img class="h-[64px] w-auto object-contain" 
                             src="{{ asset('imagens/logo.png') }}" 
                             alt="ValeIA" 
                             width="200" 
                             height="64">
                    </a>
                </div>

                <!-- Ações do Header -->
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
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">Termos de Uso</h1>
                    <p class="mt-2 text-slate-500">Regras de utilização e condições de serviço.</p>
                </div>
                <div class="flex items-center gap-2 text-sm bg-slate-50 px-3 py-1.5 rounded-md border border-slate-100">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    <span class="text-slate-600 font-medium">Versão Atualizada ({{ date('Y') }})</span>
                </div>
            </header>

            <div class="space-y-10 text-base leading-relaxed text-slate-600 print:text-black print:text-sm">
                
                <section>
                    <p class="font-medium text-slate-800 mb-4">
                        Bem-vindo à ValeIA. Ao utilizar nosso software, você concorda com as condições abaixo descritas. Estes termos regem a relação entre a contratante ("Cliente") e a ValeIA Tecnologia ("Contratada").
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span class="text-brand-600">1.</span> O Serviço
                    </h2>
                    <p class="mb-3">
                        1.1. A ValeIA é uma ferramenta SaaS (Software as a Service) focada em auditoria de despesas corporativas. Utilizamos tecnologia OCR proprietária para extração de dados fiscais.
                    </p>
                    <p>
                        1.2. O sistema é entregue "como está" (as-is), sujeito a evoluções contínuas sem aviso prévio. Garantimos a manutenção das funcionalidades essenciais contratadas.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span class="text-brand-600">2.</span> Período de Teste (Trial)
                    </h2>
                    <p class="mb-3">
                        2.1. Oferecemos <strong>15 dias de acesso gratuito</strong>. Não há cobrança automática ao final deste período.
                    </p>
                    <p>
                        2.2. Caso não haja contratação após o teste, o acesso administrativo é suspenso. Seus dados históricos permanecem seguros por 60 dias antes da exclusão definitiva.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span class="text-brand-600">3.</span> Pagamentos e Licença
                    </h2>
                    <p class="mb-3">
                        3.1. O modelo é pré-pago. A licença é renovada a cada 30 dias mediante pagamento (Pix ou Boleto).
                    </p>
                    
                    <div class="bg-brand-50 p-6 rounded-lg border border-brand-100 my-5 print:border-gray-300">
                        <h3 class="text-brand-900 font-bold text-sm mb-2 uppercase tracking-wide flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Política de Crédito Cumulativo
                        </h3>
                        <p class="text-brand-800 text-sm">
                            Se você renovar o plano antes do vencimento, os dias restantes são somados ao novo período. Exemplo: Pagou faltando 5 dias? Seu próximo vencimento será em 35 dias. Você nunca perde dias pagos.
                        </p>
                    </div>

                    <p>
                        3.2. Notas Fiscais Eletrônicas (NF-e) de serviço referente à assinatura são emitidas automaticamente para o CNPJ cadastrado após a compensação bancária.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span class="text-brand-600">4.</span> Responsabilidade sobre a Auditoria
                    </h2>
                    <p class="mb-3">
                        4.1. Nossa IA aponta inconsistências e fraudes potenciais com alta precisão, mas a decisão final de aprovação ou reprovação do reembolso cabe exclusivamente ao gestor da conta.
                    </p>
                    <p>
                        4.2. A ValeIA não movimenta valores financeiros entre empresa e funcionário. Somos uma plataforma de gestão e controle, não um banco.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span class="text-brand-600">5.</span> Proteção de Dados
                    </h2>
                    <p>
                        5.1. Seus dados são seus. Não vendemos informações para terceiros.
                    </p>
                    <p class="mt-2">
                        5.2. Utilizamos criptografia em repouso e em trânsito. O acesso ao suporte é feito apenas mediante autorização expressa do administrador da conta para fins de debug.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span class="text-brand-600">6.</span> SLA e Suporte
                    </h2>
                    <ul class="list-disc pl-5 space-y-2 marker:text-slate-300">
                        <li><strong>Disponibilidade:</strong> Garantia de 99% de uptime mensal.</li>
                        <li><strong>Atendimento:</strong> Dias úteis, das 09h às 18h.</li>
                        <li><strong>Canais:</strong> E-mail (ticket) e Chat na plataforma.</li>
                    </ul>
                </section>

            </div>

            <footer class="mt-16 pt-8 border-t border-slate-100 print:hidden">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm">
                    <p class="text-slate-500">
                        Dúvidas legais? <a href="mailto:juridico@valeia.com.br" class="text-brand-600 font-semibold hover:underline">juridico@valeia.com.br</a>
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