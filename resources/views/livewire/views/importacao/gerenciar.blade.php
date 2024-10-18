<div class="main">
  <h1>Importação de XML</h1>
  <livewire:componentes.utils.notificacao.flash />

  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="importacaoXML-tab" data-bs-toggle="tab" wire:ignore.self data-bs-target="#importacaoXML" type="button" role="tab" aria-controls="importacaoXML" aria-selected="true">Importacao de XML</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="importacaoContabilidade-tab" data-bs-toggle="tab" wire:ignore.self data-bs-target="#importacaoContabilidade" type="button" role="tab" aria-controls="importacaoContabilidade" aria-selected="false">Importacao de Contabilidades</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="importacaoEmpresa-tab" data-bs-toggle="tab" data-bs-target="#importacaoEmpresa" wire:ignore.self type="button" role="tab" aria-controls="importacaoEmpresa" aria-selected="false">Importacao de Empresas</button>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade" id="importacaoXML" role="tabpanel" wire:ignore.self aria-labelledby="importacaoXML-tab">
      <form wire:submit="importacaoXML">
        <select class="form-select" aria-label="Selecione a empresa que vai receber os XMLS" wire:model="importacaoXMLForm.empresa_id">
          <option value="{{ null }}" selected>Selecione a empresa que vai receber os XMLS</option>
          @foreach ($empresas as $empresa)
          <option value="{{ $empresa->getAttribute('cnpj') }}">{{ $empresa->getAttribute('fantasia') }}</option>
          @endforeach
        </select>

        @error('importacaoXMLForm.empresa_id')
        <span class="text-danger">{{ $message }}</span>
        @enderror

        <div class="mb-3">
          <label for="arquivos" class="form-label">Seleciona o arquivo .rar com os XML's</label>
          <input class="form-control" type="file" id="arquivos" wire:model="importacaoXMLForm.arquivo">
          @error('importacaoXMLForm.arquivo')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-xml-acoes">
          <button type="submit">
            Importar
            <div wire:loading wire:target="importacaoXMLForm.arquivo">
              <div class="spinner-border spinner-border-sm" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
          </button>
        </div>
      </form>
    </div>
    <div class="tab-pane fade" id="importacaoContabilidade" role="tabpanel" wire:ignore.self aria-labelledby="importacaoContabilidade-tab">
      <form wire:submit="importacaoContabilidade">
        <div class="mb-3">
          <label for="arquivos" class="form-label">Importe o Excel com os dados das contabilidades: </label>
          <input class="form-control" type="file" id="arquivos" wire:model="importacaoContabilidadeForm.arquivo">
          @error('importacaoContabilidadeForm.arquivo')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>
        <div class="form-xml-acoes">
          <button type="submit" class="float-end me-1">Importar</button>
          <button type="button" class="float-end me-1" wire:click="downloadArquivoMolde('contabilidade')">Download Molde</button>
          <button type="button" class="float-end me-1" wire:click="voltar">Voltar</button>
        </div>
      </form>
    </div>
    <div class="tab-pane fade" id="importacaoEmpresa" role="tabpanel" wire:ignore.self aria-labelledby="importacaoEmpresa-tab">
      <form wire:submit="importacaoEmpresa">
        <div class="mb-3">
          <label for="arquivos" class="form-label">Importe o Excel com os dados das empresas: </label>
          <input class="form-control" type="file" id="arquivos" wire:model="importacaoEmpresaForm.arquivo">
          @error('importacaoEmpresaForm.arquivo')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>
        <div class="form-xml-acoes">
          <button type="submit" class="float-end me-1">Importar</button>
          <button type="button" class="float-end me-1" wire:click="downloadArquivoMolde('empresa')">Download Molde</button>
          <button type="button" class="float-end me-1" wire:click="voltar">Voltar</button>
        </div>
      </form>
    </div>
  </div>

  <style>
    .main {
      display: flex;
      flex-direction: column;
      padding: 3rem 0 0 5rem;
    }

    form {
      display: flex;
      flex-direction: column;
      width: 80%;
      gap: .5rem;
      padding: 2rem 0;
    }

    .form-xml-acoes button {
      padding: .5rem 1rem;
      border: none;
      border-radius: 5px;
      background-color: var(--primary-color);
      color: white;
      font-weight: 700;
      transition: var(--tran-04);
    }
  </style>
</div>
