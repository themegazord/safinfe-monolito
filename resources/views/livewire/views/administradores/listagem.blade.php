<div class="p-4 md:p-6 flex flex-col gap-4">
  <h1 class="font-bold text-2xl">Listagem de Administradores</h1>
  <div class="flex flex-col gap-4">
    <div class="flex flex-col md:grid md:grid-cols-5 gap-4">
      <div class="w-full md:w-auto md:col-span-4">
        <x-input label="Insira o dado a ser pesquisado" placeholder="Nome, email, empresa..." wire:model.blur="pesquisa" inline />
      </div>
      <x-button wire:click="irCadastrar" label="Cadastrar" class="btn btn-primary w-full md:col-span-1 md:w-auto"/>
    </div>
    @php
      $administradores = \App\Models\User::query()->select([
      'id',
      'role',
      'name',
      'email',
    ])->where('role', "ADMIN")
    ->where(function ($query) use ($pesquisa) {
      $query->Where('name', 'like', "%$pesquisa%")
          ->orWhere('email', 'like', "%$pesquisa%");
      });

      $administradores = $administradores->paginate($porPagina);

    $headers = [
      ['key' => 'id', 'label' => '#'],
      ['key' => 'name', 'label' => 'Nome'],
      ['key' => 'email', 'label' => 'Email'],
    ];
    @endphp
    <x-table :headers="$headers" :rows="$administradores" with-pagination striped>
      @scope('actions', $administrador)
        <div class="flex gap-4">
          <x-button class="btn btn-ghost rounded" icon="o-pencil-square" wire:click="irEdicaoAdministrador({{ $administrador->id }})"/>
          <x-button class="btn btn-ghost rounded" icon="o-trash" wire:click="setRemoverAdministrador({{ $administrador->id }})" />
        </div>
      @endscope
    </x-table>
  </div>

  <x-modal wire:model="modalConfirmandoRemocaoAdministrador" title="Remover administrador?" class="backdrop-blur">
    @if ($administradorAtual !== null)
    Tem certeza que deseja remover este administrador?

    <x-slot:actions>
      <x-button
        label="Cancelar"
        @click="$wire.modalConfirmandoRemocaoAdministrador = false"
        class="btn btn-info" />

      <x-button
        label="Remover"
        wire:click="removerAdministrador"
        class="btn btn-error" />
    </x-slot:actions>
    @endif
  </x-modal>
</div>
