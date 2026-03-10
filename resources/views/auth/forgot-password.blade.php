<!-- resources/views/auth/forgot-password.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - ValeIA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { brand: { 500: '#10b981', 600: '#059669', 900: '#064e3b' } }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-100 flex justify-center items-center min-h-screen p-4">
    <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Recuperar Senha</h1>
            <p class="text-slate-500 text-sm mt-2">Informe seu e-mail para receber o código de acesso.</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-4 rounded-lg text-sm mb-6 border border-red-100">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-slate-700">E-mail Corporativo</label>
                <input type="email" name="email" required class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block w-full p-3 outline-none transition" placeholder="nome@empresa.com.br">
            </div>

            <button type="submit" class="w-full text-white bg-brand-600 hover:bg-brand-900 font-bold rounded-lg text-sm px-5 py-3.5 text-center transition shadow-lg shadow-brand-500/30">
                Enviar Código
            </button>
            
            <a href="{{ route('login') }}" class="block text-center mt-6 text-sm text-slate-500 hover:text-brand-600 font-medium">Voltar para o Login</a>
        </form>
    </div>
</body>
</html>