<div class="p-4 md:p-6 flex flex-col gap-4">
  <h1 class="font-bold text-2xl">Listagem de Versões</h1>
  <div class="flex flex-col gap-4">
    <div class="flex flex-col md:grid md:grid-cols-5 gap-4">
      <div class="{{ $usuario->getAttribute('role') === 'ADMIN' ? 'col-span-4' : 'col-span-5' }}">
        <x-input label="Insira o dado a ser pesquisado" placeholder="Versão, o que foi feito..." wire:model.blur="pesquisa" inline />
      </div>
      @if ($usuario->getAttribute('role') === 'ADMIN')
      <x-button wire:click="irCadastrar" label="Cadastrar" class="btn btn-primary" />
      @endif
    </div>
    @php
    $versoes = \App\Models\Versionamento::query()
    ->orWhere('patch', $this->pesquisa)
    ->orWhere('detalhe', 'like', "%$this->pesquisa%")
    ->orderBy('versionamento_id', 'desc')
    ->paginate($this->perPage);

    $headers = [
    ['key' => 'versionamento_id', 'label' => '#'],
    ['key' => 'patch', 'label' => 'Patch'],
    ['key' => 'created_at', 'label' => 'Data'],
    ];
    @endphp
    <x-table :headers="$headers" :rows="$versoes" with-pagination striped>
      @scope('cell_created_at', $versao)
      <p>{{ date('d/m/Y', strtotime($versao->getAttribute('created_at'))) }}</p>
      @endscope
      @scope('actions', $versao)
      <div class="flex gap-4">
        <x-button class="btn btn-ghost rounded" icon="o-eye" wire:click="selecionaVersaoAtual({{$versao->getAttribute('versionamento_id')}})" />
      </div>
      @endscope
    </x-table>
  </div>

  <!-- Inicia modal de visualização dos detalhes da atualização -->

  <x-modal wire:model="modalVisualizarVersao" class="backdrop-blur" box-class="w-11/12 max-w-5xl">
    @if ($versaoAtual !== null)
    <x-slot:title>
      Visualização da versão: {{ $versaoAtual->patch }}
    </x-slot:title>

    <div class="preview">
      {!! $versaoAtual->detalhe !!}
    </div>

    <x-slot:actions>
      <x-button
        label="Cancelar"
        @click="$wire.modalVisualizarVersao = false"
        class="btn btn-info" />
    </x-slot:actions>
    @endif

    <style>
      /* Restaura estilos padrão do HTML dentro de .preview */
      .preview {
        font-family: inherit;
        color: inherit;
        line-height: 1.6;
      }

      .preview h1 {
        font-size: 2rem;
        /* 32px */
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
      }

      .preview h2 {
        font-size: 1.75rem;
        /* 28px */
        font-weight: 700;
        margin-top: 1.75rem;
        margin-bottom: 1rem;
      }

      .preview h3 {
        font-size: 1.5rem;
        /* 24px */
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
      }

      .preview h4 {
        font-size: 1.25rem;
        /* 20px */
        font-weight: 600;
        margin-top: 1.25rem;
        margin-bottom: 0.5rem;
      }

      .preview h5 {
        font-size: 1.125rem;
        /* 18px */
        font-weight: 500;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
      }

      .preview h6 {
        font-size: 1rem;
        /* 16px */
        font-weight: 500;
        margin-top: 0.75rem;
        margin-bottom: 0.5rem;
      }

      .preview p {
        margin-bottom: 1rem;
      }

      .preview ul {
        list-style-type: disc;
        margin-left: 1.5rem;
        margin-bottom: 1rem;
      }

      .preview ol {
        list-style-type: decimal;
        margin-left: 1.5rem;
        margin-bottom: 1rem;
      }

      .preview li {
        margin-bottom: 0.25rem;
      }

      .preview strong {
        font-weight: bold;
      }

      .preview em {
        font-style: italic;
      }

      .preview blockquote {
        margin: 1rem 0;
        padding-left: 1rem;
        border-left: 4px solid #ccc;
        color: #666;
        font-style: italic;
      }
    </style>
  </x-modal>
  <!-- Fim modal de visualização dos detalhes da atualização -->
</div>
