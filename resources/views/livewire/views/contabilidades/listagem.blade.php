<div class="container p-4">
  <h1 class="font-bold text-2xl mb-4">Listagem de Contabilidades</h1>
  <div class="flex flex-col gap-4">
    <div class="flex flex-col w-full gap-4 lg:grid lg:grid-cols-5">
      <div class="col-span-4">
        <x-input label="Consulta" placeholder="Razão social, CNPJ, telefone corporativo, email corporativo..." wire:model.blur="consulta" inline />
      </div>
      <x-button wire:click="irCadastrar" label="Cadastrar" class="btn btn-primary" class="col-span-1" />
    </div>
    @php
    $paginacaoContabilidades = \App\Models\Contabilidade::query()
    ->orWhere('social', 'like', '%' . $this->consulta . '%')
    ->orWhere('cnpj', 'like', '%' . $this->consulta . '%')
    ->orWhere('telefone_corporativo', 'like', '%' . $this->consulta . '%')
    ->orWhere('email_corporativo', 'like', '%' . $this->consulta . '%')
    ->paginate($this->porPagina);

    $headers = [
    ['key' => 'contabilidade_id', 'label' => '#', 'class' => 'w-1'],
    ['key' => 'social', 'label' => 'Razão Social'],
    ['key' => 'cnpj', 'label' => 'CNPJ'],
    ['key' => 'telefone_corporativo', 'label' => 'Telefone Corporativo'],
    ['key' => 'email_corporativo', 'label' => 'Email Corporativo'],
    ];
    @endphp

    <x-table
      :headers="$headers"
      :rows="$paginacaoContabilidades"
      with-pagination
      per-page="porPagina"
      :per-page-values="[10, 15, 20, 30, 50]">
      @scope('actions', $contabilidade)
      <div class="flex">
        <x-button class="btn btn-ghost" icon="o-pencil" wire:click="irEdicaoContabilidade({{ $contabilidade->contabilidade_id }})" />
        <x-button class="btn btn-ghost" icon="o-trash" wire:click="setRemocaoContabilidade({{ $contabilidade->contabilidade_id }})" />
      </div>
      @endscope
    </x-table>
  </div>

  <x-modal wire:model="modalConfirmandoRemocaoContabilidade" title="Remover contabilidade?" class="backdrop-blur">
    @if ($contabilidadeAtual !== null)
    Tem certeza que deseja remover esta contabilidade? Esta ação não poderá ser desfeita.

    <x-slot:actions>
      <x-button
        label="Cancelar"
        @click="$wire.modalConfirmandoRemocaoContabilidade = false"
        class="btn btn-info" />

      <x-button
        label="Remover"
        wire:click="excluirContabilidade"
        class="btn btn-error" />
    </x-slot:actions>
    @endif
  </x-modal>

</div>
