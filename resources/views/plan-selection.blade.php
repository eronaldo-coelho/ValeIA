<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolher Plano - ValeIA</title>
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
<body class="bg-slate-900 min-h-screen py-10 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">Escolha o plano ideal</h1>
            <p class="text-brand-400 text-lg">Comece com 15 dias totalmente grátis. Não precisa de cartão de crédito.</p>
        </div>

        <form action="{{ route('plan.store') }}" method="POST" id="planForm">
            @csrf
            <input type="hidden" name="plano_id" id="plano_id_input">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($planos as $plano)
                    @php
                        $precoFinal = $plano->valor;
                        if($plano->desconto > 0) {
                            $precoFinal = $plano->valor - ($plano->valor * ($plano->desconto / 100));
                        }
                    @endphp

                    <div class="bg-slate-800 rounded-3xl p-8 border hover:border-brand-500 transition cursor-pointer group relative flex flex-col {{ $loop->iteration == 2 ? 'border-brand-500 shadow-2xl shadow-brand-900/50' : 'border-slate-700' }}" onclick="selectPlan({{ $plano->id }})">
                        @if($loop->iteration == 2)
                            <div class="absolute top-0 right-0 left-0 mx-auto w-fit -mt-4 bg-brand-500 text-white text-xs font-bold px-4 py-1 rounded-full uppercase tracking-wide">Recomendado</div>
                        @endif
                        
                        <h3 class="text-xl font-bold text-white">{{ $plano->nome }}</h3>
                        
                        <div class="my-6">
                            <span class="text-4xl font-extrabold text-white">R$ {{ number_format($precoFinal, 0, ',', '.') }}</span>
                            <span class="text-slate-400">/mês</span>
                        </div>

                        <ul class="space-y-4 mb-8 flex-1">
                            @foreach($plano->descricao as $item)
                                <li class="flex text-slate-300"><span class="text-brand-500 mr-3">✓</span> {{ $item }}</li>
                            @endforeach
                        </ul>

                        <button type="button" class="w-full py-4 rounded-xl font-bold transition {{ $loop->iteration == 2 ? 'bg-brand-500 text-white hover:bg-brand-400' : 'bg-slate-700 text-white hover:bg-slate-600' }}">
                            Selecionar e Testar Grátis
                        </button>
                    </div>
                @endforeach
            </div>
        </form>
    </div>

    <script>
        function selectPlan(id) {
            document.getElementById('plano_id_input').value = id;
            document.getElementById('planForm').submit();
        }
    </script>
</body>
</html>