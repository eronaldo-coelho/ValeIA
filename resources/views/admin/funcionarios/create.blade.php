<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Funcionário - ValeIA</title>
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
<body class="bg-slate-50 font-sans text-slate-600" x-data="{ sidebarOpen: false }">

    @include('admin.partials.mobile-header')

    <div class="flex h-screen overflow-hidden pt-16 md:pt-0">
        @include('admin.partials.sidebar')

        <main class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-8" @click="sidebarOpen = false">
            <header class="flex items-center gap-4 mb-8">
                <a href="{{ route('admin.funcionarios.index') }}" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Novo Funcionário</h1>
                    <p class="text-slate-500">Cadastre os dados iniciais.</p>
                </div>
            </header>

            <form action="{{ route('admin.funcionarios.store') }}" method="POST">
                @csrf
                
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 mb-8">
                    <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2 text-lg">
                        <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Dados Pessoais
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nome Completo</label>
                            <input type="text" name="nome" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500 focus:border-brand-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Cargo</label>
                            <input list="cargos" name="cargo" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500 focus:border-brand-500" placeholder="Ex: Vendedor">
                            <datalist id="cargos">
                                @foreach($cargos as $cargo)
                                    <option value="{{ $cargo }}">
                                @endforeach
                            </datalist>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">CPF</label>
                            <input type="text" name="cpf" id="cpfInput" maxlength="14" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500 focus:border-brand-500" oninput="maskCPF(this)">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Data de Nascimento</label>
                            <input type="date" name="data_nascimento" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500 focus:border-brand-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Data de Admissão</label>
                            <input type="date" name="data_admissao" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500 focus:border-brand-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">E-mail (Opcional)</label>
                            <input type="email" name="email" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500 focus:border-brand-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Telefone (Opcional)</label>
                            <input type="text" name="telefone" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 outline-none focus:ring-brand-500 focus:border-brand-500">
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Benefícios Iniciais
                        </h3>
                        <button type="button" onclick="addVale()" class="text-sm bg-slate-100 text-slate-700 px-3 py-1.5 rounded-lg hover:bg-slate-200 font-medium border border-slate-200 transition">
                            + Adicionar Vale
                        </button>
                    </div>

                    <div id="vales-container" class="space-y-4"></div>
                    
                    <p id="no-vales-msg" class="text-slate-400 text-sm italic text-center py-4">Benefícios e Contas Bancárias completas podem ser configurados após salvar.</p>
                </div>

                <div class="flex justify-end gap-3 pb-8">
                    <a href="{{ route('admin.funcionarios.index') }}" class="px-6 py-3 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 font-medium">Cancelar</a>
                    <button type="submit" class="px-6 py-3 rounded-lg bg-brand-600 text-white hover:bg-brand-800 font-bold shadow-lg shadow-brand-500/20">Salvar e Continuar</button>
                </div>
            </form>
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

        const valesDisponiveis = @json($vales);
        let valeIndex = 0;

        function addVale() {
            const container = document.getElementById('vales-container');
            const msg = document.getElementById('no-vales-msg');
            if(msg) msg.style.display = 'none';

            let optionsHtml = '<option value="">Selecione o tipo...</option>';
            valesDisponiveis.forEach(vale => {
                optionsHtml += `<option value="${vale.id}">${vale.nome}</option>`;
            });

            const div = document.createElement('div');
            div.className = 'grid grid-cols-1 md:grid-cols-7 gap-4 items-end p-4 bg-slate-50 rounded-xl border border-slate-100 relative group';
            div.innerHTML = `
                <div class="md:col-span-3">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Tipo de Vale</label>
                    <select name="vales[${valeIndex}][vale_id]" required class="w-full bg-white border border-slate-200 rounded-lg p-2 text-sm outline-none focus:ring-brand-500">
                        ${optionsHtml}
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Valor (R$)</label>
                    <input type="number" step="0.01" name="vales[${valeIndex}][valor]" placeholder="0,00" required class="w-full bg-white border border-slate-200 rounded-lg p-2 text-sm outline-none focus:ring-brand-500">
                </div>
                <div class="md:col-span-2 flex gap-2">
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-slate-500 mb-1">Periodicidade</label>
                        <select name="vales[${valeIndex}][periodicidade]" required class="w-full bg-white border border-slate-200 rounded-lg p-2 text-sm outline-none focus:ring-brand-500">
                            <option value="mensal">Mensal</option>
                            <option value="semanal">Semanal</option>
                            <option value="diario">Diário</option>
                        </select>
                    </div>
                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="bg-red-100 text-red-500 hover:bg-red-200 rounded-lg w-9 h-9 flex items-center justify-center mt-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            `;
            container.appendChild(div);
            valeIndex++;
        }
    </script>
</body>
</html>