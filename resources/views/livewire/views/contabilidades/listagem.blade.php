<div class="main">
  <livewire:componentes.utils.notificacao.flash />
  <h1>Listagem de Contabilidades</h1>
  <div class="container-tabela-contabilidades">
    <div class="container-consulta-contabilidades">
      <div>
        <label for="pesquisa" class="form-label">Razão social, nome fantasia, CNPJ, IE, email de contato...</label>
        <input type="text" class="form-control" id="pesquisa" wire:model="pesquisa">
      </div>
      <button wire:click="irCadastrar">Cadastrar</button>
    </div>
  </div>
</div>
