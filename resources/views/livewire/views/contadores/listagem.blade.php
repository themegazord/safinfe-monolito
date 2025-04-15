<div class="p-4 md:p-6 flex flex-col gap-4">
  <h1 class="font-bold text-2xl">Listagem de Contadores</h1>
  <div class="flex flex-col gap-4">
    <div class="flex flex-col md:grid md:grid-cols-6 gap-4">
      <div class="col-span-4">
        <x-input label="Insira o dado a ser pesquisado" placeholder="Nome, email, empresa..." wire:model.blur="pesquisa" inline />
      </div>
      <div class="col-span-1">
        <x-checkbox label="Ativo ?" wire:model.live="estaAtivo" left />
      </div>
      <div class="col-span-1">
        <x-button wire:click="irCadastrar" label="Cadastrar" class="btn btn-primary w-full"/>
      </div>
    </div>
    @php
      $contadores = \App\Models\Contador::query()->select([
        'contador_id',
        'usuario_id',
        'contadores.contabilidade_id',
        'nome',
        'email',
        'cpf',
        'contabilidades.social as social'
    ])
      ->join('contabilidades', 'contabilidades.contabilidade_id', '=', 'contadores.contabilidade_id')
      ->where(function ($query) use ($pesquisa) {
        $query->where('nome', 'like', "%$pesquisa%")
          ->orWhere('email', 'like', "%$pesquisa%")
          ->orWhere('cpf', 'like', "%pesquisa%")
          ->orWhere('contabilidades.social', 'like', "%$pesquisa%");
      });

    if (!$estaAtivo) {
      $contadores = $contadores->onlyTrashed()->paginate($porPagina);
    } else {
      $contadores = $contadores->whereNull('contadores.deleted_at')->paginate($porPagina);
    }

    $headers = [
      ['key' => 'contador_id', 'label' => '#'],
      ['key' => 'nome', 'label' => 'Nome'],
      ['key' => 'email', 'label' => 'Email'],
      ['key' => 'cpf', 'label' => 'CPF'],
      ['key' => 'social', 'label' => 'Razão Social da Contabilidade'],
    ];
    @endphp
    <x-table :headers="$headers" :rows="$contadores" with-pagination striped show-empty-text empty-text="{{ $estaAtivo ? 'Não contêm contadores ativos' : 'Não contêm contadores inativos' }}">
      @scope('actions', $contador)
        <div class="flex gap-4">
          <x-button class="btn btn-ghost rounded" icon="o-pencil-square" wire:click="irEdicaoContador({{ $contador->contador_id }})"/>
          <x-button class="btn btn-ghost rounded" icon="o-arrow-path-rounded-square" wire:click="setInativacaoContador({{ $contador->contador_id }})" />
        </div>
      @endscope
    </x-table>
  </div>
</div>
