<div class="container p-4">
  <h1 class="font-bold text-2xl mb-4">Listagem de Empresas</h1>
  <div class="flex flex-col gap-4">
    <div class="flex flex-col w-full gap-4 lg:grid lg:grid-cols-5">
      <div class="col-span-4">
        <x-input label="Consulta" placeholder="Razão social, nome fantasia, CNPJ, IE, email de contato..." wire:model.blur="consulta" inline />
      </div>
      <x-button wire:click="irCadastrar" label="Cadastrar" class="btn btn-primary" class="col-span-1" />
    </div>
    @php
    $paginacaoEmpresa = \App\Models\Empresa::query()
    ->orWhere('fantasia', 'like', '%' . $this->consulta . '%')
    ->orWhere('social', 'like', '%' . $this->consulta . '%')
    ->orWhere('cnpj', 'like', '%' . $this->consulta . '%')
    ->orWhere('ie', 'like', '%' . $this->consulta . '%')
    ->orWhere('email_contato', 'like', '%' . $this->consulta . '%')
    ->paginate($this->porPagina);

    $headers = [
    ['key' => 'empresa_id', 'label' => '#', 'class' => 'w-1'],
    ['key' => 'fantasia', 'label' => 'Nome fantasia'],
    ['key' => 'social', 'label' => 'Razão social'],
    ['key' => 'cnpj', 'label' => 'CNPJ'],
    ['key' => 'ie', 'label' => 'IE'],
    ['key' => 'email_contato', 'label' => 'Email de contato'],
    ];
    @endphp

    <x-table
      :headers="$headers"
      :rows="$paginacaoEmpresa"
      with-pagination
      per-page="porPagina"
      :per-page-values="[10, 15, 20, 30, 50]">
      @scope('actions', $empresa)
      <div class="flex">
        <x-button class="btn btn-ghost" icon="o-pencil" wire:click="irEdicaoEmpresa({{ $empresa->empresa_id }})" />
        <x-button class="btn btn-ghost" icon="o-trash" wire:click="setRemocaoEmpresa({{ $empresa->empresa_id }})" />
      </div>
      @endscope
    </x-table>
  </div>

  <x-modal wire:model="modalConfirmandoRemocaoEmpresa" title="Remover empresa?" class="backdrop-blur">
    @if ($empresaAtual !== null)
    Tem certeza que deseja remover esta empresa? Esta ação não poderá ser desfeita.

    <x-slot:actions>
      <x-button
        label="Cancelar"
        @click="$wire.modalConfirmandoRemocaoEmpresa = false"
        class="btn btn-info" />

      <x-button
        label="Remover"
        wire:click="excluirEmpresa"
        class="btn btn-error" />
    </x-slot:actions>
    @endif
  </x-modal>

</div>
