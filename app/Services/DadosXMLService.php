<?php

namespace App\Services;

use App\Models\Empresa;
use App\Repositories\Interface\IDadosXML;
use App\Repositories\Interface\IEmpresa;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class DadosXMLService
{
  public function __construct(
    private readonly IDadosXML $dadosXMLRepository,
    private readonly IEmpresa $empresaRepository
  ) {}

  public function cadastro(string $arquivo, string $idXML, string $cnpj): void
  {
    $infNFe = simplexml_load_string($arquivo)->NFe[0]->infNFe[0];
    $this->dadosXMLRepository->cadastro(
      $this->trataDadosXML(
        chaveNota: $infNFe->attributes()['Id'],
        status: 'AUTORIZADO',
        idXML: $idXML,
        empresa: $this->empresaRepository->consultaEmpresaPorCNPJ($cnpj),
        dhEmissao: new DateTime((string)$infNFe->ide[0]->dhEmi),
        ide: $infNFe->ide
      )
    );
  }

  public function cadastroCancelado(string $arquivo, string $idXML, string $cnpj): void
  {
    $xml = simplexml_load_string($arquivo);
    $this->dadosXMLRepository->cadastro(
      $this->trataDadosXMLEventoCancelamento(
        chaveNota: (string)$xml->evento[0]->infEvento[0]->chNFe[0],
        status: 'CANCELADO',
        xml_id: $idXML,
        justificativa: (string)$xml->evento[0]->infEvento[0]->detEvento[0]->xJust,
        empresa: $this->empresaRepository->consultaEmpresaPorCNPJ($cnpj),
        dhEvento: new DateTime($xml->evento[0]->infEvento[0]->dhEvento)
      )
    );
  }

  public function cadastroInutilizado(string $arquivo, string $xml_id, string $cnpj, string $nomeArquivo): void
  {
    $xml = simplexml_load_string($arquivo);
    $this->dadosXMLRepository->cadastro(
      $this->trataDadosXMLEventoInutilizacao(
        chaveNota: str_replace('-', '', filter_var($nomeArquivo, FILTER_SANITIZE_NUMBER_INT)),
        status: 'INUTILIZADO',
        xml_id: $xml_id,
        modelo: (string)$xml->retInutNFe[0]->infInut[0]->mod,
        serie: (string)$xml->retInutNFe[0]->infInut[0]->serie,
        numeronf: (string)$xml->retInutNFe[0]->infInut[0]->nNFIni,
        numeronf_final: (string)$xml->retInutNFe[0]->infInut[0]->nNFFin,
        justificativa: (string)$xml->inutNFe[0]->infInut[0]->xJust,
        empresa: $this->empresaRepository->consultaEmpresaPorCNPJ($cnpj),
        dhEvento: new DateTime((string)$xml->retInutNFe[0]->infInut[0]->dhRecbto)
      )
    );
  }

  public function consultaDadosXMLPorChave(string $chave): ?Model {
    return $this->dadosXMLRepository->consultaPorChave($chave);
  }

  private function trataDadosXML(
    string $chaveNota,
    string $status,
    string $idXML,
    Empresa $empresa,
    DateTime $dhEmissao,
    \SimpleXMLElement $ide
  ): array {
    return [
      'xml_id' => $idXML,
      'empresa_id' => $empresa->getAttribute('empresa_id'),
      'status' => strtoupper($status),
      'modelo' => (string)$ide->mod,
      'serie' => (string)$ide->serie,
      'numeronf' => (string)$ide->nNF,
      'dh_emissao_evento' => $dhEmissao->format('Y-m-d H:i:s'),
      'chave' => substr($chaveNota, 3)
    ];
  }

  private function trataDadosXMLEventoCancelamento(
    string $chaveNota,
    string $status,
    string $xml_id,
    string $justificativa,
    Empresa $empresa,
    DateTime $dhEvento
  ): array {
    return [
      'xml_id' => $xml_id,
      'empresa_id' => $empresa->getAttribute('empresa_id'),
      'status' => strtoupper($status),
      'modelo' => (string)(int)substr($chaveNota, 20, 2),
      'serie' => (string)(int)substr($chaveNota, 22, 3),
      'numeronf' => (string)(int)substr($chaveNota, 25, 9),
      'justificativa' => $justificativa,
      'dh_emissao_evento' => $dhEvento->format('Y-m-d H:i:s'),
      'chave' => $chaveNota
    ];
  }

  private function trataDadosXMLEventoInutilizacao(
    string $chaveNota,
    string $status,
    string $xml_id,
    string $modelo,
    string $serie,
    string $numeronf,
    string $numeronf_final,
    string $justificativa,
    Empresa $empresa,
    DateTime $dhEvento): array {
    return [
        'xml_id' => $xml_id,
        'empresa_id' => $empresa->getAttribute('empresa_id'),
        'status' => strtoupper($status),
        'modelo' => $modelo,
        'serie' => $serie,
        'numeronf' => $numeronf,
        'numeronf_final' => $numeronf_final,
        'justificativa' => $justificativa,
        'dh_emissao_evento' => $dhEvento->format('Y-m-d H:i:s'),
        'chave' => $chaveNota
    ];
}
}
