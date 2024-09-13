<?php

namespace App\Providers;

use App\Livewire\Views\Importacao\Xml;
use App\Repositories\Eloquent\Repository\DadosXMLRepository;
use App\Repositories\Eloquent\Repository\EmpresaRepository;
use App\Repositories\Eloquent\Repository\XMLRepository;
use App\Repositories\Interface\IDadosXML;
use App\Repositories\Interface\IEmpresa;
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
      return new XMLService($xmlRepository);
    });

    $this->app->scoped(Xml::class, function (Application $app) {
      $xmlService = $app->make(XMLService::class);
      $dadosXMLService = $app->make(DadosXMLService::class);
      return new Xml($xmlService, $dadosXMLService);
    });
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    $this->app->bind(IDadosXML::class, DadosXMLRepository::class);
    $this->app->bind(IEmpresa::class, EmpresaRepository::class);
    $this->app->bind(IXML::class, XMLRepository::class);
  }
}
