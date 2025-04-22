<div class="p-4 md:p-6">
  <h1 class="font-bold text-2xl mb-8">Edição de Contabilidades</h1>
  <form wire:submit="editar">
    <div class="flex flex-col gap-4">
      <h5 class="font-bold text-xl">Dados da contabilidade:</h5>
      <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
        <x-input label="Insira a razão social da contabilidade" placeholder="Insira a razão social da contabilidade..." value="{{ $contabilidadeAtual->social }}" wire:model.fill="contabilidade.social" inline />
        <x-input label="Insira o documento da contabilidade" placeholder="Insira o documento da contabilidade..." value="{{ $contabilidadeAtual->cnpj }}" wire:model.fill="contabilidade.documento" inline />
      </div>
      <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
        <x-input label="Insira o telefone corporativo da contabilidade" placeholder="Insira o telefone corporativo da contabilidade..." value="{{ $contabilidadeAtual->telefone_corporativo }}" wire:model.fill="contabilidade.telefone_corporativo" inline />
        <x-input label="Insira a email corporativo da contabilidade" placeholder="Insira a email corporativo da contabilidade..." value="{{ $contabilidadeAtual->email_corporativo }}" wire:model.fill="contabilidade.email_corporativo" inline />
      </div>
      <div class="divider"></div>
      <h5 class="font-bold text-xl">Dados de contato da contabilidade:</h5>
      <x-input label="Insira o email de contato da contabilidade" placeholder="Insira o email de contato da contabilidade..." value="{{ $contabilidadeAtual->email_contato }}" wire:model.fill="contabilidade.email_contato" inline />
      <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
        <x-input label="Insira o telefone de contato da contabilidade" placeholder="Insira o telefone de contato da contabilidade..." value="{{ $contabilidadeAtual->telefone_contato }}" wire:model.fill="contabilidade.telefone_contato" inline />
        <x-input label="Insira o telefone reserva da contabilidade" placeholder="Insira o telefone reserva da contabilidade..." value="{{ $contabilidadeAtual->telefone_reserva }}" wire:model.fill="contabilidade.telefone_reserva" inline />
      </div>
      <div class="divider"></div>
      <h5 class="font-bold text-xl">Empresas que a contabilidade é responsavel</h5>
      <x-choices-offline
        label="Selecione a empresa:"
        wire:model="contabilidade.empresas"
        :options="$empresas"
        option-label="fantasia"
        option-value="empresa_id"
        placeholder="Consulta pelo nome da empresa"
        no-result-text="Opa, não encontramos essa empresa."
        clearable
        searchable />
      <div class="divider"></div>
      <h5 class="font-bold text-xl">Endereço da contabilidade:</h5>
      <div class="flex flex-col md:grid md:grid-cols-5 gap-4">
        <div class="md:col-span-4">
          <x-input label="Insira a rua" placeholder="Insira a rua..." value="{{ $enderecoAtual->rua }}" wire:model.fill="endereco.rua" inline />
        </div>
        <div class="md:col-span-1">
          <x-input label="Numero" placeholder="Numero..." value="{{ $enderecoAtual->numero }}" wire:model.fill="endereco.numero" inline />
        </div>
      </div>
      <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
        <x-input label="Insira o CEP" placeholder="Insira o CEP..." value="{{ $enderecoAtual->cep }}" wire:model.fill="endereco.cep" inline />
        <x-input label="Insira o bairro" placeholder="Insira o bairro..." value="{{ $enderecoAtual->bairro }}" wire:model.fill="endereco.bairro" inline />
      </div>
      <x-textarea rows="3" label="Insira o complemento" placeholder="Insira o complemento..." inline />
      <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
        <x-input label="Insira o cidade" placeholder="Insira o cidade..." value="{{ $enderecoAtual->cidade }}" wire:model.fill="endereco.cidade" inline />
        <x-input label="Insira o estado" placeholder="Insira o estado..." value="{{ $enderecoAtual->estado }}" wire:model.fill="endereco.estado" inline />
      </div>
    </div>
    <div class="flex flex-col-reverse sm:flex-row-reverse gap-4 mt-8">
      <x-button type="submit" class="btn btn-success" label="Editar" spinner="editar" wire:loading.attr="disabled" />
      <x-button class="btn btn-error" wire:click="voltar" label="Voltar" />
    </div>
  </form>
</div>
