<?php

namespace App\Livewire\Componentes\Relatorios\Dashboard;

use Livewire\Component;

class Topprodutosvendidos extends Component
{
  public array $dadosTopProdutosVendidos = [];
  public array $graficoTopProdutosVendidos = [];

  public function mount(array $dados): void
  {
    $this->dadosTopProdutosVendidos = [];

    $labels = [];
    $values = [];

    foreach ($dados as $dado) {
      $nomeProduto = $dado['Nome Produto'];
      $quantidade = $dado['Quantidade'];

      $labels[] = $nomeProduto;
      $values[] = $quantidade;

      $this->dadosTopProdutosVendidos[] = [
        'y' => $nomeProduto,
        'value' => $quantidade,
      ];
    }

    $this->graficoTopProdutosVendidos = [
      'type' => 'bar',
      'data' => [
        'labels' => $labels,
        'datasets' => [
          [
            'label' => 'Top produtos vendidos',
            'data' => $values,
            'borderWidth' => 1,
            'backgroundColor' => 'rgba(59, 130, 246, 0.7)', // azul padrão Tailwind
          ],
        ],
      ],
      'options' => [
        'responsive' => true,
        'scales' => [
          'y' => [
            'beginAtZero' => true,
          ],
        ],
      ],
    ];
  }

  public function render()
  {
    return <<<'HTML'
      <x-chart wire:model="graficoTopProdutosVendidos" />
    HTML;
  }
}
