<?php

namespace App\Repositories\Eloquent\Repository;

use App\Models\DadosXML;
use App\Repositories\Interface\IDadosXML;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class DadosXMLRepository implements IDadosXML
{
    public function cadastro(array $dadosXML): DadosXML
    {
        return DadosXML::query()
            ->create($dadosXML);
    }

    public function primeiroUltimoXML(int $cliente_id): array
    {
        $xmlCliente = DadosXML::query()
            ->where('cliente_id', $cliente_id)
            ->get();

        return [
            'min' => $xmlCliente->min('numeronf'),
            'max' => $xmlCliente->max('numeronf'),
        ];
    }

    public function dadosXMLPorChave(string $chave): ?DadosXML
    {
        return DadosXML::query()
            ->where('chave', $chave)
            ->where('status', 'AUTORIZADA')
            ->first();
    }

    public function preConsultaDadosXML(array $dadosCliente, int $empresa_id): Builder
    {
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
            ->where('empresa_id', $empresa_id);
        if (
            ! is_null($dadosCliente['data_inicio']) &&
            ! is_null($dadosCliente['data_fim']) &&
            ($dadosCliente['data_inicio'] !== $dadosCliente['data_fim'])
        ) {
            $dados_xml = $dados_xml->whereDate('dh_emissao_evento', '>=', date('Y/m/d', strtotime($dadosCliente['data_inicio'])))
                ->whereDate('dh_emissao_evento', '<=', date('Y/m/d', strtotime($dadosCliente['data_fim'])));
        }
        if (
            ! is_null($dadosCliente['data_inicio']) &&
            ! is_null($dadosCliente['data_fim']) &&
            ($dadosCliente['data_inicio'] === $dadosCliente['data_fim'])
        ) {
            $dados_xml = $dados_xml->whereDate('dh_emissao_evento', date('Y/m/d', strtotime($dadosCliente['data_inicio'])));
        }
        if (! is_null($dadosCliente['data_inicio']) && is_null($dadosCliente['data_fim'])) {
            $dados_xml = $dados_xml->where('dh_emissao_evento', '>=', date('Y/m/d', strtotime($dadosCliente['data_inicio'])));
        }
        if (is_null($dadosCliente['data_inicio']) && ! is_null($dadosCliente['data_fim'])) {
            $dados_xml = $dados_xml->where('dh_emissao_evento', '<=', date('Y/m/d', strtotime($dadosCliente['data_fim'])));
        }
        if ($dadosCliente['status'] !== 'TODAS') {
            $dados_xml = $dados_xml->where('dx1.status', $dadosCliente['status']);
        }
        if ($dadosCliente['modelo'] !== 'TODAS') {
            $dados_xml = $dados_xml->where('dx1.modelo', $dadosCliente['modelo']);
        }
        if (! is_null($dadosCliente['serie'])) {
            $dados_xml = $dados_xml->where('dx1.serie', $dadosCliente['serie']);
        }
        if (! is_null($dadosCliente['numeroInicial']) && ! is_null($dadosCliente['numeroFinal'])) {
            $dados_xml = $dados_xml->whereBetween('numeronf', [$dadosCliente['numeroInicial'], $dadosCliente['numeroFinal']]);
        }
        if (! is_null($dadosCliente['numeroInicial']) && is_null($dadosCliente['numeroFinal'])) {
            $dados_xml = $dados_xml->where('numeronf', '>=', $dadosCliente['numeroInicial']);
        }
        if (is_null($dadosCliente['numeroInicial']) && ! is_null($dadosCliente['numeroFinal'])) {
            $dados_xml = $dados_xml->where('numeronf', '<=', $dadosCliente['numeroFinal']);
        }

        return $dados_xml->orderBy('dh_emissao_evento', 'asc');
    }

    public function consultaPorChave(string $chave): ?DadosXML
    {
        return DadosXML::query()
            ->where('chave', $chave)
            ->first();
    }

    public function consultaPorID(int $dado_id): ?DadosXML
    {
        return DadosXML::query()
            ->where('dados_id', $dado_id)
            ->first();
    }

    public function consultaDadosNotaFiscalAutorizada(int $numeronf): ?DadosXML
    {
        return DadosXML::query()
            ->where('numeronf', $numeronf)
            ->where('status', 'AUTORIZADO')
            ->first();
    }

    public function consultaVariosXML($xmls)
    {
        return DadosXML::query()
            ->where('status', 'AUTORIZADA')
            ->whereIn('chave', $xmls)
            ->get();
    }

    public function consultaXMLChaveStatus(string $chave, string $status): ?DadosXML
    {
        return DadosXML::query()
            ->where('chave', $chave)
            ->where('status', $status)
            ->first();
    }

    public function consultaUltimaNotaRecebidaEmpresa(int $empresa_id): ?DadosXML
    {
        return DadosXML::query()
            ->where('empresa_id', $empresa_id)
            ->orderBy('dados_id', 'desc')
            ->first();
    }
}
