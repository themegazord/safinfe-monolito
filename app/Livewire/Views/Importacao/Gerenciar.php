<?php

namespace App\Livewire\Views\Importacao;

use App\Exceptions\Tipos\EnumTiposValidacao;
use App\Jobs\ImportaXMLsJob;
use App\Livewire\Forms\ImportacaoContabilidadeForm;
use App\Livewire\Forms\ImportacaoEmpresaForm;
use App\Livewire\Forms\ImportacaoXMLForm;
use App\Models\User;
use App\Repositories\Eloquent\Repository\ContabilidadeRepository;
use App\Repositories\Eloquent\Repository\EmpresaRepository;
use App\Repositories\Eloquent\Repository\EnderecoRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class Gerenciar extends Component
{
    use Toast, WithFileUploads;

    public array $xmls = [];

    public string $xmlNomeAtual = '';

    public int $cont = 0;

    public ?int $numeroTotalArquivos = null;

    public ?int $qtdeLinhasArquivo = null;

    public bool $startPolling = false;

    public string $importacaoTabSelecionada = 'importacaoXML-tab';

    public User|Authenticatable $usuario;

    public Collection $empresas;

    public ImportacaoXMLForm $importacaoXMLForm;

    public ImportacaoContabilidadeForm $importacaoContabilidadeForm;

    public ImportacaoEmpresaForm $importacaoEmpresaForm;

    public function mount(EmpresaRepository $empresaRepository): void
    {
        $this->empresas = $empresaRepository->listagemEmpresas();
        $this->usuario = Auth::user();
        if ($this->usuario->cannot('viewAny', \App\Models\User::class)) {
            abort('401', 'Você não tem permissão para acessar essa página');
        }
    }

    #[Title('SAFI NFE - Importação de XML')]
    #[Layout('components.layouts.main')]
    public function render()
    {
        return view('livewire.views.importacao.gerenciar');
    }

    /**
     * @throws ValidationException
     */
    public function importacaoXML(): void
    {
        $this->importacaoXMLForm->validate();
        if ($this->importacaoXMLForm->arquivo->getClientOriginalExtension() !== 'zip') {
            $this->warning('Aceitamos somente .zip');

            return;
        }
        $this->recebeRARXMLS();
    }

    /**
     * @throws ValidationException
     */
    public function importacaoContabilidade(ContabilidadeRepository $contabilidadeRepository, EnderecoRepository $enderecoRepository): Redirector|RedirectResponse
    {
        $erros = [];

        $camposCabecalhoEsperados = [
            'Razao Social',
            'CNPJ',
            'Telefone Corporativo',
            'Email Corporativo',
            'Email Contato',
            'Telefone Contato',
            'Telefone Reserva',
            'Rua',
            'Numero',
            'CEP',
            'Bairro',
            'Complemento',
            'Cidade',
            'Estado (UF)',
        ];

        $this->importacaoContabilidadeForm->validate();
        $path = $this->importacaoContabilidadeForm->arquivo->storeAs('importacaoTemp/impcontabilidade'.$this->usuario->getAttribute('id').'.xlsx');

        $planilha = \PhpOffice\PhpSpreadsheet\IOFactory::load(storage_path('app/'.$path))->getActiveSheet();
        $data = $planilha->toArray();
        $cabecalho = $data[0];

        $this->qtdeLinhasArquivo = count($data) - 1;

        // Valida integridade do modelo do Excel

        // Validacao de quantidades de colunas
        if (count($cabecalho) !== 14) {
            $erros[] = ['tipo' => EnumTiposValidacao::ValidacaoDeContagemDeCampos->value, 'mensagem' => 'A quantidade de colunas dos cabecalhos e diferente do que foi esperado.'];
        }
        // Validacao de schema do cabecalho
        if (count(array_diff($camposCabecalhoEsperados, array_map(fn ($campo) => trim(str_replace('*', '', $campo)), $cabecalho)))) {
            $erros[] = ['tipo' => 'Alteracao no schema do XLSX', 'mensagem' => 'O schema do cabecalho foi alterado.'];
        }
        // Validacao dos campos obrigatorios
        for ($i = 1; $i < count($data); $i++) {
            foreach ($data[$i] as $key => $campo) {
                if ($key != 11) { // Complemento não é obrigatório
                    // Verifica se o campo é nulo ou vazio
                    if (is_null($campo) || $campo === '') {
                        // Verifica se o índice existe no cabeçalho
                        if (isset($data[0][$key])) {
                            $erros[] = [
                                'tipo' => 'Campo obrigatório',
                                'mensagem' => "O campo {$data[0][$key]} na linha {$this->retornaLinha($i + 1)} é obrigatório",
                            ];
                        }
                    }
                }
                if ($key === 0 /* Razao social */) {
                    if (strlen($campo) > 255) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "A razao social na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 255 caracteres"];
                    }
                }
                if ($key === 1 /* CNPJ */) {
                    if (! strlen(preg_replace('/[^0-9]/', '', trim($campo))) === 14 || ! strlen(preg_replace('/[^0-9]/', '', trim($campo))) === 11) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O documento limpo da linha {$this->retornaLinha($i + 1)} deve conter 11 (CPF) ou 14 caracteres"];
                    }
                    if (strlen(preg_replace('/[^0-9]/', '', trim($campo))) === 14) {
                        $validatorCnpj = Validator::make(
                            ['cnpj' => $campo],
                            ['cnpj' => 'cnpj']
                        );
                        if ($validatorCnpj->fails()) {
                            $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O CNPJ na linha {$this->retornaLinha($i + 1)} e matematicamente invalido"];
                        }
                    }

                    if (strlen(preg_replace('/[^0-9]/', '', trim($campo))) === 11) {
                        $validatorCpf = Validator::make(
                            ['cpf' => $campo],
                            ['cpf' => 'cpf']
                        );
                        if ($validatorCpf->fails()) {
                            $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O CPF na linha {$this->retornaLinha($i + 1)} e matematicamente invalido"];
                        }
                    }
                    if (! is_null($contabilidadeRepository->consultaContabilidadePorCNPJ(preg_replace('/[^0-9]/', '', trim($campo))))) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O CNPJ na linha {$this->retornaLinha($i + 1)} ja esta em uso"];
                    }
                }
                if ($key === 2 /* Telefone principal */) {
                    if (strlen(preg_replace('/[^0-9]/', '', trim($campo))) > 20) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O telefone na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 20 caracteres"];
                    }
                }
                if ($key === 3 /* Email corporativo */) {
                    if (strlen($campo) > 255) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O email corporativo na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 255 caracteres"];
                    }
                    if (! is_null($contabilidadeRepository->consultaContabilidadePorEmailCorporativo(trim($campo)))) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O email corporativo na linha {$this->retornaLinha($i + 1)} ja esta em uso."];
                    }
                }
                if ($key === 4 /* Email contato */) {
                    if (strlen($campo) > 255) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O email de contato na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 255 caracteres"];
                    }
                }
                if ($key === 5 /* Telefone contato */) {
                    if (strlen(preg_replace('/[^0-9]/', '', trim($campo))) > 20) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O telefone de contato na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 20 caracteres"];
                    }
                }
                if ($key === 6 /* Telefone reserva */) {
                    if (strlen(preg_replace('/[^0-9]/', '', trim($campo))) > 20) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O telefone de reserva na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 20 caracteres"];
                    }
                }
                if ($key === 7 /* Rua */) {
                    if (strlen(trim($campo)) > 155) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "A rua na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 155 caracteres"];
                    }
                }
                if ($key === 8 /* Numero */) {
                    if (strlen(trim($campo)) > 20) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O numero na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 20 caracteres"];
                    }
                }
                if ($key === 9 /* CEP */) {
                    if (strlen(preg_replace('/[^0-9]/', '', trim($campo))) !== 8) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O CEP na linha {$this->retornaLinha($i + 1)} tem que ter 8 caracteres"];
                    }
                }
                if ($key === 10 /* Bairro */) {
                    if (strlen(preg_replace('/[^0-9]/', '', trim($campo))) > 155) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O Bairro na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 155 caracteres"];
                    }
                }
                if ($key === 11 /* complemento */) {
                    if (strlen(preg_replace('/[^0-9]/', '', trim($campo))) > 255) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O complemento na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 255 caracteres"];
                    }
                }
                if ($key === 12 /* cidade */) {
                    if (strlen(preg_replace('/[^0-9]/', '', trim($campo))) > 155) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "A cidade na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 155 caracteres"];
                    }
                }
                if ($key === 13 /* UF */) {
                    if (strlen(trim($campo)) !== 2) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "A UF na linha {$this->retornaLinha($i + 1)} deve conter 2 caracteres"];
                    }
                }
            }
        }

        if (count($erros) > 0) {
            $hash = base64_encode(json_encode($erros));
            cache()->put('erros', $hash);

            return redirect('/importacao/listagemerros');
        }

        DB::beginTransaction();

        for ($i = 1; $i < count($data); $i++) {
            try {
                $enderecoCadastrado = $enderecoRepository->cadastraEndereco([
                    'rua' => trim($data[$i][7]),
                    'numero' => trim($data[$i][8]),
                    'cep' => trim(str_replace(['.', '-'], '', $data[$i][9])),
                    'bairro' => trim($data[$i][10]),
                    'complemento' => trim($data[$i][11]),
                    'cidade' => trim($data[$i][12]),
                    'estado' => strtoupper(trim($data[$i][13])),
                ]);

                $contabilidadeRepository->cadastroContabilidade([
                    'endereco_id' => $enderecoCadastrado->getAttribute('endereco_id'),
                    'social' => trim($data[$i][0]),
                    'cnpj' => preg_replace('/[^0-9]/', '', trim($data[$i][1])),
                    'telefone_corporativo' => preg_replace('/[^0-9]/', '', trim($data[$i][2])),
                    'email_corporativo' => trim($data[$i][3]),
                    'email_contato' => trim($data[$i][4]),
                    'telefone_contato' => preg_replace('/[^0-9]/', '', trim($data[$i][5])),
                    'telefone_reserva' => preg_replace('/[^0-9]/', '', trim($data[$i][6])),
                ]);

                DB::commit();
            } catch (\Exception $e) {
                $erros[] = ['tipo' => 'Insercao no banco de dados', 'mensagem' => $e->getMessage()];
                DB::rollBack();
            }
        }

        if (count($erros) > 0) {
            $hash = base64_encode(json_encode($erros));
            cache()->put('erros', $hash);

            return redirect('/importacao/listagemerros');
        }

        Session::flash('sucesso', 'Importação da(s) contabilidade(s) finalizada com sucesso.');

        return redirect('/importacao');
    }

    public function importacaoEmpresa(EmpresaRepository $empresaRepository, EnderecoRepository $enderecoRepository): Redirector|RedirectResponse
    {
        $erros = [];

        $camposCabecalhoEsperados = [
            'Razao Social',
            'Fantasia',
            'CNPJ',
            'IE',
            'Email Contato',
            'Telefone Contato',
            'Telefone Reserva',
            'Rua',
            'Numero',
            'CEP',
            'Bairro',
            'Complemento',
            'Cidade',
            'Estado (UF)',
        ];

        $this->importacaoEmpresaForm->validate();
        $path = $this->importacaoEmpresaForm->arquivo->storeAs('importacaoTemp/impempresa'.$this->usuario->getAttribute('id').'.xlsx');

        $planilha = \PhpOffice\PhpSpreadsheet\IOFactory::load(storage_path('app/'.$path))->getActiveSheet();
        $data = $planilha->toArray();
        $cabecalho = $data[0];

        $this->qtdeLinhasArquivo = count($data) - 1;

        // Valida integridade do modelo do Excel

        // Validacao de quantidades de colunas
        if (count($cabecalho) !== 14) {
            $erros[] = ['tipo' => EnumTiposValidacao::ValidacaoDeContagemDeCampos->value, 'mensagem' => 'A quantidade de colunas dos cabecalhos e diferente do que foi esperado.'];
        }
        // Validacao de schema do cabecalho
        if (count(array_diff($camposCabecalhoEsperados, array_map(fn ($campo) => trim(str_replace('*', '', $campo)), $cabecalho)))) {
            $erros[] = ['tipo' => 'Alteracao no schema do XLSX', 'mensagem' => 'O schema do cabecalho foi alterado.'];
        }
        // Validacao dos campos obrigatorios
        for ($i = 1; $i < count($data); $i++) {
            foreach ($data[$i] as $key => $campo) {
                if ($key != 3 && $key != 11) { // IE não é obrigatório
                    // Verifica se o campo é nulo ou vazio
                    if (is_null($campo) || $campo === '') {
                        // Verifica se o índice existe no cabeçalho
                        if (isset($data[0][$key])) {
                            $erros[] = [
                                'tipo' => 'Campo obrigatório',
                                'mensagem' => "O campo {$data[0][$key]} na linha {$this->retornaLinha($i + 1)} é obrigatório",
                            ];
                        }
                    }
                }
                if ($key === 0 /* Razao social */) {
                    if (strlen($campo) > 255) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "A razao social na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 255 caracteres"];
                    }
                }
                if ($key === 1 /* Fantasia */) {
                    if (strlen($campo) > 255) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "A razao social na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 255 caracteres"];
                    }
                }
                if ($key === 2 /* CNPJ */) {
                    if (strlen(preg_replace('/[^0-9]/', '', trim($campo))) !== 14) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O CNPJ limpo da linha {$this->retornaLinha($i + 1)} deve conter 14 caracteres"];
                    }
                    $validatorCnpj = Validator::make(
                        ['cnpj' => $campo],
                        ['cnpj' => 'cnpj']
                    );
                    if ($validatorCnpj->fails()) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O CNPJ na linha {$this->retornaLinha($i + 1)} e matematicamente invalido"];
                    }
                    if (! is_null($empresaRepository->consultaEmpresaPorCNPJ(preg_replace('/[^0-9]/', '', trim($campo))))) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O CNPJ na linha {$this->retornaLinha($i + 1)} ja esta em uso"];
                    }
                }
                if ($key === 3 /* ie */) {
                    if (strlen(preg_replace('/[^0-9]/', '', trim($campo))) > 20) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "A IE na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 20 caracteres"];
                    }
                }
                if ($key === 4 /* Email contato */) {
                    if (strlen($campo) > 255) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O email de contato na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 255 caracteres"];
                    }
                }
                if ($key === 5 /* Telefone contato */) {
                    if (strlen(preg_replace('/[^0-9]/', '', trim($campo))) > 20) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O telefone de contato na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 20 caracteres"];
                    }
                }
                if ($key === 6 /* Telefone reserva */) {
                    if (strlen(preg_replace('/[^0-9]/', '', trim($campo))) > 20) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O telefone de reserva na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 20 caracteres"];
                    }
                }
                if ($key === 7 /* Rua */) {
                    if (strlen(trim($campo)) > 155) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "A rua na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 155 caracteres"];
                    }
                }
                if ($key === 8 /* Numero */) {
                    if (strlen(trim($campo)) > 20) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O numero na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 20 caracteres"];
                    }
                }
                if ($key === 9 /* CEP */) {
                    if (strlen(preg_replace('/[^0-9]/', '', trim($campo))) !== 8) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O CEP na linha {$this->retornaLinha($i + 1)} tem que ter 8 caracteres"];
                    }
                }
                if ($key === 10 /* Bairro */) {
                    if (strlen(preg_replace('/[^0-9]/', '', trim($campo))) > 155) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O Bairro na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 155 caracteres"];
                    }
                }
                if ($key === 11 /* complemento */) {
                    if (strlen(preg_replace('/[^0-9]/', '', trim($campo))) > 255) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "O complemento na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 255 caracteres"];
                    }
                }
                if ($key === 12 /* cidade */) {
                    if (strlen(preg_replace('/[^0-9]/', '', trim($campo))) > 155) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "A cidade na linha {$this->retornaLinha($i + 1)} tem que ter no maximo 155 caracteres"];
                    }
                }
                if ($key === 13 /* UF */) {
                    if (strlen(trim($campo)) !== 2) {
                        $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo->value, 'mensagem' => "A UF na linha {$this->retornaLinha($i + 1)} deve conter 2 caracteres"];
                    }
                }
            }
        }

        if (count($erros) > 0) {
            $hash = base64_encode(json_encode($erros));
            cache()->put('erros', $hash);

            return redirect('/importacao/listagemerros');
        }

        DB::beginTransaction();

        for ($i = 1; $i < count($data); $i++) {
            try {
                $enderecoCadastrado = $enderecoRepository->cadastraEndereco([
                    'rua' => trim($data[$i][7]),
                    'numero' => trim($data[$i][8]),
                    'cep' => trim(str_replace(['.', '-'], '', $data[$i][9])),
                    'bairro' => trim($data[$i][10]),
                    'complemento' => trim($data[$i][11]),
                    'cidade' => trim($data[$i][12]),
                    'estado' => strtoupper(trim($data[$i][13])),
                ]);

                $empresaRepository->cadastroEmpresa([
                    'endereco_id' => $enderecoCadastrado->getAttribute('endereco_id'),
                    'social' => trim($data[$i][0]),
                    'fantasia' => trim($data[$i][1]),
                    'cnpj' => preg_replace('/[^0-9]/', '', trim($data[$i][2])),
                    'ie' => preg_replace('/[^0-9]/', '', trim($data[$i][3])),
                    'email_contato' => trim($data[$i][4]),
                    'telefone_contato' => preg_replace('/[^0-9]/', '', trim($data[$i][5])),
                    'telefone_reserva' => preg_replace('/[^0-9]/', '', trim($data[$i][6])),
                ]);

                DB::commit();
            } catch (\Exception $e) {
                $erros[] = ['tipo' => 'Insercao no banco de dados', 'mensagem' => $e->getMessage()];
                DB::rollBack();
            }
        }

        if (count($erros) > 0) {
            $hash = base64_encode(json_encode($erros));
            cache()->put('erros', $hash);

            return redirect('/importacao/listagemerros');
        }

        Session::flash('sucesso', 'Importação da(s) empresa(s) finalizada com sucesso.');

        return redirect('/importacao');
    }

    public function downloadArquivoMolde(string $importacao)
    {
        return match ($importacao) {
            'contabilidade' => Storage::download('moldes/molde_importacao_contabilidades.xlsx'),
            'empresa' => Storage::download('moldes/molde_importacao_empresas.xlsx')
        };
    }

    private function recebeRARXMLS(): void
    {
        $path = $this->importacaoXMLForm->arquivo->storeAs('public', $this->importacaoXMLForm->arquivo->getClientOriginalName());
        $realPath = storage_path('app/'.$path);

        dispatch(new ImportaXMLsJob($realPath, $this->importacaoXMLForm->cnpj));

        $this->success('Arquivo enviado');
    }

    private function retornaLinha(int $numeroIndex): int
    {
        return $numeroIndex++;
    }
}
