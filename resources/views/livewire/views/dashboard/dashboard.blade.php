<div class="p-4 md:p-6">
  <h1 class="font-bold text-3xl text-white mb-6">Dashboard</h1>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @if ($usuario->getAttribute('role') === 'CONTADOR')
    <div class="col-span-1 sm:col-span-2">
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
    <div class="col-span-1 sm:col-span-2 lg:col-span-3">
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
    <div class="col-span-1 sm:col-span-2">
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
    @endphp

    <div class="col-span-1 sm:col-span-2">
      <x-datepicker
        label="Data inicial - Data final"
        wire:model="consulta.data_inicio_fim"
        icon="o-calendar"
        :config="$configRangeDatePicker" />
    </div>

    <div class="col-span-1">
      <x-button
        wire:click="consultar"
        label="Gerar Dashboard"
        class="w-full btn btn-primary"
        spinner="consultar" />
    </div>
  </div>

  <div class="space-y-6">
    @if (!is_null($informacoesTotaisNotas) && !is_null($totalNotasPorDiaMes) && !is_null($topProdutosVendidos))
    <h5 class="text-lg font-semibold text-white">Valores totais de todas as notas mensais</h5>
    <livewire:componentes.relatorios.dashboard.valorestotaismensais :dados="$informacoesTotaisNotas" :key="bin2hex(random_bytes(32))" />

    <h5 class="text-lg font-semibold text-white">Valores total por dia de todas as notas mensais</h5>
    <livewire:componentes.relatorios.dashboard.totalnotaspordiames :dados="$totalNotasPorDiaMes" :key="bin2hex(random_bytes(32))" />

    <h5 class="text-lg font-semibold text-white">Top produtos vendidos</h5>
    <livewire:componentes.relatorios.dashboard.topprodutosvendidos :dados="$topProdutosVendidos" :key="bin2hex(random_bytes(32))" />
    @else
    <h2 class="text-red-500 font-semibold text-xl">Não contém notas fiscais emitidas no mês atual</h2>
    @endif
  </div>
</div>
