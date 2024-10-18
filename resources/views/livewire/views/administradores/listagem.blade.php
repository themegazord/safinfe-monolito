<div class="main">
  <h1>Listagem de Administradores</h1>
  <livewire:componentes.utils.notificacao.flash />
  <div class="container-tabela-administradores">
    <div class="container-consulta-administradores">
      <div>
        <label for="pesquisa" class="form-label">Insira o dado a ser pesquisado</label>
        <input type="text" class="form-control" id="pesquisa" wire:model.blur="pesquisa" placeholder="Nome, email, empresa...">
      </div>
      <button wire:click="irCadastrar">Cadastrar</button>
    </div>
    <table class="table table-striped table-hover table-sm">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>Email</th>
          <th>Acoes</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($listagem['administradores'] as $administrador)
        <tr wire:key="{{ $administrador->id }}">
          <td>{{ $administrador->id }}</td>
          <td>{{ $administrador->name }}</td>
          <td>{{ $administrador->email }}</td>
          <td>
            <i class="fa-solid fa-pen-to-square text-primary" wire:click="irEdicaoAdministrador({{ $administrador->id }})" style="cursor: pointer;"></i>
            <i class="fa-solid fa-rotate text-danger" style="cursor: pointer;" onclick="emiteEventoExclusaoAdministrador(<?= $administrador->id ?>)"></i>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{ $listagem['administradores']->links() }}
  </div>
  <script>
    function emiteEventoExclusaoadministrador(id) {
      var resposta = confirm("Deseja realmente alterar o status desse administrador?");
      if (resposta) {
        Livewire.dispatch('inativar-administrador', {
          id
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

    .container-tabela-administradores {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      width: 80%;
    }

    .container-tabela-administradores .container-consulta-administradores {
      display: flex;
      align-items: end;
      gap: 1rem;
    }

    .container-tabela-administradores .container-consulta-administradores button {
      height: 2rem;
      padding: 0 1rem;
      border-radius: 10px;
      border: none;
      background-color: var(--primary-color);
      color: white;
      font-weight: 700;
      transition: var(--tran-04);
    }

    .container-tabela-administradores .container-consulta-administradores button:hover {
      background-color: var(--primary-color-hover);
    }

    .container-tabela-administradores .container-consulta-administradores div:first-child {
      width: 100%;
    }

    .container-tabela-administradores .container-consulta-administradores div:first-child input {
      height: 2rem;
    }
  </style>
</div>
