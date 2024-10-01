# Cadastro de XML ![Static Badge](https://img.shields.io/badge/Rota_autenticada-49CC90)

## Explicação de Rotas

Rota usada para enviar um XML.

## URL

![Static Badge](https://img.shields.io/badge/POST-%2Fapi%2Fenviaxml-%2361AFFE)

## Parametro de requisição

| Parametro | Tipo   | Tamanho | Descrição                          | Obrigatório? |
|-----------|--------|---------|------------------------------------|--------------|
| cnpj      | string | 14      | O CNPJ da empresa que emitiu o XML | Sim          |
| status    | string | 255     | Status da nota fiscal              | Sim          |
| arquivo   | string | 255     | O arquivo .xml da nota fiscal      | Sim          |

## Status permitidos

1. AUTORIZADO
2. CANCELADO
3. INUTILIZADO

## Exemplo de requisição

```json
{
    "cnpj": "19902227000150",
    "status": "AUTORIZADO",
    "arquivo": "ENVIAR O ARQUIVO XML AQUI"
  }
```

## Parametro de resposta

| Parametro | Tipo   | Descrição                                                 |
|-----------|--------|-----------------------------------------------------------|
| mensagem  | string | Mensagem informando que a nota foi cadastrada com sucesso |

## Exemplo de resposta

```json
{
    "mensagem": "XML cadastrado com sucesso",
}
```

## Possibilidade de erro

| Código | Resposta                                                                                   | Motivo                                                              |
|--------|--------------------------------------------------------------------------------------------|---------------------------------------------------------------------|
| 400    | O status não foi informado.                                                                | Quando o status não é enviado na requisição.                        |
| 400    | O status [status] é inválido, consultar documentação.                                      | Quando o status informado não é válido.                             |
| 400    | A empresa não foi informada.                                                               | Quando a empresa não é enviada na requisição.                       |
| 400    | O CNPJ informado [cnpj] não contém os 14 caracteres válidos para um CNPJ.                  | Quando o CNPJ informado tem uma quantidade incorreta de caracteres. |
| 404    | O CNPJ [cnpj] não existe na base de dados.                                                 | Quando o CNPJ informado não é encontrado na base de dados.          |
| 400    | O arquivo não foi enviado.                                                                 | Quando nenhum arquivo é enviado na requisição.                      |
| 400    | O único tipo de arquivo aceitável é o XML.                                                 | Quando o tipo de arquivo enviado não é XML.                         |
| 422    | O CNPJ informado na API [cnpjAPI] não condiz com o CNPJ emitente da nota fiscal [cnpjXML]. | Quando o CNPJ informado na API é diferente do CNPJ do XML emitente. |
| 409    | O XML já foi enviado.                                                                      | Quando o XML já foi registrado no sistema anteriormente.            |
