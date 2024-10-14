<?php

namespace App\Providers;

use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\XMLController;
use App\Repositories\Eloquent\Repository\DadosXMLRepository;
use App\Repositories\Eloquent\Repository\EmpresaRepository;
use App\Repositories\Eloquent\Repository\UsuarioRepository;
use App\Repositories\Eloquent\Repository\XMLRepository;
use App\Repositories\Interface\IDadosXML;
use App\Repositories\Interface\IEmpresa;
use App\Repositories\Interface\IUsuario;
use App\Repositories\Interface\IXML;
use App\Services\DadosXMLService;
use App\Services\XMLService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    $this->app->scoped(DadosXMLService::class, function (Application $app) {
      $dadosXMLRepository = $app->make(IDadosXML::class);
      $empresaRepository = $app->make(IEmpresa::class);
      return new DadosXMLService($dadosXMLRepository, $empresaRepository);
    });

    $this->app->scoped(XMLService::class, function (Application $app) {
      $xmlRepository = $app->make(IXML::class);
      $empresaRepository = $app->make(IEmpresa::class);
      $dadosXMLRepository = $app->make(IDadosXML::class);
      return new XMLService($xmlRepository, $empresaRepository, $dadosXMLRepository);
    });

    $this->app->scoped(AutenticacaoController::class, function (Application $app) {
      $usuarioRepository = $app->make(IUsuario::class);
      return new AutenticacaoController($usuarioRepository);
    });

    $this->app->scoped(XMLController::class, function (Application $app) {
      $xmlService = $app->make(XMLService::class);
      $dadosXMLService = $app->make(DadosXMLService::class);
      $dadosXMLRepository = $app->make(IDadosXML::class);
      return new XMLController($xmlService, $dadosXMLService, $dadosXMLRepository);
    });
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    $this->app->bind(IUsuario::class, UsuarioRepository::class);
    $this->app->bind(IDadosXML::class, DadosXMLRepository::class);
    $this->app->bind(IEmpresa::class, EmpresaRepository::class);
    $this->app->bind(IXML::class, XMLRepository::class);
  }
}
