<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Nota - ValeIA</title>
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
            <header class="flex items-center gap-4 mb-6">
                <a href="{{ route('admin.notas.index') }}" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Nota #NB-{{ $nota->id }}</h1>
                    <p class="text-slate-500">Enviado por {{ $nota->funcionario->nome }} • {{ $nota->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="ml-auto">
                    @if($nota->status == 'aprovado')
                        <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-bold">Aprovado</span>
                    @elseif($nota->status == 'reprovado')
                        <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-bold">Rejeitado</span>
                    @else
                        <span class="bg-orange-100 text-orange-700 px-4 py-2 rounded-full text-sm font-bold">Pendente</span>
                    @endif
                </div>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 h-full">
                
                <div class="bg-slate-900 rounded-2xl flex items-center justify-center overflow-hidden shadow-lg border border-slate-800 h-[80vh]">
                    @if($nota->imagem)
                        <img src="{{ Str::startsWith($nota->imagem, 'data:image') ? $nota->imagem : 'data:image/jpeg;base64,'.$nota->imagem }}" class="max-w-full max-h-full object-contain" alt="Comprovante">
                    @else
                        <p class="text-slate-500">Imagem não disponível</p>
                    @endif
                </div>

                <div class="flex flex-col gap-6 overflow-y-auto h-[80vh] pr-2">
                    
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Dados Extraídos (IA)</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-xs text-slate-500">Estabelecimento</p>
                                <p class="font-medium text-slate-900">{{ $nota->estabelecimento ?? 'Não identificado' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">CNPJ</p>
                                <p class="font-medium text-slate-900">{{ $nota->cnpj_cpf_emitente ?? 'Não identificado' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Data Compra</p>
                                <p class="font-medium text-slate-900">{{ $nota->data_emissao ? $nota->data_emissao->format('d/m/Y') : '--' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Valor Total</p>
                                <p class="font-bold text-brand-600 text-lg">R$ {{ number_format($nota->valor_total_nota, 2, ',', '.') }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs text-slate-500">Endereço</p>
                                <p class="text-slate-700">{{ $nota->endereco ?? 'Não identificado' }}</p>
                            </div>
                            @if($nota->contem_bebida_alcoolica)
                            <div class="col-span-2 bg-red-50 p-3 rounded-lg border border-red-100">
                                <p class="text-red-700 font-bold text-xs flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Alerta: Contém Bebida Alcoólica
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex-1">
                        <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Itens da Nota</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs text-slate-500 uppercase bg-slate-50">
                                    <tr>
                                        <th class="px-3 py-2">Produto</th>
                                        <th class="px-3 py-2 text-right">Qtd</th>
                                        <th class="px-3 py-2 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($nota->produtos as $produto)
                                    <tr>
                                        <td class="px-3 py-2 text-slate-700">{{ $produto->produto }}</td>
                                        <td class="px-3 py-2 text-right text-slate-500">{{ number_format($produto->quantidade, 2, ',', '.') }}</td>
                                        <td class="px-3 py-2 text-right font-medium text-slate-900">R$ {{ number_format($produto->valor, 2, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="font-bold text-slate-800 mb-4">Avaliação</h3>
                        <form action="{{ route('admin.notas.update', $nota->id) }}" method="POST">
                            @csrf
                            @method('POST')
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Motivo / Observação (Opcional)</label>
                                <textarea name="motivo" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-3 text-sm outline-none focus:ring-brand-500" placeholder="Ex: Aprovado conforme política interna...">{{ $nota->motivo }}</textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <button type="submit" name="status" value="reprovado" class="flex items-center justify-center gap-2 w-full py-3 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 font-bold transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    Reprovar
                                </button>
                                <button type="submit" name="status" value="aprovado" class="flex items-center justify-center gap-2 w-full py-3 rounded-lg bg-green-600 text-white hover:bg-green-700 font-bold shadow-lg shadow-green-500/20 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Aprovar
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </main>
    </div>
</body>
</html>