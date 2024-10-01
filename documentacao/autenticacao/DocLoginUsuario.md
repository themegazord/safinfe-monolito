# Login de usuários ![Static Badge](https://img.shields.io/badge/Rota_n%C3%A3o_autenticada-%23F93E3E)

## Explicação da rota

Rota utilizada para logar dentro do sistema.

## URL

![Static Badge](https://img.shields.io/badge/POST-%2Fapi%2Fautenticacao-%2349CC90)

## Parametro de requisição

| Parametro | Tipo   | Tamanho | Descrição        | Obrigatório? |
|-----------|--------|---------|------------------|--------------|
| email     | string | 255     | Email do usuário | Sim          |
| password  | string | 255     | Senha do usuário | Sim          |

## Exemplo de requisição

```json
{
    "email": "contato.wanjalagus@outlook.com.br",
    "password": "81590619"
}
```

## Parametro de resposta

| Parametro | Tipo   | Descrição             |
|-----------|--------|-----------------------|
| token     | string | Token de autenticação |

## Exemplo de resposta

```json
{
  "token" => "3|ohbAEJLwbgoSGhyrzGyFVyq6kv6T69eotYRQcKvN5bfbf69d"
}
```

## Possibilidade de erro

| Código | Resposta                                                       | Motivo                                  |
|--------|----------------------------------------------------------------|-----------------------------------------|
| 404    | O usuário não foi identificado no sistema.                     | Quando o usuário não existe no sistema. |
| 409    | O email [email] e a senha informada não condizem com os dados. | Quando o email e a senha não condizem   |
