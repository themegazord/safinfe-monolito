#!/bin/bash
# Pint Watcher - Formata código automaticamente ao salvar arquivos

echo "🎨 Pint Watcher iniciado..."
echo "Monitorando arquivos PHP em app/, routes/, config/"

while true; do
    # Espera por mudanças em arquivos PHP
    inotifywait -r -e modify,create app/ routes/ config/ database/ 2>/dev/null
    
    # Aguarda 1 segundo para evitar múltiplas execuções
    sleep 1
    
    # Executa Pint
    echo "🔧 Formatando código..."
    ./vendor/bin/pint --quiet
    echo "✅ Código formatado!"
done
