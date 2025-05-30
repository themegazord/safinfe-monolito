<div class="p-4 md:p-6">
  <div class="flex flex-col md:grid md:grid-cols-4 gap-4 mb-8">
    @if ($usuario->getAttribute('role') === 'CONTADOR')
    <div class="md:col-span-4">
      <label for="empresaContador" class="block text-sm font-medium text-gray-200 mb-1">Selecione a empresa:</label>
      <select
        wire:model="consulta.empresa_id"
        id="empresaContador"
        class="w-full bg-gray-800 border border-gray-600 text-white rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
        <option selected>Selecione a empresa...</option>
        @foreach ($empresasContador as $empresa)
        <option value="{{ $empresa->getAttribute('empresa_id') }}">{{ $empresa->getAttribute('fantasia') }}</option>
        @endforeach
      </select>
    </div>
    @endif

    @if ($usuario->getAttribute('role') === 'ADMIN')
    <div class="md:col-span-4">
      <x-select
        wire:model="consulta.empresa_id"
        label="Selecione a empresa:"
        :options="$empresasGeral"
        option-value="empresa_id"
        option-label="fantasia"
        id="empresaGeral" />
    </div>
    @endif

    @if ($usuario->getAttribute('role') === 'CLIENTE')
    <div class="md:col-span-4">
      <label for="empresaCliente" class="block text-sm font-medium text-gray-200 mb-1">Sua empresa:</label>
      <input
        type="text"
        id="empresaCliente"
        readonly
        value="{{ $usuario->cliente->empresa->getAttribute('fantasia') }}"
        class="w-full bg-gray-800 border border-gray-600 text-white rounded-md px-3 py-2 text-sm" />
    </div>
    @endif

    @php
    $configRangeDatePicker = ['altFormat' => 'd/m/Y', 'mode' => 'range', 'locale' => 'pt'];
    $modelos = [
    ["id" => "TODAS", "name" => "Todas"],
    ["id" => "55", "name" => "NF-e"],
    ["id" => "65", "name" => "NFC-e"],
    ];
    $status = [
    ["id" => "TODAS", "name" => "Todas"],
    ["id" => "AUTORIZADO", "name" => "Autorizadas"],
    ["id" => "CANCELADO", "name" => "Canceladas"],
    ["id" => "INUTILIZADO", "name" => "Inutilizadas"],
    ];
    @endphp

    <div class="flex flex-col  md:col-span-4  md:grid md:grid-cols-4 gap-4">
      <div class="w-full md:col-span-2 md:w-auto">
        <x-datepicker label="Data inicio - Data fim" placeholder="Data inicio - Data fim" wire:model="consulta.data_inicio_fim" icon="o-calendar" :config="$configRangeDatePicker" inline />
      </div>
      <div class="w-full md:col-span-1 md:w-auto">
        <x-input label="Série" placeholder="Insira a série das notas fiscais..." wire:model="consulta.serie" autocomplete="off" inline />
      </div>
      <div class="w-full md:col-span-1 md:w-auto">
        <x-select label="Modelo" placeholder="Selecione o tipo da nota fiscal" placeholder-value="TODAS" wire:model="consulta.modelo" :options="$modelos" inline />
      </div>
    </div>

    <div class="flex flex-col md:col-span-4 md:grid md:grid-cols-4 gap-4">
      <div class="w-full md:col-span-2 md:w-auto">
        <x-select label="Selecione o status" placeholder="Selecione o status da nota fiscal..." placeholder-value="TODAS" wire:model="consulta.status" :options="$status" inline />
      </div>
      <div class="w-full md:col-span-1 md:w-auto">
        <x-input label="Numero inicial:" placeholder="Insira o número inicial a ser consultado" wire:model="consulta.numeroInicial" inline />
      </div>
      <div class="w-full md:col-span-1 md:w-auto">
        <x-input label="Numero final:" placeholder="Insira o número final a ser consultado" wire:model="consulta.numeroFinal" inline />
      </div>
    </div>
  </div>
  <div class="flex items-end">
    <x-button
      wire:click="consultar"
      label="Gerar relatório"
      class="w-full btn btn-primary"
      spinner="consultar" />
  </div>

  @if ($dadosXML !== null)
  <div class="mt-4 w-full overflow-x-scroll">
    <table class="w-full table table-auto border-collapse text-xs">
      <thead>
        <tr class="bg-gray-100 text-gray-700">
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
        <tr class="bg-gray-50 text-gray-600">
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
        <tr class="bg-gray-50 text-gray-800">
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
        <tr class="bg-gray-200">
          <td colspan="100%" class="px-2 py-1 text-left font-bold text-lg text-gray-700">
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
  @endif
</div>
