<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Negado - ValeIA</title>
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
<body class="bg-slate-50 font-sans text-slate-600 h-screen flex items-center justify-center">
    <div class="text-center max-w-md p-6">
        <div class="mb-6 flex justify-center">
            <div class="bg-red-100 p-4 rounded-full">
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <h1 class="text-2xl font-bold text-slate-800 mb-2">Acesso Não Autorizado</h1>
        <p class="text-slate-500 mb-8">Você não tem permissão para acessar esta área do sistema.</p>
        <a href="{{ route('dashboard') }}" class="bg-slate-800 text-white px-6 py-3 rounded-lg font-bold hover:bg-slate-700 transition">
            Voltar ao Início
        </a>
    </div>
</body>
</html>