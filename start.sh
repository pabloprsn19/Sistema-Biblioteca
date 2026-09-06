#!/bin/bash
# start.sh — Script de inicialização para produção (Koyeb / Railway)
# Roda o setup do banco na primeira vez e inicia o servidor PHP

set -e

echo "=== Bibliotech — Inicializando ==="

# Garante que a pasta do banco existe e é gravável
mkdir -p /app/database

# Roda o setup se o banco ainda não existir
if [ ! -f /app/database/biblioteca.db ]; then
    echo "Banco não encontrado — executando setup.php..."
    php /app/setup.php
    echo "Setup concluído."
else
    echo "Banco já existente — pulando setup."
fi

echo "Iniciando servidor PHP na porta $PORT..."
exec php -S "0.0.0.0:$PORT" -t /app/public
