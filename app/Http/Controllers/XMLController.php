<?php

namespace App\Http\Controllers;

use App\Exceptions\XMLException;
use App\Repositories\Interface\IDadosXML;
use App\Services\DadosXMLService;
use App\Services\XMLService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XMLController extends Controller
{
  public function __construct(
    private readonly XMLService $xmlService,
    private readonly DadosXMLService $dadosXMLService,
    private readonly IDadosXML $dadosXMLRepository
  ) {}

  public function store(Request $request): JsonResponse
  {
    try {
      $this->xmlService->validaDados($request);
      $path = $request->arquivo->storeAs('public/tempXMLAPI', $request->arquivo->getClientOriginalName());
      $realPath = storage_path('app/' . $path);
      $arquivo = file_get_contents($realPath);
      $xml = $this->xmlService->cadastro($realPath);

      match ($request->status) {
        'AUTORIZADO' => $this->dadosXMLService->cadastro($arquivo, $xml->getAttribute('xml_id'), $request->cnpj),
        'CANCELADO' => $this->dadosXMLService->cadastroCancelado($arquivo, $xml->getAttribute('xml_id'), $request->cnpj),
        'INUTILIZADO' => $this->dadosXMLService->cadastroInutilizado($arquivo, $xml->getAttribute('xml_id'), $request->cnpj, $request->arquivo->getClientOriginalName()),
      };
      unlink($realPath);

      return response()->json('XML cadastrado com sucesso');

    } catch (XMLException $xmle) {
      return response()->json($xmle->getMessage(), $xmle->getCode());
      unlink($realPath);
    }
  }

  public function ultimoXML(Request $request): JsonResponse {
    try {
      $empresa = $this->xmlService->validaDadosEmpresa($request->cnpj);
      $nota = $this->dadosXMLRepository->consultaUltimaNotaRecebidaEmpresa($empresa->getAttribute('empresa_id'));
      if (is_null($nota)) return response()->json(['mensagem' => 'Empresa não possui nota fiscal cadastrada'], Response::HTTP_NOT_FOUND);
      return response()->json(['nota' => $nota->toArray()]);
    } catch (XMLException $xmle) {
      return response()->json($xmle->getMessage(), $xmle->getCode());
      unlink($realPath);
    }
  }
}