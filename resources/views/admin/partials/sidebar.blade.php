@php
    $user = auth()->user();
    $isCompanyUser = $user instanceof \App\Models\CompanyUser;
    $perms = $user->permissions ?? [];

    $can = function($permission) use ($isCompanyUser, $perms) {
        return !$isCompanyUser || in_array($permission, $perms);
    };

    $adminId = $isCompanyUser ? $user->admin_id : $user->id;

    $notasPendentesCount = \App\Models\Nota::where('admin_id', $adminId)
        ->where('status', 'pendente')
        ->count();

    // Verificação de Plano para API
    $hasApiAccess = false;
    if (!$isCompanyUser) {
        $userPlano = \App\Models\UserPlano::where('admin_id', $user->id)->first();
        if ($userPlano) {
            $plano = \App\Models\Plano::find($userPlano->plano_id);
            if ($plano && is_array($plano->descricao)) {
                foreach ($plano->descricao as $item) {
                    if (str_contains($item, 'API Dedicada')) {
                        $hasApiAccess = true;
                        break;
                    }
                }
            }
        }
    }
@endphp

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 text-white transition-transform duration-300 md:static md:translate-x-0 flex flex-col shadow-2xl">
    
    <div class="h-40 flex items-center justify-center border-b border-slate-800 bg-slate-950/50 p-2 overflow-visible relative">
        <img src="{{ asset('imagens/logo.png') }}" class="h-32 w-auto object-contain brightness-0 invert transform transition hover:scale-105" alt="Logo">
    </div>

    <nav class="flex-1 px-4 py-6 flex flex-col overflow-y-auto no-scrollbar">
        <div class="space-y-2">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Principal</p>
            
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="font-medium">Visão Geral</span>
            </a>

            @if($can('gerenciar_equipe'))
            <a href="{{ route('admin.acessos.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.acessos.*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="font-medium">Acessos e Equipe</span>
            </a>
            @endif

            @if($can('auditoria'))
            <a href="{{ route('admin.auditoria.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.auditoria.*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <span class="font-medium">Auditoria</span>
            </a>
            @endif

            @if($can('aprovar_notas'))
            <a href="{{ route('admin.notas.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.notas.*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="font-medium">Notas Fiscais</span>
                @if($notasPendentesCount > 0)
                    <span class="ml-auto bg-brand-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $notasPendentesCount }}</span>
                @endif
            </a>
            @endif

            @if($can('gerenciar_saldo'))
            <a href="{{ route('admin.funcionarios.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.funcionarios.*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="font-medium">Funcionários</span>
            </a>

            <a href="{{ route('admin.vales.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.vales.*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4zm2 5a2 2 0 111-4.001A2 2 0 019 8z"></path></svg>
                <span class="font-medium">Tipos de Vales</span>
            </a>
            @endif

            @if($can('reembolsar') || $can('visualizar_relatorios'))
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2">Financeiro</p>
            @endif
            
            @if($can('reembolsar'))
            <a href="/admin/pagamentos" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.pagamentos.*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium">Pagamentos</span>
            </a>
            @endif
            
            @if($can('visualizar_relatorios'))
            <a href="/admin/relatorios" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.relatorios.*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="font-medium">Relatórios</span>
            </a>
            @endif
        </div>

        @if(!$isCompanyUser)
        <div class="mt-auto pt-6 border-t border-slate-800">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Conta</p>
            
            <a href="{{ route('admin.configuracoes.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.configuracoes.*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="font-medium">Configurações</span>
            </a>

            @if($hasApiAccess)
            <a href="{{ route('admin.api.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.api.*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                <span class="font-medium">API & Integrações</span>
            </a>
            @endif

            <a href="{{ route('admin.planos.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.planos.*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                <span class="font-medium">Meu Plano</span>
            </a>
        </div>
        @endif
    </nav>

    <div class="p-4 bg-slate-950 border-t border-slate-800">
        <div class="flex items-center gap-3">
            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}" class="w-10 h-10 rounded-full border border-slate-600">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-400 truncate">
                    @if(auth()->user() instanceof \App\Models\CompanyUser)
                        {{ ucfirst(auth()->user()->role) }} • Usuário
                    @else
                        {{ auth()->user()->type ?? 'Admin' }} • Admin
                    @endif
                </p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-red-400 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>
    </div>
</aside>