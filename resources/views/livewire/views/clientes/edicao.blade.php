<div class="p-4 md:p-6">
  <h1 class="font-bold text-2xl mb-4">Edição de Clientes</h1>
  <form wire:submit="editar" class="flex flex-col gap-4">
    <h5 class="font-bold text-xl">Dados da cliente:</h5>
    <x-input label="Insira o nome do cliente" placeholder="Insira o nome do cliente..." value="{{ $clienteAtual->nome }}" wire:model.fill="cliente.nome" inline/>
    <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
      <x-input label="Insira o email do cliente" placeholder="Insira o email do cliente..." value="{{ $clienteAtual->email }}" wire:model.fill="cliente.email" inline/>
      <x-button label="Enviar email para troca de senha..." class="btn btn-primary w-full" wire:click="enviaEmailTrocaSenha" spinner="enviaEmailTrocaSenha" wire:loading.attr="disabled" />
    </div>
    <div class="divider"></div>
    <h5 class="font-bold text-xl">Insira a empresa do cliente</h5>
    <x-choices-offline
      placeholder="Seleciona a empresa que o cliente pertence..."
      wire:model="cliente.empresa_id"
      :options="$empresas"
      option-value="empresa_id"
      option-label="fantasia"
      single
      clearable
      searchable
    />
    <div class="flex flex-col-reverse md:flex-row-reverse gap-4">
      <x-button type="submit" class="btn btn-success" label="Salvar" wire:loading.attr="disabled" spinner="editar" />
      <x-button class="btn btn-error" wire:click="voltar" label="Voltar"/>
    </div>
  </form>
</div>
