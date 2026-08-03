#!/bin/bash
# iniciar.sh — sobe o servidor de desenvolvimento do Bibliotech
# Execute com: bash iniciar.sh

echo "Iniciando Bibliotech em http://127.0.0.1:9090 ..."
php -S 127.0.0.1:9090 -t public
