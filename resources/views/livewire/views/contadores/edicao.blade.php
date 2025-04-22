<div class="p-4 md:p-6 flex flex-col gap-4">
  <h1 class="font-bold text-2xl">Edição de Contadores</h1>
  <form wire:submit="editar" class="flex flex-col gap-4">
    <h5 class="font-bold text-xl">Dados da contador:</h5>
    <x-input label="Insira o nome do contador" value="{{ $contadorAtual->nome }}" placeholder="Insira o nome do contador..." wire:model.fill="contador.nome" inline />
    <x-input label="Insira o email do contador" value="{{ $contadorAtual->email }}" placeholder="Insira o email do contador..." wire:model.fill="contador.email" inline />
    <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
      <div class="col-span-1">
        <x-input label="Insira o CPF do contador" placeholder="Insira o CPF do contador..." value="{{ $contadorAtual->cpf }}" wire:model.fill="contador.cpf" inline />
      </div>
      <div class="col-span-1">
        <x-button wire:click="enviaEmailTrocaSenha" class="btn btn-primary w-full" label="Trocar senha" wire:loading.attr="disabled" spinner="enviaEmailTrocaSenha"/>
      </div>
    </div>
    <div class="divider"></div>
    <h5 class="font-bold text-xl">Insira a contabilidade do contador</h5>
    <x-choices-offline
      placeholder="Seleciona a contabilidade que o contador pertence..."
      wire:model="contador.contabilidade_id"
      :options="$contabilidades"
      option-value="contabilidade_id"
      option-label="social"
      single
      clearable
      searchable />
    <div class="flex flex-col-reverse md:flex-row-reverse gap-4">
      <x-button type="submit" class="btn btn-success" label="Salvar" wire:loading.attr="disabled" spinner="editar" />
      <x-button class="btn btn-error" wire:click="voltar" label="Voltar" />
    </div>
  </form>
</div>
