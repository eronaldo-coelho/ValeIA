<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completar Cadastro - ValeIA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { 500: '#10b981', 600: '#059669', 900: '#064e3b' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-100 flex justify-center items-center min-h-screen py-10 px-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <img src="{{ asset('imagens/logo.png') }}" alt="ValeIA" class="h-20 mx-auto mb-4 object-contain">
            <h1 class="text-2xl font-bold text-slate-800">Quase lá!</h1>
            <p class="text-slate-500 mt-2">Precisamos de alguns dados para configurar sua conta.</p>
        </div>

        <form action="{{ route('auth.complete.store') }}" method="POST">
            @csrf
            
            @if(session('google_new_user'))
            <div class="mb-6 bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                <p class="text-sm text-yellow-800 font-medium">Defina uma senha para sua conta.</p>
            </div>
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nova Senha</label>
                    <input type="password" name="password" required class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block w-full p-3 outline-none transition" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Confirmar Senha</label>
                    <input type="password" name="password_confirmation" required class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block w-full p-3 outline-none transition" />
                </div>
            </div>
            @endif

            <div class="flex gap-4 mb-6">
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="type" value="PF" class="peer sr-only" checked onchange="togglePersonType('PF')">
                    <div class="text-center py-3 rounded-lg border border-slate-200 peer-checked:border-brand-500 peer-checked:text-brand-600 peer-checked:bg-brand-50 text-sm font-medium transition">
                        Pessoa Física
                    </div>
                </label>
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="type" value="PJ" class="peer sr-only" onchange="togglePersonType('PJ')">
                    <div class="text-center py-3 rounded-lg border border-slate-200 peer-checked:border-brand-500 peer-checked:text-brand-600 peer-checked:bg-brand-50 text-sm font-medium transition">
                        Empresa (PJ)
                    </div>
                </label>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Documento</label>
                    <input type="text" name="document" id="documentInput" placeholder="CPF" required oninput="maskDocument(this)" maxlength="14" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block w-full p-3 outline-none transition" />
                </div>

                <div id="birthDateContainer">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Data de Nascimento</label>
                    <input type="date" name="birth_date" id="birthDateInput" required class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block w-full p-3 outline-none transition text-slate-500" />
                </div>
            </div>

            @if ($errors->any())
                <div class="mt-4 bg-red-50 text-red-600 p-3 rounded-lg text-sm border border-red-100">
                    <ul>@foreach ($errors->all() as $error) <li>• {{ $error }}</li> @endforeach</ul>
                </div>
            @endif

            <button type="submit" class="mt-8 w-full text-white bg-brand-600 hover:bg-brand-900 font-bold rounded-lg text-sm px-5 py-4 text-center transition shadow-lg shadow-brand-500/30">
                Continuar
            </button>
        </form>
    </div>

    <script>
        function togglePersonType(type) {
            const docInput = document.getElementById('documentInput');
            const birthContainer = document.getElementById('birthDateContainer');
            const birthInput = document.getElementById('birthDateInput');
            
            if (type === 'PF') {
                docInput.placeholder = "CPF (000.000.000-00)";
                docInput.setAttribute('maxlength', '14');
                birthContainer.style.display = 'block';
                birthInput.setAttribute('required', 'required');
            } else {
                docInput.placeholder = "CNPJ (00.000.000/0000-00)";
                docInput.setAttribute('maxlength', '18');
                birthContainer.style.display = 'none';
                birthInput.removeAttribute('required');
                docInput.value = '';
            }
        }

        function maskDocument(input) {
            let value = input.value.replace(/\D/g, "");
            const isPJ = document.querySelector('input[name="type"]:checked').value === 'PJ';

            if (isPJ) {
                if (value.length > 14) value = value.slice(0, 14);
                value = value.replace(/^(\d{2})(\d)/, "$1.$2");
                value = value.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
                value = value.replace(/\.(\d{3})(\d)/, ".$1/$2");
                value = value.replace(/(\d{4})(\d)/, "$1-$2");
            } else {
                if (value.length > 11) value = value.slice(0, 11);
                value = value.replace(/(\d{3})(\d)/, "$1.$2");
                value = value.replace(/(\d{3})(\d)/, "$1.$2");
                value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
            }
            input.value = value;
        }
    </script>
</body>
</html>