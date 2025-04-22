<?php

namespace App\Jobs;

use App\Services\DadosXMLService;
use App\Services\XMLService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Mary\Traits\Toast;
use ZipArchive;

class ImportaXMLsJob implements ShouldQueue
{
  use Queueable, Toast;

  protected ?int $usuario_id = null;
  public string $xmlNomeAtual = '';
  /**
   * Create a new job instance.
   */
  public function __construct(protected string $pathArquivo, protected int $empresa_id)
  {
    $this->usuario_id = Auth::user()->id;
  }

  /**
   * Execute the job.
   */
  public function handle(XMLService $xmlService, DadosXMLService $dadosXMLService): void
  {
    $pathXMLUsuario = storage_path('app/tempXML/' . $this->usuario_id);

    if (!is_dir($pathXMLUsuario)) {
      mkdir($pathXMLUsuario, 0777, true);
    }

    $zip = new ZipArchive();

    if ($zip->open($this->pathArquivo) === TRUE) {

      $zip->extractTo($pathXMLUsuario);
      $zip->close();

      $arquivos = array_filter(scandir($pathXMLUsuario), fn ($f) => $f !== '.' && $f !== '..');

      DB::beginTransaction();

      foreach ($arquivos as $arquivo) {
        try {
          $this->xmlNomeAtual = $arquivo;
          $path = "{$pathXMLUsuario}/{$arquivo}";
          $this->defineGravaXML($path, $xmlService, $dadosXMLService);
          DB::commit();
        } catch (\Throwable $e) {
          DB::rollBack();
          Log::warning("{$e->getMessage()} => XML com erro: $this->xmlNomeAtual");
          $this->warning("{$e->getMessage()} => XML com erro: $this->xmlNomeAtual", redirectTo: route('importacao'));
        } finally {
        }
      }

      Cache::forget("importacao_progress_{$this->usuario_id}");
      unlink($this->pathArquivo);
      File::deleteDirectory($pathXMLUsuario);
    }
  }

  private function defineGravaXML(string $caminho, XMLService $xmlService, DadosXMLService $dadosXMLService): void
  {
    $xmlConsultado = $dadosXMLService->consultaDadosXMLPorChave(str_replace('-', '', filter_var($this->xmlNomeAtual, FILTER_SANITIZE_NUMBER_INT)));

    if (str_contains($this->xmlNomeAtual, 'ProcNfe')) {
      if (is_null($xmlConsultado) || $xmlConsultado->getAttribute('status') !== 'AUTORIZADO') {
        $xmlGravado = $xmlService->cadastro($caminho);
        $dadosXMLService->cadastro($xmlGravado->getAttribute('xml'), $xmlGravado->getAttribute('xml_id'), $this->empresa_id);
      }
    }

    if (str_contains($this->xmlNomeAtual, 'Can')) {
      if (is_null($xmlConsultado) || $xmlConsultado->getAttribute('status') !== 'CANCELADO') {
        $xmlGravado = $xmlService->cadastro($caminho);
        $dadosXMLService->cadastroCancelado($xmlGravado->getAttribute('xml'), $xmlGravado->getAttribute('xml_id'), $this->empresa_id);
      }
    }
    if (str_contains($this->xmlNomeAtual, 'inu')) {
      if (is_null($xmlConsultado) || $xmlConsultado->getAttribute('status') !== 'INUTILIZADO') {
        $xmlGravado = $xmlService->cadastro($caminho);
        $dadosXMLService->cadastroInutilizado($xmlGravado->getAttribute('xml'), $xmlGravado->getAttribute('xml_id'), $this->empresa_id, $this->xmlNomeAtual);
      }
    }

    unlink($caminho);
  }
}
