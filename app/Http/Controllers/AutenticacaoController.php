<?php

namespace App\Http\Controllers;

use App\Exceptions\AutenticacaoException;
use App\Repositories\Interface\IUsuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutenticacaoController extends Controller
{
  public function __construct(private readonly IUsuario $usuarioRepository) {}

  public function autenticar(Request $request): JsonResponse {
    $usuario = $this->usuarioRepository->consultaUsuarioPorEmail($request->email);
    if (is_null($usuario)) {
      return response()->json(AutenticacaoException::usuarioNaoIdentificado()->getMessage(), AutenticacaoException::usuarioNaoIdentificado()->getCode());
    }
    if (!Auth::attempt($request->only(['email', 'password']))) {
      return response()->json(AutenticacaoException::emailSenhaIncondizentes($request->email)->getMessage(), AutenticacaoException::emailSenhaIncondizentes($request->email)->getCode());
    }

    return response()->json(['token' => $usuario->createToken('sfsistemas')->plainTextToken]);
  }
}
