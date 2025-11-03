<?php

namespace App\Repositories\Interface;

use App\Models\DadosXML;
use Illuminate\Contracts\Database\Query\Builder;

interface IDadosXML
{
    public function cadastro(array $dadosXML): DadosXML;

    public function primeiroUltimoXML(int $cliente_id): array;

    public function dadosXMLPorChave(string $chave): ?DadosXML;

    public function preConsultaDadosXML(array $dadosCliente, int $empresa_id): Builder;

    public function consultaPorChave(string $chave): ?DadosXML;

    public function consultaPorID(int $dado_id): ?DadosXML;

    public function consultaDadosNotaFiscalAutorizada(int $numeronf): ?DadosXML;

    public function consultaVariosXML($xmls);

    public function consultaXMLChaveStatus(string $chave, string $status): ?DadosXML;

    public function consultaUltimaNotaRecebidaEmpresa(int $empresa_id): ?DadosXML;
}
