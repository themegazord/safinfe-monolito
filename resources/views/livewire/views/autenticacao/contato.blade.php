<div class="container w-[90%] sm:w-[50%] bg-gray-600 rounded-xl p-4">
  <div class="flex flex-col mb-4">
    <h1 class="font-bold text-2xl">Entre em contato conosco!</h1>
    <h2 class="font-bold text-lg">
      Quer contratar ou saber mais sobre as nossas soluções? Solicite o contato do nosso time
      preenchendo o formulário abaixo:
    </h2>
  </div>
  <form wire:submit="contatar">
    <div class="mb-4">
      <x-input label="Insira seu nome completo" placeholder="Insira seu nome completo..." wire:model="nome" inline />
    </div>
    <div class="mb-4">
      <x-input label="Insira seu email" placeholder="Insira seu email..." wire:model="email" type="email" inline />
    </div>
    <div class="mb-4">
      <x-input label="Insira seu telefone" placeholder="Insira seu telefone..." wire:model="telefone" inline />
    </div>
    <div class="mb-4">
      <x-textarea wire:model="assunto" rows="5" placeholder="Insira seu assunto..." label="Insira seu assunto" inline />
    </div>
    <div class="flex w-full">
      <x-button class="btn btn-primary mx-auto" label="Entrar em contato!" type="submit" spinner="contatar"/>
    </div>
  </form>
</div>
