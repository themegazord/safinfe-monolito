<form wire:submit="alterarSenha" class="container justify-center items-center w-[90%] sm:w-[50%] rounded-lg p-4 bg-gray-600">
  <h1 class="font-bold text-2xl">Insira os dados para resetar a senha.</h1>
  <div class="mb-4">
    <x-input label="Insira o email da conta que deseja alterar a senha" wire:model="resetSenha.email" placeholder="Insira seu email..." inline/>
  </div>

  <x-button class="btn btn-primary" label="Enviar email" wire:click="alterarSenha"/>
</form>
