<div class="main">
  <h1>Importação de XML</h1>
  <livewire:componentes.utils.notificacao.flash />

  <form wire:submit="verXML">
    <select class="form-select" aria-label="Selecione a empresa que vai receber os XMLS" wire:model="cnpjEmpresaAtual">
      <option value="{{ null }}" selected>Selecione a empresa que vai receber os XMLS</option>
      @foreach ($empresas as $empresa)
      <option value="{{ $empresa->getAttribute('cnpj') }}">{{ $empresa->getAttribute('fantasia') }}</option>
      @endforeach
    </select>

    <div class="mb-3">
      <label for="arquivos" class="form-label">Default file input example</label>
      <input class="form-control" type="file" id="arquivos" wire:model="arquivo">
    </div>

    <div class="form-xml-acoes">
      <button type="submit">
        Ver XML
        <div wire:loading wire:target="arquivo">
          <div class="spinner-border spinner-border-sm" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </button>
    </div>
  </form>
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
      padding-bottom: 2rem;
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
