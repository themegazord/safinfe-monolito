<div class="mt-4 w-full overflow-x-scroll">
    <table class="w-full table table-auto border-collapse text-xs">
        <thead>
            <tr class="bg-gray-100 text-black">
                <th class="border px-1 py-0.5" rowspan="2">Mod.</th>
                <th class="border px-1 py-0.5" rowspan="2">Sér</th>
                <th class="border px-1 py-0.5" rowspan="2">Número</th>
                <th class="border px-1 py-0.5" rowspan="2">Dt.Emissão</th>
                <th class="border px-1 py-0.5" rowspan="2">Nome do Cadastro</th>
                <th class="border px-1 py-0.5" colspan="6" class="text-center">Impostos -></th>
                <th class="border px-1 py-0.5" rowspan="2">Vr. Desp.</th>
                <th class="border px-1 py-0.5" rowspan="2">Vr. Frete</th>
                <th class="border px-1 py-0.5" rowspan="2">Vr. Prod</th>
                <th class="border px-1 py-0.5" rowspan="2">Valor Total</th>
                <th class="border px-1 py-0.5" rowspan="2">Situação NFe</th>
            </tr>
            <tr class="bg-gray-50 text-black">
                <th class="border px-1 py-0.5">Vr. IP</th>
                <th class="border px-1 py-0.5">Vr. Base ICMS</th>
                <th class="border px-1 py-0.5">Vr. ICMS</th>
                <th class="border px-1 py-0.5">Vr. FCP</th>
                <th class="border px-1 py-0.5">Base ICMS ST</th>
                <th class="border px-1 py-0.5">Vr. ICMS ST</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dadosXML as $data => $dados)
                @foreach ($dados as $dado)
                    <tr class="bg-gray-50 text-black">
                        <td class="border px-1 py-0.5">{{ $dado['modelo'] }}</td>
                        <td class="border px-1 py-0.5">{{ $dado['serie'] }}</td>
                        <td class="border px-1 py-0.5">{{ $dado['numeronf'] }}</td>
                        <td class="border px-1 py-0.5">{{ $dado['data_emissao'] }}</td>
                        <td class="border px-1 py-0.5">{{ $dado['destinatario'] }}</td>


                        <td class="border px-1 py-0.5">R$ {{ number_format($dado['vripi'], 2, ',', '.') }}</td>
                        <td class="border px-1 py-0.5">R$ {{ number_format($dado['vrbcicms'], 2, ',', '.') }}</td>
                        <td class="border px-1 py-0.5">R$ {{ number_format($dado['vricms'], 2, ',', '.') }}</td>
                        <td class="border px-1 py-0.5">R$ {{ number_format($dado['vrfcp'], 2, ',', '.') }}</td>
                        <td class="border px-1 py-0.5">R$ {{ number_format($dado['vrbcst'], 2, ',', '.') }}</td>
                        <td class="border px-1 py-0.5">R$ {{ number_format($dado['vrst'], 2, ',', '.') }}</td>


                        <td class="border px-1 py-0.5">R$ {{ number_format($dado['vrdesp'], 2, ',', '.') }}</td>
                        <td class="border px-1 py-0.5">R$ {{ number_format($dado['vrfrete'], 2, ',', '.') }}</td>
                        <td class="border px-1 py-0.5">R$ {{ number_format($dado['vrprod'], 2, ',', '.') }}</td>
                        <td class="border px-1 py-0.5">R$ {{ number_format($dado['vrtotal'], 2, ',', '.') }}</td>
                        <td class="border px-1 py-0.5">{{ $dado['situacao'] }}</td>
                    </tr>
                @endforeach
                <tr class="bg-gray-50">
                    <td colspan="100%" class="px-2 py-1 text-left font-bold text-lg text-black">
                        <div class="grid grid-cols-5 gap-4">
                            <div class="flex flex-col justify-center">
                                <p class="font-bold text-base">
                                    Total da data: {{ date('d/m/Y', strtotime($data)) }}
                                </p>
                            </div>
                            <div class="flex flex-col">
                                <p class="font-bold text-base">Vr. Produtos: R$ {{ number_format($dados->sum('vrprod'), 2, ',', '.') }}</p>
                                <p class="font-bold text-base">Vr. Base ICMS: R$ {{ number_format($dados->sum('vrbcicms'), 2, ',', '.') }}</p>
                                <p class="font-bold text-base">Vr. Base ICMS ST: R$ {{ number_format($dados->sum('vrbcst'), 2, ',', '.') }}</p>
                                <p class="font-bold text-base">Vr. FCP: R$ {{ number_format($dados->sum('vrfcp'), 2, ',', '.') }}</p>
                            </div>
                            <div class="flex flex-col">
                                <p class="font-bold text-base">Vr. Despesas: R$ {{ number_format($dados->sum('vrdesp'), 2, ',', '.') }}</p>
                                <p class="font-bold text-base">Vr. ICMS: R$ {{ number_format($dados->sum('vricms'), 2, ',', '.') }}</p>
                                <p class="font-bold text-base">Vr. ICMS ST: R$ {{ number_format($dados->sum('vrst'), 2, ',', '.') }}</p>
                                <p class="font-bold text-base">Vr. IPI: R$ {{ number_format($dados->sum('vripi'), 2, ',', '.') }}</p>
                            </div>
                            <div class="flex flex-col  justify-center">
                                <p class="font-bold text-base">Vr. Frete: R$ {{ number_format($dados->sum('vrfrete'), 2, ',', '.') }}</p>
                            </div>
                            <div class="flex flex-col  justify-center">
                                <p class="font-bold text-base">Vr. Total</p>
                                <p class="font-bold text-base p-2 border">R$ {{ number_format($dados->sum('vrtotal'), 2, ',', '.') }}</p>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty

            @endforelse
        </tbody>

    </table>
</div>
