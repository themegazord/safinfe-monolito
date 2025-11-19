<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório do Movimento do faturamento</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 8pt;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 3px 5px;
            text-align: left;
        }

        thead tr {
            background-color: #f0f0f0;
        }

        .totais {
            background-color: #e8e8e8;
            padding: 10px;
            margin: 10px 0;
            page-break-inside: avoid;
        }

        .totais-grid {
            display: table;
            width: 100%;
        }

        .totais-coluna {
            display: table-cell;
            vertical-align: top;
            padding: 5px;
        }

        .totais-titulo {
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 5px;
        }

        .totais-item {
            font-size: 8pt;
            margin: 3px 0;
        }

        .totais-destaque {
            font-weight: bold;
            border: 1px solid #000;
            padding: 5px;
            margin-top: 5px;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body style="padding-left: 32px; padding-right: 32px;">
    <h2 style="margin-bottom: 15px;">Relatório de Movimento de Notas Fiscais</h2>
    <h3 style="margin-bottom: 15px;">Empresa: {{ $nome_fantasia }}</h3>
    <h3 style="margin-bottom: 15px;">Periodo: {{ date('d/m/Y', strtotime($data_inicio)) }} - {{ date('d/m/Y', strtotime($data_fim)) }}</h3>

    <table>
        <thead>
            <tr>
                <th rowspan="2">Mod.</th>
                <th rowspan="2">Sér</th>
                <th rowspan="2">Número</th>
                <th rowspan="2">Dt.Emissão</th>
                <th rowspan="2">Nome do Cadastro</th>
                <th colspan="6" style="text-align: center;">Impostos</th>
                <th rowspan="2">Vr. Desp.</th>
                <th rowspan="2">Vr. Frete</th>
                <th rowspan="2">Vr. Prod</th>
                <th rowspan="2">Valor Total</th>
                <th rowspan="2">Situação</th>
            </tr>
            <tr>
                <th>Vr. IP</th>
                <th>Base ICMS</th>
                <th>Vr. ICMS</th>
                <th>Vr. FCP</th>
                <th>Base ST</th>
                <th>Vr. ST</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dadosXML as $data => $dados)
                @foreach ($dados as $dado)
                    <tr>
                        <td>{{ $dado['modelo'] }}</td>
                        <td>{{ $dado['serie'] }}</td>
                        <td>{{ $dado['numeronf'] }}</td>
                        <td>{{ $dado['data_emissao'] }}</td>
                        <td>{{ $dado['destinatario'] }}</td>
                        <td class="text-right">{{ number_format($dado['vripi'], 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($dado['vrbcicms'], 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($dado['vricms'], 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($dado['vrfcp'], 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($dado['vrbcst'], 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($dado['vrst'], 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($dado['vrdesp'], 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($dado['vrfrete'], 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($dado['vrprod'], 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($dado['vrtotal'], 2, ',', '.') }}</td>
                        <td>{{ $dado['situacao'] }}</td>
                    </tr>
                @endforeach

                {{-- Totais da Data --}}
                <tr>
                    <td colspan="16" style="padding: 0;">
                        <div class="totais">
                            <div class="totais-titulo">
                                Total da data: {{ date('d/m/Y', strtotime($data)) }}
                            </div>

                            <table style="border: none; margin-top: 5px;">
                                <tr>
                                    <td style="border: none; width: 33%;">
                                        <div class="totais-item">Vr. Produtos: R$ {{ number_format($dados->sum('vrprod'), 2, ',', '.') }}</div>
                                        <div class="totais-item">Vr. Base ICMS: R$ {{ number_format($dados->sum('vrbcicms'), 2, ',', '.') }}</div>
                                        <div class="totais-item">Vr. Base ST: R$ {{ number_format($dados->sum('vrbcst'), 2, ',', '.') }}</div>
                                        <div class="totais-item">Vr. FCP: R$ {{ number_format($dados->sum('vrfcp'), 2, ',', '.') }}</div>
                                    </td>
                                    <td style="border: none; width: 33%;">
                                        <div class="totais-item">Vr. Despesas: R$ {{ number_format($dados->sum('vrdesp'), 2, ',', '.') }}</div>
                                        <div class="totais-item">Vr. ICMS: R$ {{ number_format($dados->sum('vricms'), 2, ',', '.') }}</div>
                                        <div class="totais-item">Vr. ICMS ST: R$ {{ number_format($dados->sum('vrst'), 2, ',', '.') }}</div>
                                        <div class="totais-item">Vr. IPI: R$ {{ number_format($dados->sum('vripi'), 2, ',', '.') }}</div>
                                    </td>
                                    <td style="border: none; width: 33%;">
                                        <div class="totais-item">Vr. Frete: R$ {{ number_format($dados->sum('vrfrete'), 2, ',', '.') }}</div>
                                        <div class="totais-destaque">Vr. Total: R$ {{ number_format($dados->sum('vrtotal'), 2, ',', '.') }}</div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="16" style="text-align: center; padding: 20px;">
                        Nenhum registro encontrado
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
