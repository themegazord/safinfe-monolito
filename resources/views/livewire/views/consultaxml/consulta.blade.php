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

    $dados_xml = $dados_xml->orderBy('dx1.dados_id')->paginate($this->porPagina);
  @endphp

  <x-table
    :headers="$headers"
    :rows="$dados_xml"
    striped
    with-pagination
    show-empty-text
    empty-text="Nenhuma nota em consulta"
  >

  </x-table>
</div>
