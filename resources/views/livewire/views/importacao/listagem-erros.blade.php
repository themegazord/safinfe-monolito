<div class="main">
  <h1>Listagem de erros de importação de XML</h1>
  <livewire:componentes.utils.notificacao.flash />
  <div class="container-listagem-erros">
    <ol>
      @foreach ($erros as $erro)
      <li><b>Tipo: {{ $erro['tipo'] }}</b> | {{ $erro['mensagem'] }}</li>
      @endforeach
    </ol>

    <div class="container-listagem-erros-acoes">
      <button class="float-end" wire:click="voltar">Voltar</button>
    </div>
  </div>

  <style>
    .main {
      padding: 3rem 0 0 5rem;
    }

    .container-listagem-erros {
      display: flex;
      flex-direction: column;
      width: 90vw;
      gap: 1rem;
    }

    .container-listagem-erros ol {
      display: flex;
      flex-direction: column;
      gap: .5rem;
    }

    .container-listagem-erros-acoes button {
      padding: .5rem 1rem;
      width: 5rem;
      border: none;
      border-radius: 5px;
      background-color: var(--primary-color);
      color: white;
      font-weight: 700;
      transition: var(--tran-04);
    }
  </style>
</div>
