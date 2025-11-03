#!/bin/bash

ACTUAL_DIRECTORY="$PWD"
BACKUP_FOLDER="${ACTUAL_DIRECTORY}/storage/app/Laravel/"
ENV_PATH="${ACTUAL_DIRECTORY}/.env"
DB_PASSWORD=$(grep 'DB_PASSWORD' $ENV_PATH | cut -d'=' -f2 | tr -d '"' | tr -d "'")
DB_HOST=$(grep 'DB_HOST' $ENV_PATH | cut -d'=' -f2 | tr -d '"' | tr -d "'")
DB_USER=$(grep 'DB_USERNAME' $ENV_PATH | cut -d'=' -f2 | tr -d '"' | tr -d "'")
DB_DATABASE=$(grep 'DB_DATABASE' $ENV_PATH | cut -d'=' -f2 | tr -d '"' | tr -d "'")

echo "=== INICIANDO RESTORE DO BACKUP ==="
echo "Diretório: $BACKUP_FOLDER"

cd $BACKUP_FOLDER || { echo "ERRO: Não foi possível acessar $BACKUP_FOLDER"; exit 1; }

if [ -z "$(ls -A)" ]; then
    echo "ERRO: Pasta está vazia"
    exit 1
fi

NEWEST_BACKUP=$(ls -Art | grep '\.zip$' | tail -n 1)

if [ -z "$NEWEST_BACKUP" ]; then
    echo "ERRO: Nenhum arquivo .zip encontrado"
    exit 1
fi

echo "Backup encontrado: $NEWEST_BACKUP"

if [ -d "db-dumps" ]; then
    echo "Removendo pasta db-dumps antiga..."
    rm -rf db-dumps
fi

echo "DESCOMPACTANDO ÚLTIMO BACKUP ENCONTRADO..."
FULL_PATH="${BACKUP_FOLDER}${NEWEST_BACKUP}"

unzip -o ${FULL_PATH}
if [ $? -ne 0 ]; then
    echo "ERRO: Falha ao descompactar $FULL_PATH"
    exit 1
fi

echo "✓ Backup descompactado com sucesso"

if [ ! -d "db-dumps" ]; then
    echo "ERRO: Pasta db-dumps não foi criada após descompactar"
    exit 1
fi

cd db-dumps || { echo "ERRO: Não foi possível acessar db-dumps"; exit 1; }

BACKUP_SQL=$(ls -Art | grep '\.sql$' | tail -n 1)

if [ -z "$BACKUP_SQL" ]; then
    echo "ERRO: Nenhum arquivo .sql encontrado"
    exit 1
fi

echo "Arquivo SQL encontrado: $BACKUP_SQL"
echo "Restaurando banco de dados..."
echo "Host: $DB_HOST"
echo "Database: $DB_DATABASE"
echo "User: $DB_USER"

mysql -h "$DB_HOST" -u "$DB_USER" -p"${DB_PASSWORD}" "$DB_DATABASE" < "$BACKUP_SQL"

if [ $? -eq 0 ]; then
    echo "✓✓✓ RESTORE CONCLUÍDO COM SUCESSO ✓✓✓"
    echo "Banco de dados restaurado: $DB_DATABASE"
    echo "Arquivo usado: $BACKUP_SQL"
else
    echo "✗✗✗ ERRO AO RESTAURAR BANCO DE DADOS ✗✗✗"
    exit 1
fi

echo "=== PROCESSO FINALIZADO ==="
