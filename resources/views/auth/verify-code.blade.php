<!-- resources/views/auth/verify-code.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Código - ValeIA</title>
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
            <h1 class="text-2xl font-bold text-slate-800">Verificar Código</h1>
            <p class="text-slate-500 text-sm mt-2">Enviamos um código de 6 dígitos para <br><strong>{{ request('email') }}</strong></p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-4 rounded-lg text-sm mb-6 text-center border border-red-100">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('password.verify.store') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ request('email') }}">
            
            <div class="mb-8 flex justify-center">
                <input type="text" name="code" maxlength="6" inputmode="numeric" required 
                       class="text-center text-3xl tracking-[10px] w-full p-4 border border-slate-300 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none font-mono text-slate-800 placeholder-slate-200" 
                       placeholder="000000" 
                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            </div>

            <button type="submit" class="w-full text-white bg-brand-600 hover:bg-brand-900 font-bold rounded-lg text-sm px-5 py-3.5 text-center transition shadow-lg shadow-brand-500/30">
                Verificar Código
            </button>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-slate-500">Não recebeu o código?</p>
                <a href="{{ route('password.request') }}" class="text-sm font-bold text-brand-600 hover:underline">Enviar novamente</a>
            </div>
        </form>
    </div>
</body>
</html>