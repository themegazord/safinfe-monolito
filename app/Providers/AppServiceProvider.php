<?php

namespace App\Providers;

use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\XMLController;
use App\Models\Cliente;
use App\Models\Contabilidade;
use App\Models\Contador;
use App\Models\DadosXML;
use App\Models\EmpCont;
use App\Models\Empresa;
use App\Models\XML;
use App\Policies\ClientePolicy;
use App\Policies\ContabilidadePolicy;
use App\Policies\ContadorPolicy;
use App\Policies\DadosXMLPolicy;
use App\Policies\EmpContPolicy;
use App\Policies\EmpresaPolicy;
use App\Policies\XMLPolicy;
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
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

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

        //Policies

        Gate::policy(Cliente::class, ClientePolicy::class);
        Gate::policy(Contabilidade::class, ContabilidadePolicy::class);
        Gate::policy(Contador::class, ContadorPolicy::class);
        Gate::policy(DadosXML::class, DadosXMLPolicy::class);
        Gate::policy(EmpCont::class, EmpContPolicy::class);
        Gate::policy(Empresa::class, EmpresaPolicy::class);
        Gate::policy(XML::class, XMLPolicy::class);
    }
}
