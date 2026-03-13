<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class GeraZIPDownloadJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public function __construct(
        private readonly int $usuarioId,
        private readonly array $filtros,
        private readonly string $chaveCache
    ) {
        $this->onQueue('downloads');
    }

    public function middleware(): array
    {
        return [new WithoutOverlapping($this->chaveCache)];
    }

    public function handle(): void
    {
        $tempFileName = 'zip_download_'.$this->chaveCache.'.zip';
        $tempZipPath = storage_path('app/temp/'.$tempFileName);

        if (! is_dir(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new ZipArchive;

        if ($zip->open($tempZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            Cache::put($this->chaveCache, ['status' => 'erro', 'mensagem' => 'Não foi possível criar o arquivo ZIP.'], now()->addMinutes(10));

            return;
        }

        try {
            foreach ($this->consultaDados()->cursor() as $dado) {
                if ($dado->xml) {
                    $zip->addFromString($dado->chave.'.xml', $dado->xml);
                }
            }
        } catch (\Throwable $e) {
            Log::error("GeraZIPDownloadJob erro usuario {$this->usuarioId}: ".$e->getMessage());
            $zip->close();
            @unlink($tempZipPath);
            Cache::put($this->chaveCache, ['status' => 'erro', 'mensagem' => 'Erro ao gerar o arquivo ZIP.'], now()->addMinutes(10));

            return;
        }

        $zip->close();

        Cache::put($this->chaveCache, [
            'status' => 'pronto',
            'arquivo' => $tempFileName,
        ], now()->addMinutes(30));
    }

    private function consultaDados()
    {
        $filtros = $this->filtros;

        return DB::table('dados_xml as dx1')
            ->join('xmls', 'xmls.xml_id', '=', 'dx1.xml_id')
            ->select('dx1.chave', 'xmls.xml')
            ->where(function ($query) {
                $query->whereIn('dx1.status', ['cancelado', 'denegado', 'inutilizado'])
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('dx1.status', 'autorizado')
                            ->whereNotExists(function ($subSubQuery) {
                                $subSubQuery->select(DB::raw(1))
                                    ->from('dados_xml as dx2')
                                    ->whereRaw('dx2.numeronf = dx1.numeronf')
                                    ->whereRaw('dx2.empresa_id = dx1.empresa_id')
                                    ->whereIn('dx2.status', ['cancelado', 'denegado', 'inutilizado']);
                            });
                    });
            })
            ->where('empresa_id', $filtros['empresa_id'])
            ->when(! is_null($filtros['data_inicio']) && ! is_null($filtros['data_fim']), function ($query) use ($filtros) {
                $inicio = date('Y-m-d', strtotime($filtros['data_inicio']));
                $fim = date('Y-m-d', strtotime($filtros['data_fim']));
                if ($inicio === $fim) {
                    $query->whereDate('dh_emissao_evento', $inicio);
                } else {
                    $query->whereBetween(DB::raw('DATE(dh_emissao_evento)'), [$inicio, $fim]);
                }
            })
            ->when(! is_null($filtros['data_inicio']) && is_null($filtros['data_fim']), function ($query) use ($filtros) {
                $query->whereDate('dh_emissao_evento', '>=', date('Y-m-d', strtotime($filtros['data_inicio'])));
            })
            ->when(is_null($filtros['data_inicio']) && ! is_null($filtros['data_fim']), function ($query) use ($filtros) {
                $query->whereDate('dh_emissao_evento', '<=', date('Y-m-d', strtotime($filtros['data_fim'])));
            })
            ->when(! is_null($filtros['status']) && $filtros['status'] !== 'TODAS', function ($query) use ($filtros) {
                $query->where('dx1.status', $filtros['status']);
            })
            ->when(! is_null($filtros['modelo']) && $filtros['modelo'] !== 'TODAS', function ($query) use ($filtros) {
                $query->where('dx1.modelo', $filtros['modelo']);
            })
            ->when(! is_null($filtros['serie']), function ($query) use ($filtros) {
                $query->where('dx1.serie', $filtros['serie']);
            })
            ->when(! is_null($filtros['numeroInicial']) && ! is_null($filtros['numeroFinal']), function ($query) use ($filtros) {
                $query->whereBetween('numeronf', [$filtros['numeroInicial'], $filtros['numeroFinal']]);
            })
            ->when(! is_null($filtros['numeroInicial']) && is_null($filtros['numeroFinal']), function ($query) use ($filtros) {
                $query->where('numeronf', '>=', $filtros['numeroInicial']);
            })
            ->when(is_null($filtros['numeroInicial']) && ! is_null($filtros['numeroFinal']), function ($query) use ($filtros) {
                $query->where('numeronf', '<=', $filtros['numeroFinal']);
            });
    }
}
