<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditoria - ValeIA</title>
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
            <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Auditoria</h1>
                    <p class="text-slate-500">Registros de atividades do sistema.</p>
                </div>
            </header>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Usuário</th>
                                <th class="px-6 py-4">Log</th>
                                <th class="px-6 py-4">Data e Hora</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($logs as $log)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-medium text-slate-900">#{{ $log->id }}</td>
                                <td class="px-6 py-4">
                                    @if($log->user_id)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $log->companyUser->name ?? 'Usuário Removido' }} (Equipe)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Administrador
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-700">{{ $log->log }}</td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ \Carbon\Carbon::parse($log->created_at)->timezone('America/Sao_Paulo')->format('d/m/Y H:i:s') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-slate-100">
                    {{ $logs->links() }}
                </div>
            </div>
        </main>
    </div>
</body>
</html>