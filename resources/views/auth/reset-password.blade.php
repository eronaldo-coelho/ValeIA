<!-- resources/views/auth/reset-password.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Senha - ValeIA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            <h1 class="text-2xl font-bold text-slate-800">Criar Nova Senha</h1>
            <p class="text-slate-500 text-sm mt-2">Escolha uma senha forte para sua conta.</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-4 rounded-lg text-sm mb-6 border border-red-100">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block mb-2 text-sm font-medium text-slate-700">Nova Senha</label>
                    <input type="password" name="password" required minlength="8" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block w-full p-3 outline-none transition" placeholder="••••••••">
                </div>
                
                <div>
                    <label class="block mb-2 text-sm font-medium text-slate-700">Confirmar Nova Senha</label>
                    <input type="password" name="password_confirmation" required class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block w-full p-3 outline-none transition" placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="w-full text-white bg-brand-600 hover:bg-brand-900 font-bold rounded-lg text-sm px-5 py-3.5 text-center transition shadow-lg shadow-brand-500/30">
                Redefinir Senha
            </button>
        </form>
    </div>
</body>
</html>