<?php

namespace App\Services;

use App\Exceptions\XMLException;
use App\Repositories\Eloquent\Repository\DadosXMLRepository;
use App\Repositories\Interface\IEmpresa;
use App\Repositories\Interface\IXML;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class XMLService
{
  public function __construct(
    private readonly IXML $xmlRepository,
    private readonly IEmpresa $empresaRepository,
    private readonly DadosXMLRepository $dadosXMLRepository
  ) {}

  public function cadastro(string $arquivo): Model
  {
    return $this->xmlRepository->cadastro([
      'xml' => file_get_contents($arquivo)
    ]);
  }

  public function validaDados(Request $request): XMLException|bool {
    if (!isset($request->status)) return XMLException::statusNaoInformado();
    if (!isset($request->arquivo)) return XMLException::arquivoNaoInformado();
    if (!in_array(strtoupper($request->status),['AUTORIZADO', 'CANCELADO', 'INUTILIZADO', 'DENAGADO'])) return XMLException::statusInvalido(strtoupper($request->status));
    $this->validaDadosEmpresa($request->cnpj);
    if ($request->arquivo->getClientMimeType() !== 'application/xml') return XMLException::tipoDoArquivoErrado();

    // Validacao de CNPJ API com CNPJ do emitente da nota fiscal
    $path = $request->arquivo->storeAs('public/tempXMLAPI', $request->arquivo->getClientOriginalName());
    $realPath = storage_path('app/' . $path);
    $cnpjXML = simplexml_load_string(file_get_contents($realPath))->NFe[0]->infNFe[0]->emit[0]->CNPJ[0]->__toString();
    unlink($realPath);
    if ($cnpjXML !== $request->cnpj) return XMLException::empresaAPIXMLIncondizente($request->cnpj, $cnpjXML);

    if (!is_null($this->dadosXMLRepository->consultaXMLChaveStatus(str_replace('-', '', filter_var($request->arquivo->getClientOriginalName(), FILTER_SANITIZE_NUMBER_INT)), $request->status))) return XMLException::xmlJaExistente();

    return true;
  }

  public function validaDadosEmpresa(string $cnpj): XMLException|Model|null {
    if (!isset($cnpj)) return XMLException::empresaNaoInformada();
    if (strlen($cnpj) !== 14) return XMLException::empresaComQuantidadeErradaDeCaracteres($cnpj);
    $empresa = $this->empresaRepository->consultaEmpresaPorCNPJ($cnpj);
    if (is_null($empresa)) return XMLException::empresaNaoCadastrada($cnpj);
    return $empresa;
  }
}
