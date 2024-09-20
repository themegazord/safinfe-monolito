<div class="main">
  <h1>Listagem dos XML</h1>
  <livewire:componentes.utils.notificacao.flash />
  <table class="table table-sm table-striped table-hover tabela-xml">
    <div class="perpage">
      <input type="number" name="perpage" id="perpage" wire:model.blur="perPage">
    </div>
    <thead>
      <tr>
        <th>ID</th>
        <th>Modelo</th>
        <th>Serie</th>
        <th>Numero NF</th>
        <th>Numero NF Final</th>
        <th>Status</th>
        <th>Data Evento</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($listagem['dados_xml'] as $dado)
      <tr wire:key="{{ $dado->dados_id }}">
        <th>{{ $dado->dados_id }}</th>
        <td>{{ $dado->modelo }}</td>
        <td>{{ $dado->serie }}</td>
        <td>{{ $dado->numeronf }}</td>
        <td>{{ $dado->numeronf_final }}</td>
        <td>{{ $dado->status }}</td>
        <td>{{ date('d/m/Y', strtotime($dado->dh_emissao_evento)) }}</td>
        <td>
          <i class="fa-solid fa-info text-info pe-1"></i>
          <i class="fa-solid fa-download text-success"></i>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div class="paginacao">
    {{ $listagem['dados_xml']->links() }}
  </div>


  <style>
    .main {
      display: flex;
      flex-direction: column;
      padding: 3rem 0 0 5rem;
    }

    .tabela-xml,
    .paginacao {
      width: 90%;
    }

    i {
      cursor: pointer;
    }

    .perpage {
      width: 90%;
      display: flex;
      flex-direction: row-reverse;
    }

    .perpage>input {
      width: 5%;
    }
  </style>
</div>
