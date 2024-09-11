<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Livewire::setUpdateRoute(function ($handle) {
  return Route::post('/livewire/update', $handle);
});

Route::get('/', \App\Livewire\Views\Autenticacao\Login::class)->name('login');
Route::get('/contato', \App\Livewire\Views\Autenticacao\Contato::class);
Route::middleware('auth')->group(function () {
  Route::get('dashboard', \App\Livewire\Views\Dashboard\Dashboard::class);
  Route::prefix('empresas')->group(function () {
    Route::get('/', \App\Livewire\Views\Empresas\Listagem::class);
    Route::get('cadastro', \App\Livewire\Views\Empresas\Cadastro::class);
    Route::get('edicao/{empresa_id}', \App\Livewire\Views\Empresas\Edicao::class);
  });
  Route::prefix('contabilidades')->group(function () {
    Route::get('/', \App\Livewire\Views\Contabilidades\Listagem::class);
    Route::get('cadastro', \App\Livewire\Views\Contabilidades\Cadastro::class);
    Route::get('edicao/{contabilidade_id}', \App\Livewire\Views\Contabilidades\Edicao::class);
  });
  Route::prefix('clientes')->group(function () {
    Route::get('/', \App\Livewire\Views\Clientes\Listagem::class);
    Route::get('cadastro', \App\Livewire\Views\Clientes\Cadastro::class);
    Route::get('edicao/{cliente_id}', \App\Livewire\Views\Clientes\Edicao::class);
  });
  Route::prefix('contadores')->group(function () {
    Route::get('/', \App\Livewire\Views\Contadores\Listagem::class);
    Route::get('cadastro', \App\Livewire\Views\Contadores\Cadastro::class);
    Route::get('edicao/{contador_id}', \App\Livewire\Views\Contadores\Edicao::class);
  });
  Route::prefix('');
});
