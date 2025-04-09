<?php

namespace App\Livewire\Componentes\Relatorios\Dashboard;

use CommerceGuys\Intl\Currency\CurrencyRepository;
use CommerceGuys\Intl\Formatter\CurrencyFormatter;
use CommerceGuys\Intl\NumberFormat\NumberFormatRepository;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Valorestotaismensais extends Component
{
  public array $informacoesTotaisNotas = [];

  public function mount(array $dados): void
  {
    $this->informacoesTotaisNotas = $dados;
  }

  public function render()
  {
    return <<<'HTML'
    <div class="flex flex-col lg:flex-row gap-4">
      <x-stat
        class="bg-base-300"
        title="Valor total das notas"
        :value="$this->formataValoresMonetarios(floatval($informacoesTotaisNotas['Totais das notas']), 'BRL')"
        icon="o-document-text"
        tooltip="Somatório do valor das notas fiscais"
        color="text-indigo-400" />

      <x-stat
        class="bg-base-300"
        title="Valor total de ICMS"
        :value="$this->formataValoresMonetarios(floatval($informacoesTotaisNotas['Total de ICMS das notas']), 'BRL')"
        icon="o-chart-bar"
        tooltip="Imposto ICMS total das notas"
        color="text-orange-400" />

      <x-stat
        class="bg-base-300"
        title="Valor total de ICMS ST"
        :value="$this->formataValoresMonetarios(floatval($informacoesTotaisNotas['Total de ICMS ST das notas']), 'BRL')"
        icon="o-shield-check"
        tooltip="ICMS Substituição Tributária"
        color="text-yellow-400" />

      <x-stat
        class="bg-base-300"
        title="Valor total de PIS"
        :value="$this->formataValoresMonetarios(floatval($informacoesTotaisNotas['Valor total do PIS']), 'BRL')"
        icon="o-currency-dollar"
        tooltip="Total de PIS das notas"
        color="text-cyan-400" />

      <x-stat
        class="bg-base-300"
        title="Valor total de COFINS"
        :value="$this->formataValoresMonetarios(floatval($informacoesTotaisNotas['Valor total do COFINS']), 'BRL')"
        icon="o-banknotes"
        tooltip="Total de COFINS das notas"
        color="text-emerald-400" />

    </div>
    HTML;
  }

  #[Computed()]
  public function formataValoresMonetarios(float $valor, string $moeda): string
  {
    $fmtRepo = new NumberFormatRepository();
    $moedaRepo = new CurrencyRepository();
    $fmt = new CurrencyFormatter($fmtRepo, $moedaRepo);
    return $fmt->format($valor, $moeda);
  }
}
