<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - ValeIA</title>
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
            <header class="mb-8">
                <h1 class="text-2xl font-bold text-slate-800">Configurações da Empresa</h1>
                <p class="text-slate-500">Gerencie seus dados de acesso e regras do sistema.</p>
            </header>

            @if(session('success'))
                <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 border border-red-100">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.configuracoes.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                        <h2 class="text-lg font-bold text-slate-800 mb-6 pb-2 border-b border-slate-100">Perfil e Acesso</h2>
                        
                        <div class="flex flex-col items-center mb-6">
                            <div class="relative group">
                                <img id="avatarPreview" src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" class="w-24 h-24 rounded-full border-4 border-slate-100 object-cover">
                                <label for="avatarInput" class="absolute bottom-0 right-0 bg-brand-600 text-white p-2 rounded-full cursor-pointer hover:bg-brand-500 transition shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </label>
                                <input type="file" name="avatar" id="avatarInput" class="hidden" accept="image/*" onchange="previewImage(event)">
                            </div>
                            <p class="text-xs text-slate-400 mt-2">Clique na câmera para alterar</p>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nome da Empresa / Responsável</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500 transition">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">E-mail de Acesso</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500 transition">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">CNPJ / CPF</label>
                                <input type="text" name="document" value="{{ old('document', $user->document) }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500 transition">
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-100">
                            <h3 class="text-sm font-bold text-slate-800 mb-4">Alterar Senha</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Nova Senha (opcional)</label>
                                    <input type="password" name="password" placeholder="Deixe em branco para manter a atual" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500 transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Confirmar Nova Senha</label>
                                    <input type="password" name="password_confirmation" placeholder="Repita a nova senha" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:ring-brand-500 transition">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                            <h2 class="text-lg font-bold text-slate-800 mb-6 pb-2 border-b border-slate-100">Regras de Auditoria</h2>
                            
                            <div class="flex items-start gap-4">
                                <div class="flex items-center h-5 mt-1">
                                    <input id="bebidas" name="bebidas_alcoolicas" type="checkbox" value="1" {{ ($configuracao->permitido['bebidas_alcoolicas'] ?? false) ? 'checked' : '' }} class="w-5 h-5 text-brand-600 border-slate-300 rounded focus:ring-brand-500">
                                </div>
                                <div class="flex-1">
                                    <label for="bebidas" class="font-medium text-slate-900 cursor-pointer">Permitir Bebidas Alcoólicas</label>
                                    <p class="text-slate-500 text-sm mt-1">Se desmarcado, nossa IA irá sinalizar automaticamente notas fiscais que contenham itens alcoólicos como "Reprovadas" ou "Suspeitas".</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-100 rounded-2xl p-6 border border-slate-200">
                            <h3 class="text-sm font-bold text-slate-700 mb-2">Informação Importante</h3>
                            <p class="text-sm text-slate-500">As alterações no perfil refletem imediatamente no login. Certifique-se de usar um e-mail válido para não perder o acesso.</p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-brand-500/30 transition transform hover:-translate-y-0.5">
                                Salvar Alterações
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('avatarPreview');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>