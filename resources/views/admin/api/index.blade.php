<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credenciais API - ValeIA</title>
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
                <h1 class="text-2xl font-bold text-slate-800">API & Integrações</h1>
                <p class="text-slate-500">Gerencie as credenciais para integração com sistemas externos.</p>
            </header>

            @if(session('success'))
                <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-brand-100 rounded-xl">
                        <svg class="w-8 h-8 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Documentação da API</h2>
                        <p class="text-slate-500 text-sm mt-1">Utilize nossa API RESTful para integrar o ValeIA ao seu ERP, sistema de RH ou aplicativos personalizados. O acesso é autenticado via Bearer Token.</p>
                        <a href="/docs" class="inline-block mt-3 text-brand-600 font-bold text-sm hover:underline">Ver Documentação Completa →</a>
                    </div>
                </div>
            </div>

            @if($credential)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-slate-800">Token Ativo</h3>
                        <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-bold uppercase">Ativo</span>
                    </div>

                    <div class="bg-slate-900 rounded-xl p-4 mb-6 relative group">
                        <code class="text-brand-400 font-mono text-sm break-all" id="apiToken">{{ $credential->token }}</code>
                        <button onclick="copyToken()" class="absolute top-2 right-2 bg-white/10 hover:bg-white/20 text-white p-2 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        </button>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-sm font-bold text-slate-700 mb-3">Permissões Habilitadas:</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($credential->permissoes as $perm)
                                <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-lg text-xs font-medium border border-slate-200">
                                    {{ $permissoesDisponiveis[$perm] ?? $perm }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-6">
                        <form action="{{ route('admin.api.destroy', $credential->id) }}" method="POST" onsubmit="return confirm('Tem certeza? O sistema externo perderá acesso imediatamente.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 text-sm font-bold hover:text-red-800 transition">Revogar Token</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Gerar Nova Credencial</h3>
                    <form action="{{ route('admin.api.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 mb-3">Selecione as permissões:</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($permissoesDisponiveis as $key => $label)
                                    <label class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition">
                                        <input type="checkbox" name="permissoes[]" value="{{ $key }}" class="w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-500">
                                        <span class="ml-3 text-sm text-slate-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-brand-500/30 transition">
                            Gerar Token de Acesso
                        </button>
                    </form>
                </div>
            @endif
        </main>
    </div>

    <script>
        function copyToken() {
            var copyText = document.getElementById("apiToken").innerText;
            navigator.clipboard.writeText(copyText);
            alert("Token copiado para a área de transferência!");
        }
    </script>
</body>
</html>