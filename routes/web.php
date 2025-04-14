<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Livewire::setUpdateRoute(function ($handle) {
  return Route::post('/livewire/update', $handle);
});

Route::get('/', \App\Livewire\Views\Autenticacao\Login::class)->name('login');
Route::get('/resetar-senha', \App\Livewire\Views\Autenticacao\ResetarSenha::class)->name('resetarSenha');
Route::get('/resetsenha/reset/{token}/{email}', \App\Livewire\Views\Autenticacao\ResetSenha::class)->name('resetSenha');
Route::get("/logout", function () {
  Auth::logout();
})->name('logout');
Route::get('/contato', \App\Livewire\Views\Autenticacao\Contato::class);
Route::middleware('auth')->group(function () {
  Route::get('dashboard', \App\Livewire\Views\Dashboard\Dashboard::class)->name('dashboard');
  Route::middleware('admin')->group(function () {
    Route::prefix('empresas')->group(function () {
      Route::get('/', \App\Livewire\Views\Empresas\Listagem::class)->name('empresas');
      Route::get('cadastro', \App\Livewire\Views\Empresas\Cadastro::class);
      Route::get('edicao/{empresa_id}', \App\Livewire\Views\Empresas\Edicao::class);
    });
    Route::prefix('contabilidades')->group(function () {
      Route::get('/', \App\Livewire\Views\Contabilidades\Listagem::class)->name('contabilidades');
      Route::get('cadastro', \App\Livewire\Views\Contabilidades\Cadastro::class);
      Route::get('edicao/{contabilidade_id}', \App\Livewire\Views\Contabilidades\Edicao::class);
    });
    Route::prefix('clientes')->group(function () {
      Route::get('/', \App\Livewire\Views\Clientes\Listagem::class)->name('clientes');
      Route::get('cadastro', \App\Livewire\Views\Clientes\Cadastro::class);
      Route::get('edicao/{cliente_id}', \App\Livewire\Views\Clientes\Edicao::class);
    });
    Route::prefix('contadores')->group(function () {
      Route::get('/', \App\Livewire\Views\Contadores\Listagem::class)->name('contadores');
      Route::get('cadastro', \App\Livewire\Views\Contadores\Cadastro::class);
      Route::get('edicao/{contador_id}', \App\Livewire\Views\Contadores\Edicao::class);
    });
    Route::prefix('administradores')->group(function () {
      Route::get('/', \App\Livewire\Views\Administradores\Listagem::class)->name('administradores');
      Route::get('cadastro', \App\Livewire\Views\Administradores\Cadastro::class);
      Route::get('edicao/{administrador_id}', \App\Livewire\Views\Administradores\Edicao::class);
    });
    Route::prefix('importacao')->group(function () {
      Route::get('/', \App\Livewire\Views\Importacao\Gerenciar::class)->name('importacao');
      Route::get('/listagemerros', \App\Livewire\Views\Importacao\ListagemErros::class);
    });
  });
  Route::prefix('versionamento')->group(function () {
    Route::get('/', \App\Livewire\Views\Versionamento\Listagem::class)->name('versionamento');
    Route::get('/cadastro', \App\Livewire\Views\Versionamento\Cadastro::class);
  });
  Route::prefix('consultaxml')->group(function () {
    Route::get('/', \App\Livewire\Views\Consultaxml\Consulta::class)->name('consultaxml');
    Route::get('/{hash}', \App\Livewire\Views\Consultaxml\Listagem::class);
  });
});
