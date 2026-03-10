<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Reembolsos</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; color: #666; }
        table { w-full: 100%; border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
        th { background-color: #f3f4f6; text-transform: uppercase; font-size: 10px; color: #555; }
        th:first-child, td:first-child { text-align: left; }
        .total-row { background-color: #333; color: #fff; font-weight: bold; }
        .total-row td { border-color: #333; }
        .total-col { background-color: #f9fafb; font-weight: bold; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ddd; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório Geral de Reembolsos</h1>
        <p>Período: {{ \Carbon\Carbon::parse($inicio)->format('d/m/Y') }} até {{ \Carbon\Carbon::parse($fim)->format('d/m/Y') }}</p>
        <p>Gerado em: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Funcionário</th>
                @foreach($vales as $vale)
                    <th>{{ $vale->nome }}</th>
                @endforeach
                <th>Total a Reembolsar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dados as $linha)
            <tr>
                <td>
                    <strong>{{ $linha['funcionario']->nome }}</strong><br>
                    <span style="color: #666; font-size: 10px;">{{ $linha['funcionario']->cpf }}</span>
                </td>
                @foreach($vales as $vale)
                    <td>R$ {{ number_format($linha['valores_por_vale'][$vale->id], 2, ',', '.') }}</td>
                @endforeach
                <td class="total-col">R$ {{ number_format($linha['total_funcionario'], 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td>TOTAIS</td>
                @foreach($vales as $vale)
                    <td>R$ {{ number_format($totaisPorVale[$vale->id], 2, ',', '.') }}</td>
                @endforeach
                <td>R$ {{ number_format($totalGeral, 2, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        ValeIA - Sistema de Gestão de Benefícios e Reembolsos
    </div>
</body>
</html>