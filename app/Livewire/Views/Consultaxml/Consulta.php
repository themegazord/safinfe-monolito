<?php

namespace App\Livewire\Views\Consultaxml;

use App\Models\Empresa;
use App\Models\User;
use App\Models\XML;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use ZipArchive;

class Consulta extends Component
{
  use WithPagination, Toast;
  public Authenticatable|User $usuario;
  public ?Collection $empresas;
  public string $diretorioUsuario = '';
  public string $diretorioRARUsuario = '';
  public ?array $dadosXML = null;
  public ?int $porPagina = 15;

  public function mount(): void
  {
    $this->usuario = Auth::user();
    $this->empresas = match ($this->usuario->getAttribute('role')) {
      'CONTADOR' => $this->usuario->contador->contabilidade->empresas,
      'ADMIN' => $this->pesquisaEmpresasAdmin(),
      default => null
    };
    $this->dadosXML = ['empresa_id' => null, 'data_inicio' => null, 'data_fim' => null, 'status' => null, 'modelo' => null, 'serie' => null, 'numeroInicial' => null, 'numeroFinal' => null];
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

  #[On('envia-consulta')]
  public function consulta(array $params): void
  {
    $this->dadosXML = $params;
  }

  #[On('download-direto')]
  public function downloadDireto(array $params)
  {
    $dados_xml = $this->consultaDadosDownload($params);

    if ($dados_xml->isEmpty()) {
      $this->warning("Nenhuma nota fiscal encontrada.");
      return;
    }

    $tempZipPath = tempnam(sys_get_temp_dir(), 'zip_');
    $zip = new ZipArchive();

    if ($zip->open($tempZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
      $this->warning("Não foi possível criar o ZIP em memória.");
      return;
    }

    foreach ($dados_xml as $dado) {
      try {
        $conteudoXML = XML::find($dado->xml_id)?->getAttribute('xml');
        if ($conteudoXML) {
          $zip->addFromString($dado->chave . '.xml', $conteudoXML);
        }
      } catch (\Exception $e) {
        $this->warning("Erro ao processar o XML ID: {$dado->xml_id}");
        Log::error("Erro ao processar o XML ID: {$dado->xml_id}, Erro: " . $e->getMessage());
        return;
      }
    }

    $zip->close();

    return response()->streamDownload(function () use ($tempZipPath) {
      readfile($tempZipPath);
      unlink($tempZipPath); // limpa após envio
    }, 'XMLArquivos.zip', [
      'Content-Type' => 'application/zip',
    ]);
  }

  public function pesquisaEmpresasAdmin(?string $valor = null): Collection
  {
    return Empresa::query()->where('fantasia', 'like', "%$valor%")->get();
  }

  private function consultaDadosDownload(array $dadosXML): Collection {
    $dados_xml = DB::table('dados_xml as dx1')
    ->where(function ($query) {
        $query->whereIn('dx1.status', ['cancelado', 'denegado', 'inutilizado'])
              ->orWhere(function ($subQuery) {
                  $subQuery->where('dx1.status', 'autorizado')
                           ->whereNotExists(function ($subSubQuery) {
                               $subSubQuery->select(DB::raw(1))
                                           ->from('dados_xml as dx2')
                                           ->whereRaw('dx2.numeronf = dx1.numeronf')
                                           ->whereIn('dx2.status', ['cancelado', 'denegado', 'inutilizado']);
                           });
              });
    })
    ->where('empresa_id', $dadosXML['empresa_id'])

    // filtro de data com when
    ->when(!is_null($dadosXML['data_inicio']) && !is_null($dadosXML['data_fim']), function ($query) use ($dadosXML) {
        $inicio = date('Y-m-d', strtotime($dadosXML['data_inicio']));
        $fim = date('Y-m-d', strtotime($dadosXML['data_fim']));

        if ($inicio === $fim) {
            $query->whereDate('dh_emissao_evento', $inicio);
        } else {
            $query->whereBetween(DB::raw('DATE(dh_emissao_evento)'), [$inicio, $fim]);
        }
    })
    ->when(!is_null($dadosXML['data_inicio']) && is_null($dadosXML['data_fim']), function ($query) use ($dadosXML) {
        $query->whereDate('dh_emissao_evento', '>=', date('Y-m-d', strtotime($dadosXML['data_inicio'])));
    })
    ->when(is_null($dadosXML['data_inicio']) && !is_null($dadosXML['data_fim']), function ($query) use ($dadosXML) {
        $query->whereDate('dh_emissao_evento', '<=', date('Y-m-d', strtotime($dadosXML['data_fim'])));
    })

    // status
    ->when($dadosXML['status'] !== "TODAS", function ($query) use ($dadosXML) {
        $query->where('dx1.status', $dadosXML['status']);
    })

    // modelo
    ->when($dadosXML['modelo'] !== "TODAS", function ($query) use ($dadosXML) {
        $query->where('dx1.modelo', $dadosXML['modelo']);
    })

    // série
    ->when(!is_null($dadosXML['serie']), function ($query) use ($dadosXML) {
        $query->where('dx1.serie', $dadosXML['serie']);
    })

    // faixa de número NF
    ->when(!is_null($dadosXML['numeroInicial']) && !is_null($dadosXML['numeroFinal']), function ($query) use ($dadosXML) {
        $query->whereBetween('numeronf', [$dadosXML['numeroInicial'], $dadosXML['numeroFinal']]);
    })
    ->when(!is_null($dadosXML['numeroInicial']) && is_null($dadosXML['numeroFinal']), function ($query) use ($dadosXML) {
        $query->where('numeronf', '>=', $dadosXML['numeroInicial']);
    })
    ->when(is_null($dadosXML['numeroInicial']) && !is_null($dadosXML['numeroFinal']), function ($query) use ($dadosXML) {
        $query->where('numeronf', '<=', $dadosXML['numeroFinal']);
    });

    return $dados_xml->get();
  }
}
