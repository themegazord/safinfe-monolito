<?php

namespace App\Livewire\Views\Consultaxml\Componentes;

use App\Livewire\Forms\ConsultaAdminXMLForm;
use App\Livewire\Forms\ConsultaClienteXMLForm;
use App\Livewire\Forms\ConsultaContadorXMLForm;
use Illuminate\Support\Collection;
use Livewire\Component;

class Consulta extends Component
{
  public ?string $modelPrefix = null;
  public ?array $status = null;
  public ?array $modelos = null;
  public ?array $config = null;
  public ?bool $empresaSelector = null;
  public ?Collection $empresas;
  public ConsultaContadorXMLForm $consultaContador;
  public ConsultaClienteXMLForm $consultaCliente;
  public ConsultaAdminXMLForm $consultaAdmin;

  public function mount(?string $modelPrefix, ?array $status, ?array $modelos, ?array $config, ?bool $empresaSelector, ?Collection $empresas) {
    $this->modelPrefix = $modelPrefix;
    $this->status = $status;
    $this->modelos = $modelos;
    $this->config = $config;
    $this->empresaSelector = $empresaSelector;
    $this->empresas = $empresas;
  }

  public function render()
  {
    return
      <<<'HTML'
      <form wire:submit="consulta" class="flex flex-col gap-4">
        <div class="flex flex-col md:grid md:grid-cols-4 gap-4">
          <div class="w-full md:col-span-2 md:w-auto">
            <x-datepicker label="Data inicio - Data fim" placeholder="Data inicio - Data fim" :wire:model="$modelPrefix.'.data_inicio_fim'" icon="o-calendar" :config="$config" inline />
          </div>
          <div class="w-full md:col-span-1 md:w-auto">
            <x-input label="Série" placeholder="Insira a série das notas fiscais..." :wire:model="$modelPrefix.'.serie'" autocomplete="off" inline />
          </div>
          <div class="w-full md:col-span-1 md:w-auto">
            <x-select label="Modelo" placeholder="Selecione o tipo da nota fiscal" placeholder-value="TODAS" :wire:model="$modelPrefix.'.modelo'" :options="$modelos" inline />
          </div>
        </div>

        <div class="flex flex-col md:grid md:grid-cols-4 gap-4">
          <div class="w-full md:col-span-2 md:w-auto">
            <x-select label="Selecione o status" placeholder="Selecione o status da nota fiscal..." placeholder-value="TODAS" :wire:model="$modelPrefix.'.status'" :options="$status" inline />
          </div>
          <div class="w-full md:col-span-1 md:w-auto">
            <x-input label="Numero inicial:" placeholder="Insira o número inicial a ser consultado" :wire:model="$modelPrefix.'.numeroInicial'" inline />
          </div>
          <div class="w-full md:col-span-1 md:w-auto">
            <x-input label="Numero final:" placeholder="Insira o número final a ser consultado" :wire:model="$modelPrefix.'.numeroFinal'" inline />
          </div>
        </div>

        @if ($empresaSelector ?? false)
          <x-choices-offline
            label="Selecione a empresa que você quer consultar as notas fiscais."
            :wire:model="$modelPrefix.'.empresa_id'"
            :options="$empresas"
            option-label="fantasia"
            option-value="empresa_id"
            placeholder="Digite o nome da empresa..."
            search-function="pesquisaContadorAdmin"
            no-result-text="Opa, não encontramos essa empresa."
            single
            clearable
            searchable />
        @endif

        <div class="flex flex-col-reverse md:flex-row-reverse gap-4">
          <x-button type="button" wire:loading.attr="disabled" spinner="enviaConsulta" wire:click="enviaConsulta" label="Consultar" class="btn btn-primary w-full md:w-auto" />
          <x-button type="button" wire:loading.attr="disabled" spinner="solicitaDownload" wire:click="solicitaDownload" label="Download Direto" class="btn btn-secondary w-full md:w-auto"/>
        </div>
      </form>
    HTML;
  }

  public function enviaConsulta(): void {
    $this->{$this->modelPrefix}->validate();
    $splitData = explode(' até ', $this->{$this->modelPrefix}->data_inicio_fim);
    $this->{$this->modelPrefix}->data_inicio = $splitData[0];
    $this->{$this->modelPrefix}->data_fim = $splitData[1];
    $this->dispatch('envia-consulta', [
      'tipo' => $this->modelPrefix,
      ...match ($this->modelPrefix) {
        'consultaCliente' => $this->consultaCliente->toArray(),
        'consultaAdmin' => $this->consultaAdmin->toArray(),
        'consultaContador' => $this->consultaContador->toArray(),
      },
    ]);
  }

  public function solicitaDownload(): void {
    $this->{$this->modelPrefix}->validate();
    $splitData = explode(' até ', $this->{$this->modelPrefix}->data_inicio_fim);
    $this->{$this->modelPrefix}->data_inicio = $splitData[0];
    $this->{$this->modelPrefix}->data_fim = $splitData[1];
    $this->dispatch('download-direto', [
      'tipo' => $this->modelPrefix,
      ...match ($this->modelPrefix) {
        'consultaCliente' => $this->consultaCliente->toArray(),
        'consultaAdmin' => $this->consultaAdmin->toArray(),
        'consultaContador' => $this->consultaContador->toArray(),
      },
    ]);
  }
}
