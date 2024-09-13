<?php

namespace App\Livewire\Views\Importacao;

use App\Repositories\Eloquent\Repository\EmpresaRepository;
use App\Services\DadosXMLService;
use App\Services\XMLService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use Livewire\WithFileUploads;

class Xml extends Component
{
  use WithFileUploads;

  public $arquivo;
  public array $xmls = [];
  public string $xmlNomeAtual = '';
  public Collection $empresas;
  public ?string $cnpjEmpresaAtual = null;

  public function mount(EmpresaRepository $empresaRepository): void {
    $this->empresas = $empresaRepository->listagemEmpresas();
  }

  #[Title('SAFI NFE - Importação de XML')]
  #[Layout('components.layouts.main')]
  public function render()
  {
    return view('livewire.views.importacao.xml');
  }

  public function verXML(XMLService $xmlService, DadosXMLService $dadosXMLService): Redirector|RedirectResponse
  {
    if ($this->arquivo) {
      if ($this->arquivo->getClientOriginalExtension() !== 'rar') {
        Session::flash('erro', 'Aceitamos apenas .rar');
        return redirect('/importacaoxml');
      }
      $this->recebeRARXMLS($xmlService, $dadosXMLService);
    }
  }

  private function recebeRARXMLS(XMLService $xmlService, DadosXMLService $dadosXMLService)
  {
    DB::beginTransaction();

    try {

      $path = $this->arquivo->storeAs('public', $this->arquivo->getClientOriginalName());
      $realPath = storage_path('app/' . $path);

      $rar_file = rar_open($realPath);
      $xmls = rar_list($rar_file);

      foreach ($xmls as $xml) {
        $this->defineGravaXML($xml, $xmlService, $dadosXMLService);
      }
      DB::commit();
      Session::flash('sucesso', "XMLS importados com sucesso.");
      redirect('/importacaoxml');
    } catch (\Exception $e) {
      DB::rollBack();
      Session::flash('erro', $e->getMessage() . " => XML com erro: " . $this->xmlNomeAtual);
      redirect('/importacaoxml');
    } finally {
      rar_close($rar_file);
      unlink($realPath);
    }
  }

  private function defineGravaXML($xml, XMLService $xmlService, DadosXMLService $dadosXMLService) {
    $this->xmlNomeAtual = $xml->getName();
    $path = storage_path('app/tempXML');
    $pathXML = storage_path('app/tempXML/' . $this->xmlNomeAtual);
    $xml->extract($path);
    $xmlConsultado = $dadosXMLService->consultaDadosXMLPorChave(str_replace('-', '', filter_var($this->xmlNomeAtual, FILTER_SANITIZE_NUMBER_INT)));

      if (str_contains($this->xmlNomeAtual, 'ProcNfe')) {
        if (is_null($xmlConsultado) || $xmlConsultado->getAttribute('status') !== 'AUTORIZADO') {
          $xmlGravado = $xmlService->cadastro($pathXML);
          $dadosXMLService->cadastro($xmlGravado->getAttribute('xml'), $xmlGravado->getAttribute('xml_id'), $this->cnpjEmpresaAtual);
        }
      }

      if (str_contains($this->xmlNomeAtual, 'Can')) {
        if (is_null($xmlConsultado) || $xmlConsultado->getAttribute('status') !== 'CANCELADO') {
          $xmlGravado = $xmlService->cadastro($pathXML);
          $dadosXMLService->cadastroCancelado($xmlGravado->getAttribute('xml'), $xmlGravado->getAttribute('xml_id'), $this->cnpjEmpresaAtual);
        }
      }
      if (str_contains($this->xmlNomeAtual, 'inu')) {
        if (is_null($xmlConsultado) || $xmlConsultado->getAttribute('status') !== 'INUTILIZADO') {
          $xmlGravado = $xmlService->cadastro($pathXML);
          $dadosXMLService->cadastroInutilizado($xmlGravado->getAttribute('xml'), $xmlGravado->getAttribute('xml_id'), $this->cnpjEmpresaAtual, $this->xmlNomeAtual);
        }
      }

    unlink($pathXML);
  }
}
