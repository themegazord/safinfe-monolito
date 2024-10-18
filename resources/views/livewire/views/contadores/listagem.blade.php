<div class="main">
  <h1>Listagem de Contadores</h1>
  <livewire:componentes.utils.notificacao.flash />
  <div class="container-tabela-contadores">
    <div class="container-consulta-contadores">
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
          <th>CPF</th>
          <th>Social</th>
          <th>Acoes</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($listagem['contadores'] as $contador)
        <tr wire:key="{{ $contador->contador_id }}">
          <td>{{ $contador->contador_id }}</td>
          <td>{{ $contador->nome }}</td>
          <td>{{ $contador->email }}</td>
          <td>{{ $contador->cpf }}</td>
          <td>{{ $contador->social }}</td>
          <td>
            <i class="fa-solid fa-pen-to-square text-primary" wire:click="irEdicaoContador({{ $contador->contador_id }})" style="cursor: pointer;"></i>
            <i class="fa-solid fa-rotate text-danger" style="cursor: pointer;" onclick="emiteEventoAlteracaoStatus(<?= $contador->contador_id ?>)"></i>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{ $listagem['contadores']->links() }}
  </div>
  <script>
    function emiteEventoAlteracaoStatus(contador_id) {
      var resposta = confirm("Deseja realmente alterar o status desse contador?");
      if (resposta) {
        Livewire.dispatch('inativar-contador', {
          contador_id
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

    .container-tabela-contadores {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      width: 80%;
    }

    .container-tabela-contadores .container-consulta-contadores {
      display: flex;
      align-items: end;
      gap: 1rem;
    }

    .container-tabela-contadores .container-consulta-contadores button {
      height: 2rem;
      padding: 0 1rem;
      border-radius: 10px;
      border: none;
      background-color: var(--primary-color);
      color: white;
      font-weight: 700;
      transition: var(--tran-04);
    }

    .container-tabela-contadores .container-consulta-contadores button:hover {
      background-color: var(--primary-color-hover);
    }

    .container-tabela-contadores .container-consulta-contadores div:first-child {
      width: 70%;
    }

    .container-tabela-contadores .container-consulta-contadores div:first-child input {
      height: 2rem;
    }
  </style>
</div>
