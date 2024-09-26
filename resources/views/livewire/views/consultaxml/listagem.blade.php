<?php
$fmt = numfmt_create('pt_BR', NumberFormatter::CURRENCY);
?>

<app>
  <div class="main">
    <h1>Listagem dos XML</h1>
    <livewire:componentes.utils.notificacao.flash />
    <table class="table table-sm table-striped table-hover tabela-xml">
      <div class="perpage">
        <button wire:click="voltar">Voltar</button>
        <input type="number" name="perpage" id="perpage" wire:model.blur="perPage">
      </div>
      <thead>
        <tr>
          <th>ID</th>
          <th>Modelo</th>
          <th>Serie</th>
          <th>Numero NF</th>
          <th>Numero NF Final</th>
          <th>Status</th>
          <th>Data Evento</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($listagem['dados_xml'] as $dado)
        <tr wire:key="{{ $dado->dados_id }}">
          <th>{{ $dado->dados_id }}</th>
          <td>{{ $dado->modelo }}</td>
          <td>{{ $dado->serie }}</td>
          <td>{{ $dado->numeronf }}</td>
          <td>{{ $dado->numeronf_final }}</td>
          <td>{{ $dado->status }}</td>
          <td>{{ date('d/m/Y', strtotime($dado->dh_emissao_evento)) }}</td>
          <td>
            @if ($dado->status === 'INUTILIZADO')
            <i class="fa-solid fa-info text-info pe-1" wire:click="selecionaXMLInutilizadoAtual({{ $dado->dados_id }})" data-bs-toggle="modal" data-bs-target="#modalDadosInutilizacao"></i>
            @else
            <i class="fa-solid fa-info text-info pe-1" wire:click="selecionaXMLAtual({{ $dado->dados_id }})" data-bs-toggle="modal" data-bs-target="#informacaoNFeModal"></i>
            @endif
            <i class="fa-solid fa-download text-success" wire:click="downloadXML({{ $dado->dados_id }})"></i>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="paginacao">
      {{ $listagem['dados_xml']->links() }}
    </div>
  </div>
  <!-- Inicio modal de visualização de dados da nota fiscal -->

  <div class="modal fade" id="informacaoNFeModal" tabindex="-1" aria-labelledby="informacaoNFeModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content">
        <div class="modal-header">
          @if (!is_null($dadosXMLAtual))
          <h5 class="modal-title" id="informacaoNFeModalLabel">Dados da nota fiscal: {{ $dadosXMLAtual['numero'] }}</h5>
          @endif
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="resetarCampos"></button>
        </div>
        <div class="modal-body bodyModalInformacaoNFe">
          @if (!is_null($dadosXMLAtual))
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="cabecalho-nota-tab" data-bs-toggle="tab" data-bs-target="#cabecalho-nota" type="button" role="tab" aria-controls="cabecalho-nota" aria-selected="true">Cabeçalho da nota</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="produtos-nota-tab" data-bs-toggle="tab" data-bs-target="#produtos-nota" type="button" role="tab" aria-controls="produtos-nota" aria-selected="false">Produtos da nota</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="informacoes-nota-tab" data-bs-toggle="tab" data-bs-target="#informacoes-nota" type="button" role="tab" aria-controls="informacoes-nota" aria-selected="false">informações da nota</button>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="cabecalho-nota" role="tabpanel" aria-labelledby="cabecalho-nota-tab">
              <div class="container-info-dados">
                <div class="valores-nota">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Valor total da nota:</h5>
                      <p class="card-text">{{ numfmt_format_currency($fmt, floatval($dadosXMLAtual['informacaoDeValoresDaNota']['totalNota']), 'BRL') }}</p>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Valor total da ICMS da nota:</h5>
                      <p class="card-text">{{ numfmt_format_currency($fmt, floatval($dadosXMLAtual['informacaoDeValoresDaNota']['totalICMS']), 'BRL') }}</p>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Valor total de ICMS ST da nota:</h5>
                      <p class="card-text">{{ numfmt_format_currency($fmt, floatval($dadosXMLAtual['informacaoDeValoresDaNota']['totalST']), 'BRL') }}</p>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Valor total de PIS da nota:</h5>
                      <p class="card-text">{{ numfmt_format_currency($fmt, floatval($dadosXMLAtual['informacaoDeValoresDaNota']['vPIS']), 'BRL') }}</p>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Valor total de COFINS da nota:</h5>
                      <p class="card-text">{{ numfmt_format_currency($fmt, floatval($dadosXMLAtual['informacaoDeValoresDaNota']['vCOFINS']), 'BRL') }}</p>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Valor total aprox. de impostos federais da nota:</h5>
                      <p class="card-text">{{ numfmt_format_currency($fmt, floatval($dadosXMLAtual['informacaoDeValoresDaNota']['valorApxImpostosFederais']), 'BRL') }}</p>
                    </div>
                  </div>
                </div>
                <div class="emit-dest">
                  <div class="emit">
                    <div class="card">
                      <div class="card-body">
                        <h5 class="card-title">Informações do emissor da nota:</h5>
                        <div class="info">
                          <label for="nome"><b>Nome do Emissor: </b></label>
                          <p>{{ $dadosXMLAtual['detalhesEmissor']['nomeEmissor'] }}</p>
                        </div>
                        <div class="grupo-info">
                          <div class="info">
                            <label for="cnpj"><b>CNPJ do Emissor: </b></label>
                            <p>{{ $dadosXMLAtual['detalhesEmissor']['cnpj'] }}</p>
                          </div>
                          <div class="info">
                            <label for="ie"><b>IE do Emissor: </b></label>
                            <p>{{ $dadosXMLAtual['detalhesEmissor']['ie'] }}</p>
                          </div>
                        </div>
                        <div class="card">
                          <div class="card-body">
                            <h5 class="card-title">Endereço do emissor:</h5>
                            <div class="info">
                              <label for="cnpj"><b>Logradouro do Emissor: </b></label>
                              <p>{{ $dadosXMLAtual['detalhesEmissor']['endereco']['rua'] }}, {{ $dadosXMLAtual['detalhesEmissor']['endereco']['numero'] }}</p>
                            </div>
                            <div class="grupo-info">
                              <div class="info">
                                <label for="cnpj"><b>Bairro do Emissor: </b></label>
                                <p>{{ $dadosXMLAtual['detalhesEmissor']['endereco']['bairro'] }}</p>
                              </div>
                              <div class="info">
                                <label for="ie"><b>CEP do Emissor: </b></label>
                                <p>{{ $dadosXMLAtual['detalhesEmissor']['endereco']['cep'] }}</p>
                              </div>
                            </div>
                            <div class="grupo-info">
                              <div class="info">
                                <label for="cnpj"><b>Cidade do Emissor: </b></label>
                                <p>{{ $dadosXMLAtual['detalhesEmissor']['endereco']['cidade'] }}</p>
                              </div>
                              <div class="info">
                                <label for="ie"><b>UF do Emissor: </b></label>
                                <p>{{ $dadosXMLAtual['detalhesEmissor']['endereco']['uf'] }}</p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  @if ($dadosXMLAtual['modelo'] === '55')
                  <div class="dest">
                    <div class="card">
                      <div class="card-body">
                        <h5 class="card-title">Informações do Destinatario da nota:</h5>
                        <div class="info">
                          <label for="nome"><b>Nome do Destinatario: </b></label>
                          <p>{{ $dadosXMLAtual['detalhesDestinatario']['nomeDestinatario'] }}</p>
                        </div>
                        <div class="grupo-info">
                          <div class="info">
                            <label for="cnpj"><b>CNPJ do Destinatario: </b></label>
                            <p>{{ $dadosXMLAtual['detalhesDestinatario']['cnpj'] }}</p>
                          </div>
                          <div class="info">
                            <label for="ie"><b>IE do Destinatario: </b></label>
                            <p>{{ $dadosXMLAtual['detalhesDestinatario']['ie'] }}</p>
                          </div>
                        </div>
                        <div class="card">
                          <div class="card-body">
                            <h5 class="card-title">Endereço do Destinatario:</h5>
                            <div class="info">
                              <label for="cnpj"><b>Logradouro do Destinatario: </b></label>
                              <p>{{ $dadosXMLAtual['detalhesDestinatario']['endereco']['rua'] }}, {{ $dadosXMLAtual['detalhesDestinatario']['endereco']['numero'] }}</p>
                            </div>
                            <div class="grupo-info">
                              <div class="info">
                                <label for="cnpj"><b>Bairro do Destinatario: </b></label>
                                <p>{{ $dadosXMLAtual['detalhesDestinatario']['endereco']['bairro'] }}</p>
                              </div>
                              <div class="info">
                                <label for="ie"><b>CEP do Destinatario: </b></label>
                                <p>{{ $dadosXMLAtual['detalhesDestinatario']['endereco']['cep'] }}</p>
                              </div>
                            </div>
                            <div class="grupo-info">
                              <div class="info">
                                <label for="cnpj"><b>Cidade do Destinatario: </b></label>
                                <p>{{ $dadosXMLAtual['detalhesDestinatario']['endereco']['cidade'] }}</p>
                              </div>
                              <div class="info">
                                <label for="ie"><b>UF do Destinatario: </b></label>
                                <p>{{ $dadosXMLAtual['detalhesDestinatario']['endereco']['uf'] }}</p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  @else
                  <div class="dest">
                    <div class="card">
                      <div class="card-body">
                        <h5 class="card-title">Informações do destinatário</h5>
                        <div class="info">
                          <label for="nome"><b>Nome do Destinatario: </b></label>
                          <p>{{ $dadosXMLAtual['detalhesDestinatario']['nomeDestinatario'] }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endif
                </div>
                <div class="informacoes-pagamento">
                  @if(count($dadosXMLAtual['pagamento']) === 1)
                  @foreach ($dadosXMLAtual['pagamento']['pag']['pag'] as $key => $dadoPagamento)
                  @if ($key !== 'card')
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">{{ $dadoPagamento['descricao'] }}: </h5>
                      <p class="card-text">
                        @if ($key === 'vPag')
                        {{ numfmt_format_currency($fmt, floatval($dadoPagamento['valor']), 'BRL') }}
                        @else
                        {{$dadoPagamento['valor']}}
                        @endif
                      </p>
                    </div>
                  </div>
                  @endif
                  @if ($key === 'card')
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Dados do cartão:</h5>
                      @foreach ($dadoPagamento as $dadoCartao)
                      <p class="card-text"><b>{{ $dadoCartao['descricao'] }}</b>: {{ $dadoCartao['valor'] }}</p>
                      @endforeach
                    </div>
                  </div>
                  @endif
                  @endforeach
                  @endif
                  @if(count($dadosXMLAtual['pagamento']) > 1)
                  <div class="card">
                    <div class="card-body multiplasfp">
                      @foreach($dadosXMLAtual['pagamento'] as $pag)
                      <div>
                        @foreach ($pag['pag']['pag'] as $key => $dadoPagamento)
                        @if ($key !== 'card')
                        <div class="card">
                          <div class="card-body">
                            <h5 class="card-title">{{ $dadoPagamento['descricao'] }}: </h5>
                            <p class="card-text">
                              @if ($key === 'vPag')
                              {{ numfmt_format_currency($fmt, floatval($dadoPagamento['valor']), 'BRL') }}
                              @else
                              {{$dadoPagamento['valor']}}
                              @endif
                            </p>
                          </div>
                        </div>
                        @endif
                        @if ($key === 'card')
                        <div class="card">
                          <div class="card-body">
                            <h5 class="card-title">Dados do cartão:</h5>
                            @foreach ($dadoPagamento as $dadoCartao)
                            <p class="card-text"><b>{{ $dadoCartao['descricao'] }}</b>: {{ $dadoCartao['valor'] }}</p>
                            @endforeach
                          </div>
                        </div>
                        @endif
                        @endforeach
                      </div>
                      @endforeach
                    </div>
                  </div>
                  @endif
                </div>
              </div>
            </div>
            <div class="tab-pane fade table-responsive" id="produtos-nota" role="tabpanel" aria-labelledby="produtos-nota-tab">
              <table class="table accordion">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Cod. Produto</th>
                    <th>Nome</th>
                    <th>NCM</th>
                    <th>Un. Medida</th>
                    <th>Qtde.</th>
                    <th>Valor Un.</th>
                    <th>Valor Total</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($dadosXMLAtual['produtos'] as $key => $detalheProduto)
                  <tr data-bs-toggle="collapse" data-bs-target="#produto_cod{{ $key }}">
                    <th>
                      {{ $key + 1 }}
                    </th>
                    <td>{{ $detalheProduto['prod']['cProd'] }}</td>
                    <td>{{ $detalheProduto['prod']['xProd'] }}</td>
                    <td>{{ $detalheProduto['prod']['NCM'] }}</td>
                    <td>{{ $detalheProduto['prod']['uCom'] }}</td>
                    <td>{{ $detalheProduto['prod']['qCom'] }}</td>
                    <td>{{ numfmt_format_currency($fmt, floatval($detalheProduto['prod']['vUnCom']), 'BRL') }}</td>
                    <td>{{ numfmt_format_currency($fmt, floatval($detalheProduto['prod']['vProd']), 'BRL') }}</td>
                  </tr>
                  <tr class="collapse accordion-collapse" id="produto_cod{{ $key }}" data-bs-parent=".table">
                    <td colspan="8">
                      @foreach ($detalheProduto['imposto'] as $keyImposto => $imposto)
                      <div class="card infoImpostoProduto">
                        <div class="card-body">
                          <h5 class="card-title">{{ $keyImposto }}:</h5>
                          @foreach ($imposto as $tagImposto)
                          @foreach ($tagImposto as $infoImposto)
                          <p class="card-text">
                            <b>{{ $infoImposto['descricao'] }}: </b> {{ $infoImposto['valor'] }}
                          </p>
                          @endforeach
                          @endforeach
                        </div>
                      </div>
                      @endforeach
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="tab-pane fade" id="informacoes-nota" role="tabpanel" aria-labelledby="informacoes-nota-tab">
              <div class="infoadc-notafiscal">
                @foreach ($dadosXMLAtual['infAdicional']['infAdic']['infAdic'] as $detalhesInfAdicional)
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">{{ $detalhesInfAdicional['descricao'] }}: </h5>
                    <p class="card-text">{{ $detalhesInfAdicional['valor'] }}</p>
                  </div>
                </div>
                @endforeach
                @if (!is_null($dadosXMLAtualCancelado))
                <div class="card">
                  <div class="card-body">
                    <h3 class="card-title">Dados do cancelamento da nota fiscal</h3>
                    <div class="card">
                      <div class="card-body">
                        <p class="card-text"><b>CNPJ emissor do evento:</b> {{ $dadosXMLAtualCancelado['cnpj'] }}</p>
                        <p class="card-text"><b>Chave da nota cancelada:</b> {{ $dadosXMLAtualCancelado['chaveNFe'] }}</p>
                        <p class="card-text"><b>Data e Hora do cancelamento:</b> {{ date('d/m/Y H:i:s', strtotime($dadosXMLAtualCancelado['dh_cancelamento'])) }}</p>
                        <p class="card-text"><b>Motivo do cancelamento:</b> {{ $dadosXMLAtualCancelado['justificativa'] }}</p>
                      </div>
                    </div>
                  </div>
                </div>
                @endif
              </div>
            </div>
          </div>
          @else
          <div class="container-loading-dados">
            <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <h2>Carregado dados...</h2>
          </div>
          @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="resetarCampos">Fechar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Fim do modal de visualização de dados da nota fiscal -->

  <!-- Inicio modal de visualizacao dos dados da nota fiscal inutilizado -->

  <div class="modal fade" id="modalDadosInutilizacao" tabindex="-1" aria-labelledby="modalDadosInutilizacaoLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          @if (!is_null($dadosXMLAtualInutilizado))
          <h5 class="modal-title" id="informacaoNFeModalLabel">Dados da(s) nota(s) fiscal(is) inutilizada(s): {{ $dadosXMLAtualInutilizado['nfInicial'] }} - {{ $dadosXMLAtualInutilizado['nfFinal'] }}</h5>
          @endif
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="resetarCampos"></button>
        </div>
        <div class="modal-body">
          @if (!is_null($dadosXMLAtualInutilizado))
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Dados da inutilização</h4>
              <div class="card">
                <div class="card-body">
                  <p class="card-text"><b>CNPJ do emissor do evento: </b> {{ $dadosXMLAtualInutilizado['cnpj'] }}</p>
                  <p class="card-text"><b>Modelo: </b> {{ $dadosXMLAtualInutilizado['modelo'] }}</p>
                  <p class="card-text"><b>Série: </b> {{ $dadosXMLAtualInutilizado['serie'] }}</p>
                  <p class="card-text"><b>Nota inicial/Nota Final: </b> {{ $dadosXMLAtualInutilizado['nfInicial'] }} - {{ $dadosXMLAtualInutilizado['nfFinal'] }}</p>
                  <p class="card-text"><b>Data e hora do evento: </b> {{ date('d/m/Y H:i:s', strtotime($dadosXMLAtualInutilizado['dh_inutilizado'])) }}</p>
                  <p class="card-text"><b>Justificativa: </b> {{ $dadosXMLAtualInutilizado['justificativa'] }}</p>
                </div>
              </div>
            </div>
          </div>
          @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="resetarCampos">Fechar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Fim modal de visualizacao dos dados da nota fiscal inutilizado -->


  <style>
    .main {
      display: flex;
      flex-direction: column;
      padding: 3rem 0 0 5rem;
    }

    .tabela-xml,
    .paginacao {
      width: 90%;
    }

    i {
      cursor: pointer;
    }

    .perpage {
      width: 90%;
      display: flex;
      gap: 1rem;
      flex-direction: row-reverse;
    }

    .perpage>button {
      padding: .5rem 1rem;
      border: none;
      border-radius: 5px;
      background-color: var(--primary-color);
      color: white;
      font-weight: 700;
      transition: var(--tran-04);
      width: 5%;
    }

    .perpage>button:hover {
      background-color: var(--primary-color-hover);
    }

    .perpage>input {
      width: 5%;
    }

    .bodyModalInformacaoNFe,
    .container-loading-dados,
    .container-info-dados {
      width: 100%;
      height: 100%;
    }

    .container-loading-dados {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 2rem;
    }

    .container-info-dados,
    .infoadc-notafiscal {
      display: flex;
      flex-direction: column;
      margin-top: 1rem;
      gap: 1rem;
    }

    .valores-nota,
    .informacoes-pagamento {
      display: flex;
      gap: 1rem;
    }

    .emit-dest {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    .info {
      display: flex;
      gap: .5rem;
    }

    /* .grupo-info {
      display: grid;
      grid-template-columns: 1fr 1fr;
    } */

    .infoImpostoProduto {
      margin-bottom: .5rem;
    }

    .multiplasfp {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .multiplasfp > div {
      display: flex;
      gap: 1rem;
    }
  </style>
</app>
