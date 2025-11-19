<?php

namespace App\Livewire\Icons\Repo;

use Livewire\Attributes\Computed;
use Livewire\Component;

class Xlsx extends Component
{
    public ?string $cor = null;
    public ?string $tamanho = null;

    #[Computed()]
    public function defineTamanho(): string {
        return match($this->tamanho) {
            'xs' => '20',
            'sm' => '24',
            'md' => '28',
            'lg' => '32',
            'xl' => '36',
            default => '24'
        };
    }

    #[Computed()]
    public function defineCor(): string {
        return match($this->cor) {
            'light' => '#FFF',
            'dark' => '#000',
            default => '#000'
        };
    }

    public function render()
    {
        return <<<'HTML'
            <svg width="{{ $this->defineTamanho }}px" height="{{ $this->defineTamanho }}px" viewBox="0 0 192 192" xmlns="http://www.w3.org/2000/svg" fill="none">
                <path d="M56 30c0-1.662 1.338-3 3-3h108c1.662 0 3 1.338 3 3v132c0 1.662-1.338 3-3 3H59c-1.662 0-3-1.338-3-3v-32m0-68V30" style="fill-opacity:.402658;stroke:{{ $this->defineCor }};stroke-width:12;stroke-linecap:round;paint-order:stroke fill markers"/>
                <rect width="68" height="68" x="-58.1" y="40.3" rx="3" style="fill:none;fill-opacity:.402658;stroke:{{ $this->defineCor }};stroke-width:12;stroke-linecap:round;stroke-linejoin:miter;stroke-dasharray:none;stroke-opacity:1;paint-order:stroke fill markers" transform="translate(80.1 21.7)"/>
                <path d="M138.79 164.725V27.175M56.175 58.792H170M170 96H90.328M169 133.21H56.175M44.5 82l23 28m0-28-23 28" style="fill:none;stroke:{{ $this->defineCor }};stroke-width:12;stroke-linecap:round;stroke-linejoin:round;stroke-dasharray:none;stroke-opacity:1"/>
            </svg>
        HTML;
    }
}
