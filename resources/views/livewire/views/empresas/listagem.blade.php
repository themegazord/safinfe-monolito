<div class="main">
  <h1>Listagem de Empresas</h1>
  <livewire:componentes.utils.notificacao.flash />
  <div class="container-tabela-empresas">
    <div class="container-consulta-empresas">
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
        @foreach ($listagem['empresas'] as $empresa)
        <tr wire:key="{{ $empresa->getAttribute('empresa_id') }}">
          <td>{{ $empresa->getAttribute('empresa_id') }}</td>
          <td>{{ $empresa->getAttribute('social') }}</td>
          <td>{{ $empresa->getAttribute('fantasia') }}</td>
          <td>{{ $empresa->getAttribute('cnpj') }}</td>
          <td>{{ $empresa->getAttribute('ie') }}</td>
          <td>{{ $empresa->getAttribute('email_contato') }}</td>
          <td>
            <i class="fa-solid fa-pen-to-square text-primary" wire:click="irEdicaoEmpresa({{ $empresa->getAttribute('empresa_id') }})" style="cursor: pointer;"></i>
            <i class="fa-solid fa-trash text-danger" style="cursor: pointer;" onclick="emiteEventoExclusaoEmpresa(<?= $empresa->getAttribute('empresa_id') ?>)"></i>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{ $listagem['empresas']->links() }}
  </div>
  <script>
    function emiteEventoExclusaoEmpresa(empresa_id) {
      var resposta = confirm("Caso exclua a empresa, se não tiver XML no banco de dados, será apagada, junto com os dados do endereço, deseja realmente apagar a empresa?");
      if (resposta) {
        Livewire.dispatch('excluir-empresa', {empresa_id})
      }
    }
  </script>
  <style>
    .main {
      display: flex;
      flex-direction: column;
      padding: 3rem 0 0 5rem;
    }

    .container-tabela-empresas {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      width: 80%;
    }

    .container-tabela-empresas .container-consulta-empresas {
      display: flex;
      align-items: end;
      gap: 1rem;
    }

    .container-tabela-empresas .container-consulta-empresas button {
      height: 2rem;
      padding: 0 1rem;
      border-radius: 10px;
      border: none;
      background-color: var(--primary-color);
      color: white;
      font-weight: 700;
      transition: var(--tran-04);
    }

    .container-tabela-empresas .container-consulta-empresas button:hover {
      background-color: var(--primary-color-hover);
    }

    .container-tabela-empresas .container-consulta-empresas div {
      width: 100%;
    }

    .container-tabela-empresas .container-consulta-empresas div input {
      height: 2rem;
    }
  </style>
</div>
