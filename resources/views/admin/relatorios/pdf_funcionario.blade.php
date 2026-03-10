<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório - {{ $funcionario->nome }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #ddd; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; color: #666; }
        
        .info-box { background: #f9fafb; border: 1px solid #e5e7eb; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .info-box h2 { margin-top: 0; font-size: 14px; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px; }
        .info-row { display: block; margin-bottom: 5px; }
        .label { font-weight: bold; color: #555; }

        .vale-section { margin-bottom: 30px; page-break-inside: avoid; }
        .vale-header { background-color: #333; color: #fff; padding: 8px 15px; font-weight: bold; display: flex; justify-content: space-between; align-items: center; }
        .vale-summary { background-color: #f3f4f6; padding: 10px; border: 1px solid #ddd; border-top: none; font-size: 11px; margin-bottom: 10px; }
        
        table { width: 100%; border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 6px 10px; text-align: left; font-size: 11px; }
        th { background-color: #f3f4f6; text-transform: uppercase; color: #555; font-size: 10px; }
        td.text-right, th.text-right { text-align: right; }
        
        .total-geral { text-align: right; margin-top: 30px; font-size: 16px; font-weight: bold; border-top: 2px solid #333; padding-top: 10px; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #aaa; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório Individual de Despesas</h1>
        <p>Período: {{ \Carbon\Carbon::parse($inicio)->format('d/m/Y') }} até {{ \Carbon\Carbon::parse($fim)->format('d/m/Y') }}</p>
    </div>

    <div class="info-box">
        <h2>Dados do Funcionário</h2>
        <span class="info-row"><span class="label">Nome:</span> {{ $funcionario->nome }}</span>
        <span class="info-row"><span class="label">Cargo:</span> {{ $funcionario->cargo }}</span>
        <span class="info-row"><span class="label">CPF:</span> {{ $funcionario->cpf }}</span>
        <span class="info-row"><span class="label">Status:</span> {{ $funcionario->ativo ? 'Ativo' : 'Inativo' }}</span>
    </div>

    @foreach($relatorioVales as $item)
    <div class="vale-section">
        <div class="vale-header">
            <span>{{ $item['nome_vale'] }}</span>
        </div>
        <div class="vale-summary">
            <strong>Configuração:</strong> R$ {{ number_format($item['config']->valor, 2, ',', '.') }} / {{ ucfirst($item['config']->periodicidade) }} |
            <strong>Teto Estimado Período:</strong> R$ {{ number_format($item['limite'], 2, ',', '.') }} |
            <strong>Saldo Estimado:</strong> R$ {{ number_format($item['saldo'], 2, ',', '.') }}
        </div>

        @if(count($item['notas']) > 0)
        <table>
            <thead>
                <tr>
                    <th width="15%">Data</th>
                    <th>Estabelecimento</th>
                    <th width="20%">Tipo Despesa</th>
                    <th width="15%" class="text-right">Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($item['notas'] as $nota)
                <tr>
                    <td>{{ $nota->created_at->format('d/m/Y') }}</td>
                    <td>{{ $nota->estabelecimento ?? 'N/A' }}</td>
                    <td>{{ ucfirst($nota->tipo) }}</td>
                    <td class="text-right">R$ {{ number_format($nota->valor_total_nota, 2, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-right"><strong>Subtotal {{ $item['nome_vale'] }}:</strong></td>
                    <td class="text-right"><strong>R$ {{ number_format($item['gasto'], 2, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
        @else
        <div style="padding: 10px; border: 1px solid #ddd; border-top: none; text-align: center; color: #888; font-style: italic;">
            Nenhuma despesa registrada para este benefício neste período.
        </div>
        @endif
    </div>
    @endforeach

    <div class="total-geral">
        Total a Reembolsar: R$ {{ number_format($totalGastoGeral, 2, ',', '.') }}
    </div>

    <div class="footer">
        Gerado automaticamente pelo sistema ValeIA em {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>