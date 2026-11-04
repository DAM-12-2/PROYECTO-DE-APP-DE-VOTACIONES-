#!/usr/bin/env bash
# Prueba de carga del flujo de voto.
# Uso: ./scripts/stress-vote.sh <base_url> <cantidad> <concurrencia> [identificacion_inicial]
set -euo pipefail
export LC_ALL=C

BASE="${1:-http://127.0.0.1:8124}"
N="${2:-100}"
CONC="${3:-10}"
PREFIX="${4:-STRESS}"
OUT_DIR="${OUT_DIR:-docs/produccion/evidencias}"
COOKIES="$(mktemp)"
RESULTS="$(mktemp)"

mkdir -p "$OUT_DIR"

# Login JRV y token CSRF
curl -s -c "$COOKIES" "$BASE/login" -o /tmp/login.html
TOKEN=$(grep -o 'name="_token" value="[^"]*"' /tmp/login.html | head -1 | cut -d'"' -f4)
curl -s -b "$COOKIES" -c "$COOKIES" -X POST "$BASE/login" \
  -d "_token=$TOKEN&username=jrv&password=jrv" -o /dev/null

curl -s -b "$COOKIES" "$BASE/jrv" -o /tmp/jrv.html
TOKEN=$(grep -o 'name="csrf-token" content="[^"]*"' /tmp/jrv.html | head -1 | cut -d'"' -f4)

if [ -z "$TOKEN" ]; then
  echo "No se pudo obtener el token CSRF (login fallido)." >&2
  exit 1
fi

export BASE TOKEN COOKIES RESULTS

seq 1 "$N" | xargs -P "$CONC" -I{} bash -c '
  ID=$(printf "%s-%04d" "'"$PREFIX"'" {})
  OUT=$(curl -s -o /tmp/voto_{}.json -w "%{http_code} %{time_total}" \
    -b "$COOKIES" -X POST "$BASE/jrv/api/votar" \
    -H "Content-Type: application/json" -H "Accept: application/json" \
    -H "X-CSRF-TOKEN: $TOKEN" \
    -d "{\"identificacion\":\"$ID\",\"party_id\":1}")
  echo "$OUT" >> "$RESULTS"
'

TOTAL=$(wc -l < "$RESULTS")
OK=$(awk '$1==201' "$RESULTS" | wc -l)
ERR=$(awk '$1!=201' "$RESULTS" | wc -l)
AVG=$(awk '{s+=$2} END {if (NR>0) printf "%.3f", s/NR}' "$RESULTS")
P95=$(awk '{print $2}' "$RESULTS" | sort -n | awk '{a[NR]=$1} END {printf "%.3f", a[int(NR*0.95)]}')
MAX=$(awk '{print $2}' "$RESULTS" | sort -n | tail -1)
CODES=$(awk '{print $1}' "$RESULTS" | sort | uniq -c | tr '\n' ' ')

SUMMARY="$OUT_DIR/carga-votos.txt"
{
  echo "base_url=$BASE"
  echo "solicitudes=$TOTAL"
  echo "concurrencia=$CONC"
  echo "exitos_201=$OK"
  echo "errores=$ERR"
  echo "codigos=$CODES"
  echo "latencia_media_s=$AVG"
  echo "latencia_p95_s=$P95"
  echo "latencia_max_s=$MAX"
  echo "fecha=$(date -Iseconds)"
} | tee "$SUMMARY"

cp "$RESULTS" "$OUT_DIR/carga-votos-raw.txt"
rm -f "$COOKIES" "$RESULTS"
