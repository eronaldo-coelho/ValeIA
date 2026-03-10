<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>API Docs | ValeIA Developers</title>
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="theme-color" content="#ffffff">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Fira Code para os blocos de código + Plus Jakarta para texto -->
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Scrollbar personalizada para a área de navegação lateral */
        .sidebar-scroll::-webkit-scrollbar { width: 5px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Ajuste fino para blocos de código */
        pre { font-variant-ligatures: normal; }
        code { font-family: 'Fira Code', monospace; }
    </style>
</head>
<body class="font-sans text-slate-600 bg-white antialiased h-screen flex flex-col overflow-hidden">

    <!-- Header Unificado (Mesmo estilo das outras páginas) -->
    <nav class="flex-none bg-white border-b border-slate-200 z-50">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-[90px]">
                
                <!-- Logo Grande -->
                <div class="flex-shrink-0 flex items-center gap-4">
                    <a href="/" title="Voltar para Home">
                        <img class="h-[64px] w-auto object-contain" 
                             src="{{ asset('imagens/logo.png') }}" 
                             alt="ValeIA" 
                             width="200" 
                             height="64">
                    </a>
                    <!-- Badge de Área do Desenvolvedor -->
                    <div class="hidden md:flex items-center gap-2 border-l border-slate-200 pl-4 h-10">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Developers</span>
                        <span class="px-2 py-0.5 rounded bg-brand-50 text-brand-700 text-[10px] font-mono border border-brand-100">v1.0</span>
                    </div>
                </div>

                <!-- Ações -->
                <div class="flex items-center gap-6">
                    <a href="/" class="text-sm font-medium text-slate-500 hover:text-brand-700 hidden sm:block transition-colors">
                        Voltar ao Site
                    </a>
                    <a href="/admin/api-tokens" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white bg-brand-900 rounded-lg hover:bg-brand-800 transition-all shadow-sm">
                        Gerar Token
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Layout de Documentação (Sidebar Fixa + Conteúdo Scroll) -->
    <div class="flex flex-1 overflow-hidden">
        
        <!-- Sidebar Navigation -->
        <aside class="w-72 flex-none border-r border-slate-200 bg-slate-50 overflow-y-auto sidebar-scroll hidden lg:block">
            <nav class="p-8 space-y-8">
                
                <div>
                    <h5 class="mb-3 font-bold text-slate-900 text-xs uppercase tracking-wider">Começando</h5>
                    <ul class="space-y-2.5">
                        <li><a href="#intro" class="block text-sm text-slate-600 hover:text-brand-700 hover:translate-x-1 transition-transform">Visão Geral</a></li>
                        <li><a href="#auth" class="block text-sm text-slate-600 hover:text-brand-700 hover:translate-x-1 transition-transform">Autenticação</a></li>
                        <li><a href="#status" class="block text-sm text-slate-600 hover:text-brand-700 hover:translate-x-1 transition-transform">Health Check</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="mb-3 font-bold text-slate-900 text-xs uppercase tracking-wider">Funcionários</h5>
                    <ul class="space-y-2.5">
                        <li><a href="#func-listar" class="block text-sm text-slate-600 hover:text-brand-700 hover:translate-x-1 transition-transform">Listar Todos</a></li>
                        <li><a href="#func-criar" class="block text-sm text-slate-600 hover:text-brand-700 hover:translate-x-1 transition-transform">Criar Novo</a></li>
                        <li><a href="#func-detalhe" class="block text-sm text-slate-600 hover:text-brand-700 hover:translate-x-1 transition-transform">Detalhes & Update</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="mb-3 font-bold text-slate-900 text-xs uppercase tracking-wider">Notas Fiscais</h5>
                    <ul class="space-y-2.5">
                        <li><a href="#nota-ia" class="block text-sm text-slate-600 hover:text-brand-700 hover:translate-x-1 transition-transform">Analisar com IA</a></li>
                        <li><a href="#nota-manual" class="block text-sm text-slate-600 hover:text-brand-700 hover:translate-x-1 transition-transform">Envio Manual</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="mb-3 font-bold text-slate-900 text-xs uppercase tracking-wider">Financeiro</h5>
                    <ul class="space-y-2.5">
                        <li><a href="#fin-vales" class="block text-sm text-slate-600 hover:text-brand-700 hover:translate-x-1 transition-transform">Listar Vales</a></li>
                        <li><a href="#fin-saldo" class="block text-sm text-slate-600 hover:text-brand-700 hover:translate-x-1 transition-transform">Saldo & Extrato</a></li>
                        <li><a href="#fin-relatorio" class="block text-sm text-slate-600 hover:text-brand-700 hover:translate-x-1 transition-transform">Relatórios</a></li>
                    </ul>
                </div>

            </nav>
        </aside>

        <!-- Conteúdo Principal -->
        <main class="flex-1 overflow-y-auto scroll-smooth bg-white">
            <div class="max-w-5xl mx-auto px-6 py-12 lg:px-12">
                
                <!-- Intro Section -->
                <section id="intro" class="mb-16">
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6 tracking-tight">Referência da API</h1>
                    <p class="text-lg text-slate-600 mb-8 leading-relaxed max-w-3xl">
                        Integre a inteligência do <strong>ValeIA</strong> no seu sistema. Use esta documentação para automatizar o envio de notas, gerenciar funcionários e consultar relatórios financeiros.
                    </p>
                    
                    <div class="bg-slate-900 rounded-lg p-4 font-mono text-sm text-slate-300 flex items-center gap-4 shadow-lg">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-green-500"></span>
                            <span class="font-bold text-white">Base URL</span>
                        </div>
                        <span class="select-all">https://api.valeia.com.br/api</span>
                    </div>
                </section>

                <!-- Auth Section -->
                <section id="auth" class="mb-20 pt-10 border-t border-slate-100">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Autenticação</h2>
                    <p class="text-slate-600 mb-6">
                        Utilizamos o padrão <strong>Bearer Token</strong>. Você deve enviar o token no cabeçalho de todas as requisições.
                    </p>
                    
                    <div class="rounded-lg overflow-hidden border border-slate-200 bg-slate-50">
                        <div class="bg-slate-100 px-4 py-2 border-b border-slate-200 text-xs font-bold text-slate-500 font-mono">HEADER</div>
                        <div class="p-4 bg-slate-800 overflow-x-auto">
                            <pre class="font-mono text-sm text-slate-300">Authorization: Bearer <span class="text-brand-400">SEU_TOKEN_AQUI</span></pre>
                        </div>
                    </div>
                </section>

                <!-- Status Section -->
                <section id="status" class="mb-20 pt-10 border-t border-slate-100">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Health Check</h2>
                    
                    <div class="mb-6">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-2.5 py-1 rounded bg-blue-100 text-blue-700 text-xs font-bold font-mono">GET</span>
                            <code class="text-sm font-mono text-slate-700">/status</code>
                        </div>
                        <p class="text-sm text-slate-600">Verifica se a API está online.</p>
                    </div>

                    <div class="rounded-lg overflow-hidden border border-slate-200">
                        <div class="bg-slate-50 px-4 py-2 border-b border-slate-200 text-xs font-bold text-slate-500">Response 200 OK</div>
                        <div class="p-4 bg-slate-900 overflow-x-auto">
                            <pre class="text-xs text-slate-300 font-mono">{ "status": "ok", "mensagem": "API Online" }</pre>
                        </div>
                    </div>
                </section>

                <!-- Funcionarios -->
                <section id="func-listar" class="mb-20 pt-10 border-t border-slate-100">
                    <h2 class="text-2xl font-bold text-slate-900 mb-8">Funcionários</h2>
                    
                    <!-- 1. Listar -->
                    <div class="mb-12">
                        <h3 class="text-lg font-bold text-slate-800 mb-3">1. Listar Funcionários</h3>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-2.5 py-1 rounded bg-blue-100 text-blue-700 text-xs font-bold font-mono">GET</span>
                            <code class="text-sm font-mono text-slate-700">/funcionarios</code>
                        </div>
                        <p class="text-sm text-slate-600 mb-4">Retorna a lista de funcionários ativos.</p>
                    </div>

                    <!-- 2. Criar -->
                    <div id="func-criar" class="mb-12 scroll-mt-24">
                        <h3 class="text-lg font-bold text-slate-800 mb-3">2. Criar Funcionário</h3>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-2.5 py-1 rounded bg-green-100 text-green-700 text-xs font-bold font-mono">POST</span>
                            <code class="text-sm font-mono text-slate-700">/funcionarios</code>
                        </div>
                        
                        <div class="rounded-lg border border-slate-200 overflow-hidden shadow-sm">
                            <div class="bg-slate-50 px-4 py-2 border-b border-slate-200 text-xs font-bold text-slate-500">JSON Body</div>
                            <div class="p-4 bg-slate-900 overflow-x-auto">
<pre class="text-xs text-slate-300 font-mono">
{
  "nome": "Maria Souza",
  "cpf": "11122233344",
  "email": "maria@empresa.com",
  "cargo": "Vendedora",
  "vales": [
    { "vale_id": 1, "valor": 500.00 }
  ]
}
</pre>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Detalhes -->
                    <div id="func-detalhe" class="scroll-mt-24">
                        <h3 class="text-lg font-bold text-slate-800 mb-3">3. Detalhes & Update</h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded bg-blue-100 text-blue-700 text-xs font-bold font-mono w-14 text-center">GET</span>
                                <code class="text-sm font-mono text-slate-700">/funcionarios/{id}</code>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded bg-orange-100 text-orange-700 text-xs font-bold font-mono w-14 text-center">PUT</span>
                                <code class="text-sm font-mono text-slate-700">/funcionarios/{id}</code>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Notas -->
                <section id="nota-ia" class="mb-20 pt-10 border-t border-slate-100">
                    <h2 class="text-2xl font-bold text-slate-900 mb-8">Notas Fiscais</h2>
                    
                    <!-- IA -->
                    <div class="mb-12">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-bold text-slate-800">1. Analisar com IA</h3>
                            <span class="bg-indigo-50 text-indigo-700 text-xs px-2 py-1 rounded border border-indigo-100 font-bold">Automático</span>
                        </div>
                        <div class="flex items-center gap-3 mb-6">
                            <span class="px-2.5 py-1 rounded bg-green-100 text-green-700 text-xs font-bold font-mono">POST</span>
                            <code class="text-sm font-mono text-slate-700">/notas/analisar</code>
                        </div>

                        <div class="grid lg:grid-cols-2 gap-6">
                            <div class="rounded-lg border border-slate-200 overflow-hidden shadow-sm">
                                <div class="bg-slate-50 px-4 py-2 border-b border-slate-200 text-xs font-bold text-slate-500">Request</div>
                                <div class="p-4 bg-slate-900 overflow-x-auto">
<pre class="text-xs text-slate-300 font-mono">
{
  "imagem": "base64...", 
  "cpf_funcionario": "123..."
}
</pre>
                                </div>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 text-sm text-slate-600">
                                <p>A IA extrai dados, valida regras (álcool/data) e salva a nota automaticamente.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Manual -->
                    <div id="nota-manual" class="scroll-mt-24">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-bold text-slate-800">2. Envio Manual</h3>
                            <span class="bg-slate-100 text-slate-600 text-xs px-2 py-1 rounded font-bold">Sem IA</span>
                        </div>
                        <div class="flex items-center gap-3 mb-6">
                            <span class="px-2.5 py-1 rounded bg-green-100 text-green-700 text-xs font-bold font-mono">POST</span>
                            <code class="text-sm font-mono text-slate-700">/notas/manual</code>
                        </div>

                        <div class="rounded-lg border border-slate-200 overflow-hidden shadow-sm">
                            <div class="bg-slate-50 px-4 py-2 border-b border-slate-200 text-xs font-bold text-slate-500">Payload Completo</div>
                            <div class="p-4 bg-slate-900 overflow-x-auto">
<pre class="text-xs text-slate-300 font-mono">
{
  "funcionario_id": 5,
  "valor_total": 150.50,
  "data_emissao": "2024-12-10",
  "estabelecimento": "Posto Ipiranga",
  "cnpj": "00000000000191",
  "itens": [
    { "nome": "Gasolina", "valor": 50.00 }
  ]
}
</pre>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Financeiro -->
                <section id="fin-vales" class="mb-20 pt-10 border-t border-slate-100">
                    <h2 class="text-2xl font-bold text-slate-900 mb-8">Financeiro</h2>
                    
                    <div class="grid md:grid-cols-2 gap-6 mb-10">
                        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
                            <h4 class="font-bold text-slate-800 mb-2">Listar Tipos de Vales</h4>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-[10px] font-bold font-mono">GET</span>
                                <code class="text-xs">/financeiro/vales</code>
                            </div>
                        </div>

                        <div id="fin-saldo" class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm scroll-mt-24">
                            <h4 class="font-bold text-slate-800 mb-2">Saldo & Extrato</h4>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-[10px] font-bold font-mono">GET</span>
                                <code class="text-xs">/financeiro/saldo</code>
                            </div>
                        </div>
                    </div>

                    <div id="fin-relatorio" class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm scroll-mt-24">
                        <h3 class="text-lg font-bold text-slate-800 mb-4">Relatórios</h3>
                        
                        <div class="space-y-6">
                            <div>
                                <h4 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-2">Geral (Todos)</h4>
                                <div class="flex items-center gap-3">
                                    <span class="px-2.5 py-1 rounded bg-blue-100 text-blue-700 text-xs font-bold font-mono">GET</span>
                                    <code class="text-sm font-mono text-slate-700">/financeiro/relatorio/geral</code>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-100">
                                <h4 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-2">Individual</h4>
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="px-2.5 py-1 rounded bg-blue-100 text-blue-700 text-xs font-bold font-mono">GET</span>
                                    <code class="text-sm font-mono text-slate-700">/financeiro/relatorio/funcionario/{id}</code>
                                </div>
                                
                                <div class="rounded-lg bg-slate-900 p-4 overflow-x-auto">
<pre class="text-xs text-slate-300 font-mono">
{
  "funcionario": { "id": 5, "nome": "Carlos" },
  "total_gasto": 1200.00,
  "detalhes": [...]
}
</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <footer class="pt-10 border-t border-slate-200">
                    <p class="text-center text-sm text-slate-400">
                        &copy; {{ date('Y') }} ValeIA Tecnologia.
                    </p>
                </footer>

            </div>
        </main>
    </div>

    <!-- Script para scroll suave na sidebar -->
    <script>
        document.querySelectorAll('aside a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>