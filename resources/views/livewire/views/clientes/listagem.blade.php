<div class="main">
  <h1>Listagem de Clientes</h1>
  <livewire:componentes.utils.notificacao.flash />
  <div class="container-tabela-clientes">
    <div class="container-consulta-clientes">
      <div>
        <label for="pesquisa" class="form-label">Insira o dado a ser pesquisado</label>
        <input type="text" class="form-control" id="pesquisa" wire:model.blur="pesquisa" placeholder="Nome, email, empresa...">
      </div>
      <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="ativo_inativo" wire:model.live="estaAtivo">
        <label class="form-check-label" for="ativo_inativo">Inativo / Ativo</label>
      </div>
      <button wire:click="irCadastrar">Cadastrar</button>
    </div>
    <table class="table table-striped table-hover table-sm">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>Email</th>
          <th>Empresa</th>
          <th>Acoes</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($listagem['clientes'] as $cliente)
        <tr wire:key="{{ $cliente->cliente_id }}">
          <td>{{ $cliente->cliente_id }}</td>
          <td>{{ $cliente->nome }}</td>
          <td>{{ $cliente->email }}</td>
          <td>{{ $cliente->fantasia }}</td>
          <td>
            <i class="fa-solid fa-pen-to-square text-primary" wire:click="irEdicaoCliente({{ $cliente->cliente_id }})" style="cursor: pointer;"></i>
            <i class="fa-solid fa-rotate text-danger" style="cursor: pointer;" onclick="emiteEventoExclusaocliente(<?= $cliente->cliente_id ?>)"></i>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{ $listagem['clientes']->links() }}
  </div>
  <script>
    function emiteEventoExclusaocliente(cliente_id) {
      var resposta = confirm("Deseja realmente alterar o status desse cliente?");
      if (resposta) {
        Livewire.dispatch('inativar-cliente', {
          cliente_id
        })
      }
    }
  </script>
  <style>
    .main {
      display: flex;
      flex-direction: column;
      padding: 3rem 0 0 5rem;
    }

    .container-tabela-clientes {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      width: 80%;
    }

    .container-tabela-clientes .container-consulta-clientes {
      display: flex;
      align-items: end;
      gap: 1rem;
    }

    .container-tabela-clientes .container-consulta-clientes button {
      height: 2rem;
      padding: 0 1rem;
      border-radius: 10px;
      border: none;
      background-color: var(--primary-color);
      color: white;
      font-weight: 700;
      transition: var(--tran-04);
    }

    .container-tabela-clientes .container-consulta-clientes button:hover {
      background-color: var(--primary-color-hover);
    }

    .container-tabela-clientes .container-consulta-clientes div:first-child {
      width: 70%;
    }

    .container-tabela-clientes .container-consulta-clientes div:first-child input {
      height: 2rem;
    }
  </style>
</div>
