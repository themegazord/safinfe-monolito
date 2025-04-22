<div class="p-4 md:p-6 flex flex-col gap-4">
  <h1 class="font-bold text-2xl">Cadastro de Contadores</h1>
  <form wire:submit="cadastrar" class="flex flex-col gap-4">
    <h5 class="font-bold text-xl">Dados da contador:</h5>
    <x-input label="Insira o nome do contador" placeholder="Insira o nome do contador..." wire:model="contador.nome" inline />
    <x-input label="Insira o email do contador" placeholder="Insira o email do contador..." wire:model="contador.email" inline />
    <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
      <div class="col-span-1">
        <x-input label="Insira o CPF do contador" placeholder="Insira o CPF do contador..." wire:model="contador.cpf" inline />
      </div>
      <div class="col-span-1">
        <x-password label="Insira a senha do contador" placeholder="Insira o senha do contador..." wire:model="usuario.password" clearable inline />
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
      searchable
    />
    @error('contador.contabilidade_id') <span class="form-error">{{ $message }}</span> @enderror
    <div class="flex flex-col-reverse md:flex-row-reverse gap-4">
      <x-button type="submit" class="btn btn-success" label="Cadastrar" wire:loading.attr="disabled" spinner="cadastrar" />
      <x-button class="btn btn-error" wire:click="voltar" label="Voltar"/>
    </div>
  </form>
</div>
