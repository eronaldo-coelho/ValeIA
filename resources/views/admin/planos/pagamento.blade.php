<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento - ValeIA</title>
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
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-600" x-data="paymentChecker({{ $pagamento->id }})">

    @include('admin.partials.mobile-header')

    <div class="flex h-screen overflow-hidden pt-16 md:pt-0">
        @include('admin.partials.sidebar')

        <main class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-8 flex items-center justify-center">
            
            <div class="max-w-3xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden relative border border-slate-100">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-brand-400 to-brand-600"></div>

                <div class="p-8 text-center" x-show="status !== 'approved'">
                    <div class="mb-6 inline-flex items-center justify-center w-16 h-16 rounded-full bg-brand-50 text-brand-600 animate-pulse-slow">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>

                    <h1 class="text-3xl font-bold text-slate-800 mb-2">Pagamento Pendente</h1>
                    <p class="text-slate-500 mb-8">Valor total: <span class="text-slate-900 font-bold text-xl">R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</span></p>

                    @if($pagamento->metodo_pagamento == 'pix')
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 mb-6 max-w-sm mx-auto">
                            <p class="text-sm font-semibold text-slate-700 mb-4">Escaneie o QR Code</p>
                            @if($pagamento->qr_code_base64)
                                <img src="data:image/png;base64,{{ $pagamento->qr_code_base64 }}" class="w-48 h-48 mx-auto mb-4 rounded-lg shadow-sm">
                            @endif
                            
                            <div class="relative">
                                <textarea id="pixCode" readonly class="w-full bg-white border border-slate-200 rounded-lg p-3 text-xs text-slate-500 resize-none h-20 outline-none focus:border-brand-500 transition">{{ $pagamento->qr_code }}</textarea>
                                <button onclick="copyPix()" class="mt-2 w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-2 rounded-lg text-sm transition">Copiar Código Pix</button>
                            </div>
                        </div>
                    @else
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 mb-6">
                            <p class="text-sm font-semibold text-slate-700 mb-4">Boleto Bancário</p>
                            <div class="mb-4">
                                <p class="text-xs text-slate-500 mb-1">Linha Digitável</p>
                                <div class="flex items-center gap-2 bg-white border border-slate-200 p-3 rounded-lg">
                                    <input type="text" id="boletoCode" value="{{ $pagamento->boleto_linha_digitavel }}" readonly class="w-full text-sm outline-none text-slate-700">
                                    <button onclick="copyBoleto()" class="text-brand-600 hover:text-brand-800 font-bold text-xs uppercase">Copiar</button>
                                </div>
                            </div>
                            <a href="{{ $pagamento->boleto_url }}" target="_blank" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-white font-bold py-3 px-6 rounded-xl transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                Baixar Boleto PDF
                            </a>
                        </div>
                    @endif

                    <div class="flex items-center justify-center gap-2 text-sm text-slate-400">
                        <span class="w-2 h-2 bg-brand-500 rounded-full animate-ping"></span>
                        Aguardando confirmação automática...
                    </div>
                    <p class="text-xs text-slate-400 mt-2">Vencimento em: {{ $pagamento->data_expiracao->format('d/m/Y H:i') }}</p>
                </div>

                <div x-show="status === 'approved'" class="p-12 text-center" style="display: none;">
                    <div class="mb-6 inline-flex items-center justify-center w-24 h-24 rounded-full bg-green-100 text-green-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h1 class="text-3xl font-bold text-slate-800 mb-4">Pagamento Aprovado!</h1>
                    <p class="text-slate-500 mb-8 text-lg">Seu plano foi renovado com sucesso.</p>
                    <a href="{{ route('dashboard') }}" class="inline-block bg-brand-600 hover:bg-brand-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition transform hover:-translate-y-1">
                        Voltar ao Dashboard
                    </a>
                </div>

            </div>
        </main>
    </div>

    <script>
        function copyPix() {
            var copyText = document.getElementById("pixCode");
            copyText.select();
            copyText.setSelectionRange(0, 99999); 
            navigator.clipboard.writeText(copyText.value);
            alert("Código Pix copiado!");
        }

        function copyBoleto() {
            var copyText = document.getElementById("boletoCode");
            copyText.select();
            copyText.setSelectionRange(0, 99999); 
            navigator.clipboard.writeText(copyText.value);
            alert("Código de barras copiado!");
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('paymentChecker', (id) => ({
                status: '{{ $pagamento->status }}',
                init() {
                    if (this.status !== 'approved') {
                        setInterval(() => {
                            this.checkStatus(id);
                        }, 5000); 
                    }
                },
                async checkStatus(id) {
                    try {
                        const response = await fetch(`/admin/pagamento/${id}/status`);
                        const data = await response.json();
                        this.status = data.status;
                        
                        if (this.status === 'approved') {
                            window.location.reload();
                        }
                    } catch (error) {
                        console.error('Erro ao verificar status:', error);
                    }
                }
            }))
        })
    </script>
</body>
</html>