<div class="main">
  <h1>Importação de XML</h1>

  <x-tabs wire:model="importacaoTabSelecionada">
    <x-tab name="importacaoXML-tab" label="Importação de XML" icon="o-document-arrow-down">
      <form wire:submit.prevent="importacaoXML">
        <div class="flex flex-col sm:grid sm:grid-cols-3 gap-4">
          <x-select class="cols-span-2" label="Selecione a empresa que vai receber os XMLS" wire:model="importacaoXMLForm.cnpj" :options="$empresas" option-value="cnpj" option-label="fantasia" />

          <x-file class="cols-span-1" wire:model="importacaoXMLForm.arquivo" label="Seleciona o arquivo .zip com os XML's" hint="Somente .zip" accept=".zip,application/zip,application/x-zip-compressed" />
        </div>
        <div class="flex flex-row-reverse">
          <x-button type="submit" label="Importar" class="btn btn-primary mt-4" spinner="importacaoXML" />
        </div>
      </form>
    </x-tab>
    <x-tab name="importacaoContabilidade-tab" label="Importacao de Contabilidades" icon="o-building-library">
      <form wire:submit="importacaoContabilidade">
        <div class="mb-3">
          <x-file
            wire:model="importacaoContabilidadeForm.arquivo"
            label="Importe o Excel com os dados das contabilidades:"
            hint="Somente arquivos .xls ou .xlsx"
            accept=".xls,.xlsx,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
        </div>
        <div class="flex flex-col sm:flex-row-reverse gap-4">
          <x-button type="submit" class="btn btn-success" label="Importar"/>
          <x-button type="button" class="btn btn-secondary" label="Download Molde" wire:click="downloadArquivoMolde('contabilidade')" />
          <x-button type="button" class="btn btn-error" label="Voltar" wire:click="voltar" />
        </div>
      </form>
    </x-tab>
    <x-tab name="importacaoEmpresa-tab" label="Importacao de Empresas" icon="o-building-office">
      <form wire:submit="importacaoEmpresa">
        <div class="mb-3">
        <x-file
            wire:model="importacaoEmpresaForm.arquivo"
            label="Importe o Excel com os dados das empresas:"
            hint="Somente arquivos .xls ou .xlsx"
            accept=".xls,.xlsx,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
        </div>
        <div class="flex flex-col sm:flex-row-reverse gap-4">
          <x-button type="submit" class="btn btn-success" label="Importar"/>
          <x-button type="button" class="btn btn-secondary" label="Download Molde" wire:click="downloadArquivoMolde('empresa')" />
          <x-button type="button" class="btn btn-error" label="Voltar" wire:click="voltar" />
        </div>
      </form>
    </x-tab>
  </x-tabs>
</div>
