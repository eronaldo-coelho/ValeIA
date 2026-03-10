<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Funcionário - ValeIA</title>
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
<body class="bg-slate-50 font-sans text-slate-600" x-data="{ sidebarOpen: false, modalContaOpen: false }">

    @include('admin.partials.mobile-header')

    <div class="flex h-screen overflow-hidden pt-16 md:pt-0">
        @include('admin.partials.sidebar')

        <main class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-8" @click="sidebarOpen = false">
            
            <header class="flex items-center gap-4 mb-8">
                <a href="{{ route('admin.funcionarios.index') }}" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Editar Funcionário</h1>
                    <p class="text-slate-500">Dados de {{ $funcionario->nome }}</p>
                </div>
            </header>

            @if(session('success'))
                <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-8">
                    <!-- DADOS PESSOAIS -->
                    <form action="{{ route('admin.funcionarios.update', $funcionario->id) }}" method="POST" class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        @csrf
                        @method('POST')
                        <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Dados Pessoais
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nome Completo</label>
                                <input type="text" name="nome" value="{{ $funcionario->nome }}" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500 focus:border-brand-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Cargo</label>
                                <input list="cargos" name="cargo" value="{{ $funcionario->cargo }}" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500 focus:border-brand-500">
                                <datalist id="cargos">
                                    @foreach($cargos as $cargo)
                                        <option value="{{ $cargo }}">
                                    @endforeach
                                </datalist>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">CPF</label>
                                <input type="text" name="cpf" id="cpfInput" value="{{ $funcionario->cpf }}" maxlength="14" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500 focus:border-brand-500" oninput="maskCPF(this)">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Data de Nascimento</label>
                                <input type="date" name="data_nascimento" value="{{ $funcionario->data_nascimento->format('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500 focus:border-brand-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Data de Admissão</label>
                                <input type="date" name="data_admissao" value="{{ $funcionario->data_admissao ? $funcionario->data_admissao->format('Y-m-d') : '' }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500 focus:border-brand-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">E-mail (Opcional)</label>
                                <input type="email" name="email" value="{{ $funcionario->email }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500 focus:border-brand-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Telefone (Opcional)</label>
                                <input type="text" name="telefone" value="{{ $funcionario->telefone }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500 focus:border-brand-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                                <select name="ativo" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500 focus:border-brand-500">
                                    <option value="1" {{ $funcionario->ativo ? 'selected' : '' }}>Ativo</option>
                                    <option value="0" {{ !$funcionario->ativo ? 'selected' : '' }}>Inativo</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <button type="submit" class="px-6 py-3 rounded-lg bg-brand-600 text-white hover:bg-brand-800 font-bold shadow-lg shadow-brand-500/20">Salvar Dados Pessoais</button>
                        </div>
                    </form>

                    <!-- CONTAS BANCÁRIAS -->
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                Contas Bancárias
                            </h3>
                            <button @click="modalContaOpen = true" class="text-sm bg-slate-100 text-slate-700 px-3 py-1.5 rounded-lg hover:bg-slate-200 font-medium border border-slate-200 transition">
                                + Nova Conta
                            </button>
                        </div>

                        @if($funcionario->contas->isEmpty())
                            <p class="text-slate-400 text-sm text-center py-4 bg-slate-50 rounded-xl border border-dashed border-slate-200">Nenhuma conta cadastrada.</p>
                        @else
                            <div class="grid gap-4">
                                @foreach($funcionario->contas as $conta)
                                    <div class="p-4 rounded-xl border {{ $conta->principal ? 'border-brand-500 bg-brand-50' : 'border-slate-200 bg-slate-50' }} relative group">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="font-bold text-slate-800 uppercase">{{ $conta->tipo_pagamento }}</span>
                                                    @if($conta->principal)
                                                        <span class="text-[10px] bg-brand-600 text-white px-2 py-0.5 rounded-full font-bold">Principal</span>
                                                    @else
                                                        <form action="{{ route('admin.funcionarios.contas.principal', $conta->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button class="text-[10px] text-slate-400 hover:text-brand-600 font-bold underline">Definir Principal</button>
                                                        </form>
                                                    @endif
                                                </div>
                                                
                                                @if($conta->tipo_pagamento == 'pix')
                                                    <p class="text-sm text-slate-600">Chave: <span class="font-mono text-slate-800">{{ $conta->chave_pix }}</span> ({{ $conta->tipo_chave_pix }})</p>
                                                @else
                                                    <p class="text-sm text-slate-600">{{ $conta->banco }}</p>
                                                    <p class="text-xs text-slate-500">Ag: {{ $conta->agencia }} | Cc: {{ $conta->conta }} | {{ ucfirst($conta->tipo_conta) }}</p>
                                                @endif

                                                <p class="text-xs text-slate-400 mt-2">
                                                    Pagamento: {{ ucfirst($conta->frequencia_pagamento) }}
                                                    @if($conta->frequencia_pagamento == 'mensal') (Dia {{ $conta->dia_pagamento }})
                                                    @elseif($conta->frequencia_pagamento == 'semanal') ({{ ucfirst($conta->dia_semana) }})
                                                    @endif
                                                </p>
                                            </div>
                                            <form action="{{ route('admin.funcionarios.contas.destroy', $conta->id) }}" method="POST" onsubmit="return confirm('Remover conta?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-slate-300 hover:text-red-500 p-1">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- ADICIONAR VALE -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Novo Benefício
                        </h3>
                        <form action="{{ route('admin.funcionarios.vales.store', $funcionario->id) }}" method="POST">
                            @csrf
                            <div class="space-y-3">
                                <label class="block text-xs font-medium text-slate-500">Tipo de Benefício</label>
                                <select name="vale_id" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500">
                                    <option value="">Selecione...</option>
                                    @foreach($vales as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->nome }}</option>
                                    @endforeach
                                </select>

                                <input type="number" step="0.01" name="valor" placeholder="Valor (R$)" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500">
                                <select name="periodicidade" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500">
                                    <option value="mensal">Mensal</option>
                                    <option value="semanal">Semanal</option>
                                    <option value="diario">Diário</option>
                                </select>
                                <button type="submit" class="w-full bg-slate-800 text-white py-2.5 rounded-lg text-sm font-bold hover:bg-slate-700">Adicionar Vale</button>
                            </div>
                        </form>
                    </div>

                    <!-- LISTA DE VALES (EDITÁVEIS) -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="font-bold text-slate-800 mb-4">Benefícios Ativos</h3>
                        @if($funcionario->vales->isEmpty())
                            <p class="text-sm text-slate-400 text-center py-4">Nenhum vale cadastrado.</p>
                        @else
                            <div class="space-y-4">
                                @foreach($funcionario->vales as $vale)
                                <form action="{{ route('admin.funcionarios.vales.update', $vale->id) }}" method="POST" class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                    @csrf
                                    @method('PUT')
                                    <div class="flex justify-between items-center mb-2">
                                        <p class="font-bold text-slate-700 text-sm">{{ $vale->tipo->nome ?? 'Desconhecido' }}</p>
                                        <button type="submit" form="delete-vale-{{ $vale->id }}" class="text-slate-300 hover:text-red-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                    <div class="flex gap-2">
                                        <input type="number" step="0.01" name="valor" value="{{ $vale->valor }}" class="w-1/2 bg-white border border-slate-200 rounded px-2 py-1 text-xs outline-none focus:border-brand-500">
                                        <select name="periodicidade" class="w-1/2 bg-white border border-slate-200 rounded px-2 py-1 text-xs outline-none focus:border-brand-500">
                                            <option value="mensal" {{ $vale->periodicidade == 'mensal' ? 'selected' : '' }}>Mensal</option>
                                            <option value="semanal" {{ $vale->periodicidade == 'semanal' ? 'selected' : '' }}>Semanal</option>
                                            <option value="diario" {{ $vale->periodicidade == 'diario' ? 'selected' : '' }}>Diário</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="w-full mt-2 bg-brand-100 text-brand-700 text-[10px] font-bold py-1 rounded hover:bg-brand-200 uppercase">Atualizar</button>
                                </form>
                                @endforeach
                            </div>
                            <!-- Forms ocultos para delete -->
                            @foreach($funcionario->vales as $vale)
                                <form id="delete-vale-{{ $vale->id }}" action="{{ route('admin.funcionarios.vales.destroy', $vale->id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- MODAL NOVA CONTA -->
            <div x-show="modalContaOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" @click="modalContaOpen = false"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                    
                    <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                        <form action="{{ route('admin.funcionarios.contas.store', $funcionario->id) }}" method="POST" x-data="{ tipo: 'pix', freq: 'mensal' }">
                            @csrf
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-lg font-bold text-slate-800 mb-4">Nova Conta Bancária</h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-500 mb-1">Método de Pagamento</label>
                                        <select name="tipo_pagamento" x-model="tipo" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500">
                                            <option value="pix">Pix</option>
                                            <option value="ted">TED</option>
                                            <option value="doc">DOC</option>
                                            <option value="transferencia">Transferência</option>
                                        </select>
                                    </div>

                                    <!-- Campos PIX -->
                                    <div x-show="tipo === 'pix'">
                                        <label class="block text-xs font-medium text-slate-500 mb-1">Tipo de Chave</label>
                                        <select name="tipo_chave_pix" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500 mb-3">
                                            <option value="cpf">CPF/CNPJ</option>
                                            <option value="email">E-mail</option>
                                            <option value="telefone">Telefone</option>
                                            <option value="aleatoria">Chave Aleatória</option>
                                        </select>
                                        <label class="block text-xs font-medium text-slate-500 mb-1">Chave Pix</label>
                                        <input type="text" name="chave_pix" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500">
                                    </div>

                                    <!-- Campos Bancários -->
                                    <div x-show="tipo !== 'pix'" class="grid grid-cols-2 gap-3">
                                        <div class="col-span-2">
                                            <label class="block text-xs font-medium text-slate-500 mb-1">Banco</label>
                                            <input type="text" name="banco" placeholder="Ex: Nubank, Itaú..." class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-500 mb-1">Agência</label>
                                            <input type="text" name="agencia" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-500 mb-1">Conta</label>
                                            <input type="text" name="conta" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-xs font-medium text-slate-500 mb-1">Tipo de Conta</label>
                                            <select name="tipo_conta" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500">
                                                <option value="corrente">Corrente</option>
                                                <option value="poupanca">Poupança</option>
                                                <option value="salario">Salário</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="pt-4 border-t border-slate-100">
                                        <label class="block text-xs font-medium text-slate-500 mb-1">Frequência de Pagamento</label>
                                        <select name="frequencia_pagamento" x-model="freq" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500 mb-3">
                                            <option value="mensal">Mensal</option>
                                            <option value="semanal">Semanal</option>
                                            <option value="diario">Diário</option>
                                        </select>

                                        <div x-show="freq === 'mensal'">
                                            <label class="block text-xs font-medium text-slate-500 mb-1">Dia do Mês</label>
                                            <input type="number" name="dia_pagamento" min="1" max="31" placeholder="Ex: 5" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500">
                                        </div>

                                        <div x-show="freq === 'semanal'">
                                            <label class="block text-xs font-medium text-slate-500 mb-1">Dia da Semana</label>
                                            <select name="dia_semana" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500">
                                                <option value="segunda">Segunda-feira</option>
                                                <option value="terca">Terça-feira</option>
                                                <option value="quarta">Quarta-feira</option>
                                                <option value="quinta">Quinta-feira</option>
                                                <option value="sexta">Sexta-feira</option>
                                                <option value="sabado">Sábado</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-brand-600 text-base font-medium text-white hover:bg-brand-700 sm:ml-3 sm:w-auto sm:text-sm">Salvar Conta</button>
                                <button type="button" @click="modalContaOpen = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <script>
        function maskCPF(input) {
            let value = input.value.replace(/\D/g, "");
            if (value.length > 11) value = value.slice(0, 11);
            value = value.replace(/(\d{3})(\d)/, "$1.$2");
            value = value.replace(/(\d{3})(\d)/, "$1.$2");
            value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
            input.value = value;
        }
    </script>
</body>
</html>