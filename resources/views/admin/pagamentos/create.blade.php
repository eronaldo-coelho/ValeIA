<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Pagamento - ValeIA</title>
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
<body class="bg-slate-50 font-sans text-slate-600" x-data="paymentForm()">

    @include('admin.partials.mobile-header')

    <div class="flex h-screen overflow-hidden pt-16 md:pt-0">
        @include('admin.partials.sidebar')

        <main class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-8">
            <header class="flex items-center gap-4 mb-8">
                <a href="{{ route('admin.pagamentos.index') }}" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Registrar Pagamento</h1>
                    <p class="text-slate-500">O sistema valida o comprovante automaticamente antes de salvar.</p>
                </div>
            </header>

            <form @submit.prevent="submitForm" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Coluna Esquerda: Upload -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="font-bold text-slate-800 mb-4">Comprovante</h3>
                        
                        <div class="relative w-full h-64 border-2 border-dashed border-slate-300 rounded-xl bg-slate-50 flex flex-col items-center justify-center text-center p-4 hover:border-brand-500 transition cursor-pointer"
                             @click="document.getElementById('fileInput').click()"
                             x-show="!previewUrl">
                            <svg class="w-10 h-10 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-sm text-slate-500 font-medium">Clique para enviar</p>
                            <p class="text-xs text-slate-400 mt-1">JPG, PNG (Max 5MB)</p>
                        </div>

                        <div x-show="previewUrl" class="relative w-full h-auto rounded-xl overflow-hidden border border-slate-200">
                            <img :src="previewUrl" class="w-full object-contain bg-slate-100">
                            <button type="button" @click="limparImagem" class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full shadow hover:bg-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <input type="file" id="fileInput" class="hidden" accept="image/*" @change="handleFile">

                        <!-- Loading da Análise -->
                        <div x-show="loading" class="mt-4 flex flex-col items-center justify-center text-indigo-600 animate-pulse">
                            <svg class="w-6 h-6 mb-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span class="text-sm font-medium" x-text="loadingMsg"></span>
                        </div>

                        <div x-show="errorMsg" class="mt-4 p-3 bg-red-50 text-red-700 text-sm rounded-lg border border-red-100 flex items-start gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span x-text="errorMsg"></span>
                        </div>
                    </div>
                </div>

                <!-- Coluna Direita: Dados -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="font-bold text-slate-800 mb-6">Dados do Pagamento</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Seleção de Funcionário -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Funcionário</label>
                                <select x-model="form.funcionario_id" @change="updateFuncionario" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500" required>
                                    <option value="">Selecione...</option>
                                    @foreach($funcionarios as $f)
                                        <option value="{{ $f->id }}">{{ $f->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Exibição da Conta Bancária -->
                            <div class="md:col-span-2" x-show="contaPrincipal">
                                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-start gap-3">
                                    <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-blue-800 uppercase mb-1">Conta Principal para Depósito</p>
                                        <template x-if="contaPrincipal.tipo_pagamento == 'pix'">
                                            <p class="text-sm text-blue-900 font-medium">
                                                PIX: <span x-text="contaPrincipal.chave_pix"></span> 
                                                <span class="text-xs text-blue-600 ml-1" x-text="'(' + contaPrincipal.tipo_chave_pix + ')'"></span>
                                            </p>
                                        </template>
                                        <template x-if="contaPrincipal.tipo_pagamento != 'pix'">
                                            <div>
                                                <p class="text-sm text-blue-900 font-medium" x-text="contaPrincipal.banco"></p>
                                                <p class="text-xs text-blue-700">
                                                    Ag: <span x-text="contaPrincipal.agencia"></span> | 
                                                    CC: <span x-text="contaPrincipal.conta"></span> | 
                                                    <span x-text="contaPrincipal.tipo_conta"></span>
                                                </p>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Seleção de Vales (Débitos) -->
                            <div class="md:col-span-2" x-show="valesDisponiveis.length > 0">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Abater dos Vales (Opcional)</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <template x-for="vale in valesDisponiveis" :key="vale.id">
                                        <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 transition-colors" :class="{'bg-brand-50 border-brand-200': selectedVales.includes(vale.vale_id)}">
                                            <input type="checkbox" :value="vale.vale_id" x-model="selectedVales" class="w-4 h-4 text-brand-600 rounded focus:ring-brand-500">
                                            <div class="flex-1">
                                                <div class="flex justify-between items-center">
                                                    <span class="font-bold text-slate-800 text-sm" x-text="vale.tipo.nome"></span>
                                                    <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full font-bold" x-text="'Deve: R$ ' + parseFloat(vale.saldo_devedor).toFixed(2)"></span>
                                                </div>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Valor Pago (R$)</label>
                                <input type="number" step="0.01" x-model="form.valor" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500 font-bold" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Data Pagamento</label>
                                <input type="date" x-model="form.data_pagamento" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500" required>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Forma de Pagamento</label>
                                <input type="text" x-model="form.forma_pagamento" placeholder="Ex: PIX, Dinheiro, TED" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500" required>
                            </div>
                        </div>

                        <div class="flex justify-end gap-4 pt-6 mt-6 border-t border-slate-100">
                            <a href="{{ route('admin.pagamentos.index') }}" class="px-6 py-3 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 font-medium">Cancelar</a>
                            <button type="submit" :disabled="loading" class="px-8 py-3 rounded-lg bg-brand-600 text-white hover:bg-brand-800 font-bold shadow-lg shadow-brand-500/20 disabled:opacity-50">
                                Salvar Pagamento
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script>
        const funcionariosData = @json($funcionarios);

        function paymentForm() {
            return {
                previewUrl: null,
                loading: false,
                loadingMsg: '',
                errorMsg: null,
                valesDisponiveis: [],
                selectedVales: [],
                contaPrincipal: null,
                
                form: {
                    funcionario_id: '',
                    imagem_base64: null,
                    valor: '',
                    data_pagamento: new Date().toISOString().split('T')[0],
                    forma_pagamento: ''
                },

                updateFuncionario() {
                    const func = funcionariosData.find(f => f.id == this.form.funcionario_id);
                    
                    // Atualiza conta principal
                    if (func && func.contas && func.contas.length > 0) {
                        this.contaPrincipal = func.contas[0]; // Controller já filtra 'principal' => true
                    } else {
                        this.contaPrincipal = null;
                    }

                    // Atualiza vales
                    if (func && func.vales) {
                        this.valesDisponiveis = func.vales.filter(v => parseFloat(v.saldo_devedor) > 0);
                    } else {
                        this.valesDisponiveis = [];
                    }
                    this.selectedVales = [];
                },

                handleFile(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    
                    this.previewUrl = URL.createObjectURL(file);
                    const reader = new FileReader();
                    
                    reader.onloadend = () => {
                        this.form.imagem_base64 = reader.result;
                        this.preencherComIA(); // Chama IA automaticamente
                    };
                    reader.readAsDataURL(file);
                },

                limparImagem() {
                    this.previewUrl = null;
                    this.form.imagem_base64 = null;
                    this.errorMsg = null;
                    document.getElementById('fileInput').value = '';
                },

                async preencherComIA() {
                    this.loading = true;
                    this.loadingMsg = 'Extraindo dados com IA...';
                    this.errorMsg = null;

                    try {
                        const response = await fetch('{{ route("admin.pagamentos.analisar") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ 
                                imagem: this.form.imagem_base64,
                                modo: 'preencher'
                            })
                        });
                        const data = await response.json();
                        
                        if (response.ok) {
                            if (data.valor) this.form.valor = data.valor;
                            if (data.forma_pagamento) this.form.forma_pagamento = data.forma_pagamento;
                            if (data.data_pagamento) this.form.data_pagamento = data.data_pagamento;
                        }
                    } catch (e) {
                        console.error(e);
                    } finally {
                        this.loading = false;
                    }
                },

                async submitForm() {
                    if (!this.form.imagem_base64) {
                        alert('Por favor, faça upload do comprovante.');
                        return;
                    }

                    // Se não tiver funcionário ou conta, não dá pra validar destinatário
                    if (!this.contaPrincipal) {
                        if (!confirm('Este funcionário não tem conta bancária cadastrada. A validação de destinatário será ignorada. Deseja continuar?')) return;
                        this.enviarParaServidor();
                        return;
                    }

                    this.loading = true;
                    this.loadingMsg = 'Validando autenticidade do comprovante...';
                    this.errorMsg = null;

                    try {
                        const response = await fetch('{{ route("admin.pagamentos.analisar") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ 
                                imagem: this.form.imagem_base64,
                                modo: 'validar',
                                conta_dados: this.contaPrincipal,
                                valor_esperado: this.form.valor
                            })
                        });
                        const data = await response.json();

                        if (response.ok) {
                            if (data.valido === true) {
                                this.enviarParaServidor();
                            } else {
                                this.errorMsg = 'Comprovante REJEITADO pela IA: ' + data.motivo;
                                this.loading = false;
                            }
                        } else {
                            this.errorMsg = 'Erro na validação IA. Tente novamente.';
                            this.loading = false;
                        }
                    } catch (e) {
                        this.errorMsg = 'Erro de conexão com validação.';
                        this.loading = false;
                    }
                },

                enviarParaServidor() {
                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('funcionario_id', this.form.funcionario_id);
                    formData.append('imagem_base64', this.form.imagem_base64);
                    formData.append('valor', this.form.valor);
                    formData.append('data_pagamento', this.form.data_pagamento);
                    formData.append('forma_pagamento', this.form.forma_pagamento);
                    
                    this.selectedVales.forEach(id => {
                        formData.append('funcionario_vale_id[]', id);
                    });

                    fetch('{{ route("admin.pagamentos.store") }}', {
                        method: 'POST',
                        body: formData
                    }).then(res => {
                        if (res.ok) window.location.href = '{{ route("admin.pagamentos.index") }}';
                        else alert('Erro ao salvar pagamento.');
                    });
                }
            }
        }
    </script>
</body>
</html>