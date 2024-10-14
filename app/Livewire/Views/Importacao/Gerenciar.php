<?php

namespace App\Livewire\Views\Importacao;

use App\Exceptions\Tipos\EnumTiposValidacao;
use App\Livewire\Forms\ImportacaoContabilidadeForm;
use App\Livewire\Forms\ImportacaoXMLForm;
use App\Models\User;
use App\Repositories\Eloquent\Repository\ContabilidadeRepository;
use App\Repositories\Eloquent\Repository\EmpresaRepository;
use App\Repositories\Eloquent\Repository\EnderecoRepository;
use App\Services\DadosXMLService;
use App\Services\XMLService;
use App\Traits\ValidacoesTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use ZipArchive;

class Gerenciar extends Component
{
  use WithFileUploads, ValidacoesTrait;

  public array $xmls = [];
  public string $xmlNomeAtual = '';
  public User|Authenticatable $usuario;
  public Collection $empresas;
  public ImportacaoXMLForm $importacaoXMLForm;
  public ImportacaoContabilidadeForm $importacaoContabilidadeForm;

  public function mount(EmpresaRepository $empresaRepository): void
  {
    $this->empresas = $empresaRepository->listagemEmpresas();
    $this->usuario = Auth::user();
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
  public function importacaoXML(XMLService $xmlService, DadosXMLService $dadosXMLService): Redirector|RedirectResponse
  {
    $this->importacaoXMLForm->validate();
      if ($this->importacaoXMLForm->arquivo->getClientOriginalExtension() !== 'zip') {
        Session::flash('erro', 'Aceitamos apenas .zip');
        return redirect('/importacaoxml');
      }
      return $this->recebeRARXMLS($xmlService, $dadosXMLService);
  }

  /**
   * @throws ValidationException
   */
  public function importacaoContabilidade(ContabilidadeRepository $contabilidadeRepository, EnderecoRepository $enderecoRepository): void {
    $erros = array();

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
    $path = $this->importacaoContabilidadeForm->arquivo->storeAs('importacaoTemp/impcontabilidade' . $this->usuario->getAttribute('id') . '.xlsx');

    $planilha = \PhpOffice\PhpSpreadsheet\IOFactory::load(storage_path('app/' . $path))->getActiveSheet();
    $cabecalho = $planilha->toArray()[0];
    // Valida integridade do modelo do Excel

    // Validacao de quantidades de colunas
    if (count($cabecalho) !== 14) $erros[] = ['tipo' => 'Validacao de contagem de campos', 'mensagem' => 'A quantidade de colunas dos cabecalhos e diferente do que foi esperado.'];
    // Validacao de schema do cabecalho
    if (count(array_diff($camposCabecalhoEsperados, array_map(fn ($campo) => trim(str_replace('*', '', $campo)), $cabecalho)))) $erros[] = ['tipo' => 'Alteracao no schema do XLSX', 'mensagem' => 'O schema do cabecalho foi alterado.'];
    // Validacao dos campos obrigatorios
    $data = $planilha->toArray();
    for ($i = 1; $i < count($data); $i++) {
      foreach ($data[$i] as $key => $campo) {
        if ($key != 11) { // Complemento não é obrigatório
          // Verifica se o campo é nulo ou vazio
          if (is_null($campo) || $campo === '') {
            // Verifica se o índice existe no cabeçalho
            if (isset($data[0][$key])) {
              $erros[] = [
                'tipo' => 'Campo obrigatório',
                'mensagem' => "O campo {$data[0][$key]} na linha {$this->retornaLinha($i + 1)} é obrigatório"
              ];
            }
          }
        }
        if ($key === 0 /* Razao social */) {
          if (strlen($campo) > 255) $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo, 'mensagem' => "A razao social na linha {$this->retornaLinha($i + 1)} tem que ter 255 caracteres"];
        }
        if ($key === 1 /* CNPJ */) {
          if (strlen(preg_replace("/[^0-9]/", "", trim($campo))) !== 14) $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo, 'mensagem' => "O CNPJ limpo da linha {$this->retornaLinha($i + 1)} deve conter 14 caracteres"];
          if (!$this->validar_cnpj($campo)) $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo, 'mensagem' => "O CNPJ na linha {$this->retornaLinha($i + 1)} e matematicamente invalido"];
          if (is_null($contabilidadeRepository->consultaContabilidadePorCNPJ(preg_replace("/[^0-9]/", "", trim($campo))))) $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo, 'mensagem' => "O CNPJ na linha {$this->retornaLinha($i + 1)} ja esta em uso"];
        }
        if ($key === 2 /* Telefone principal */) {
          if (strlen(preg_replace("/[^0-9]/", "", trim($campo))) > 20) $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo, 'mensagem' => "O telefone na linha {$this->retornaLinha($i + 1)} tem que ter 20 caracteres"];
        }
        if ($key === 3 /* Email corporativo */) {
          if (strlen($campo) > 255) $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo, 'mensagem' => "O email corporativo na linha {$this->retornaLinha($i + 1)} tem que ter 255 caracteres"];
          if (is_null($contabilidadeRepository->consultaContabilidadePorEmailCorporativo(trim($campo)))) $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo, 'mensagem' => "O email corporativo na linha {$this->retornaLinha($i + 1)} ja esta em uso."];
        }
        if ($key === 4 /* Email contato */) {
          if (strlen($campo) > 255) $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo, 'mensagem' => "O email de contato na linha {$this->retornaLinha($i + 1)} tem que ter 255 caracteres"];
        }
        if ($key === 5 /* Telefone contato */) {
          if (strlen(preg_replace("/[^0-9]/", "", trim($campo))) > 20) $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo, 'mensagem' => "O telefone de contato na linha {$this->retornaLinha($i + 1)} tem que ter 20 caracteres"];
        }
        if ($key === 6 /* Telefone reserva */) {
          if (strlen(preg_replace("/[^0-9]/", "", trim($campo))) > 20) $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo, 'mensagem' => "O telefone de reserva na linha {$this->retornaLinha($i + 1)} tem que ter 20 caracteres"];
        }
        if ($key === 7 /* Rua */ ) {
          if (strlen($campo) > 155) $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo, 'mensagem' => "A rua na linha {$this->retornaLinha($i + 1)} tem que ter 155 caracteres"];
        }
        if ($key === 13 /* UF */) {
          if (strlen($campo) !== 2) $erros[] = ['tipo' => EnumTiposValidacao::IntegridadeDoCampo, 'mensagem' => "A UF na linha {$this->retornaLinha($i + 1)} deve conter no maximo 2 caracteres"];
        }
      }
    }

    dd($erros);

    dd($planilha->toArray()[1]);

    DB::beginTransaction();

    try {

      $enderecoRepository->cadastraEndereco([
        'rua' => trim($planilha->toArray()[1][7]),
        'numero' => trim($planilha->toArray()[1][8]),
        'cep' => trim(str_replace(['.', '-'], '', $planilha->toArray()[1][9])),
        'bairro' => trim($planilha->toArray()[1][10]),
        'complemento' => trim($planilha->toArray()[1][11]),
        'cidade' => trim($planilha->toArray()[1][12]),
        'estado' => strtoupper(trim($planilha->toArray()[1][13])),
      ]);

      DB::commit();
    } catch (\Exception $e) {
      $erros[] = ['tipo' => 'Insercao no banco de dados', 'mensagem' => $e->getMessage()];
    } finally {
    DB::rollBack();
    }

    if (count($erros) > 0) dd($erros);
  }

  public function downloadArquivoMolde(string $importacao) {
    return match($importacao) {
      'contabilidade' => Storage::download('moldes/molde_importacao_contabilidades.xlsx'),
    };
  }

  private function recebeRARXMLS(XMLService $xmlService, DadosXMLService $dadosXMLService): Redirector|RedirectResponse
  {
    DB::beginTransaction();

    try {
      $zip = new ZipArchive();

      $path = $this->importacaoXMLForm->arquivo->storeAs('public', $this->importacaoXMLForm->arquivo->getClientOriginalName());
      $realPath = storage_path('app/' . $path);
      $pathXMLUsuario = storage_path('app/tempXML/' . Auth::user()->id);

      DB::commit();
      if ($zip->open($realPath) === TRUE) {
        $zip->extractTo($pathXMLUsuario);
        $zip->close();
        foreach(array_filter(scandir($pathXMLUsuario), fn ($arq) => $arq !== '.' && $arq !== '..') as $arquivo) {
          $this->xmlNomeAtual = $arquivo;
          $this->defineGravaXML("{$pathXMLUsuario}/{$arquivo}", $xmlService, $dadosXMLService);
        }
      }
      Session::flash('sucesso', "XMLS importados com sucesso.");
      return redirect('/importacaoxml');
    } catch (\Exception $e) {
      DB::rollBack();
      Session::flash('erro', $e->getMessage() . " => XML com erro: " . $this->xmlNomeAtual);
      return redirect('/importacaoxml');
    } finally {
      unlink($realPath);
    }
  }

  private function defineGravaXML(string $caminho, XMLService $xmlService, DadosXMLService $dadosXMLService): void
  {
    $xmlConsultado = $dadosXMLService->consultaDadosXMLPorChave(str_replace('-', '', filter_var($this->xmlNomeAtual, FILTER_SANITIZE_NUMBER_INT)));

    if (str_contains($this->xmlNomeAtual, 'ProcNfe')) {
      if (is_null($xmlConsultado) || $xmlConsultado->getAttribute('status') !== 'AUTORIZADO') {
        $xmlGravado = $xmlService->cadastro($caminho);
        $dadosXMLService->cadastro($xmlGravado->getAttribute('xml'), $xmlGravado->getAttribute('xml_id'), $this->importacaoXMLForm->empresa_id);
      }
    }

    if (str_contains($this->xmlNomeAtual, 'Can')) {
      if (is_null($xmlConsultado) || $xmlConsultado->getAttribute('status') !== 'CANCELADO') {
        $xmlGravado = $xmlService->cadastro($caminho);
        $dadosXMLService->cadastroCancelado($xmlGravado->getAttribute('xml'), $xmlGravado->getAttribute('xml_id'), $this->importacaoXMLForm->empresa_id);
      }
    }
    if (str_contains($this->xmlNomeAtual, 'inu')) {
      if (is_null($xmlConsultado) || $xmlConsultado->getAttribute('status') !== 'INUTILIZADO') {
        $xmlGravado = $xmlService->cadastro($caminho);
        $dadosXMLService->cadastroInutilizado($xmlGravado->getAttribute('xml'), $xmlGravado->getAttribute('xml_id'), $this->importacaoXMLForm->empresa_id, $this->xmlNomeAtual);
      }
    }

    unlink($caminho);
  }

  private function retornaLinha(int $numeroIndex): int {
    return $numeroIndex++;
  }
}
