<form wire:submit="alterarSenha" class="container justify-center items-center w-[90%] sm:w-[50%] rounded-lg p-4 bg-gray-600">
  <h1 class="font-bold text-2xl">Insira sua senha e confirme-a para alterá-la</h1>
  <div class="my-4">
    <x-input label="Email" wire:model="email" placeholder="Insira seu email..." inline disabled/>
  </div>
  <div class="flex flex-col sm:grid sm:grid-cols-2 gap-4">
    <x-password label="Insira sua nova senha" placeholder="Insira sua nova senha..." wire:model="senha" clearable inline/>
    <x-password label="Confirme a sua senha" placeholder="Confirme a sua senha..." wire:model="novaSenha" clearable inline/>
  </div>

  <div class="flex flex-row-reverse mt-8">
    <x-button class="btn btn-primary" type="submit" label="Alterar a senha" spinner="alterarSenha" wire:loading.attr="disabled"/>
  </div>
</form>
