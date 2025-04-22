<div class="p-4 md:p-6 flex flex-col gap-4">
  <h1 class="font-bold text-2xl">Edição de Administradores</h1>
  <form wire:submit="editar" class="flex flex-col gap-4">
    <h5 class="font-bold text-xl">Dados da administrador:</h5>
    <x-input label="Insira o nome do administrador" placeholder="Insira o nome do administrador..." value="{{ $administradorAtual->name }}" wire:model.fill="administrador.name" inline />
    <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
      <div class="col-span-1">
        <x-input label="Insira o email do administrador" placeholder="Insira o email do administrador..." value="{{ $administradorAtual->email }}" wire:model.fill="administrador.email" inline />
      </div>
      <x-button class="btn btn-primary w-full col-span-1" label="Enviar email para troca de senha" wire:click="enviarEmailResetSenha" wire:loading.attr="disabled" spinner="enviarEmailResetSenha"/>
    </div>
    <div class="flex flex-col-reverse md:flex-row-reverse gap-4">
      <x-button type="submit" class="btn btn-success" label="Salvar" />
      <x-button class="btn btn-error" wire:click="voltar" label="Voltar"/>
    </div>
  </form>
</div>
