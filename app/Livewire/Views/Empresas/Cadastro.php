<?php

namespace App\Livewire\Views\Empresas;

use App\Livewire\Forms\EmpresaForm;
use App\Livewire\Forms\EnderecoForm;
use App\Models\User;
use App\Repositories\Eloquent\Repository\EmpresaRepository;
use App\Repositories\Eloquent\Repository\EnderecoRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

class Cadastro extends Component
{
    use Toast;

    public EnderecoForm $endereco;

    public EmpresaForm $empresa;

    public User|Authenticatable $usuario;

    public function mount(): void {
        $this->usuario = Auth::user();
        if ($this->usuario->cannot('create', \App\Models\Empresa::class)) {
            abort('401', 'Você não tem permissão para acessar essa página');
        }
    }

    #[Layout('components.layouts.main')]
    #[Title('SAFI NFE - Cadastro de Empresas')]
    public function render()
    {
        return view('livewire.views.empresas.cadastro');
    }

    public function voltar(): void
    {
        redirect('/empresas');
    }

    public function cadastrar(EmpresaRepository $empresaRepository, EnderecoRepository $enderecoRepository)
    {
        $this->endereco->tratarCamposSujos();
        $this->empresa->tratarCamposSujos();
        $this->endereco->validate();
        $this->empresa->validate();
        if (! is_null(DB::table('empresas')->where('cnpj', $this->empresa->cnpj)->first())) {
            return $this->addError('empresa.cnpj', 'CNPJ já existente.');
        }
        if (! is_null(DB::table('empresas')->where('ie', $this->empresa->ie)->first())) {
            return $this->addError('empresa.ie', 'IE já existente.');
        }
        $this->validate(
            ['empresa.ie' => 'inscricao_estadual:'.$this->endereco->estado],
            ['empresa.ie.inscricao_estadual' => 'A Inscrição Estadual é inválida para o estado '.$this->endereco->estado.'.']
        );

        $endereco_cadastrado = $enderecoRepository->cadastraEndereco($this->endereco->all());

        $this->empresa->endereco_id = $endereco_cadastrado->getAttribute('endereco_id');

        $empresaRepository->cadastroEmpresa($this->empresa->all());

        $this->success('Empresa cadastrada com sucesso.', redirectTo: route('empresas'));
    }
}
