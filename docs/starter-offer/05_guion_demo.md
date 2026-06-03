# Guion de demo — ARVIOR Revenue System Starter

> Activo comercial · Fase 5
> Demo en vivo del sistema **real** (no slides). Duración objetivo: **12–15 min**.
> Requisito: instancia con datos demo cargados → ver [`07_datos_demo.md`](07_datos_demo.md).
> Regla de oro: **mostrar plata y velocidad**, no features. Cada pantalla responde
> una pregunta del dueño, no "mirá este botón".

---

## 0. Antes de empezar (preparación, 2 min antes de la llamada)

- [ ] Instancia abierta y logueada en `tudominio/admin/`.
- [ ] Datos demo cargados (leads en varias etapas, algunos ganados/perdidos con valor).
- [ ] Pestaña extra con la **landing pública** abierta (para el momento "lead nuevo").
- [ ] Moneda en CLP. Reporte con rango "Mes actual".
- [ ] Cerrar notificaciones/mails personales. Pantalla limpia.

---

## 1. Apertura (1 min) — anclar el dolor, no el software

> "Antes de mostrarte nada: ¿hoy cómo te llegan los clientes nuevos y qué pasa con
> ellos?" — _(dejar que hable; tomar 1–2 frases textuales para usarlas después)_

> "Perfecto. Te voy a mostrar exactamente dónde se te están cayendo y cómo se ve
> cuando dejan de caerse. Son 12 minutos."

---

## 2. El momento "lead nuevo" (2–3 min) — el gancho

1. En la pestaña de la **landing**, completar el formulario en vivo con datos del
   propio cliente (su nombre/empresa). Enviar.
2. Volver al **admin → Leads**: el lead **ya está ahí**, arriba de todo.
3. Mostrar el aviso por email (si está configurado) / la autorespuesta al prospecto.

> "Esto que acabás de ver toma **segundos**. Hoy, ¿cuánto tarda tu negocio en
> responderle a alguien que escribe un sábado a la noche?"

---

## 3. El embudo / CRM (2–3 min) — "dónde está cada quién"

1. Abrir el lead recién creado → mostrar el **timeline**: se registró solo la
   captura, y ya se generó una **tarea automática "Contactar lead"**.
2. Cambiar el estado a "Contactado" → mostrar cómo queda registrado.
3. Volver a la lista y mostrar el **embudo**: leads ordenados por etapa, filtros,
   "leads sin actividad".

> "Nadie se pierde. El sistema te dice **a quién le toca seguimiento hoy** — no
> depende de que alguien se acuerde."

---

## 4. Tareas y seguimiento (1–2 min)

1. Ir a **Tareas**: mostrar Vencidas / Hoy / Próximas.
2. Completar una tarea → mostrar que queda en el timeline del lead.

> "Esta es la diferencia entre 'tengo muchos contactos' y 'estoy trabajando mis
> oportunidades'."

---

## 5. El cierre con valor (2 min) — meter plata en la conversación

1. En un lead avanzado, abrir **"Valor y cierre"**, cargar un monto (ej. el ticket
   real del cliente) y marcarlo **Ganado**.
2. Mostrar cómo el ganado impacta de inmediato en los números.

> "Fijate que no estamos contando 'mensajes'. Estamos contando **pesos**."

---

## 6. Reportes — el momento decisivo (3 min) — "cuánto te deja"

Abrir **Reportes** (rango Mes actual) y leer en voz alta, señalando:
- **Leads / Ganados / Perdidos / Win rate.**
- **Revenue ganado** y **ticket promedio** (en CLP).
- **Embudo de conversión** con los % entre etapas → "acá se ve dónde se cae la plata".
- **Pipeline y forecast ponderado** → "esto es lo que razonablemente vas a cerrar".
- **Performance por fuente** → "te dice qué canal te trae plata, no solo clics".
- **Exportar CSV** en vivo → "te lo llevás a tu contador o a tu reunión de equipo".

> "Esto es lo que hoy no tenés: un número que te dice si tu marketing rinde y dónde
> mejorar. Y este reporte te llega **todos los meses sin que hagas nada**."

---

## 7. Filtros (30 s, opcional)

Cambiar rango de fecha y, si hay 2ª cuenta, filtrar por cuenta. "Lo podés ver por
período o por sucursal."

---

## 8. Cierre de la demo (1–2 min) — pedir el próximo paso

> "Lo que viste es el **Starter**: lo construimos, lo dejamos andando y **lo
> operamos nosotros**. Vos recibís los leads y el reporte. ¿Lo ves resolviendo
> {{el dolor que mencionó al inicio}}?"

- Si **sí** → "Te paso la propuesta hoy; reservamos el cupo de este mes con el
  primer pago. ¿Avanzamos?"
- Si **duda** → ir a [`09_objeciones_respuestas.md`](09_objeciones_respuestas.md).

---

## 9. Errores a evitar en la demo

- ❌ Hablar de tecnología (PHP, MySQL, migraciones). Al dueño no le importa.
- ❌ Mostrar configuración/ajustes. Solo resultado.
- ❌ Demo con base vacía. **Siempre** con datos demo poblados.
- ❌ Prometer WhatsApp/IA "que ya casi están". Son planes superiores, futuros.
- ❌ Terminar sin pedir el próximo paso.
