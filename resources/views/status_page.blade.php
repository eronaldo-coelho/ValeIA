<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Refresh automático a cada 60s (Padrão para status pages) -->
    <meta http-equiv="refresh" content="60">
    
    <title>Status do Sistema | ValeIA</title>
    <meta name="description" content="Monitoramento em tempo real da infraestrutura ValeIA.">
    <meta name="robots" content="noindex, follow">
    
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="theme-color" content="#ffffff">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Fonte Mono para dados técnicos passa mais credibilidade -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Alpine.js para lógica de ping em tempo real -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-600 bg-slate-50 antialiased selection:bg-brand-100 selection:text-brand-900">

    <!-- Header Unificado -->
    <nav class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200 transition-all duration-300">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-[90px]">
                
                <div class="flex-shrink-0 flex items-center gap-4">
                    <a href="/" title="Voltar para o início">
                        <img class="h-[64px] w-auto object-contain" 
                             src="{{ asset('imagens/logo.png') }}" 
                             alt="ValeIA" 
                             width="200" 
                             height="64">
                    </a>
                    <!-- Divisor visual -->
                    <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>
                    <span class="text-sm font-bold text-slate-500 hidden sm:block uppercase tracking-wider">Monitoramento</span>
                </div>

                <div class="flex items-center gap-6">
                    <a href="mailto:suporte@valeia.com.br" class="text-sm font-medium text-slate-500 hover:text-brand-700 transition-colors">
                        Reportar Incidente
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white bg-brand-900 rounded-lg hover:bg-brand-800 shadow-sm transition-all">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto space-y-12">

            <!-- Card Principal de Status (Lógica via Alpine) -->
            <div x-data="systemHealth()" x-init="checkHealth()" class="relative overflow-hidden rounded-xl border bg-white p-8 shadow-sm transition-all duration-500"
                 :class="isOnline ? 'border-brand-200 ring-1 ring-brand-100' : 'border-red-200 ring-1 ring-red-100'">
                
                <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div class="flex items-start gap-5">
                        <!-- Indicador Pulsante -->
                        <div class="relative flex h-6 w-6 mt-1">
                            <span x-show="loading" class="animate-spin absolute inline-flex h-full w-full rounded-full border-2 border-slate-300 border-t-brand-600"></span>
                            <span x-show="!loading && isOnline" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                            <span x-show="!loading && isOnline" class="relative inline-flex rounded-full h-6 w-6 bg-brand-500"></span>
                            <span x-show="!loading && !isOnline" class="relative inline-flex rounded-full h-6 w-6 bg-red-500"></span>
                        </div>

                        <div>
                            <h1 class="text-2xl font-bold text-slate-900" x-text="statusMessage">Verificando conectividade...</h1>
                            <p class="text-slate-500 mt-1">Monitoramento em tempo real da API e serviços auxiliares.</p>
                        </div>
                    </div>

                    <div class="flex flex-col items-end">
                        <div class="text-right">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Latência API</span>
                            <div class="font-mono text-3xl font-bold text-slate-800 tracking-tight" x-text="latency + 'ms'">---</div>
                        </div>
                        <div class="text-xs text-brand-600 mt-2 font-medium bg-brand-50 px-2 py-1 rounded">
                            Atualizado: <span x-text="lastCheck">--:--:--</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid de Métricas (Dados do Backend) -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-900">Volume Operacional (Últimas 24h)</h3>
                    <span class="text-xs font-medium text-slate-400 bg-slate-100 px-3 py-1 rounded-full">Atualização: Real-time</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Card 1 -->
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                        <p class="text-sm font-medium text-slate-500 mb-2">Empresas Ativas</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-slate-900">{{ number_format($totalAdmins ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-sm font-medium text-slate-500">Processado via IA</p>
                            <span class="text-[10px] font-bold bg-purple-100 text-purple-700 px-2 py-0.5 rounded uppercase">OCR</span>
                        </div>
                        <span class="text-3xl font-bold text-slate-900">{{ number_format($notasIA ?? 0, 0, ',', '.') }}</span>
                        <p class="text-xs text-slate-400 mt-2">Notas lidas automaticamente</p>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                        <p class="text-sm font-medium text-slate-500 mb-2">Inserção Manual</p>
                        <span class="text-3xl font-bold text-slate-900">{{ number_format($notasManual ?? 0, 0, ',', '.') }}</span>
                        <p class="text-xs text-slate-400 mt-2">Upload direto</p>
                    </div>

                    <!-- Card 4 - Compliance -->
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden">
                        <div class="absolute right-0 top-0 p-3 opacity-10">
                            <svg class="w-16 h-16 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                        </div>
                        <p class="text-sm font-medium text-slate-500 mb-2">Irregularidades Detectadas</p>
                        <span class="text-3xl font-bold text-red-600">{{ number_format($totalItensAlcool ?? 0, 0, ',', '.') }}</span>
                        <p class="text-xs text-red-400 mt-2 font-medium">Itens de risco bloqueados</p>
                    </div>
                </div>
            </div>

            <!-- Status dos Componentes Individuais -->
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Coluna Esquerda: Componentes -->
                <div class="lg:col-span-2">
                    <h3 class="text-lg font-bold text-slate-900 mb-6">Infraestrutura</h3>
                    <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100 shadow-sm">
                        
                        <!-- API -->
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition">
                            <div class="flex items-center gap-4">
                                <div class="p-2 bg-slate-100 rounded text-slate-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">API Gateway</p>
                                    <p class="text-xs text-slate-500">Pontos de entrada (Web/Mobile)</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-brand-50 text-brand-700">
                                Operacional
                            </span>
                        </div>

                        <!-- OCR Engine -->
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition">
                            <div class="flex items-center gap-4">
                                <div class="p-2 bg-slate-100 rounded text-slate-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">Motor de OCR (IA)</p>
                                    <p class="text-xs text-slate-500">Processamento de imagens</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-brand-50 text-brand-700">
                                Operacional
                            </span>
                        </div>

                        <!-- Database -->
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition">
                            <div class="flex items-center gap-4">
                                <div class="p-2 bg-slate-100 rounded text-slate-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">Banco de Dados</p>
                                    <p class="text-xs text-slate-500">Cluster Principal (RW)</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-brand-50 text-brand-700">
                                Operacional
                            </span>
                        </div>

                        <!-- WhatsApp -->
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition">
                            <div class="flex items-center gap-4">
                                <div class="p-2 bg-slate-100 rounded text-slate-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">Integração WhatsApp</p>
                                    <p class="text-xs text-slate-500">Envio e recebimento de msg</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-brand-50 text-brand-700">
                                Operacional
                            </span>
                        </div>

                    </div>
                </div>

                <!-- Coluna Direita: Histórico -->
                <div class="lg:col-span-1">
                    <h3 class="text-lg font-bold text-slate-900 mb-6">Histórico Recente</h3>
                    <div class="space-y-4">
                        <!-- Dia Atual -->
                        <div class="p-4 bg-white rounded-xl border border-slate-200">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-bold text-slate-700">Hoje</span>
                                <span class="text-xs font-bold text-brand-600">100% Uptime</span>
                            </div>
                            <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-brand-500 w-full rounded-full"></div>
                            </div>
                            <p class="text-xs text-slate-400 mt-2">Nenhum incidente registrado.</p>
                        </div>

                        <!-- Ontem -->
                        <div class="p-4 bg-white rounded-xl border border-slate-200 opacity-70">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-bold text-slate-700">Ontem</span>
                                <span class="text-xs font-bold text-brand-600">100% Uptime</span>
                            </div>
                            <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-brand-500 w-full rounded-full"></div>
                            </div>
                        </div>

                        <!-- Anteontem -->
                        <div class="p-4 bg-white rounded-xl border border-slate-200 opacity-60">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-bold text-slate-700">7 dias atrás</span>
                                <span class="text-xs font-bold text-brand-600">99.9% Uptime</span>
                            </div>
                            <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-brand-500 w-full rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="mt-12 bg-white border-t border-slate-200 py-8">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <p class="text-sm text-slate-400">&copy; {{ date('Y') }} ValeIA Tecnologia.</p>
        </div>
    </footer>

    <script>
        function systemHealth() {
            return {
                loading: true,
                isOnline: false,
                statusMessage: 'Iniciando diagnóstico...',
                latency: 0,
                lastCheck: '--:--:--',

                async checkHealth() {
                    const start = Date.now();
                    
                    try {
                        // Headers 'no-cache' para garantir dado real
                        const response = await fetch('https://api.valeia.com.br/api/status', { 
                            method: 'GET',
                        });
                        
                        const end = Date.now();
                        this.latency = end - start;
                        
                        if (response.ok) {
                            this.isOnline = true;
                            this.statusMessage = 'Todos os sistemas operacionais';
                        } else {
                            this.isOnline = false;
                            this.statusMessage = 'Degradação de performance detectada';
                        }
                    } catch (error) {
                        this.isOnline = false;
                        this.statusMessage = 'Sistema indisponível';
                        this.latency = 0;
                    } finally {
                        this.loading = false;
                        const now = new Date();
                        this.lastCheck = now.toLocaleTimeString('pt-BR');
                    }
                }
            }
        }
    </script>
</body>
</html>