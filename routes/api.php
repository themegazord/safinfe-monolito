<?php

use App\Exceptions\AutenticacaoException;
use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\XMLController;
use App\Repositories\Eloquent\Repository\UsuarioRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::post('autenticacao', [AutenticacaoController::class, 'autenticar']);
Route::middleware('auth:sanctum')->group(function () {
  Route::post('/enviaxml', [XMLController::class, 'store']);
  Route::get('/ultimoxml', [XMLController::class, 'ultimoXML']);
});
