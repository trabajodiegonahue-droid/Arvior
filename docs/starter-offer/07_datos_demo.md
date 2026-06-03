# Datos demo — para mostrar el sistema poblado

> Activo comercial/operativo · Fase 5
> Una demo **nunca** se hace con la base vacía. Acá hay un dataset realista (clínica
> dental, CLP) y dos formas de cargarlo: **script** (rápido) o **manual** (sin terminal).
> Borralo antes de entregar el sistema a un cliente real.

---

## 1. Dataset propuesto (clínica dental "Sonríe" — ejemplo)

Negocio relatable, ticket alto, varias fuentes. Estados repartidos para que el
embudo, el forecast y la performance por fuente se vean ricos.

| Nombre | Fuente | Estado | Valor (CLP) | Notas |
|---|---|---|---|---|
| Camila Rojas | instagram | Ganado | 1.200.000 | implante |
| Matías Fuentes | google | Ganado | 850.000 | ortodoncia |
| Valentina Soto | referido | Negociación | 1.500.000 | conducto + corona |
| Diego Araya | instagram | Propuesta enviada | 600.000 | blanqueamiento + limpieza |
| Francisca Núñez | website | Reunión agendada | 900.000 | evaluación implantes |
| Joaquín Vera | google | Contactado | — | consulta general |
| Antonia Díaz | instagram | Nuevo | — | escribió por una promo |
| Ignacio Pérez | referido | Perdido | 700.000 | motivo: precio |

**Métricas esperadas (rango mes, todas las cuentas):**
- Leads: 8 · Ganados: 2 · Perdidos: 1 · Win rate: 66,7%
- Revenue ganado: **CLP 2.050.000** · Ticket promedio: **CLP 1.025.000**
- Pipeline abiertos: 4 (negociación, propuesta, reunión, nuevo) · valor 3.000.000
- Forecast ponderado ≈ negociación 1.500.000×0,7 + propuesta 600.000×0,5 +
  reunión 900.000×0,3 + nuevo 0×0,05 = **CLP 1.620.000**
- Fuentes: instagram (3 leads, 1 ganado), google (2, 1 ganado), referido (2, 0),
  website (1, 0).

---

## 2. Opción A — Script (rápido, vía HTTP real)

Carga los leads por el **intake público** y setea valor/estado por las **acciones
del admin** (las mismas que usa la UI). Requiere `bash`, `curl` y una sesión admin.

> Reemplazá `BASE`, el usuario/clave admin, y verificá el `public_token` de la cuenta
> interna (Admin → Cuentas → ARVIOR, o el de la cuenta del cliente).

```bash
#!/usr/bin/env bash
set -e
BASE="https://tudominio.com"          # sin slash final
ADMIN_EMAIL="admin@tudominio.com"
ADMIN_PASS="TU_CLAVE"
TOKEN="arvior-internal"                # token público de la cuenta destino
J="$(mktemp)"
csrf(){ grep -o 'name="csrf" value="[^"]*"' "$1" | head -1 | sed 's/.*value="//;s/"//'; }

# 1) login (guarda sesión en cookie jar)
curl -s -c "$J" "$BASE/admin/" -o /tmp/_l.html
curl -s -b "$J" -c "$J" -d action=login --data-urlencode "csrf=$(csrf /tmp/_l.html)" \
  --data-urlencode "email=$ADMIN_EMAIL" --data-urlencode "password=$ADMIN_PASS" -o /dev/null "$BASE/admin/"

mklead(){ # nombre email fuente
  curl -s "$BASE/intake.php" -d format=json --data-urlencode "public_token=$TOKEN" \
    --data-urlencode "name=$1" --data-urlencode "email=$2" --data-urlencode "source=$3" >/dev/null
}
# fija valor/estado de un lead por id (usa las acciones reales del admin)
setval(){ # id valor
  curl -s -b "$J" -c "$J" "$BASE/admin/?id=$1" -o /tmp/_d.html
  curl -s -b "$J" -c "$J" -X POST "$BASE/admin/" --data-urlencode "csrf=$(csrf /tmp/_d.html)" \
    -d action=update_lead_value --data-urlencode "value_amount=$2" -d "id=$1" >/dev/null; }
setstate(){ # id estado [motivo]
  curl -s -b "$J" -c "$J" "$BASE/admin/?id=$1" -o /tmp/_d.html
  curl -s -b "$J" -c "$J" -X POST "$BASE/admin/" --data-urlencode "csrf=$(csrf /tmp/_d.html)" \
    -d action=update_lead_status -d "status=$2" -d "id=$1" >/dev/null
  if [ -n "$3" ]; then
    curl -s -b "$J" -c "$J" "$BASE/admin/?id=$1" -o /tmp/_d.html
    curl -s -b "$J" -c "$J" -X POST "$BASE/admin/" --data-urlencode "csrf=$(csrf /tmp/_d.html)" \
      -d action=update_lead_value --data-urlencode "lost_reason=$3" -d "id=$1" >/dev/null
  fi; }

# 2) crear leads (emails únicos para no chocar con el dedup)
mklead "Camila Rojas"     "camila.demo@sonrie.cl"    "instagram"
mklead "Matias Fuentes"   "matias.demo@sonrie.cl"    "google"
mklead "Valentina Soto"   "valentina.demo@sonrie.cl" "referido"
mklead "Diego Araya"      "diego.demo@sonrie.cl"     "instagram"
mklead "Francisca Nunez"  "francisca.demo@sonrie.cl" "website"
mklead "Joaquin Vera"     "joaquin.demo@sonrie.cl"   "google"
mklead "Antonia Diaz"     "antonia.demo@sonrie.cl"   "instagram"
mklead "Ignacio Perez"    "ignacio.demo@sonrie.cl"   "referido"

echo ">> Leads creados. Ahora asigná valor/estado por id desde el panel,"
echo ">> o completá los ids reales en las llamadas setval/setstate de abajo:"
echo "   setval <id> 1200000 ; setstate <id> won        # Camila"
echo "   setval <id> 850000  ; setstate <id> won        # Matias"
echo "   setval <id> 1500000 ; setstate <id> negotiation # Valentina"
echo "   setval <id> 600000  ; setstate <id> proposal_sent # Diego"
echo "   setval <id> 900000  ; setstate <id> meeting_scheduled # Francisca"
echo "   setstate <id> contacted                         # Joaquin"
echo "   # Antonia queda en 'new' (no tocar)"
echo "   setval <id> 700000  ; setstate <id> lost 'precio' # Ignacio"
```

> Los `id` se ven en el panel (`/admin/` → columna #) o en la URL del detalle. El
> script crea los leads automáticamente; el valor/estado se aplica por id para que
> el mapeo sea explícito y sin sorpresas. (Si preferís, hacelo 100% por la UI.)

---

## 3. Opción B — Manual (sin terminal, 100% UI)

1. **Crear leads:** completá el formulario de la landing 8 veces con los nombres de
   la tabla §1 (o cargalos como prueba). Cada uno aparece en **Leads**.
2. **Asignar valor y estado:** abrí cada lead → sección **"Valor y cierre"** (cargá
   el monto) → **Cambiar estado** (poné la etapa de la tabla). Para Ignacio, además
   escribí el motivo de pérdida.
3. **Verificá en Reportes** que los números coincidan con §1.

> Tarda ~10 minutos pero no requiere nada técnico — sirve incluso para que el
> propio cliente "juegue" en la capacitación.

---

## 4. CSV de importación (si el Build incluye carga inicial)

Formato simple para la importación del onboarding (1 carga):

```csv
nombre,email,telefono,fuente,estado,valor,nota
Camila Rojas,camila.demo@sonrie.cl,+56911111111,instagram,won,1200000,implante
Matias Fuentes,matias.demo@sonrie.cl,+56922222222,google,won,850000,ortodoncia
Valentina Soto,valentina.demo@sonrie.cl,+56933333333,referido,negotiation,1500000,conducto+corona
Diego Araya,diego.demo@sonrie.cl,+56944444444,instagram,proposal_sent,600000,blanqueamiento
Francisca Nunez,francisca.demo@sonrie.cl,+56955555555,website,meeting_scheduled,900000,evaluacion
Joaquin Vera,joaquin.demo@sonrie.cl,+56966666666,google,contacted,,consulta
Antonia Diaz,antonia.demo@sonrie.cl,+56977777777,instagram,new,,promo
Ignacio Perez,ignacio.demo@sonrie.cl,+56988888888,referido,lost,700000,precio
```

---

## 5. Limpieza (antes de entregar a un cliente real)

- Borrar los leads demo (tienen email `*.demo@sonrie.cl` — fácil de filtrar).
- Verificar que Reportes vuelva a cero.
- Confirmar que no quedaron tareas/actividades demo en el timeline.
