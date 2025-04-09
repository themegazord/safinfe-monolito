<div class="p-4 md:p-6">
  <h1 class="font-bold text-2xl mb-4">Cadastro de Empresas</h1>
  <form wire:submit="cadastrar">
    <h5 class="font-bold text-xl mb-2">Dados da empresa:</h5>
    <div class="flex flex-col gap-4">
      <x-input label="Insira o nome fantasia da empresa" placeholder="Insira o nome fantasia da empresa..." wire:model="empresa.fantasia" />
      <x-input label="Insira a razão social da empresa" placeholder="Insira a razão social da empresa..." wire:model="empresa.social" />
      <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
        <div class="col-span-1">
          <x-input label="Insira o CNPJ da empresa" placeholder="Insira o CNPJ da empresa..." wire:model="empresa.cnpj" />
        </div>
        <div class="col-span-1">
          <x-input label="Insira a IE da empresa" placeholder="Insira a IE da empresa..." wire:model="empresa.ie" />
        </div>
      </div>
      <div class="divider"></div>
      <h5 class="font-bold text-xl mb-2">Dados de contato da empresa:</h5>
      <x-input label="Insira o email de contato da empresa" placeholder="Insira o email de contato da empresa..." wire:model="empresa.email_contato" />
      <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
        <div class="col-span-1">
          <x-input label="Insira o telefone de contato da empresa" placeholder="Insira o telefone de contato da empresa..." wire:model="empresa.telefone_contato" />
        </div>
        <div class="col-span-1">
          <x-input label="Insira o telefone reserva da empresa" placeholder="Insira o telefone reserva da empresa..." wire:model="empresa.telefone_reserva" />
        </div>
      </div>
      <div class="divider"></div>
      <h5 class="font-bold text-xl">Endereço da empresa:</h5>
      <div class="flex flex-col md:grid md:grid-cols-5 gap-4">
        <div class="col-span-4">
          <x-input label="Insira a rua" placeholder="Insira a rua..." wire:model="endereco.rua" />
        </div>
        <div class="col-span-1">
          <x-input label="Numero" placeholder="Numero da casa..." wire:model="endereco.numero" />
        </div>
      </div>
      <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
        <div class="col-span-1">
          <x-input label="Insira o CEP" placeholder="Insira o CEP..." wire:model="endereco.cep" />
        </div>
        <div class="col-span-1">
          <x-input label="Insira o bairro" placeholder="Insira o bairro..." wire:model="endereco.bairro" />
        </div>
      </div>
      <x-textarea rows="3" label="Insira o complemento" placeholder="Insira o complemento..." wire:model="endereco.complemento" />
      <div class="flex flex-col md:grid md:grid-cols-2 gap-4">
        @php
        $estados = [
        ['uf' => 'AC', 'estado' => 'Acre'],
        ['uf' => 'AL', 'estado' => 'Alagoas'],
        ['uf' => 'AP', 'estado' => 'Amapá'],
        ['uf' => 'AM', 'estado' => 'Amazonas'],
        ['uf' => 'BA', 'estado' => 'Bahia'],
        ['uf' => 'CE', 'estado' => 'Ceará'],
        ['uf' => 'DF', 'estado' => 'Distrito Federal'],
        ['uf' => 'ES', 'estado' => 'Espírito Santo'],
        ['uf' => 'GO', 'estado' => 'Goiás'],
        ['uf' => 'MA', 'estado' => 'Maranhão'],
        ['uf' => 'MT', 'estado' => 'Mato Grosso'],
        ['uf' => 'MS', 'estado' => 'Mato Grosso do Sul'],
        ['uf' => 'MG', 'estado' => 'Minas Gerais'],
        ['uf' => 'PA', 'estado' => 'Pará'],
        ['uf' => 'PB', 'estado' => 'Paraíba'],
        ['uf' => 'PR', 'estado' => 'Paraná'],
        ['uf' => 'PE', 'estado' => 'Pernambuco'],
        ['uf' => 'PI', 'estado' => 'Piauí'],
        ['uf' => 'RJ', 'estado' => 'Rio de Janeiro'],
        ['uf' => 'RN', 'estado' => 'Rio Grande do Norte'],
        ['uf' => 'RS', 'estado' => 'Rio Grande do Sul'],
        ['uf' => 'RO', 'estado' => 'Rondônia'],
        ['uf' => 'RR', 'estado' => 'Roraima'],
        ['uf' => 'SC', 'estado' => 'Santa Catarina'],
        ['uf' => 'SP', 'estado' => 'São Paulo'],
        ['uf' => 'SE', 'estado' => 'Sergipe'],
        ['uf' => 'TO', 'estado' => 'Tocantins'],
        ];
        @endphp
        <div class="col-span-1">
          <x-input label="Insira o cidade" placeholder="Insira o cidade..." wire:model="endereco.cidade" />
        </div>
        <div class="col-span-1">
          <x-select
            label="Insira o estado"
            placeholder="Insira o estado..."
            wire:model="endereco.estado"
            :options="$estados"
            option-value="uf"
            option-label="estado" />
        </div>
      </div>
    </div>
    <div class="flex flex-col sm:flex-row-reverse gap-4 mt-4">
      <x-button type="submit" class="btn btn-success" label="Cadastrar" />
      <x-button class="btn btn-error" wire:click="voltar" label="Voltar" />
    </div>
  </form>
</div>
