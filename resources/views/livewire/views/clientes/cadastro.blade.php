<div class="main">
  <h1>Cadastro de Clientes</h1>
  <livewire:componentes.utils.notificacao.flash />
  <form wire:submit="cadastrar">
    <h5>Dados da cliente:</h5>
    <div>
      <label for="nome" class="form-label">Insira o nome do cliente</label>
      <input type="text" wire:model="cliente.nome" id="nome" class="form-control">
      @error('cliente.nome') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="subinput-form-clientes">
      <div>
        <label for="email" class="form-label">Insira o email do cliente</label>
        <input type="email" wire:model="cliente.email" id="email" class="form-control">
        @error('cliente.email') <span class="form-error">{{ $message }}</span> @enderror
      </div>
      <div>
        <label for="senha" class="form-label">Insira a senha do cliente</label>
        <input type="password" wire:model="usuario.password" id="senha" class="form-control">
        @error('usuario.password') <span class="form-error">{{ $message }}</span> @enderror
      </div>
    </div>
    <hr>
    <h5>Insira a empresa do cliente</h5>
    <select class="form-select" aria-label="Seleciona a empresa que o cliente pertence..." wire:model="cliente.empresa_id">
      <option value="{{ null }}" selected>Seleciona a empresa que o cliente pertence...</option>
      @foreach ($empresas as $empresa)
      <option value="{{ $empresa->getAttribute('empresa_id') }}">{{ $empresa->getAttribute('fantasia') }}</option>
      @endforeach
    </select>
    <div class="form-endereco-acoes">
      <button type="submit" class="cadastrar">Cadastrar</button>
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

    .subinput-form-clientes {
      display: grid;
      grid-template-columns: 50% 50%;
      gap: 1rem;
    }

    .subinput-form-clientes.logradouro {
      grid-template-columns: 77% 20%;
      gap: 1rem;
    }

    .form-endereco-acoes {
      display: flex;
      gap: 1rem;
      flex-direction: row-reverse;
    }

    .form-endereco-acoes button {
      padding: .5rem 1rem;
      border: none;
      border-radius: 5px;
      background-color: var(--primary-color);
      color: white;
      font-weight: 700;
      transition: var(--tran-04);
    }

    .form-endereco-acoes button:hover {
      background-color: var(--primary-color-hover);
    }


    @media screen and (max-width: 1060px) {
      .subinput-form-clientes {
        display: flex;
        flex-direction: column;
        gap: 1rem;
      }

      .subinput-form-clientes.logradouro {
        display: grid;
        grid-template-columns: 77% 20%;
      }
    }

    @media screen and (max-width: 590px) {
      .main {
        padding: 1.5rem 0 0 2.5rem;
      }

      .form-endereco-acoes button {
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

      .subinput-form-clientes.logradouro {
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
