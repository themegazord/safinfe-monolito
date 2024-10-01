<div class="main">
  <h1>Cadastro de Versão</h1>
  <livewire:componentes.utils.notificacao.flash />
  <div class="container-cadastro-preview-versao">
    <form wire:submit="cadastrar">
      <div>
        <label for="patch">Insira o patch da versão</label>
        <input type="text" class="form-control" id="patch" placeholder="Insira o patch da versão" wire:model="versao.patch">
        @error('versao.patch') <span class="form-error">{{ $message }}</span> @enderror
      </div>
      <div>
        <label for="detalhe">Insira o que foi feito...</label>
        <textarea class="form-control" placeholder="Insira o que foi feito..." id="detalhe" wire:model="versao.detalhe"></textarea>
        @error('versao.detalhe') <span class="form-error">{{ $message }}</span> @enderror
      </div>
      <div class="botoes-acoes">
        <button type="button" wire:click="voltar">Voltar</button>
        <button type="button" id="geraPreview">Preview</button>
        <button type="submit">Cadastrar</button>
      </div>
    </form>
    <div class="card">
      <div class="card-body">
        <div id="preview"></div>
      </div>
    </div>
  </div>
  <script type="module">
    import markdownIt from 'https://cdn.jsdelivr.net/npm/markdown-it@14.1.0/+esm'
    let geraPreview = document.getElementById('geraPreview');
    let preview = document.getElementById('preview');
    let detalhe = document.getElementById('detalhe');

    geraPreview.addEventListener('click', function() {
      const md = markdownIt();
      const result = md.render(detalhe.value);
      preview.innerHTML = result
    });
  </script>
  <style>
    .main {
      padding: 3rem 0 0 5rem;
    }

    .container-cadastro-preview-versao {
      width: 80%;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 3rem;
    }

    .container-cadastro-preview-versao form {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    #detalhe {
      height: 50%;
    }

    .botoes-acoes {
      margin-top: 1rem;
      gap: 1rem;
      display: flex;
      justify-content: end;
    }

    .botoes-acoes button {
      padding: .5rem 1rem;
      border: none;
      border-radius: 5px;
      background-color: var(--primary-color);
      color: white;
      font-weight: 700;
      transition: var(--tran-04);
    }

    .botoes-acoes button:hover {
      background-color: var(--primary-color-hover);
    }

    #preview {
      padding: 1rem;
    }
  </style>
</div>
