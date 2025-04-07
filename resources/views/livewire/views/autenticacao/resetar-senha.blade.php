<form wire:submit="alterarSenha" class="container justify-center items-center w-[90%] sm:w-[50%] rounded-lg p-4 bg-gray-600">
  <h1 class="font-bold text-2xl">Insira os dados para resetar a senha.</h1>
  <div class="mb-4">
    <x-input label="Insira seu email" wire:model="resetSenha.email" placeholder="Insira seu email..." inline/>
  </div>
  <div class="mb-4">
    <x-password label="Insira sua senha antiga" wire:model="resetSenha.oldPassword" placeholder="Insira sua senha antiga..." inline clearable/>
  </div>
  <div class="mb-4">
    <x-password label="Insira sua senha nova" wire:model="resetSenha.newPassword" placeholder="Insira sua senha nova..." inline clearable/>
  </div>
  <div class="flex w-full">
    <x-button class="btn btn-success mx-auto" label="Alterar senha" spinner="alterarSenha" wire:click="Alterar senha"/>
  </div>
</form>
