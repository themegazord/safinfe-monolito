<div class="main">
  <livewire:componentes.utils.notificacao.flash />
  <h1>Listagem de Contabilidades</h1>
  <div class="container-tabela-contabilidades">
    <div class="container-consulta-contabilidades">
      <div>
        <label for="pesquisa" class="form-label">Razão social, CNPJ, telefone corporativo, email corporativo...</label>
        <input type="text" class="form-control" id="pesquisa" wire:model="pesquisa">
      </div>
      <button wire:click="irCadastrar">Cadastrar</button>
    </div>
    <table class="table table-striped table-hover table-sm">
      <thead>
        <tr>
          <th>ID</th>
          <th>Razão Social</th>
          <th>CNPJ</th>
          <th>Telefone Corporativo</th>
          <th>Email Corporativo</th>
          <th>Acoes</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($listagem['contabilidades'] as $contabilidade)
        <tr wire:key="{{ $contabilidade->getAttribute('contabilidade_id') }}">
          <td>{{ $contabilidade->getAttribute('contabilidade_id') }}</td>
          <td>{{ $contabilidade->getAttribute('social') }}</td>
          <td>{{ $contabilidade->getAttribute('cnpj') }}</td>
          <td>{{ $contabilidade->getAttribute('telefone_corporativo') }}</td>
          <td>{{ $contabilidade->getAttribute('email_corporativo') }}</td>
          <td>
            <i class="fa-solid fa-pen-to-square text-primary" wire:click="irEdicaoContabilidade({{ $contabilidade->getAttribute('contabilidade_id') }})" style="cursor: pointer;"></i>
            <i class="fa-solid fa-trash text-danger" style="cursor: pointer;" onclick="emiteEventoExclusaoContabilidade(<?= $contabilidade->getAttribute('contabilidade_id') ?>)"></i>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    {{ $listagem['contabilidades']->links()  }}
  </div>
  <script>
    function emiteEventoExclusaoContabilidade(contabilidade_id) {
      var resposta = confirm("Deseja realmente apagar a contabilidade?");
      if (resposta) {
        Livewire.dispatch('excluir-contabilidade', {contabilidade_id})
      }
    }
  </script>
  <style>
    .main {
      display: flex;
      flex-direction: column;
      padding: 3rem 0 0 5rem;
    }

    .container-tabela-contabilidades {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      width: 80%;
    }

    .container-tabela-contabilidades .container-consulta-contabilidades {
      display: flex;
      align-items: end;
      gap: 1rem;
    }

    .container-tabela-contabilidades .container-consulta-contabilidades button {
      height: 2rem;
      padding: 0 1rem;
      border-radius: 10px;
      border: none;
      background-color: var(--primary-color);
      color: white;
      font-weight: 700;
      transition: var(--tran-04);
    }

    .container-tabela-contabilidades .container-consulta-contabilidades button:hover {
      background-color: var(--primary-color-hover);
    }

    .container-tabela-contabilidades .container-consulta-contabilidades div {
      width: 100%;
    }

    .container-tabela-contabilidades .container-consulta-contabilidades div input {
      height: 2rem;
    }
  </style>
</div>
