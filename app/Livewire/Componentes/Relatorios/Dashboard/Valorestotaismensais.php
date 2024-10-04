<?php

namespace App\Livewire\Componentes\Relatorios\Dashboard;

use CommerceGuys\Intl\Currency\CurrencyRepository;
use CommerceGuys\Intl\Formatter\CurrencyFormatter;
use CommerceGuys\Intl\NumberFormat\NumberFormatRepository;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class Valorestotaismensais extends Component
{
  public array $informacoesTotaisNotas = [];

  public function mount(array $dados): void {
    $this->informacoesTotaisNotas = $dados;
  }

  public function render()
  {
    return view('livewire.componentes.relatorios.dashboard.valorestotaismensais');
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
