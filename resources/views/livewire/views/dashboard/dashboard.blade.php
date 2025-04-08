<div class="container">
  <h1>Dashboard</h1>
  <div class="flex flex-col sm:grid sm:grid-cols-4 gap-4 items-end">
    @if ($usuario->getAttribute('role') === 'CONTADOR')
    <div>
      <label for="empresaContador">Selecione a empresa:</label>
      <select wire:model="consulta.empresa_id" class="form-select" id="empresaContador" aria-label="Selecione a empresa...">
        <option selected>Selecione a empresa...</option>
        @foreach ($empresasContador as $empresa)
        <option value="{{ $empresa->getAttribute('empresa_id') }}">{{ $empresa->getAttribute('fantasia') }}</option>
        @endforeach
      </select>
    </div>
    @endif
    @if ($usuario->getAttribute('role') === 'ADMIN')
    <div class="col-span-2">
      <x-select wire:model="consulta.empresa_id" label="Selecione a empresa:" :options="$empresasGeral" option-value="empresa_id" option-label="fantasia" id="empresaGeral" aria-label="Selecione a empresa..." />
    </div>
    @endif
    @if ($usuario->getAttribute('role') === 'CLIENTE')
    <div>
      <label for="empresaCliente" class="form-label">Sua empresa:</label>
      <input type="text" class="form-control" id="empresaCliente"
        placeholder="name@example.com"
        value="{{ $usuario->cliente->empresa->getAttribute('fantasia') }}">
    </div>
    @endif
    @php
      $configRangeDatePicker = ['altFormat' => 'd/m/Y', 'mode' => 'range', 'locale' => 'pt'];
    @endphp
    <x-datepicker label="Data inicial - Data final" wire:model="consulta.data_inicio_fim" icon="o-calendar" :config="$configRangeDatePicker" class="col-span-1"/>
    <x-button wire:click="consultar" label="Gerar Dashboard" class="btn btn-primary" class="col-span-1" spinner="consultar"/>
  </div>
  <div class="container-dashboard-informacoes">
    @if (!is_null($informacoesTotaisNotas) && !is_null($totalNotasPorDiaMes) && !is_null($topProdutosVendidos))
    <h5>Valores totais de todas as notas mensais</h5>
    <livewire:componentes.relatorios.dashboard.valorestotaismensais :dados="$informacoesTotaisNotas" :key="bin2hex(random_bytes(32))"/>
    <livewire:componentes.relatorios.dashboard.totalnotaspordiames :dados="$totalNotasPorDiaMes" :key="bin2hex(random_bytes(32))"/>
    <livewire:componentes.relatorios.dashboard.topprodutosvendidos :dados="$topProdutosVendidos" :key="bin2hex(random_bytes(32))"/>
    @else
    <h1>Nao contem notas fiscais emitidas no mes atual</h1>
    @endif
  </div>
</div>
