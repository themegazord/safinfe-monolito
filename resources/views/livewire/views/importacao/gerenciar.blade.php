<div class="main">
  <h1>Importação de XML</h1>

  <x-tabs wire:model="importacaoTabSelecionada">
    <x-tab name="importacaoXML-tab" label="Importação de XML" icon="o-document-arrow-down">
      <form wire:submit.prevent="importacaoXML">
        <div class="flex flex-col sm:grid sm:grid-cols-3 gap-4">
          <x-select class="cols-span-2" label="Selecione a empresa que vai receber os XMLS" wire:model="importacaoXMLForm.empresa_id" :options="$empresas" option-value="cnpj" option-label="fantasia" />

          <x-file class="cols-span-1" wire:model="importacaoXMLForm.arquivo" label="Seleciona o arquivo .zip com os XML's" hint="Somente .zip" accept=".zip,application/zip,application/x-zip-compressed" />
        </div>
        @if ($startPolling)
        <div wire:poll.500ms>
          <x-progress :value="$this->progresso['processados']" :max="$this->progresso['total']" />
          <p>Importando: {{ $this->progresso['processados'] }} / {{ $this->progresso['total'] }}</p>
        </div>
        @endif
        <div class="flex flex-row-reverse">
          <x-button type="submit" label="Importar" class="btn btn-primary mt-4" spinner="importacaoXML" />
        </div>
      </form>
    </x-tab>
    <x-tab name="importacaoContabilidade-tab" label="Importacao de Contabilidades" icon="o-building-library">
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
    </x-tab>
    <x-tab name="importacaoEmpresa-tab" label="Importacao de Empresas" icon="o-building-office">
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
    </x-tab>
  </x-tabs>
</div>
