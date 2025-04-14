<div class="p-4 md:p-6">
  <h1 class="font-bold text-2xl mb-4">Listagem de Clientes</h1>
  <div class="flex flex-col gap-4">
    <div class="flex flex-col md:grid md:grid-cols-6 gap-4">
      <div class="col-span-4">
        <x-input label="Insira o dado a ser pesquisado" placeholder="Nome, email, empresa..." wire:model.blur="pesquisa" inline />
      </div>
      <div class="col-span-1">
        <x-checkbox label="Inativo / Ativo" wire:model.live="estaAtivo" left />
      </div>
      <div class="col-span-1">
        <x-button class="btn btn-primary" wire:click="irCadastrar" label="Cadastrar"/>
      </div>
    </div>
    @php
      $clientes = \App\Models\Cliente::query()->select([
      'cliente_id',
      'usuario_id',
      'clientes.empresa_id',
      'nome',
      'email',
      'empresas.fantasia as fantasia'
    ])
      ->join('empresas', 'empresas.empresa_id', '=', 'clientes.empresa_id')
      ->where(function ($query) use ($pesquisa) {
        $query->where('nome', 'like', "%$pesquisa%")
          ->orWhere('email', 'like', "%$pesquisa%")
          ->orWhere('empresas.fantasia', 'like', "%$pesquisa%");
      });

    if (!$estaAtivo) {
      $clientes = $clientes->onlyTrashed()->paginate($porPagina);
    } else {
      $clientes = $clientes->whereNull('clientes.deleted_at')->paginate($porPagina);
    }

    $headers = [
      ['key' => 'cliente_id', 'label' => '#'],
      ['key' => 'nome', 'label' => 'Nome'],
      ['key' => 'email', 'label' => 'Email'],
      ['key' => 'fantasia', 'label' => 'Empresa'],
    ];
    @endphp
    <x-table :headers="$headers" :rows="$clientes" striped>
      @scope('actions', $cliente)
        <div class="flex gap-4">
          <x-button class="btn btn-ghost rounded" icon="o-pencil-square" wire:click="irEdicaoCliente({{ $cliente->cliente_id }})"/>
          <x-button class="btn btn-ghost rounded" icon="o-arrow-path-rounded-square" onclick="emiteEventoExclusaocliente({{ $cliente->cliente_id }})" />
        </div>
      @endscope
    </x-table>
  </div>
  <script>
    function emiteEventoExclusaocliente(cliente_id) {
      console.log(cliente_id);
      var resposta = confirm("Deseja realmente alterar o status desse cliente?");
      if (resposta) {
        Livewire.dispatch('inativar-cliente', {
          cliente_id
        })
      }
    }
  </script>
</div>
