<?php

namespace App\Livewire\Componentes\Relatorios\Dashboard;

use Livewire\Component;

class Totalnotaspordiames extends Component
{
    public array $dadosAtual = [];

    public array $relatorioTotalNotasPorDiaMes = [];

    public function mount(array $dados): void
    {
        $this->dadosAtual = [];

        $labels = [];
        $values = [];

        foreach ($dados as $key => $dado) {
            $dataFormatada = date('d/m/Y', strtotime($key));
            $labels[] = $dataFormatada;
            $values[] = $dado;

            $this->dadosAtual[] = [
                'y' => $dataFormatada,
                'value' => $dado,
            ];
        }

        $this->relatorioTotalNotasPorDiaMes = [
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Total de vendas por dia',
                        'data' => $values,
                        'borderWidth' => 1,
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
        <x-chart wire:model="relatorioTotalNotasPorDiaMes" />
        HTML;
    }
}
