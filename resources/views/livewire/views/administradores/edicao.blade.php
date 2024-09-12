<div class="main">
  <h1>Edição de Administradores</h1>
  <livewire:componentes.utils.notificacao.flash />
  <form wire:submit="editar">
    <h5>Dados da administrador:</h5>
    <div>
      <label for="name" class="form-label">Insira o nome do administrador</label>
      <input type="text" wire:model.fill="administrador.name" value="{{ $administradorAtual['name'] }}" id="name" class="form-control">
      @error('administrador.name') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="subinput-form-administradors">
      <div>
        <label for="email" class="form-label">Insira o email do administrador</label>
        <input type="email" wire:model.fill="administrador.email" value="{{ $administradorAtual['email'] }}" id="email" class="form-control">
        @error('administrador.email') <span class="form-error">{{ $message }}</span> @enderror
      </div>
      <button wire:click="enviaEmailTrocaSenha" class="envia-troca-senha">Enviar email para troca de senha...</button>
    </div>
    <div class="form-endereco-acoes">
      <button type="submit" class="cadastrar">Editar</button>
      <button class="botao-voltar" wire:click="voltar">Voltar</button>
    </div>
  </form>

  <style>
    .main {
      padding: 3rem 0 0 5rem;
    }

    form {
      display: flex;
      flex-direction: column;
      width: 80%;
      gap: .5rem;
      padding-bottom: 2rem;
    }

    .subinput-form-administradors {
      display: grid;
      grid-template-columns: 50% 50%;
      gap: 1rem;
    }

    .subinput-form-administradors.logradouro {
      grid-template-columns: 77% 20%;
      gap: 1rem;
    }

    .form-endereco-acoes {
      display: flex;
      gap: 1rem;
      flex-direction: row-reverse;
    }

    .form-endereco-acoes button,
    .envia-troca-senha {
      padding: .5rem 1rem;
      border: none;
      border-radius: 5px;
      background-color: var(--primary-color);
      color: white;
      font-weight: 700;
      transition: var(--tran-04);
    }

    .form-endereco-acoes button:hover,
    .envia-troca-senha:hover {
      background-color: var(--primary-color-hover);
    }


    @media screen and (max-width: 1060px) {
      .subinput-form-administradors {
        display: flex;
        flex-direction: column;
        gap: 1rem;
      }

      .subinput-form-administradors.logradouro {
        display: grid;
        grid-template-columns: 77% 20%;
      }
    }

    @media screen and (max-width: 590px) {
      .main {
        padding: 1.5rem 0 0 2.5rem;
      }

      .form-endereco-acoes button,
      .envia-troca-senha {
        padding: .25rem .5rem;
        border: none;
        border-radius: 5px;
        background-color: var(--primary-color);
        color: white;
        font-weight: 700;
        font-size: .7rem;
        transition: var(--tran-04);
      }
    }

    @media screen and (max-width: 470px) {
      h1 {
        font-size: 1.5rem;
      }

      h5 {
        font-size: 1rem;
      }

      label {
        font-size: .8rem;
      }

      .subinput-form-administradors.logradouro {
        display: flex;
        flex-direction: column;
        gap: 1rem;
      }
    }

    @media screen and (max-width: 370px) {
      .main {
        padding: .5rem 0 0 1rem;
      }
    }
  </style>
</div>
