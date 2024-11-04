<?php

use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\XMLController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('autenticacao', [AutenticacaoController::class, 'autenticar']);
Route::middleware('auth:sanctum')->group(function () {
  Route::post('/enviaxml', [XMLController::class, 'store']);
  Route::post('/enviaxmltexto', [XMLController::class, 'storeTexto']);
  Route::get('/ultimoxml', [XMLController::class, 'ultimoXML']);
  Route::get('/ping', function (Request $request) {
    return response()->json('pong');
  });
});
