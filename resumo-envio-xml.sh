#!/bin/bash

LOG_DIR="$(dirname "$0")/storage/logs/envio_xml"

if [ ! -d "$LOG_DIR" ]; then
    echo "Nenhum log encontrado em: $LOG_DIR"
    exit 1
fi

if [ -n "$1" ]; then
    LOG_FILE="$1"
else
    LOG_FILE=$(ls -t "$LOG_DIR"/*.log 2>/dev/null | head -1)
fi

if [ -z "$LOG_FILE" ] || [ ! -f "$LOG_FILE" ]; then
    echo "Nenhum arquivo de log encontrado."
    exit 1
fi

contar() { grep -c "$1" "$2" 2>/dev/null || true; }

SESSOES=$(contar ': INICIO {'   "$LOG_FILE"); SESSOES=${SESSOES:-0}
OK=$(contar ': OK {'            "$LOG_FILE"); OK=${OK:-0}
ERRO=$(contar ': ERRO_ENVIO {'  "$LOG_FILE"); ERRO=${ERRO:-0}
ERRO_L=$(contar ': ERRO_LEITURA {' "$LOG_FILE"); ERRO_L=${ERRO_L:-0}
IGNORADOS=$(contar ': IGNORADO {' "$LOG_FILE"); IGNORADOS=${IGNORADOS:-0}
TOTAL_ERROS=$((ERRO + ERRO_L))

echo "========================================"
echo "  RESUMO DE ENVIO DE XML"
echo "  Arquivo: $(basename "$LOG_FILE")"
echo "========================================"
echo ""
echo "  Execuções registradas : $SESSOES"
echo ""
echo "  Enviados com sucesso  : $OK"
echo "  Erros de envio        : $ERRO"
echo "  Erros de leitura      : $ERRO_L"
echo "  Ignorados             : $IGNORADOS"
echo "  Total de erros        : $TOTAL_ERROS"
echo ""

echo "----------------------------------------"
echo "  ÚLTIMA EXECUÇÃO"
echo "----------------------------------------"
ULTIMO_FIM=$(grep ': FIM {' "$LOG_FILE" 2>/dev/null | tail -1)

if [ -n "$ULTIMO_FIM" ]; then
    DATA=$(echo "$ULTIMO_FIM"   | grep -oP '\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]')
    ULTIMA_OK=$(echo "$ULTIMO_FIM"   | grep -oP '"enviados":\K[0-9]+')
    ULTIMO_ERRO=$(echo "$ULTIMO_FIM" | grep -oP '"erros":\K[0-9]+')
    ULTIMO_IGN=$(echo "$ULTIMO_FIM"  | grep -oP '"ignorados":\K[0-9]+')
    echo "  Data    : $DATA"
    echo "  OK      : ${ULTIMA_OK:-0}"
    echo "  Erros   : ${ULTIMO_ERRO:-0}"
    echo "  Ignor.  : ${ULTIMO_IGN:-0}"
fi

echo ""

if [ "$TOTAL_ERROS" -gt 0 ]; then
    echo "----------------------------------------"
    echo "  ÚLTIMOS ARQUIVOS COM ERRO (máx 10)"
    echo "----------------------------------------"
    grep ': ERRO_ENVIO {\|: ERRO_LEITURA {' "$LOG_FILE" 2>/dev/null | tail -10 | \
        grep -oP '"arquivo":"[^"]+' | \
        sed 's/"arquivo":"/ → /'
    echo ""
fi

echo "========================================"
echo "  Log completo: $LOG_FILE"
echo "========================================"
