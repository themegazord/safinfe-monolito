<?php

namespace App\Livewire\Views\Consultaxml;

use App\Livewire\Forms\ConsultaClienteXMLForm;
use App\Livewire\Forms\ConsultaContadorXMLForm;
use App\Models\User;
use App\Repositories\Eloquent\Repository\DadosXMLRepository;
use App\Repositories\Eloquent\Repository\XMLRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use ZipArchive;

class Consulta extends Component
{
  public Authenticatable|User $usuario;
  public ConsultaContadorXMLForm $consultaContador;
  public ConsultaClienteXMLForm $consultaCliente;
  public ?Collection $empresas;
  public string $diretorioUsuario = '';
  public string $diretorioRARUsuario = '';

  public function mount(): void
  {
    $this->usuario = Auth::user();
    $this->empresas = ($this->usuario->getAttribute('role') === 'CONTADOR') ? $this->usuario->contador->contabilidade->empresas : null;
  }

  public function __destruct()
  {
    if (!empty($this->diretorioUsuario)) $this->removePasta($this->diretorioUsuario);
    if (!empty($this->diretorioRARUsuario)) $this->removePasta($this->diretorioRARUsuario);
  }

  #[Title('SAFI NFE - Consulta XML')]
  #[Layout('components.layouts.main')]
  public function render()
  {

    return view('livewire.views.consultaxml.consulta');
  }

  public function consulta(): Redirector|RedirectResponse
  {

    if ($this->usuario->getAttribute('role') === 'CLIENTE') {
      $this->consultaCliente->validate();
      return redirect('/consultaxml/' . base64_encode(json_encode($this->consultaCliente)));
    }

    if ($this->usuario->getAttribute('role') === 'CONTADOR') {
      return redirect('/consultaxml/' . base64_encode(json_encode($this->consultaContador)));
    }
  }

  public function downloadDireto(DadosXMLRepository $dadosXMLRepository, XMLRepository $xmlRepository)
  {
    match ($this->usuario->getAttribute('role')) {
      'CLIENTE' => $this->consultaCliente->validate(),
      'CONTADOR' => $this->consultaContador->validate()
    };
    $zip = new ZipArchive();
    $dados_xml = match ($this->usuario->getAttribute('role')) {
      'CLIENTE' => $dadosXMLRepository->preConsultaDadosXML(
        $this->consultaCliente->all(),
        $this->usuario->cliente->empresa->getAttribute('empresa_id')
      ),
      'CONTADOR' => $dadosXMLRepository->preConsultaDadosXML(
        $this->consultaContador->all(),
        $this->consultaContador->empresa_id
      )
    };

    $this->diretorioUsuario = storage_path('app/tempXMLExportPorUsuario/' . $this->usuario->getAttribute('id'));
    $this->diretorioRARUsuario = storage_path("app/tempRARDownload/" . $this->usuario->getAttribute('id'));
    if (!file_exists($this->diretorioUsuario)) {
      mkdir($this->diretorioUsuario, 0755, true);
    }

    if (!file_exists($this->diretorioRARUsuario)) {
      mkdir($this->diretorioRARUsuario, 0755, true);
    }
    $nomeZIP = match($this->usuario->getAttribute('role')) {
      'CLIENTE' => $this->diretorioRARUsuario . '/' . $this->consultaCliente->data_inicio . '_' . $this->consultaCliente->data_fim . '.zip',
      'CONTADOR' => $this->diretorioRARUsuario . '/' . $this->consultaContador->data_inicio . '_' . $this->consultaContador->data_fim . '.zip'
    };
    if ($zip->open($nomeZIP, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
      throw new \Exception("Não foi possível criar o arquivo .zip: " . $nomeZIP);
    }
    foreach ($dados_xml->get() as $dado) {
      try {
        $caminhoXML = $this->diretorioUsuario . '/' . $dado->chave . '.xml';

        $xml = fopen($caminhoXML, "a+");

        if ($xml === false) {
          throw new \Exception("Não foi possível criar o arquivo XML: " . $caminhoXML);
        }

        fwrite($xml, $xmlRepository->consultaPorId($dado->xml_id)->getAttribute('xml'));
        fclose($xml);
        $zip->addFile($caminhoXML, $dado->chave . '.xml');
      } catch (\Exception $e) {
        Log::error("Erro ao processar o XML ID: {$dado->xml_id}, Erro: " . $e->getMessage());
      }
    }
    $zip->close();
    return response()->download($nomeZIP, "XMLArquivos.zip", [
      'Content-Type' => 'application/zip',
    ])->deleteFileAfterSend(true);
  }

  private function removePasta(string $caminho): void
  {
    $arquivos = array_diff(scandir($caminho), array('.', '..'));

    foreach ($arquivos as $arquivo) {
      is_dir("$caminho/$arquivo") ? $this->removePasta($caminho) : unlink("$caminho/$arquivo");
    }

    rmdir($caminho);
  }
}
