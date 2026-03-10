<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nota - ValeIA</title>
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
<body class="bg-slate-50 font-sans text-slate-600" x-data="notaForm()">

    @include('admin.partials.mobile-header')

    <div class="flex h-screen overflow-hidden pt-16 md:pt-0">
        @include('admin.partials.sidebar')

        <main class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-8">
            <header class="flex items-center gap-4 mb-8">
                <a href="{{ route('admin.notas.index') }}" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Lançar Nova Nota</h1>
                    <p class="text-slate-500">Preencha manualmente ou faça upload para preenchimento automático.</p>
                </div>
            </header>

            <!-- FLASH MESSAGES (BACKEND) -->
            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm animate-pulse">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800">Atenção: Nota Rejeitada</h3>
                            <div class="mt-1 text-sm text-red-700 font-medium">
                                {{ session('error') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form @submit.prevent="submitWithValidation" class="grid grid-cols-1 lg:grid-cols-3 gap-8" id="mainForm">
                @csrf
                
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="font-bold text-slate-800 mb-4">1. Imagem da Nota</h3>
                        
                        <div class="relative w-full h-64 border-2 border-dashed border-slate-300 rounded-xl bg-slate-50 flex flex-col items-center justify-center text-center p-4 hover:border-brand-500 transition cursor-pointer"
                             @click="document.getElementById('fileInput').click()"
                             x-show="!previewUrl">
                            <svg class="w-10 h-10 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-sm text-slate-500 font-medium">Clique para enviar imagem</p>
                            <p class="text-xs text-slate-400 mt-1">JPG, PNG (Max 5MB)</p>
                        </div>

                        <div x-show="previewUrl" class="relative w-full h-auto rounded-xl overflow-hidden border border-slate-200">
                            <img :src="previewUrl" class="w-full object-contain bg-slate-100">
                            <button type="button" @click="limparImagem" class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full shadow hover:bg-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <input type="file" id="fileInput" class="hidden" accept="image/*" @change="handleFile">

                        <div x-show="previewUrl && !loading" class="mt-4">
                            <button type="button" @click="analisarComIA" :disabled="!form.funcionario_id"
                                class="w-full bg-indigo-600 text-white py-2.5 rounded-lg font-bold shadow-lg shadow-indigo-500/20 hover:bg-indigo-700 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                Preencher com IA
                            </button>
                            <p x-show="!form.funcionario_id" class="text-xs text-red-500 mt-2 text-center">Selecione o funcionário ao lado primeiro.</p>
                        </div>
                        
                        <div x-show="loading" class="mt-4 flex flex-col items-center justify-center text-indigo-600">
                            <svg class="animate-spin h-8 w-8 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-sm font-medium" x-text="loadingMsg"></span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            2. Detalhes da Nota
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Funcionário *</label>
                                <select x-model="form.funcionario_id" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500" required>
                                    <option value="">Selecione...</option>
                                    @foreach($funcionarios as $f)
                                        <option value="{{ $f->id }}">{{ $f->nome }} ({{ $f->cpf }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Vale Utilizado</label>
                                <select x-model="form.vale_id" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500">
                                    <option value="">Nenhum / Não identificado</option>
                                    @foreach($vales as $v)
                                        <option value="{{ $v->id }}">{{ $v->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tipo de Documento</label>
                                <input type="text" x-model="form.tipo" list="tipos_documento" placeholder="Ex: NFC-e, Recibo..." class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500">
                                <datalist id="tipos_documento">
                                    <option value="NFC-e">
                                    <option value="NF-e">
                                    <option value="SAT">
                                    <option value="Cupom Fiscal">
                                    <option value="Cupom Não Fiscal">
                                    <option value="Recibo">
                                </datalist>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Data Emissão *</label>
                                <input type="date" x-model="form.data_emissao" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Estabelecimento</label>
                                <input type="text" x-model="form.estabelecimento" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500">
                            </div>

                             <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">CNPJ</label>
                                <input type="text" x-model="form.cnpj" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Endereço</label>
                                <input type="text" x-model="form.endereco" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nº Documento (COO/Extrato)</label>
                                <input type="text" x-model="form.numero_documento" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Valor Total (R$) *</label>
                                <input type="number" step="0.01" x-model="form.valor_total" class="w-full bg-slate-50 border border-brand-200 rounded-lg p-2.5 outline-none focus:ring-brand-500 font-bold text-slate-800" required>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-slate-800">3. Itens da Nota</h3>
                            <button type="button" @click="addItem" class="text-sm bg-slate-100 text-slate-700 px-3 py-1.5 rounded-lg hover:bg-slate-200 font-medium">
                                + Adicionar Item
                            </button>
                        </div>

                        <div class="space-y-3">
                            <template x-for="(item, index) in form.itens" :key="index">
                                <div class="grid grid-cols-12 gap-2 items-end p-3 bg-slate-50 rounded-lg border border-slate-100">
                                    <div class="col-span-5">
                                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Produto</label>
                                        <input type="text" x-model="item.nome" class="w-full p-1.5 text-sm border border-slate-200 rounded" placeholder="Nome">
                                    </div>
                                    <div class="col-span-3">
                                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Categoria</label>
                                        <select x-model="item.categoria" class="w-full p-1.5 text-sm border border-slate-200 rounded">
                                            <option value="alimento">Alimento</option>
                                            <option value="bebida">Bebida</option>
                                            <option value="bebida_alcoolica">Bebida Alcoólica</option>
                                            <option value="outros">Outros</option>
                                        </select>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Total</label>
                                        <input type="number" step="0.01" x-model="item.valor_total" @input="recalcTotal" class="w-full p-1.5 text-sm border border-slate-200 rounded">
                                        <input type="hidden" x-model="item.quantidade">
                                    </div>
                                    <div class="col-span-2 text-right">
                                        <button type="button" @click="removeItem(index)" class="text-red-400 hover:text-red-600 p-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            <div x-show="form.itens.length === 0" class="text-center text-slate-400 text-sm py-4">
                                Nenhum item listado.
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 pt-4">
                        <a href="{{ route('admin.notas.index') }}" class="px-6 py-3 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 font-medium">Cancelar</a>
                        <button type="submit" :disabled="loading" class="px-8 py-3 rounded-lg bg-brand-600 text-white hover:bg-brand-800 font-bold shadow-lg shadow-brand-500/20 disabled:opacity-50">
                            Salvar Nota Fiscal
                        </button>
                    </div>
                </div>
            </form>
        </main>

        <!-- MODAL DE ERRO CUSTOMIZADO -->
        <div x-show="showErrorModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" @click="showErrorModal = false"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all scale-100">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                
                <h3 class="text-lg leading-6 font-bold text-center text-slate-900 mb-2">Nota Recusada</h3>
                
                <div class="mt-2 px-4">
                    <p class="text-sm text-slate-500 text-center" x-text="errorMessage"></p>
                </div>

                <div class="mt-6 flex justify-center">
                    <button type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:w-auto sm:text-sm" @click="showErrorModal = false">
                        Entendido
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- FORMULÁRIO OCULTO PARA SUBMISSÃO REAL -->
    <form id="realForm" action="{{ route('admin.criar-nota.store') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="imagem_base64">
        <input type="hidden" name="funcionario_id">
        <input type="hidden" name="vale_id">
        <input type="hidden" name="tipo">
        <input type="hidden" name="data_emissao">
        <input type="hidden" name="estabelecimento">
        <input type="hidden" name="cnpj">
        <input type="hidden" name="endereco">
        <input type="hidden" name="numero_documento">
        <input type="hidden" name="valor_total">
        <input type="hidden" name="itens_alcoolicos_detectados">
    </form>

    <script>
        function notaForm() {
            return {
                previewUrl: null,
                loading: false,
                loadingMsg: '',
                showErrorModal: false,
                errorMessage: '',
                form: {
                    funcionario_id: '',
                    vale_id: '',
                    imagem_base64: null,
                    tipo: '',
                    data_emissao: new Date().toISOString().split('T')[0],
                    estabelecimento: '',
                    cnpj: '',
                    endereco: '',
                    numero_documento: '',
                    valor_total: 0,
                    itens: [],
                    itens_alcoolicos_detectados: []
                },
                
                handleFile(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    this.previewUrl = URL.createObjectURL(file);
                    const reader = new FileReader();
                    reader.onloadend = () => {
                        this.form.imagem_base64 = reader.result;
                    };
                    reader.readAsDataURL(file);
                },

                limparImagem() {
                    this.previewUrl = null;
                    this.form.imagem_base64 = null;
                    document.getElementById('fileInput').value = '';
                },

                showError(msg) {
                    this.errorMessage = msg;
                    this.showErrorModal = true;
                },

                async analisarComIA() {
                    if (!this.form.imagem_base64 || !this.form.funcionario_id) return;
                    this.loading = true;
                    this.loadingMsg = 'Lendo nota fiscal...';

                    try {
                        const response = await fetch('{{ route("admin.criar-nota.analisar") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                imagem: this.form.imagem_base64,
                                funcionario_id: this.form.funcionario_id
                            })
                        });

                        const data = await response.json();

                        if (response.ok) {
                            this.form.vale_id = data.vale_id || '';
                            this.form.tipo = data.tipo || '';
                            if (data.data_compra) {
                                const parts = data.data_compra.split('/');
                                if (parts.length === 3) this.form.data_emissao = `${parts[0]}-${parts[1]}-${parts[2]}`;
                                else this.form.data_emissao = data.data_compra;
                            }
                            this.form.estabelecimento = data.estabelecimento || '';
                            this.form.cnpj = data.cnpj || '';
                            this.form.endereco = data.endereco || '';
                            this.form.numero_documento = data.numero_documento || '';
                            this.form.valor_total = data.valor_total || 0;
                            this.form.itens = data.itens || [];
                            this.form.itens_alcoolicos_detectados = data.itens_alcoolicos || [];
                            
                            this.form.itens.forEach(item => {
                                if(!item.quantidade) item.quantidade = 1;
                                if(!item.valor_total && item.valor_unitario) item.valor_total = item.quantidade * item.valor_unitario;
                            });

                        } else {
                            this.showError('Erro na análise: ' + (data.erro || 'Desconhecido'));
                        }

                    } catch (error) {
                        console.error(error);
                        this.showError('Erro ao comunicar com o servidor.');
                    } finally {
                        this.loading = false;
                    }
                },

                async submitWithValidation() {
                    if (!this.form.imagem_base64) {
                        this.showError("Por favor, anexe a imagem da nota.");
                        return;
                    }

                    this.loading = true;
                    this.loadingMsg = 'Validando autenticidade com IA...';

                    try {
                        const response = await fetch('{{ route("admin.criar-nota.analisar") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                imagem: this.form.imagem_base64,
                                funcionario_id: this.form.funcionario_id,
                                modo: 'validar_final',
                                valor_total: this.form.valor_total,
                                data_emissao: this.form.data_emissao,
                                estabelecimento: this.form.estabelecimento,
                                cnpj: this.form.cnpj
                            })
                        });

                        const data = await response.json();

                        if (data.valido === true) {
                            const realForm = document.getElementById('realForm');
                            realForm.querySelector('[name="imagem_base64"]').value = this.form.imagem_base64;
                            realForm.querySelector('[name="funcionario_id"]').value = this.form.funcionario_id;
                            realForm.querySelector('[name="vale_id"]').value = this.form.vale_id;
                            realForm.querySelector('[name="tipo"]').value = this.form.tipo;
                            realForm.querySelector('[name="data_emissao"]').value = this.form.data_emissao;
                            realForm.querySelector('[name="estabelecimento"]').value = this.form.estabelecimento;
                            realForm.querySelector('[name="cnpj"]').value = this.form.cnpj;
                            realForm.querySelector('[name="endereco"]').value = this.form.endereco;
                            realForm.querySelector('[name="numero_documento"]').value = this.form.numero_documento;
                            realForm.querySelector('[name="valor_total"]').value = this.form.valor_total;
                            realForm.querySelector('[name="itens_alcoolicos_detectados"]').value = JSON.stringify(this.form.itens_alcoolicos_detectados);

                            this.form.itens.forEach((item, index) => {
                                ['nome', 'categoria', 'valor_total', 'quantidade'].forEach(field => {
                                    let input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = `itens[${index}][${field}]`;
                                    input.value = item[field];
                                    realForm.appendChild(input);
                                });
                            });

                            realForm.submit();

                        } else {
                            this.showError('Motivo: ' + data.motivo_recusa);
                            this.loading = false;
                        }

                    } catch (error) {
                        console.error(error);
                        this.showError('Erro na validação de segurança.');
                        this.loading = false;
                    }
                },

                addItem() {
                    this.form.itens.push({
                        nome: '',
                        categoria: 'alimento',
                        valor_total: 0,
                        quantidade: 1
                    });
                },

                removeItem(index) {
                    this.form.itens.splice(index, 1);
                },

                recalcTotal() {}
            }
        }
    </script>
</body>
</html>