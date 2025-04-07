<div class="flex justify-center items-center h-[90vh] bg-gray-950">
  <form wire:submit="logar" class="p-4 bg-gray-600 container w-[90%] sm:w-[50%] rounded-xl">
    <div class="flex flex-col mb-4">
      <h1 class="text-lg font-bold">Que bom te ver de novo!</h1>
      <h3 class="text-lg font-bold">Acesse e use agora mesmo!</h3>
    </div>
    <div class="mb-4">
      <x-input label="Insira seu email:" wire:model="login.email" placeholder="Insira seu email" inline />
    </div>
    <div class="mb-4">
      <x-password label="Insira sua senha:" hint="Clique para visualizar sua senha" wire:model="login.password" clearable inline />
    </div>
    <div class="flex flex-col sm:flex-row sm:justify-between">
      <div class="mb-2">
        <x-checkbox label="Lembrar senha?" wire:model="item1" />
      </div>
      <p href="/resetar-senha" class="text-sm link link-hover" wire:model="lembraSenha">Esqueceu sua senha?</a>
    </div>
    <div class="flex w-full">
      <x-button class="btn btn-primary mx-auto" label="Entrar" spinner="logar" wire:click="logar"/>
    </div>
  </form>
</div>
