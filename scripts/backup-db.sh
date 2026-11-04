#!/usr/bin/env bash
# Respaldo cifrado de PostgreSQL. Uso: scripts/backup-db.sh
set -euo pipefail

APP_DIR="/home/jeffer_gr/PROYECTO-DE-APP-DE-VOTACIONES"
DEST="${BACKUP_DIR:-/var/backups/votaciones}"
KEY="${BACKUP_KEY:-/etc/votaciones-backup.key}"

env_value() {
    grep -E "^$1=" "$APP_DIR/.env" | head -1 | cut -d= -f2-
}

export PGPASSWORD
PGPASSWORD="$(env_value DB_PASSWORD)"
DB_USER="$(env_value DB_USERNAME)"
DB_NAME="$(env_value DB_DATABASE)"
DB_HOST="$(env_value DB_HOST)"
DB_PORT="$(env_value DB_PORT)"

mkdir -p "$DEST"
chmod 700 "$DEST"

STAMP="$(date +%Y%m%d-%H%M%S)"
OUT="$DEST/votaciones-$STAMP.dump.enc"

if [ "$(id -u)" -eq 0 ]; then
    runuser -u postgres -- pg_dump -Fc "$DB_NAME" \
        | openssl enc -aes-256-cbc -pbkdf2 -salt -pass "file:$KEY" -out "$OUT"
else
    pg_dump -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USER" -d "$DB_NAME" -Fc \
        | openssl enc -aes-256-cbc -pbkdf2 -salt -pass "file:$KEY" -out "$OUT"
fi

chmod 600 "$OUT"
find "$DEST" -type f -name '*.enc' -mtime +14 -delete

echo "Respaldo: $OUT ($(du -h "$OUT" | cut -f1))"
