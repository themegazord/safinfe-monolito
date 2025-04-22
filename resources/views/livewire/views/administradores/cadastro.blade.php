<div class="p-4 md:p-6 flex flex-col gap-4">
  <h1 class="font-bold text-2xl">Cadastro de Administradores</h1>
  <form wire:submit="cadastrar" class="flex flex-col gap-4">
    <h5 class="font-bold text-xl">Dados da administrador:</h5>
    <x-input label="Insira o nome do administrador" placeholder="Insira o nome do administrador..." wire:model="administrador.name" inline />
    <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
      <div class="col-span-1">
        <x-input label="Insira o email do administrador" placeholder="Insira o email do administrador..." wire:model="administrador.email" inline />
      </div>
      <div class="col-span-1">
        <x-password label="Insira a senha do administrador" placeholder="Insira a senha do administrador..." wire:model="administrador.password" clearable inline />
      </div>
    </div>
    <div class="flex flex-col-reverse md:flex-row-reverse gap-4">
      <x-button type="submit" class="btn btn-success" label="Cadastrar" wire:loading.attr="disabled" spinner="cadastrar"/>
      <x-button class="btn btn-error" wire:click="voltar" label="Voltar" />
    </div>
  </form>
</div>
