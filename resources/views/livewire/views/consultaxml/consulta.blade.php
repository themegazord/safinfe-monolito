<div class="main">
  <h1>Consulta XML</h1>
  <livewire:componentes.utils.notificacao.flash />
  <div class="container-consulta-xml">
    @if ($usuario->getAttribute('role') === 'CLIENTE')
    <form wire:submit="consulta">
      <div class="grupo-consulta-xml">
        <div class="grupo-consulta-xml">
          <div class="input-data">
            <label for="data-inicio">Data inicio</label>
            <input type="date" name="data-inicio" id="data-inicio" wire:model="consultaCliente.data_inicio" autocomplete="off">
            @error('consultaCliente.data_inicio')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
          <div class="input-data">
            <label for="data-fim">Data fim</label>
            <input type="date" name="data-fim" id="data-fim" wire:model="consultaCliente.data_fim" autocomplete="off">
            @error('consultaCliente.data_fim')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="subgrupo-consulta-xml">
          <div class="form-floating">
            <input type="text" class="form-control" id="serie" placeholder="Série" wire:model="consultaCliente.serie" autocomplete="off">
            <label for="serie">Série</label>
          </div>
          <select class="form-select" aria-label="Selecione o tipo da nota fiscal" wire:model="consultaCliente.modelo" autocomplete="off">
            <option value="TODAS" selected>Selecione o tipo da nota fiscal</option>
            <option value="55">NF-e</option>
            <option value="65">NFC-e</option>
          </select>
        </div>
      </div>
      <div class="grupo-consulta-xml">
        <select class="form-select" aria-label="Selecione o status" wire:model="consultaCliente.status" autocomplete="off">
          <option value="TODAS" selected>Selecione o status da nota fiscal</option>
          <option value="AUTORIZADO">Autorizadas</option>
          <option value="CANCELADO">Canceladas</option>
          <option value="INUTILIZADO">Inutilizadas</option>
        </select>
        <div class="grupo-consulta-xml">
          <div class="form-floating">
            <input type="text" class="form-control" id="numeroInicial" placeholder="Série" wire:model="consultaCliente.numeroInicial" autocomplete="off">
            <label for="numeroInicial">Numero inicial:</label>
          </div>
          <div class="form-floating">
            <input type="text" class="form-control" id="numeroFinal" placeholder="Série" wire:model="consultaCliente.numeroFinal" autocomplete="off">
            <label for="numeroFinal">Numero Final:</label>
          </div>
        </div>
      </div>
      <div class="form-consulta-xml-acoes">
        <button type="submit">Consultar</button>
        <button type="button" wire:click="downloadDireto">Download Direto</button>
      </div>
    </form>
    @endif
    @if ($usuario->getAttribute('role') === 'CONTADOR')
    <form wire:submit="consulta">
      <div class="grupo-consulta-xml">
        <div class="grupo-consulta-xml">
          <div class="input-data">
            <label for="data-inicio">Data inicio</label>
            <input type="date" name="data-inicio" id="data-inicio" wire:model="consultaContador.data_inicio" autocomplete="off">
            @error('consultaContador.data_inicio')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
          <div class="input-data">
            <label for="data-fim">Data fim</label>
            <input type="date" name="data-fim" id="data-fim" wire:model="consultaContador.data_fim" autocomplete="off">
            @error('consultaContador.data_fim')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="subgrupo-consulta-xml">
          <div class="form-floating">
            <input type="text" class="form-control" id="serie" placeholder="Série" wire:model="consultaContador.serie" autocomplete="off">
            <label for="serie">Série</label>
          </div>
          <select class="form-select" aria-label="Selecione o tipo da nota fiscal" wire:model="consultaContador.modelo" autocomplete="off">
            <option value="TODAS" selected>Selecione o tipo da nota fiscal</option>
            <option value="55">NF-e</option>
            <option value="65">NFC-e</option>
          </select>
        </div>
      </div>
      <div class="grupo-consulta-xml">
        <select class="form-select" aria-label="Selecione o status" wire:model="consultaContador.status" autocomplete="off">
          <option value="TODAS" selected>Selecione o status da nota fiscal</option>
          <option value="AUTORIZADO">Autorizadas</option>
          <option value="CANCELADO">Canceladas</option>
          <option value="INUTILIZADO">Inutilizadas</option>
        </select>
        <div class="grupo-consulta-xml">
          <div class="form-floating">
            <input type="text" class="form-control" id="numeroInicial" placeholder="Série" wire:model="consultaContador.numeroInicial" autocomplete="off">
            <label for="numeroInicial">Numero inicial:</label>
          </div>
          <div class="form-floating">
            <input type="text" class="form-control" id="numeroFinal" placeholder="Série" wire:model="consultaContador.numeroFinal" autocomplete="off">
            <label for="numeroFinal">Numero Final:</label>
          </div>
        </div>
      </div>
      <select class="form-select" aria-label="Selecione a empresa que você quer consultar as notas fiscais." wire:model="consultaContador.empresa_id" autocomplete="off">
        <option selected>Selecione a empresa que você quer consultar as notas fiscais.</option>
        @foreach ($empresas as $empresa)
        <option value="{{ $empresa->getAttribute('empresa_id') }}">{{ $empresa->getAttribute('fantasia') }}</option>
        @endforeach
      </select>
      @error('consultaContador.empresa_id')
      <span class="text-danger">{{ $message }}</span>
      @enderror
      <div class="form-consulta-xml-acoes">
        <button type="submit">Consultar</button>
        <button type="button" wire:click="downloadDireto">Download Direto</button>
      </div>
    </form>
    @endif
  </div>

  <style>
    .main {
      display: flex;
      flex-direction: column;
      padding: 3rem 0 0 5rem;
    }

    .container-consulta-xml {
      display: flex;
      flex-direction: column;
      width: 80%;
    }

    .grupo-consulta-xml {
      display: grid;
      grid-template-columns: 48% 48%;
      gap: 1rem;
      margin-bottom: 1rem;
    }

    .subgrupo-consulta-xml {
      display: flex;
      gap: 1rem;
      width: 100%;
    }

    .input-data {
      display: flex;
      flex-direction: column;
    }

    .input-data>input {
      width: 100%;
    }

    .form-consulta-xml-acoes {
      display: flex;
      flex-direction: row-reverse;
      gap: 2rem;
      margin-top: 2rem;
    }

    .form-consulta-xml-acoes button:last-child {
      background-color: var(--green-confirm);
    }

    .form-consulta-xml-acoes button {
      padding: .5rem 1rem;
      border: none;
      border-radius: 5px;
      background-color: var(--primary-color);
      color: white;
      font-weight: 700;
      transition: var(--tran-04);
      width: 100%;
    }

    .form-consulta-xml-acoes button:hover {
      background-color: var(--primary-color-hover);
    }
  </style>
</div>
