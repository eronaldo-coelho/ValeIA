<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acesso | {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    <!-- Assumindo que você usa Vite padrão do Laravel 10. Se usar Mix, troque por mix() -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        /* Auth Slider Animation Logic */
        .auth-container { min-height: 700px; }
        .form-panel { transition: all 0.6s ease-in-out; }
        
        /* Mobile Specifics */
        @media (max-width: 768px) {
            .auth-container { min-height: 800px; }
        }

        /* Overlay Animations */
        .overlay-container {
            transition: transform 0.6s ease-in-out;
            z-index: 100;
        }
        
        .overlay {
            background: linear-gradient(to right, #059669, #064e3b);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 0 0;
            transition: transform 0.6s ease-in-out;
        }
        
        .overlay-panel { transition: transform 0.6s ease-in-out; }

        /* State: Right Panel Active (Register Mode) */
        .right-panel-active .sign-in-container { transform: translateX(100%); opacity: 0; z-index: 1; }
        .right-panel-active .sign-up-container { transform: translateX(100%); opacity: 1; z-index: 5; }
        .right-panel-active .overlay-container { transform: translateX(-100%); }
        .right-panel-active .overlay { transform: translateX(50%); }
        .right-panel-active .overlay-left { transform: translateX(0); }
        .right-panel-active .overlay-right { transform: translateX(20%); }

        /* State: Default (Login Mode) */
        .sign-up-container { opacity: 0; z-index: 1; }
        .sign-in-container { z-index: 2; }
        .overlay-left { transform: translateX(-20%); }
        .overlay { left: -100%; width: 200%; transform: translateX(0); }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-900 antialiased flex justify-center items-center min-h-screen py-10 px-4">

    <!-- Auth Wrapper with Alpine State -->
    <div 
        x-data="authFlow()" 
        class="auth-container bg-white rounded-2xl shadow-2xl relative overflow-hidden w-full max-w-4xl"
        :class="{ 'right-panel-active': isRegistering }"
    >
        
        <!-- ========================================== -->
        <!-- SIGN UP FORM (CADASTRO)                   -->
        <!-- ========================================== -->
        <div class="form-panel sign-up-container absolute top-0 left-0 h-full w-full md:w-1/2 p-8 md:p-12 overflow-y-auto"
             :class="{ 'opacity-100 z-20': isRegistering, 'opacity-0 z-0 md:opacity-0': !isRegistering }">
            
            <form action="{{ route('auth.register') }}" method="POST" class="flex flex-col h-full justify-center">
                @csrf
                
                <div class="text-center mb-6">
                    <img src="{{ asset('imagens/logo.png') }}" alt="Logo" class="h-12 mx-auto mb-4 object-contain">
                    <h1 class="font-bold text-2xl text-slate-800">Criar Conta</h1>
                </div>

                <!-- Google Button -->
                <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center gap-3 bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-slate-600 hover:bg-slate-50 transition font-medium text-sm">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="h-5 w-5" alt="Google">
                    <span>Cadastrar com Google</span>
                </a>

                <div class="relative flex py-2 items-center mb-6">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="flex-shrink-0 mx-4 text-slate-400 text-xs uppercase font-semibold">Ou via e-mail</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                <!-- Type Selector (PF/PJ) -->
                <div class="flex gap-4 mb-4" x-data="{ type: '{{ old('type', 'PF') }}' }">
                    <input type="hidden" name="type" :value="type">
                    
                    <button type="button" @click="type = 'PF'; personType = 'PF'"
                        :class="type === 'PF' ? 'border-brand-500 bg-brand-50 text-brand-700' : 'border-slate-200 text-slate-600 hover:bg-slate-50'"
                        class="flex-1 py-2.5 text-sm font-medium border rounded-lg transition-colors duration-200">
                        Pessoa Física
                    </button>
                    
                    <button type="button" @click="type = 'PJ'; personType = 'PJ'"
                        :class="type === 'PJ' ? 'border-brand-500 bg-brand-50 text-brand-700' : 'border-slate-200 text-slate-600 hover:bg-slate-50'"
                        class="flex-1 py-2.5 text-sm font-medium border rounded-lg transition-colors duration-200">
                        Empresa (PJ)
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="space-y-1">
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Nome Completo" required 
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition @error('name') border-red-500 @enderror" />
                        @error('name') <span class="text-xs text-red-500 ml-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="space-y-1">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="E-mail" required 
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition @error('email') border-red-500 @enderror" />
                        @error('email') <span class="text-xs text-red-500 ml-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="col-span-2 md:col-span-1" :class="{ 'col-span-2': personType === 'PJ' }">
                            <input type="text" name="document" x-model="document" x-on:input="formatDocument" 
                                :placeholder="personType === 'PF' ? 'CPF' : 'CNPJ'" required 
                                :maxlength="personType === 'PF' ? 14 : 18"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition @error('document') border-red-500 @enderror" />
                        </div>
                        
                        <div x-show="personType === 'PF'" class="col-span-2 md:col-span-1">
                            <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                                :required="personType === 'PF'"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm text-slate-600 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition" />
                        </div>
                    </div>
                    @error('document') <span class="text-xs text-red-500 ml-1 block">{{ $message }}</span> @enderror

                    <div class="grid grid-cols-2 gap-3">
                        <input type="password" name="password" placeholder="Senha" required 
                            class="bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition @error('password') border-red-500 @enderror" />
                        <input type="password" name="password_confirmation" placeholder="Confirmar" required 
                            class="bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition" />
                    </div>
                    @error('password') <span class="text-xs text-red-500 ml-1 block">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="mt-8 w-full bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-lg text-sm px-5 py-3.5 shadow-lg shadow-brand-500/20 transition-all transform hover:-translate-y-0.5">
                    Concluir Cadastro
                </button>

                <p class="mt-6 text-center text-sm text-slate-500 md:hidden">
                    Já possui cadastro? <button type="button" @click="toggleMode" class="font-bold text-brand-600">Acessar</button>
                </p>
            </form>
        </div>

        <!-- ========================================== -->
        <!-- SIGN IN FORM (LOGIN)                      -->
        <!-- ========================================== -->
        <div class="form-panel sign-in-container absolute top-0 left-0 h-full w-full md:w-1/2 p-8 md:p-12 flex items-center justify-center"
             :class="{ 'opacity-0 z-0 pointer-events-none md:pointer-events-auto': isRegistering, 'opacity-100 z-20': !isRegistering }">
            
            <form action="{{ route('auth.login') }}" method="POST" class="w-full max-w-sm flex flex-col">
                @csrf
                
                <div class="text-center mb-8">
                    <img src="{{ asset('imagens/logo.png') }}" alt="ValeIA" class="h-12 mx-auto mb-4 object-contain">
                    <h1 class="font-bold text-2xl text-slate-800">Bem-vindo</h1>
                    <p class="text-slate-400 text-sm mt-1">Acesse seu painel financeiro</p>
                </div>

                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-100 text-red-600 text-sm">
                        <span class="font-semibold">Atenção:</span> Verifique suas credenciais.
                    </div>
                @endif

                <!-- Google Button -->
                <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center gap-3 bg-white border border-slate-200 rounded-lg px-4 py-2.5 text-slate-600 hover:bg-slate-50 transition font-medium text-sm">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="h-5 w-5" alt="Google">
                    <span>Entrar com Google</span>
                </a>

                <div class="relative flex py-2 items-center mb-6">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="flex-shrink-0 mx-4 text-slate-400 text-xs uppercase font-semibold">Ou via credenciais</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                <div class="space-y-4">
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="E-mail" required autofocus
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition" />
                    
                    <div class="relative">
                        <input type="password" name="password" placeholder="Senha" required 
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition" />
                        <a href="/esqueceu-senha" class="absolute right-3 top-3.5 text-xs font-medium text-brand-600 hover:text-brand-700">Esqueceu?</a>
                    </div>
                </div>

                <button type="submit" class="mt-8 w-full bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-lg text-sm px-5 py-3.5 shadow-lg shadow-brand-500/20 transition-all transform hover:-translate-y-0.5">
                    Entrar
                </button>

                <p class="mt-8 text-center text-sm text-slate-500 md:hidden">
                    Não tem conta? <button type="button" @click="toggleMode" class="font-bold text-brand-600">Criar agora</button>
                </p>
            </form>
        </div>

        <!-- ========================================== -->
        <!-- DESKTOP OVERLAY (PARTE VERDE ANIMADA)     -->
        <!-- ========================================== -->
        <div class="overlay-container absolute top-0 left-1/2 w-1/2 h-full overflow-hidden hidden md:block">
            <div class="overlay relative h-full text-white">
                
                <!-- Left Panel (Offers Login) -->
                <div class="overlay-panel overlay-left absolute flex flex-col items-center justify-center text-center top-0 h-full w-1/2 px-10">
                    <h2 class="text-3xl font-bold mb-4">Já é cliente?</h2>
                    <p class="mb-8 text-brand-100 font-light leading-relaxed">Conecte-se novamente para gerenciar seus dados e visualizar seus relatórios.</p>
                    <button @click="isRegistering = false" class="border border-white bg-transparent text-white rounded-full px-8 py-3 font-semibold hover:bg-white hover:text-brand-900 transition-colors duration-300">
                        Fazer Login
                    </button>
                </div>
                
                <!-- Right Panel (Offers Register) -->
                <div class="overlay-panel overlay-right absolute right-0 flex flex-col items-center justify-center text-center top-0 h-full w-1/2 px-10">
                    <h2 class="text-3xl font-bold mb-4">Novo por aqui?</h2>
                    <p class="mb-8 text-brand-100 font-light leading-relaxed">Comece a organizar os gastos da sua empresa hoje mesmo com nossa plataforma.</p>
                    <button @click="isRegistering = true" class="bg-white text-brand-900 rounded-full px-8 py-3 font-semibold shadow-lg hover:bg-brand-50 transition-colors duration-300">
                        Criar Conta
                    </button>
                </div>

            </div>
        </div>

    </div>

    <!-- Logic Component -->
    <script>
        function authFlow() {
            return {
                // Se houver erro de validação no registro ou se o usuário tentou registrar antes,
                // a tela já carrega no modo de registro.
                isRegistering: {{ $errors->has('name') || $errors->has('document') || old('type') ? 'true' : 'false' }},
                
                personType: '{{ old('type', 'PF') }}',
                document: '{{ old('document') }}',
                
                toggleMode() {
                    this.isRegistering = !this.isRegistering;
                },

                // Lógica de máscara CPF/CNPJ usando Regex puro
                formatDocument(e) {
                    let value = e.target.value.replace(/\D/g, "");
                    
                    if (this.personType === 'PJ') {
                        if (value.length > 14) value = value.slice(0, 14);
                        value = value.replace(/^(\d{2})(\d)/, "$1.$2")
                                     .replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3")
                                     .replace(/\.(\d{3})(\d)/, ".$1/$2")
                                     .replace(/(\d{4})(\d)/, "$1-$2");
                    } else {
                        if (value.length > 11) value = value.slice(0, 11);
                        value = value.replace(/(\d{3})(\d)/, "$1.$2")
                                     .replace(/(\d{3})(\d)/, "$1.$2")
                                     .replace(/(\d{3})(\d{1,2})$/, "$1-$2");
                    }
                    this.document = value;
                }
            }
        }
    </script>
</body>
</html>