#!/usr/bin/env bash
# Envía las capturas a Telegram.
# Uso:
#   TELEGRAM_BOT_TOKEN=123:abc TELEGRAM_CHAT_ID=123456789 ./scripts/enviar-telegram.sh
set -euo pipefail

: "${TELEGRAM_BOT_TOKEN:?Falta TELEGRAM_BOT_TOKEN}"
: "${TELEGRAM_CHAT_ID:?Falta TELEGRAM_CHAT_ID}"

DIR="${1:-docs/produccion/capturas}"
API="https://api.telegram.org/bot${TELEGRAM_BOT_TOKEN}"

enviar() {
    local archivo="$1"
    local texto="$2"
    curl -s \
        -F "chat_id=${TELEGRAM_CHAT_ID}" \
        -F "caption=${texto}" \
        -F "document=@${archivo}" \
        "${API}/sendDocument" >/dev/null
    echo "[ok] $(basename "$archivo")"
}

enviar "$DIR/12-splash-carga.png"      "Pantalla de carga — Jefferson G. y Sahir M."
enviar "$DIR/13-login-creditos.png"    "Login con créditos"
enviar "$DIR/14-acerca-de.png"         "Página Acerca de"
enviar "$DIR/15-admin-footer.png"      "Créditos en el panel admin"
enviar "$DIR/16-jrv-footer.png"        "Créditos en el portal JRV"
enviar "$DIR/17-urna-footer.png"       "Créditos en la urna"

echo "Listo: 6 capturas enviadas."
