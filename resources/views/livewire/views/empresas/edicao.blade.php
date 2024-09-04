<div class="main">
  <h1>Edição de Empresas</h1>
  <livewire:componentes.utils.notificacao.flash />
  <form wire:submit="editar">
    <h5>Dados da empresa:</h5>
    <div>
      <label for="fantasia" class="form-label">Insira o nome fantasia da empresa</label>
      <input type="text" wire:model.fill="empresa.fantasia" value="{{ $empresaAtual['fantasia'] }}" id="fantasia" class="form-control">
      @error('empresa.fantasia') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div>
      <label for="social" class="form-label">Insira a razão social da empresa</label>
      <input type="text" wire:model.fill="empresa.social" value="{{ $empresaAtual['social'] }}" id="social" class="form-control">
      @error('empresa.social') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="subinput-form-empresas">
      <div>
        <label for="cnpj" class="form-label">Insira o CNPJ da empresa</label>
        <input type="text" wire:model.fill="empresa.cnpj" value="{{ $empresaAtual['cnpj'] }}" id="cnpj" class="form-control cnpj">
        @error('empresa.cnpj') <span class="form-error">{{ $message }}</span> @enderror
      </div>
      <div>
        <label for="ie" class="form-label">Insira a IE da empresa</label>
        <input type="text" wire:model.fill="empresa.ie" value="{{ $empresaAtual['ie'] }}" id="ie" class="form-control">
        @error('empresa.ie') <span class="form-error">{{ $message }}</span> @enderror
      </div>
    </div>
    <hr>
    <h5>Dados de contato da empresa:</h5>
    <div>
      <label for="email" class="form-label">Insira o email de contato da empresa</label>
      <input type="email" wire:model.fill="empresa.email_contato" value="{{ $empresaAtual['email_contato'] }}" id="email" class="form-control">
      @error('empresa.email_contato') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="subinput-form-empresas">
      <div>
        <label for="telefone_contato" class="form-label">Insira o telefone de contato da empresa</label>
        <input type="text" wire:model.fill="empresa.telefone_contato" value="{{ $empresaAtual['telefone_contato'] }}" id="telefone_contato" class="form-control cellphone_with_ddd">
        @error('empresa.telefone_contato') <span class="form-error">{{ $message }}</span> @enderror
      </div>
      <div>
        <label for="telefone_reserva" class="form-label">Insira o telefone reserva da empresa</label>
        <input type="text" wire:model.fill="empresa.telefone_reserva" value="{{ $empresaAtual['telefone_reserva'] }}" id="telefone_reserva" class="form-control cellphone_with_ddd">
        @error('empresa.telefone_reserva') <span class="form-error">{{ $message }}</span> @enderror
      </div>
    </div>
    <hr>
    <h5>Endereço da empresa:</h5>
    <div class="subinput-form-empresas logradouro">
      <div>
        <label for="rua" class="form-label">Insira a rua</label>
        <input type="text" wire:model.fill="endereco.rua" value="{{ $enderecoAtual['rua'] }}" id="rua" class="form-control">
        @error('endereco.rua') <span class="form-error">{{ $message }}</span> @enderror
      </div>
      <div>
        <label for="numero" class="form-label">Numero</label>
        <input type="text" wire:model.fill="endereco.numero" value="{{ $enderecoAtual['numero'] }}" id="numero" class="form-control">
        @error('endereco.numero') <span class="form-error">{{ $message }}</span> @enderror
      </div>
    </div>
    <div class="subinput-form-empresas">
      <div>
        <label for="cep" class="form-label">Insira o CEP</label>
        <input type="text" wire:model.fill="endereco.cep" value="{{ $enderecoAtual['cep'] }}" id="cep" class="form-control cep">
        @error('endereco.cep') <span class="form-error">{{ $message }}</span> @enderror
      </div>
      <div>
        <label for="bairro" class="form-label">Insira o bairro</label>
        <input type="text" wire:model.fill="endereco.bairro" value="{{ $enderecoAtual['bairro'] }}" id="bairro" class="form-control">
        @error('endereco.bairro') <span class="form-error">{{ $message }}</span> @enderror
      </div>
    </div>
    <div>
      <label for="complemento" class="form-label">Insira o complemento</label>
      <input type="text" wire:model.fill="endereco.complemento" value="{{ $enderecoAtual['complemento'] }}" id="complemento" class="form-control">
      @error('endereco.complemento') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="subinput-form-empresas">
      <div>
        <label for="cidade" class="form-label">Insira o cidade</label>
        <input type="text" wire:model.fill="endereco.cidade" value="{{ $enderecoAtual['cidade'] }}" id="cidade" class="form-control">
        @error('endereco.cidade') <span class="form-error">{{ $message }}</span> @enderror
      </div>
      <div>
        <label for="estado" class="form-label">Insira o estado</label>
        <input type="text" wire:model.fill="endereco.estado" value="{{ $enderecoAtual['estado'] }}" id="estado" class="form-control">
        @error('endereco.estado') <span class="form-error">{{ $message }}</span> @enderror
      </div>
    </div>
    <div class="form-endereco-acoes">
      <button type="submit" class="cadastrar">Editar</button>
      <button type="button" class="botao-voltar" wire:click="voltar">Voltar</button>
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

    .subinput-form-empresas {
      display: grid;
      grid-template-columns: 50% 50%;
      gap: 1rem;
    }

    .subinput-form-empresas.logradouro {
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
      .subinput-form-empresas {
        display: flex;
        flex-direction: column;
        gap: 1rem;
      }

      .subinput-form-empresas.logradouro {
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

      .subinput-form-empresas.logradouro {
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
