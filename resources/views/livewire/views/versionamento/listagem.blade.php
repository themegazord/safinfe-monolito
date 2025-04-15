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
          <x-button class="btn btn-ghost rounded" icon="o-eye" wire:click="selecionaVersaoAtual({{$versao->getAttribute('versionamento_id')}})"/>
        </div>
      @endscope
    </x-table>
  </div>

  <!-- Inicia modal de visualização dos detalhes da atualização -->

  <div class="modal fade" id="modal-detalhe-versao" tabindex="-1" aria-labelledby="modal-detalhe-versaoLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-detalhe-versaoLabel">
            @if (is_null($versaoAtual))
            <div class="container-loading-dados">
              <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
              <h2>Carregando dados...</h2>
            </div>
            @else
            Versão: {{ $versaoAtual->getAttribute('patch') }}
            @endif
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="preview">
            @if (is_null($versaoAtual))
            <div class="container-loading-dados">
              <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
              <h2>Carregando dados...</h2>
            </div>
            @endif
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>

  <script type="module">
    import markdownIt from 'https://cdn.jsdelivr.net/npm/markdown-it@14.1.0/+esm'
    let geraPreview = document.getElementById('geraPreview');
    let preview = document.getElementById('preview');
    let modal = document.getElementById('modal-detalhe-versao');

    document.addEventListener('livewire:init', () => {
      Livewire.on('recebe-detalhe', (event) => {
        const md = markdownIt();
        const result = md.render(event[0].detalhe);
        setTimeout(() => {
          preview.innerHTML = result
        }, 500);
      });
    });

    modal.addEventListener('hidden.bs.modal', function() {
      Livewire.dispatch('limpa-versao-selecionado')
    })
  </script>

  <!-- Fim modal de visualização dos detalhes da atualização -->
  <style>
    .main {
      display: flex;
      flex-direction: column;
      padding: 3rem 0 0 5rem;
    }

    .container-consulta-versao {
      display: flex;
      align-items: end;
      gap: 1rem;
    }

    i {
      cursor: pointer;
    }

    .container-consulta-versao button {
      height: 2rem;
      padding: 0 1rem;
      border-radius: 10px;
      border: none;
      background-color: var(--primary-color);
      color: white;
      font-weight: 700;
      transition: var(--tran-04);
    }

    .container-consulta-versao button:hover {
      background-color: var(--primary-color-hover);
    }

    .container-consulta-versao div:first-child {
      width: 70%;
    }

    .container-listagem-versao {
      width: 80%;
    }

    .paginacao {
      width: 90%;
    }

    #preview {
      padding: 1rem;
    }
  </style>
</div>
