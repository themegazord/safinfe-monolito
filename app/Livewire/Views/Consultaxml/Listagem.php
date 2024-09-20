<?php

namespace App\Livewire\Views\Consultaxml;

use App\Models\User;
use App\Repositories\Eloquent\Repository\DadosXMLRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
  use WithPagination;

  public Authenticatable|User $usuario;
  public array $dados = [];
  public Collection $dados_xml;
  public string $hash = '';
  public int $perPage = 50;

  public function mount(string $hash): void
  {
    $this->usuario = Auth::user();
    $this->hash = $hash;
  }

  #[Title('SAFI NFE - Listagem de XML')]
  #[Layout('components.layouts.main')]
  public function render(DadosXMLRepository $dadosXMLRepository)
  {
    $this->dados = json_decode(base64_decode($this->hash), true);

    $dados_xml = match($this->usuario->getAttribute('role')) {
      'CLIENTE' => $dados_xml = $dadosXMLRepository->preConsultaDadosXML($this->dados, $this->usuario->cliente->empresa->getAttribute('empresa_id')),
      'CONTADOR' => $dados_xml = $dadosXMLRepository->preConsultaDadosXML($this->dados, $this->dados['empresa_id']),
    };

    $dados_xml = $dados_xml->paginate($this->perPage);

    return view('livewire.views.consultaxml.listagem', [
      'listagem' => compact('dados_xml')
    ]);
  }
}
