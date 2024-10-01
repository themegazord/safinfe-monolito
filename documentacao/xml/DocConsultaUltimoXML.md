# Consulta de último XML da empresa ![Static Badge](https://img.shields.io/badge/Rota_autenticada-49CC90)

## Explicação de Rotas

Rota usada para consultar o último XML recebido da empresa dentro do sistema.

## URL

![Static Badge](https://img.shields.io/badge/GET-%2Fapi%2Fultimoxml-%2361AFFE)

## Parametro do endpoint

| Parametro | Tipo   | Tamanho | Descrição                            | Obrigatório? |
|-----------|--------|---------|--------------------------------------|--------------|
| cnpj      | string | 14      | CNPJ da empresa que deseja consultar | Sim          |

## Parametro de resposta

| Parametro | Tipo   | Descrição                                 |
|-----------|--------|-------------------------------------------|
| nota      | objeto | Dados do ultimo xml da empresa consultada |

## Exemplo de resposta

```json
{
  "nota": {
        "dados_id": 12146,
        "xml_id": 9660,
        "empresa_id": 3,
        "status": "AUTORIZADO",
        "modelo": 65,
        "serie": 1,
        "numeronf": 61685,
        "numeronf_final": null,
        "justificativa": null,
        "dh_emissao_evento": "2024-08-01 08:49:39",
        "chave": "50240819902227000150650010000616851100783341",
        "created_at": "2024-09-13T19:05:35.000000Z",
        "updated_at": "2024-09-13T19:05:35.000000Z"
    }
}
```

## Possibilidade de erro

| Código | Resposta                                                                  | Motivo                                                                         |
|--------|---------------------------------------------------------------------------|--------------------------------------------------------------------------------|
| 400    | A empresa não foi informada.                                              | Quando a empresa não é enviada na requisição.                                  |
| 400    | O CNPJ informado [cnpj] não contém os 14 caracteres válidos para um CNPJ. | Quando o CNPJ informado tem uma quantidade incorreta de caracteres.            |
| 404    | O CNPJ [cnpj] não existe na base de dados.                                | Quando o CNPJ informado não é encontrado na base de dados.                     |
| 404    | Empresa não possui nota fiscal cadastrada.                                | Quando é feita uma consulta de notas fiscais e a empresa não possui registros. |
