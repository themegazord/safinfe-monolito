<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\warning;

class VerificarValidacaoCnpjEmBD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:verificar-validacao-cnpj';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Valida CNPJ em varias entidades do banco de dados e logga os inválidos.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::transaction(function () {
            $empresas = DB::table('empresas')->lockForUpdate()->get();
            $contabilidades = DB::table('contabilidades')->lockForUpdate()->get();
            $contadores = DB::table('contadores')->lockForUpdate()->get();

            foreach ($empresas as $empresa) {
                $validatorEmpresaCNPJ = Validator::make(
                    ['cnpj' => $empresa->cnpj],
                    ['cnpj' => 'cnpj']
                );

                if ($validatorEmpresaCNPJ->fails()) {
                    Log::channel('cnpj_validation')->warning("O CNPJ da empresa {$empresa->fantasia} está matematicamente inválido => CNPJ: [{$empresa->cnpj}]");
                }
            }

            foreach ($contabilidades as $contabilidade) {
                $validatorContabilidadeCPFCNPJ = Validator::make(
                    ['documento' => $contabilidade->cnpj],
                    ['documento' => 'cpf_cnpj']
                );

                if ($validatorContabilidadeCPFCNPJ->fails()) {
                    Log::channel('cnpj_validation')->warning("O CPF ou CNPJ da contabilidade {$contabilidade->social} está matematicamente inválido => Documento: [{$contabilidade->cnpj}]");
                }
            }

            foreach ($contadores as $contador) {
                $validatorContadorCPF = Validator::make(
                    ['cpf' => $contador->cpf],
                    ['cpf' => 'cpf']
                );

                if ($validatorContadorCPF->fails()) {
                    Log::channel('cnpj_validation')->warning("O CPF do contador {$contador->nome} está matematicamente inválido => CPF: [{$contador->cpf}]");
                }
            }

        });
    }
}
