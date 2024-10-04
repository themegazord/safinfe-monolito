<app>
  <div class="main">
    <h1>Dashboard</h1>
    <div class="container-dashboard-campos-consulta">
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
      <div>
        <label for="empresaGeral">Selecione a empresa:</label>
        <select wire:model="consulta.empresa_id" class="form-select" id="empresaGeral" aria-label="Selecione a empresa...">
          <option selected>Selecione a empresa...</option>
          @foreach ($empresasGeral as $empresa)
          <option value="{{ $empresa->getAttribute('empresa_id') }}">{{ $empresa->getAttribute('fantasia') }}</option>
          @endforeach
        </select>
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
      <div class="subcontainer-dashboard-campos-consulta">
        <div class="input-data">
          <label for="data-inicio">Data inicio</label>
          <input type="date" name="data-inicio" id="data-inicio" wire:model="consulta.data_inicio">
          @error('consulta.data_inicio')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>
        <div class="input-data">
          <label for="data-fim">Data fim</label>
          <input type="date" name="data-fim" id="data-fim" wire:model="consulta.data_fim">
          @error('consulta.data_fim')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>
      </div>
      <button type="button" wire:click="consultar">Gerar Dashboard</button>
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
  <style>
    .main {
      display: flex;
      flex-direction: column;
      padding: 3rem 0 0 1rem;
    }

    .container-dashboard-campos-consulta {
      display: grid;
      grid-template-columns: 1fr .4fr .3fr;
      align-items: end;
      width: 80%;
      gap: 1rem;
    }

    .subcontainer-dashboard-campos-consulta {
      display: flex;
      gap: 1rem;
    }

    .subcontainer-dashboard-campos-consulta .input-data {
      display: flex;
      flex-direction: column;
    }

    .container-dashboard-campos-consulta button {
      padding: .5rem 1rem;
      border: none;
      border-radius: 5px;
      height: 3rem;
      background-color: var(--primary-color);
      color: white;
      font-weight: 700;
      transition: var(--tran-04);
      width: 100%;
    }

    .container-dashboard-campos-consulta button:hover {
      background-color: var(--primary-color-hover);
    }

    .container-dashboard-informacoes {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
  </style>
</app>
