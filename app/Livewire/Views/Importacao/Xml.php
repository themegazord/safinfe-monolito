<?php

namespace App\Livewire\Views\Importacao;

use App\Repositories\Eloquent\Repository\EmpresaRepository;
use App\Services\DadosXMLService;
use App\Services\XMLService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use Livewire\WithFileUploads;
use ZipArchive;

class Xml extends Component
{
  use WithFileUploads;

  public $arquivo;
  public array $xmls = [];
  public string $xmlNomeAtual = '';
  public Collection $empresas;
  public ?string $cnpjEmpresaAtual = null;

  public function mount(EmpresaRepository $empresaRepository): void
  {
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
      if ($this->arquivo->getClientOriginalExtension() !== 'zip') {
        Session::flash('erro', 'Aceitamos apenas .zip');
        return redirect('/importacaoxml');
      }
      return $this->recebeRARXMLS($xmlService, $dadosXMLService);
    }
  }

  private function recebeRARXMLS(XMLService $xmlService, DadosXMLService $dadosXMLService): Redirector|RedirectResponse
  {
    DB::beginTransaction();

    try {
      $zip = new ZipArchive();

      $path = $this->arquivo->storeAs('public', $this->arquivo->getClientOriginalName());
      $realPath = storage_path('app/' . $path);
      $pathXMLUsuario = storage_path('app/tempXML/' . Auth::user()->id);

      DB::commit();
      if ($zip->open($realPath) === TRUE) {
        $zip->extractTo($pathXMLUsuario);
        $zip->close();
        foreach(array_filter(scandir($pathXMLUsuario), fn ($arq) => $arq !== '.' && $arq !== '..') as $arquivo) {
          $this->xmlNomeAtual = $arquivo;
          $this->defineGravaXML("{$pathXMLUsuario}/{$arquivo}", $xmlService, $dadosXMLService);
        }
      }
      Session::flash('sucesso', "XMLS importados com sucesso.");
      return redirect('/importacaoxml');
    } catch (\Exception $e) {
      DB::rollBack();
      Session::flash('erro', $e->getMessage() . " => XML com erro: " . $this->xmlNomeAtual);
      return redirect('/importacaoxml');
    } finally {
      unlink($realPath);
    }
  }

  private function defineGravaXML(string $caminho, XMLService $xmlService, DadosXMLService $dadosXMLService)
  {
    $xmlConsultado = $dadosXMLService->consultaDadosXMLPorChave(str_replace('-', '', filter_var($this->xmlNomeAtual, FILTER_SANITIZE_NUMBER_INT)));

    if (str_contains($this->xmlNomeAtual, 'ProcNfe')) {
      if (is_null($xmlConsultado) || $xmlConsultado->getAttribute('status') !== 'AUTORIZADO') {
        $xmlGravado = $xmlService->cadastro($caminho);
        $dadosXMLService->cadastro($xmlGravado->getAttribute('xml'), $xmlGravado->getAttribute('xml_id'), $this->cnpjEmpresaAtual);
      }
    }

    if (str_contains($this->xmlNomeAtual, 'Can')) {
      if (is_null($xmlConsultado) || $xmlConsultado->getAttribute('status') !== 'CANCELADO') {
        $xmlGravado = $xmlService->cadastro($caminho);
        $dadosXMLService->cadastroCancelado($xmlGravado->getAttribute('xml'), $xmlGravado->getAttribute('xml_id'), $this->cnpjEmpresaAtual);
      }
    }
    if (str_contains($this->xmlNomeAtual, 'inu')) {
      if (is_null($xmlConsultado) || $xmlConsultado->getAttribute('status') !== 'INUTILIZADO') {
        $xmlGravado = $xmlService->cadastro($caminho);
        $dadosXMLService->cadastroInutilizado($xmlGravado->getAttribute('xml'), $xmlGravado->getAttribute('xml_id'), $this->cnpjEmpresaAtual, $this->xmlNomeAtual);
      }
    }

    unlink($caminho);
  }
}
