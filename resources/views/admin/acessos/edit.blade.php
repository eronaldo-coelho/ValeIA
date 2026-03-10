@php
    $currentUser = auth()->user();
    $canGrant = function($perm) use ($currentUser) {
        if ($currentUser instanceof \App\Models\User) return true;
        return in_array($perm, $currentUser->permissions ?? []);
    };
@endphp

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário - ValeIA</title>
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
                <a href="{{ route('admin.acessos.index') }}" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Editar Usuário</h1>
                    <p class="text-slate-500">Alterar dados e permissões de {{ $user->name }}.</p>
                </div>
            </header>

            <form action="{{ route('admin.acessos.update', $user->id) }}" method="POST" class="max-w-4xl">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 h-fit">
                        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Dados Pessoais
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nome Completo</label>
                                <input type="text" name="name" value="{{ $user->name }}" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 focus:ring-brand-500 focus:border-brand-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">E-mail Corporativo</label>
                                <input type="email" name="email" value="{{ $user->email }}" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 focus:ring-brand-500 focus:border-brand-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nova Senha (Opcional)</label>
                                <input type="password" name="password" placeholder="Deixe em branco para manter a atual" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 focus:ring-brand-500 focus:border-brand-500 outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 h-fit">
                        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11.536 19.336a1.992 1.992 0 01-1.002.574l-4.204 1.202a.5.5 0 01-.63-.63l1.201-4.204a1.993 1.993 0 01.574-1.002l4.608-2.607M12.5 7a6.602 6.602 0 016.6 6.6M15 10a3 3 0 11-6 0 3 3 0 016 0zm-9.165 5.57a6 6 0 115.165 2.83"></path></svg>
                            Controle de Acesso
                        </h3>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Selecione o Cargo</label>
                            <select name="role" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 focus:ring-brand-500 focus:border-brand-500 outline-none">
                                <option value="funcionario" {{ $user->role == 'funcionario' ? 'selected' : '' }}>Funcionário (Básico)</option>
                                <option value="rh" {{ $user->role == 'rh' ? 'selected' : '' }}>Recursos Humanos (RH)</option>
                                <option value="financeiro" {{ $user->role == 'financeiro' ? 'selected' : '' }}>Financeiro</option>
                                <option value="gestor" {{ $user->role == 'gestor' ? 'selected' : '' }}>Gestor de Setor</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-3">Permissões Adicionais</label>
                            <div class="space-y-2">
                                @php $perms = $user->permissions ?? []; @endphp
                                
                                @if($canGrant('aprovar_notas'))
                                <label class="flex items-center gap-3 p-3 border border-slate-100 rounded-lg hover:bg-slate-50 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="aprovar_notas" {{ in_array('aprovar_notas', $perms) ? 'checked' : '' }} class="w-4 h-4 text-brand-600 rounded focus:ring-brand-500">
                                    <span class="text-sm text-slate-600">Aprovar Notas Fiscais</span>
                                </label>
                                @endif

                                @if($canGrant('visualizar_relatorios'))
                                <label class="flex items-center gap-3 p-3 border border-slate-100 rounded-lg hover:bg-slate-50 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="visualizar_relatorios" {{ in_array('visualizar_relatorios', $perms) ? 'checked' : '' }} class="w-4 h-4 text-brand-600 rounded focus:ring-brand-500">
                                    <span class="text-sm text-slate-600">Visualizar Relatórios</span>
                                </label>
                                @endif

                                @if($canGrant('gerenciar_saldo'))
                                <label class="flex items-center gap-3 p-3 border border-slate-100 rounded-lg hover:bg-slate-50 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="gerenciar_saldo" {{ in_array('gerenciar_saldo', $perms) ? 'checked' : '' }} class="w-4 h-4 text-brand-600 rounded focus:ring-brand-500">
                                    <span class="text-sm text-slate-600">Gerenciar Funcionarios/Vales</span>
                                </label>
                                @endif

                                @if($canGrant('reembolsar'))
                                <label class="flex items-center gap-3 p-3 border border-slate-100 rounded-lg hover:bg-slate-50 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="reembolsar" {{ in_array('reembolsar', $perms) ? 'checked' : '' }} class="w-4 h-4 text-brand-600 rounded focus:ring-brand-500">
                                    <span class="text-sm text-slate-600">Reembolsar Funcionarios</span>
                                </label>
                                @endif

                                @if($canGrant('auditoria'))
                                <label class="flex items-center gap-3 p-3 border border-slate-100 rounded-lg hover:bg-slate-50 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="auditoria" {{ in_array('auditoria', $perms) ? 'checked' : '' }} class="w-4 h-4 text-brand-600 rounded focus:ring-brand-500">
                                    <span class="text-sm text-slate-600">Acesso à Auditoria</span>
                                </label>
                                @endif

                                @if($canGrant('gerenciar_equipe'))
                                <label class="flex items-center gap-3 p-3 border border-slate-100 rounded-lg hover:bg-slate-50 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="gerenciar_equipe" {{ in_array('gerenciar_equipe', $perms) ? 'checked' : '' }} class="w-4 h-4 text-brand-600 rounded focus:ring-brand-500">
                                    <span class="text-sm text-slate-600">Gerenciar Equipe</span>
                                </label>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <a href="{{ route('admin.acessos.index') }}" class="px-6 py-3 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 font-medium">Cancelar</a>
                    <button type="submit" class="px-6 py-3 rounded-lg bg-brand-600 text-white hover:bg-brand-800 font-bold shadow-lg shadow-brand-500/20">Atualizar Usuário</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>