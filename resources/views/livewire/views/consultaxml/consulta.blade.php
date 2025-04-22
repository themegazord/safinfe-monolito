<div class="p-4 md:p-6 flex flex-col gap-4">
  @php
  $config = ['mode' => 'range', 'altFormat' => 'd/m/Y'];
  $modelos = [
  ["id" => "55", "name" => "NF-e"],
  ["id" => "65", "name" => "NFC-e"],
  ];
  $status = [
  ["id" => "AUTORIZADO", "name" => "Autorizadas"],
  ["id" => "CANCELADO", "name" => "Canceladas"],
  ["id" => "INUTILIZADO", "name" => "Inutilizadas"],
  ];
  @endphp
  <h1 class="font-bold text-2xl">Consulta XML</h1>

  @if ($usuario->getAttribute('role') === 'CLIENTE')
  <livewire:views.consultaxml.componentes.consulta :model-prefix="'consultaCliente'" :modelos="$modelos" :status="$status" :config="$config" />
  @endif

  @if ($usuario->getAttribute('role') === 'CONTADOR')
  <livewire:views.consultaxml.componentes.consulta :model-prefix="'consultaContador'" :modelos="$modelos" :status="$status" :config="$config" :empresa-selector="true" :empresas="$empresas" />
  @endif

  @if ($usuario->getAttribute('role') === 'ADMIN')
  <livewire:views.consultaxml.componentes.consulta :model-prefix="'consultaAdmin'" :modelos="$modelos" :status="$status" :config="$config" :empresa-selector="true" :empresas="$empresas" />
  @endif

  @php
  $headers = [
  ['key' => 'dados_id', 'label' => 'ID'],
  ['key' => 'modelo', 'label' => 'Modelo'],
  ['key' => 'serie', 'label' => 'Série'],
  ['key' => 'numeronf', 'label' => 'Número NF'],
  ['key' => 'numeronf_final', 'label' => 'Número NF Final'],
  ['key' => 'status', 'label' => 'Status'],
  ['key' => 'dh_emissao_evento', 'label' => 'Data do evento'],
  ];

  $dados_xml = DB::table('dados_xml as dx1')
  ->where(function ($query) {
  $query->whereIn('dx1.status', ['cancelado', 'denegado', 'inutilizado'])
  ->orWhere(function ($subQuery) {
  $subQuery->where('dx1.status', 'autorizado')
  ->whereNotExists(function ($subSubQuery) {
  $subSubQuery->select(DB::raw(1))
  ->from('dados_xml as dx2')
  ->whereRaw('dx2.numeronf = dx1.numeronf')
  ->whereIn('dx2.status', ['cancelado', 'denegado', 'inutilizado']);
  });
  });
  })
  ->where('empresa_id', $dadosXML['empresa_id'])

  // filtro de data com when
  ->when(!is_null($dadosXML['data_inicio']) && !is_null($dadosXML['data_fim']), function ($query) use ($dadosXML) {
  $inicio = date('Y-m-d', strtotime($dadosXML['data_inicio']));
  $fim = date('Y-m-d', strtotime($dadosXML['data_fim']));

  if ($inicio === $fim) {
  $query->whereDate('dh_emissao_evento', $inicio);
  } else {
  $query->whereBetween(DB::raw('DATE(dh_emissao_evento)'), [$inicio, $fim]);
  }
  })
  ->when(!is_null($dadosXML['data_inicio']) && is_null($dadosXML['data_fim']), function ($query) use ($dadosXML) {
  $query->whereDate('dh_emissao_evento', '>=', date('Y-m-d', strtotime($dadosXML['data_inicio'])));
  })
  ->when(is_null($dadosXML['data_inicio']) && !is_null($dadosXML['data_fim']), function ($query) use ($dadosXML) {
  $query->whereDate('dh_emissao_evento', '<=', date('Y-m-d', strtotime($dadosXML['data_fim'])));
    })

    // status
    ->when($dadosXML['status'] !== "TODAS", function ($query) use ($dadosXML) {
    $query->where('dx1.status', $dadosXML['status']);
    })

    // modelo
    ->when($dadosXML['modelo'] !== "TODAS", function ($query) use ($dadosXML) {
    $query->where('dx1.modelo', $dadosXML['modelo']);
    })

    // série
    ->when(!is_null($dadosXML['serie']), function ($query) use ($dadosXML) {
    $query->where('dx1.serie', $dadosXML['serie']);
    })

    // faixa de número NF
    ->when(!is_null($dadosXML['numeroInicial']) && !is_null($dadosXML['numeroFinal']), function ($query) use ($dadosXML) {
    $query->whereBetween('numeronf', [$dadosXML['numeroInicial'], $dadosXML['numeroFinal']]);
    })
    ->when(!is_null($dadosXML['numeroInicial']) && is_null($dadosXML['numeroFinal']), function ($query) use ($dadosXML) {
    $query->where('numeronf', '>=', $dadosXML['numeroInicial']);
    })
    ->when(is_null($dadosXML['numeroInicial']) && !is_null($dadosXML['numeroFinal']), function ($query) use ($dadosXML) {
    $query->where('numeronf', '<=', $dadosXML['numeroFinal']);
      });

      $dados_xml=$dados_xml->orderBy('dx1.dados_id')->paginate($this->porPagina);
      @endphp

      <x-table
        :headers="$headers"
        :rows="$dados_xml"
        striped
        with-pagination
        show-empty-text
        empty-text="Nenhuma nota em consulta">

        @scope('actions', $dado)
        <div class="flex gap-2">
          <x-button class="btn btn-ghost" icon="o-eye" wire:click="setXMLAtual({{ $dado->dados_id }})" />
          <x-button class="btn btn-ghost" icon="o-arrow-down-tray" wire:click="downloadXMLUnico({{ $dado->dados_id }})"/>
        </div>
        @endscope
      </x-table>

      <x-modal wire:model="modalVisualizacaoDadosXML" class="backdrop-blur" box-class="w-11/12 max-w-full">
        @if ($dadosXMLAtual !== null)
        <x-slot:title>
          Dados da nota fiscal: {{ $dadosXMLAtual['numero'] }}
        </x-slot:title>
        <x-tabs wire:model="descricaoNotaFiscalTab">
          <x-tab name="cabecalho-tab" label="Cabeçalho da nota fiscal">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
              <div class="p-4 border rounded-xl shadow-sm">
                <p class="font-semibold">Valor total da nota:</p>
                <p>R$ {{ number_format(floatval($dadosXMLAtual['informacaoDeValoresDaNota']['totalNota']), 2, ',', '.') }}</p>
              </div>
              <div class="p-4 border rounded-xl shadow-sm">
                <p class="font-semibold">Valor total da ICMS da nota:</p>
                <p>R$ {{ number_format(floatval($dadosXMLAtual['informacaoDeValoresDaNota']['totalICMS']), 2, ',', '.') }}</p>
              </div>
              <div class="p-4 border rounded-xl shadow-sm">
                <p class="font-semibold">Valor total de ICMS ST da nota:</p>
                <p>R$ {{ number_format(floatval($dadosXMLAtual['informacaoDeValoresDaNota']['totalST']), 2, ',', '.') }}</p>
              </div>
              <div class="p-4 border rounded-xl shadow-sm">
                <p class="font-semibold">Valor total de PIS da nota:</p>
                <p>R$ {{ number_format(floatval($dadosXMLAtual['informacaoDeValoresDaNota']['vPIS']), 2, ',', '.') }}</p>
              </div>
              <div class="p-4 border rounded-xl shadow-sm">
                <p class="font-semibold">Valor total de COFINS da nota:</p>
                <p>R$ {{ number_format(floatval($dadosXMLAtual['informacaoDeValoresDaNota']['vCOFINS']), 2, ',', '.') }}</p>
              </div>
              <div class="p-4 border rounded-xl shadow-sm">
                <p class="font-semibold">Valor total aprox. de impostos federais da nota:</p>
                <p>R$ {{ number_format(floatval($dadosXMLAtual['informacaoDeValoresDaNota']['valorApxImpostosFederais']), 2, ',', '.') }}</p>
              </div>
            </div>
            <div class="flex flex-col md:grid md:grid-cols-2 gap-4 mt-4">
              <div class="p-4 rounded-xl border flex flex-col gap-2">
                <h5 class="font-bold text-xl"><b>Informações do emissor da nota fiscal:</b></h5>
                <p class="text-lg"><b>Nome do Emissor: </b> {{ $dadosXMLAtual['detalhesEmissor']['nomeEmissor'] }}</p>
                <p class="text-lg"><b>CNPJ do Emissor: </b> {{ $dadosXMLAtual['detalhesEmissor']['cnpj'] }}</p>
                <p class="text-lg"><b>IE do Emissor: </b> {{ $dadosXMLAtual['detalhesEmissor']['ie'] }}</p>
                <div class="p-4 rounded-xl border flex flex-col gap-2">
                  <h5 class="font-bold text-xl"><b>Endereço do emissor:</b></h5>
                  <p class="text-lg"><b>Logradouro do Emissor: </b> {{ $dadosXMLAtual['detalhesEmissor']['endereco']['rua'] }}, {{ $dadosXMLAtual['detalhesEmissor']['endereco']['numero'] }}</p>
                  <p class="text-lg"><b>Bairro do Emissor: </b> {{ $dadosXMLAtual['detalhesEmissor']['endereco']['bairro'] }}</p>
                  <p class="text-lg"><b>CEP do Emissor: </b> {{ $dadosXMLAtual['detalhesEmissor']['endereco']['cep'] }}</p>
                  <p class="text-lg"><b>Cidade do Emissor: </b> {{ $dadosXMLAtual['detalhesEmissor']['endereco']['cidade'] }}</p>
                  <p class="text-lg"><b>UF do Emissor: </b> {{ $dadosXMLAtual['detalhesEmissor']['endereco']['uf'] }}</p>
                </div>
              </div>
              @if ($dadosXMLAtual['modelo'] === '55')
                <div class="p-4 rounded-xl border flex flex-col gap-2">
                  <h5 class="font-bold text-xl"><b>Informações do Destinatario da nota:</b></h5>
                  <p class="text-lg"><b>Nome do Destinatario: </b> {{ $dadosXMLAtual['detalhesDestinatario']['nomeDestinatario'] }}</p>
                  <p class="text-lg"><b>CNPJ do Destinatario: </b> {{ $dadosXMLAtual['detalhesDestinatario']['cnpj'] }}</p>
                  <p class="text-lg"><b>IE do Destinatario: </b> {{ $dadosXMLAtual['detalhesDestinatario']['ie'] }}</p>
                  <div class="p-4 rounded-xl border flex flex-col gap-2">
                    <h5 class="font-bold text-xl"><b>Endereço do Destinatario:</b></h5>
                    <p class="text-lg"><b>Logradouro do Destinatario: </b> {{ $dadosXMLAtual['detalhesDestinatario']['endereco']['rua'] }}, {{ $dadosXMLAtual['detalhesDestinatario']['endereco']['numero'] }}</p>
                    <p class="text-lg"><b>Bairro do Destinatario: </b> {{ $dadosXMLAtual['detalhesDestinatario']['endereco']['bairro'] }}</p>
                    <p class="text-lg"><b>CEP do Destinatario: </b> {{ $dadosXMLAtual['detalhesDestinatario']['endereco']['cep'] }}</p>
                    <p class="text-lg"><b>Cidade do Destinatario: </b> {{ $dadosXMLAtual['detalhesDestinatario']['endereco']['cidade'] }}</p>
                    <p class="text-lg"><b>UF do Destinatario: </b> {{ $dadosXMLAtual['detalhesDestinatario']['endereco']['uf'] }}</p>
                  </div>
                </div>
                @else
                <div class="p-4 rounded-xl border flex flex-col gap-2">
                  <h5 class="font-bold text-xl"><b>Informações do Destinatario da nota:</b></h5>
                  <p class="text-lg"><b>Nome do Destinatario: </b> {{ $dadosXMLAtual['detalhesDestinatario']['nomeDestinatario'] }}</p>
                </div>
              @endif
            </div>
            <div class="flex flex-col md:flex-row gap-4 mt-4">
              @if (count($dadosXMLAtual['pagamento']) === 1)
                @foreach ($dadosXMLAtual['pagamento']['pag']['pag'] as $key => $dadoPagamento)
                  @if ($key !== 'card')
                  <div class="p-4 border rounded-xl shadow-sm">
                    <p class="font-semibold">{{ $dadoPagamento['descricao'] }}:</p>
                    @if ($key === 'vPag')
                      <p>R$ {{ number_format(floatval($dadoPagamento['valor']), 2, ',', '.') }}</p>
                    @else
                      <p>{{$dadoPagamento['valor']}}</p>
                    @endif
                  </div>
                  @endif
                  @if ($key === 'card')
                  <div class="p-4 border rounded-xl shadow-sm">
                    <p class="font-semibold">Dados do cartão:</p>
                    @foreach ($dadoPagamento as $dadoCartao)
                      <p><b>{{ $dadoCartao['descricao'] }}</b>: R$ {{ number_format(floatval($dadoCartao['valor']), 2, ',', '.') }}</p>
                    @endforeach
                  </div>
                  @endif
                @endforeach
              @endif
              @if(count($dadosXMLAtual['pagamento']) > 1)
                @foreach($dadosXMLAtual['pagamento'] as $pag)
                <div>
                  @foreach ($pag['pag']['pag'] as $key => $dadoPagamento)
                    @if ($key !== 'card')
                      <div class="p-4 border rounded-xl shadow-sm">
                        <p class="font-semibold">{{ $dadoPagamento['descricao'] }}:</p>
                        @if ($key === 'vPag')
                          <p>R$ {{ number_format(floatval($dadoPagamento['valor']), 2, ',', '.') }}</p>
                        @else
                          <p>{{$dadoPagamento['valor']}}</p>
                        @endif
                      </div>
                    @else
                      <div class="p-4 border rounded-xl shadow-sm">
                        <p class="font-semibold">Dados do cartão:</p>
                        @foreach ($dadoPagamento as $dadoCartao)
                          <p class="card-text"><b>{{ $dadoCartao['descricao'] }}</b>: {{ $dadoCartao['valor'] }}</p>
                        @endforeach
                      </div>
                    @endif
                  @endforeach
                </div>
                @endforeach
              @endif
            </div>
          </x-tab>
          <x-tab name="produtos-tab" label="Produtos da nota fiscal">
            @php
              $headers = [
                ['key' => 'prod.cProd', 'label' => 'Cod. Produto'],
                ['key' => 'prod.xProd', 'label' => 'Nome'],
                ['key' => 'prod.NCM', 'label' => 'NCM'],
                ['key' => 'prod.uCom', 'label' => 'Un. Medida'],
                ['key' => 'prod.qCom', 'label' => 'Qtde.'],
                ['key' => 'prod.vUnCom', 'label' => 'Valor Un.'],
                ['key' => 'prod.vProd', 'label' => 'Valor Total'],
              ];
            @endphp
            <x-table :headers="$headers" :rows="$dadosXMLAtual['produtos']" wire:model="expanded" expandable-key="prod.cProd" expandable>
              @scope('cell_prod.vUnCom', $detalheProduto)
                R$ {{ number_format(floatval($detalheProduto['prod']['vUnCom']), 2, ',', '.') }}
              @endscope
              @scope('cell_prod.vProd', $detalheProduto)
                R$ {{ number_format(floatval($detalheProduto['prod']['vProd']), 2, ',', '.') }}
              @endscope
              @scope('expansion', $detalheProduto)
                @php
                  unset($detalheProduto['imposto']['vTotTrib']);
                @endphp
                @foreach ($detalheProduto['imposto'] as $keyImposto => $imposto)
                  <div class="mb-2 w-full border border-primary rounded p-4">
                    <p class="mb-2 text-xl">{{ $keyImposto }}</p>
                    @foreach ($imposto as $tagImposto)
                      @foreach ($tagImposto as $infoImposto)
                        <p>
                          <b>{{ $infoImposto['descricao'] }}: </b> {{ $infoImposto['valor'] }}
                        </p>
                      @endforeach
                    @endforeach
                  </div>
                @endforeach
              @endscope
            </x-table>
          </x-tab>
          <x-tab name="info-tab" label="Informações da nota fiscal">
            <div class="flex flex-col gap-4">
              @foreach ($dadosXMLAtual['infAdicional']['infAdic']['infAdic'] as $detalhesInfAdicional)
                <div class="p-4 w-full border border-primary rounded">
                  <b class="text-xl mb-2">{{ $detalhesInfAdicional['descricao'] }}: </b>
                  <p>{{ $detalhesInfAdicional['valor'] }}</p>
                </div>
              @endforeach
              @if ($dadosXMLAtualCancelado !== null)
                <div class="p-4 w-full border border-primary rounded">
                  <b class="text-xl">Dados do cancelamento da nota fiscal</b>
                  <div class="p-4 w-full border border-primary rounded">
                    <p class="text-lg"><b>CNPJ emissor do evento:</b> {{ $dadosXMLAtualCancelado['cnpj'] }}</p>
                    <p class="text-lg"><b>Chave da nota cancelada:</b> {{ $dadosXMLAtualCancelado['chaveNFe'] }}</p>
                    <p class="text-lg"><b>Data e Hora do cancelamento:</b> {{ date('d/m/Y H:i:s', strtotime($dadosXMLAtualCancelado['dh_cancelamento'])) }}</p>
                    <p class="text-lg"><b>Motivo do cancelamento:</b> {{ $dadosXMLAtualCancelado['justificativa'] }}</p>
                  </div>
                </div>
              @endif
            </div>
          </x-tab>
        </x-tabs>
        @endif
      </x-modal>
</div>
