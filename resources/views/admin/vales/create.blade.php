<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Vale - ValeIA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
<body class="bg-slate-50 font-sans text-slate-600">
    
    <div class="flex h-screen overflow-hidden pt-16 md:pt-0">
        @include('admin.partials.sidebar')

        <main class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-8">
            <header class="flex items-center gap-4 mb-8">
                <a href="{{ route('admin.vales.index') }}" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Novo Tipo de Vale</h1>
                    <p class="text-slate-500">Cadastre um novo benefício para sua empresa.</p>
                </div>
            </header>

            <form action="{{ route('admin.vales.store') }}" method="POST" class="max-w-xl bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nome do Vale</label>
                    <input type="text" name="nome" placeholder="Ex: Auxílio Home Office" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-3 outline-none focus:ring-brand-500 focus:border-brand-500">
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.vales.index') }}" class="px-6 py-3 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 font-medium">Cancelar</a>
                    <button type="submit" class="px-6 py-3 rounded-lg bg-brand-600 text-white hover:bg-brand-800 font-bold shadow-lg shadow-brand-500/20">Salvar Vale</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>