<div class="container-form-contato">
  <div class="titulo-subtitulo">
    <h1>Entre em contato conosco!</h1>
    <h2>
      Quer contratar ou saber mais sobre as nossas soluções? Solicite o contato do nosso time
      preenchendo o formulário abaixo:
    </h2>
  </div>
  <form wire:submit="contatar">
    <div class="form-floating">
      <input type="text" wire:model="contato.nome_completo" id="nome_completo" class="form-control" placeholder="Insira seu nome completo">
      <label for="nome_completo" class="floatingInput">Insira seu nome completo:</label>
      @error('contato.nome_completo') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="form-floating">
      <input type="email" wire:model="contato.email" id="email" class="form-control" placeholder="Insira seu email">
      <label for="email" class="floatingInput">Insira seu email:</label>
      @error('contato.email') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="form-floating">
      <input type="text" wire:model="contato.telefone" id="telefone" class="form-control" placeholder="Insira seu telefone">
      <label for="telefone" class="floatingInput">Insira seu telefone:</label>
      @error('contato.telefone') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="form-floating">
      <textarea type="text" wire:model="contato.assunto" id="assunto" class="form-control" placeholder="Insira seu assunto"></textarea>
      <label for="assunto" class="floatingInput">Assunto:</label>
      @error('contato.assunto') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="container-form-acoes">
      <button type="submit">
        Enviar
        <div wire:loading class="loading">
          <i class="fa-solid fa-spinner"></i>
        </div>
      </button>
    </div>
  </form>
  <style>
    .container-form-contato {
      height: 90vh;
      display: flex;
      flex-direction: column;
      justify-content: space-evenly;
    }

    .titulo-subtitulo h1 {
    color: var(--darkblue);
    font-family: DM Sans;
    font-size: 2.5rem;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    text-align: center;
  }

  .titulo-subtitulo h2 {
    color: var(--darkblue);
    font-family: Poppins;
    font-size: 1.7rem;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
    text-align: center;
  }

  .container-form-contato > form {
    width: 60%;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }

  .container-form-acoes {
    display: flex;
    flex-direction: row-reverse;
  }

  .container-form-acoes > button {
    padding: .5rem 1rem;
    border-radius: 5px;
    border: none;
    background-color: var(--darkblue);
    color: white;
    font-weight: 700;
  }
  </style>
</div>
