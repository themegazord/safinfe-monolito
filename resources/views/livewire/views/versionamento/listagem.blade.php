<div class="main">
  <h1>Listagem de Versões</h1>
  <livewire:componentes.utils.notificacao.flash />
  <div class="container-listagem-versao">
    <div class="container-consulta-versao">
      <div>
        <label for="pesquisa" class="form-label">Insira o dado a ser pesquisado</label>
        <input type="text" class="form-control" id="pesquisa" wire:model.blur="pesquisa" placeholder="Versão, o que foi feito...">
      </div>
      @if ($usuario->getAttribute('role') === 'ADMIN')
      <button wire:click="irCadastrar">Cadastrar</button>
      @endif
    </div>
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Patch</th>
          <th>Data</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($listagem['versoes'] as $versao)
        <tr>
          <th>{{ $versao->getAttribute('versionamento_id') }}</th>
          <td>{{ $versao->getAttribute('patch') }}</td>
          <td>{{ date('d/m/Y', strtotime($versao->getAttribute('created_at'))) }}</td>
          <td>
            <i class="fa-solid fa-eye" data-bs-toggle="modal" data-bs-target="#modal-detalhe-versao" wire:click="selecionaVersaoAtual({{$versao->getAttribute('versionamento_id')}})"></i>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
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
