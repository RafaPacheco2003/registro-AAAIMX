#!/usr/bin/env bash
# Crea categoría → subcategoría → registro (PDF) usando la API.
# Requisitos: curl, jq
# Uso: ./scripts/demo-flow-register.sh
#      BASE=http://127.0.0.1:8000/api/v1/roborage ./scripts/demo-flow-register.sh

set -euo pipefail

BASE="${BASE:-http://127.0.0.1:8000/api/v1/roborage}"
OUT_PDF="${OUT_PDF:-registro-demo.pdf}"

die() {
  echo "Error: $*" >&2
  exit 1
}

need_cmd() {
  command -v "$1" >/dev/null 2>&1 || die "falta el comando '$1' (instálalo para continuar)"
}

need_cmd curl
need_cmd jq

json_post() {
  local url="$1"
  local body="$2"
  curl -sS -X POST "$url" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d "$body"
}

assert_success() {
  local label="$1"
  local json="$2"
  if ! echo "$json" | jq -e '.success == true' >/dev/null 2>&1; then
    echo "Respuesta inválida ($label):" >&2
    echo "$json" | jq . >&2 2>/dev/null || echo "$json" >&2
    die "success != true"
  fi
}

echo "==> 1. Crear categoría"
CAT_JSON=$(json_post "${BASE}/categories" "$(jq -n \
  --arg name "Demo $(date +%s)" \
  --argjson project 1 \
  '{name: $name, project: $project}')")
assert_success "categories" "$CAT_JSON"
CATEGORY_ID=$(echo "$CAT_JSON" | jq -er '.data.id')
echo "    category_id=$CATEGORY_ID"

echo "==> 2. Crear subcategoría"
SUB_JSON=$(json_post "${BASE}/subcategories" "$(jq -n \
  --arg name "Sub demo $(date +%s)" \
  --argjson project 1 \
  --argjson price 1500.50 \
  --argjson category_id "$CATEGORY_ID" \
  '{name: $name, project: $project, price: $price, category_id: $category_id}')")
assert_success "subcategories" "$SUB_JSON"
SUBCATEGORY_ID=$(echo "$SUB_JSON" | jq -er '.data.id')
echo "    subcategory_id=$SUBCATEGORY_ID"

echo "==> 3. Crear registro (descarga PDF)"
TEAM="Equipo demo $(date +%s)"
HTTP_CODE=$(curl -sS -o "$OUT_PDF" -w "%{http_code}" -X POST "${BASE}/registers" \
  -H "Content-Type: application/json" \
  -H "Accept: application/pdf" \
  -d "$(jq -n \
    --arg team_name "$TEAM" \
    --arg robot_name "Bot-X" \
    --arg education_level "university" \
    --arg institution "Universidad Demo" \
    --arg personal_email "lider@example.com" \
    --arg institutional_email "lider@uni.edu.mx" \
    --arg comments "Script demo-flow-register" \
    --arg payment_date "$(date -v+30d +%Y-%m-%d 2>/dev/null || date -d '+30 days' +%Y-%m-%d 2>/dev/null || echo '2026-12-31')" \
    --argjson category_id "$CATEGORY_ID" \
    --argjson subcategory_id "$SUBCATEGORY_ID" \
    '{
      team_name: $team_name,
      robot_name: $robot_name,
      education_level: $education_level,
      institution: $institution,
      personal_email: $personal_email,
      institutional_email: $institutional_email,
      comments: $comments,
      category_id: $category_id,
      subcategory_id: $subcategory_id,
      payment_date: $payment_date,
      team_members: [
        {
          name: "Integrante 1",
          personal_email: "m1@example.com",
          institutional_email: "m1@uni.edu.mx"
        }
      ]
    }')")

[[ "$HTTP_CODE" == "200" ]] || die "POST registers HTTP $HTTP_CODE (esperado 200). Revisa $OUT_PDF (puede ser JSON de error)."
echo "    PDF guardado en: $OUT_PDF"

echo "==> 4. Listar registros (último creado, si la API lo devuelve)"
LIST_JSON=$(curl -sS "${BASE}/registers" -H "Accept: application/json")
assert_success "registers index" "$LIST_JSON"
# Laravel ResourceCollection dentro de success: a veces .data es { data: [...] }
LAST=$(echo "$LIST_JSON" | jq -c '(.data.data // .data) | if type == "array" then .[-1] else empty end')
if [[ -n "$LAST" && "$LAST" != "null" ]]; then
  echo "$LAST" | jq .
else
  echo "    (no se pudo inferir el último ítem; revisa GET ${BASE}/registers)"
fi

echo "Listo. IDs: category=$CATEGORY_ID subcategory=$SUBCATEGORY_ID equipo=$TEAM"
