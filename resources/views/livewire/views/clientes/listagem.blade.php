<div class="main">
  <h1>Listagem de Clientes</h1>
  <livewire:componentes.utils.notificacao.flash />
  <div class="container-tabela-clientes">
    <div class="container-consulta-clientes">
      <div>
        <label for="pesquisa" class="form-label">Razão social, nome fantasia, CNPJ, IE, email de contato...</label>
        <input type="text" class="form-control" id="pesquisa" wire:model="pesquisa">
      </div>
      <button wire:click="irCadastrar">Cadastrar</button>
    </div>
    <table class="table table-striped table-hover table-sm">
      <thead>
        <tr>
          <th>ID</th>
          <th>Razão Social</th>
          <th>Nome Fantasia</th>
          <th>CNPJ</th>
          <th>IE</th>
          <th>Email de Contato</th>
          <th>Acoes</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($listagem['clientes'] as $cliente)
        <tr wire:key="{{ $cliente->getAttribute('cliente_id') }}">
          <td>{{ $cliente->getAttribute('cliente_id') }}</td>
          <td>{{ $cliente->getAttribute('social') }}</td>
          <td>{{ $cliente->getAttribute('fantasia') }}</td>
          <td>{{ $cliente->getAttribute('cnpj') }}</td>
          <td>{{ $cliente->getAttribute('ie') }}</td>
          <td>{{ $cliente->getAttribute('email_contato') }}</td>
          <td>
            <i class="fa-solid fa-pen-to-square text-primary" wire:click="irEdicaocliente({{ $cliente->getAttribute('cliente_id') }})" style="cursor: pointer;"></i>
            <i class="fa-solid fa-trash text-danger" style="cursor: pointer;" onclick="emiteEventoExclusaocliente(<?= $cliente->getAttribute('cliente_id') ?>)"></i>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <script>
    function emiteEventoExclusaocliente(cliente_id) {
      var resposta = confirm("Caso exclua a cliente, se não tiver XML no banco de dados, será apagada, junto com os dados do endereço, deseja realmente apagar a cliente?");
      if (resposta) {
        Livewire.dispatch('excluir-cliente', {cliente_id})
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

    .container-tabela-clientes .container-consulta-clientes div {
      width: 100%;
    }

    .container-tabela-clientes .container-consulta-clientes div input {
      height: 2rem;
    }
  </style>
</div>
