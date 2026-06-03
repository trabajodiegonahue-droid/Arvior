<?php

/**
 * Cuentas (multi-cuenta por columna) — ARVIOR Core Fase 1.
 *
 * Una cuenta representa a un cliente cuyo formulario/landing captura leads.
 * El `public_token` es el secreto opaco que la landing externa envía al intake
 * público (intake.php). Nunca se expone en logs ni en el front del cliente
 * más allá del propio formulario.
 *
 * Todas las funciones son defensivas ante esquema viejo (la tabla `accounts`
 * podría no existir si todavía no se corrieron las migraciones desde /admin/):
 * devuelven null/[] en vez de lanzar fatal. Esto sostiene la decisión R1 del
 * plan: el endpoint público no rompe aunque el admin no haya migrado aún.
 */

const ACCOUNT_INTERNAL_SLUG = 'arvior';

/** Genera un public_token opaco e impredecible para una cuenta nueva. */
function accountGenerateToken(): string {
    return bin2hex(random_bytes(24)); // 48 hex chars
}

/**
 * Resuelve una cuenta ACTIVA por su public_token.
 * @return array|null La fila de la cuenta, o null si no existe / no está activa
 *                    / el esquema todavía no está migrado.
 */
function accountResolveByToken(string $token): ?array {
    $token = trim($token);
    if ($token === '') return null;
    try {
        $stmt = getDB()->prepare(
            "SELECT * FROM accounts WHERE public_token = ? AND status = 'active' LIMIT 1"
        );
        $stmt->execute([$token]);
        return $stmt->fetch() ?: null;
    } catch (Throwable $e) {
        error_log('accountResolveByToken: ' . $e->getMessage());
        return null;
    }
}

/** Devuelve la cuenta interna de ARVIOR (a la que van los leads del sitio propio). */
function accountInternal(): ?array {
    try {
        $stmt = getDB()->prepare('SELECT * FROM accounts WHERE slug = ? LIMIT 1');
        $stmt->execute([ACCOUNT_INTERNAL_SLUG]);
        return $stmt->fetch() ?: null;
    } catch (Throwable $e) {
        error_log('accountInternal: ' . $e->getMessage());
        return null;
    }
}

/** id de la cuenta interna, o null si no está disponible. */
function accountInternalId(): ?int {
    $acc = accountInternal();
    return $acc ? (int) $acc['id'] : null;
}

function accountGet(int $id): ?array {
    if ($id <= 0) return null;
    $stmt = getDB()->prepare('SELECT * FROM accounts WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

/** @return array<int,array> Todas las cuentas, internas y de clientes. */
function accountsAll(): array {
    try {
        return getDB()->query(
            'SELECT * FROM accounts ORDER BY (slug = ' . getDB()->quote(ACCOUNT_INTERNAL_SLUG) . ') DESC, name ASC'
        )->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}

/**
 * Crea una cuenta de cliente. Genera slug único y public_token aleatorio.
 * @return array{ok:bool, id?:int, error?:string}
 */
function accountCreate(string $name, ?string $plan = null): array {
    $name = trim($name);
    if ($name === '') return ['ok' => false, 'error' => 'El nombre es requerido.'];

    $base = slugify($name) ?: 'cuenta';
    $db   = getDB();

    // slug único: agrega sufijo numérico si choca.
    $slug = $base;
    $i = 2;
    while (true) {
        $stmt = $db->prepare('SELECT COUNT(*) FROM accounts WHERE slug = ?');
        $stmt->execute([$slug]);
        if ((int) $stmt->fetchColumn() === 0) break;
        $slug = $base . '-' . $i++;
        if ($i > 1000) return ['ok' => false, 'error' => 'No se pudo generar un slug único.'];
    }

    // public_token único (colisión astronómicamente improbable, pero validamos).
    $token = accountGenerateToken();
    for ($t = 0; $t < 5; $t++) {
        $stmt = $db->prepare('SELECT COUNT(*) FROM accounts WHERE public_token = ?');
        $stmt->execute([$token]);
        if ((int) $stmt->fetchColumn() === 0) break;
        $token = accountGenerateToken();
    }

    try {
        $stmt = $db->prepare(
            'INSERT INTO accounts (name, slug, public_token, status, plan) VALUES (?, ?, ?, "active", ?)'
        );
        $stmt->execute([$name, $slug, $token, ($plan !== null && $plan !== '') ? $plan : null]);
        return ['ok' => true, 'id' => (int) $db->lastInsertId()];
    } catch (PDOException $e) {
        return ['ok' => false, 'error' => 'No se pudo crear la cuenta.'];
    }
}

/** Actualiza nombre/plan de una cuenta. @return array{ok:bool, error?:string} */
function accountUpdate(int $id, string $name, ?string $plan = null): array {
    if ($id <= 0) return ['ok' => false, 'error' => 'Cuenta inválida.'];
    $name = trim($name);
    if ($name === '') return ['ok' => false, 'error' => 'El nombre es requerido.'];
    $stmt = getDB()->prepare('UPDATE accounts SET name = ?, plan = ? WHERE id = ?');
    $stmt->execute([$name, ($plan !== null && $plan !== '') ? $plan : null, $id]);
    return ['ok' => true];
}

/** Cambia el estado de una cuenta (active|paused|archived). */
function accountSetStatus(int $id, string $status): bool {
    if (!in_array($status, ['active', 'paused', 'archived'], true)) return false;
    $stmt = getDB()->prepare('UPDATE accounts SET status = ? WHERE id = ?');
    return $stmt->execute([$status, $id]);
}

/** Regenera el public_token (invalida el formulario anterior del cliente). */
function accountRegenerateToken(int $id): ?string {
    if ($id <= 0) return null;
    $token = accountGenerateToken();
    $stmt = getDB()->prepare('UPDATE accounts SET public_token = ? WHERE id = ?');
    $stmt->execute([$token, $id]);
    return $token;
}
