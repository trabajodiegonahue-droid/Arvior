<?php

function currentUser(): ?array {
    if (empty($_SESSION['user_id'])) return null;
    try {
        $stmt = getDB()->prepare('SELECT id, email, name, must_change_password FROM users WHERE id = ? AND is_active = 1');
        $stmt->execute([$_SESSION['user_id']]);
    } catch (Throwable $e) {
        // Columnas todavía no migradas en instalaciones viejas.
        $stmt = getDB()->prepare('SELECT id, email FROM users WHERE id = ? AND is_active = 1');
        $stmt->execute([$_SESSION['user_id']]);
    }
    return $stmt->fetch() ?: null;
}

/** Nombre mostrado para un usuario (name si existe, sino email). */
function userDisplayName(array $u): string {
    $n = trim((string) ($u['name'] ?? ''));
    return $n !== '' ? $n : (string) ($u['email'] ?? '');
}

function requireLogin(): void {
    if (!currentUser()) {
        header('Location: /admin/');
        exit;
    }
}

// Rate-limit por IP y por email en DB (resistente a borrado de cookies).
// Ventana: 15 min. Máx: 10 intentos por IP, 5 por email.
function loginRateLimitOk(string $email, string $ip): bool {
    $db = getDB();
    $db->exec('DELETE FROM login_attempts WHERE attempted_at < DATE_SUB(NOW(), INTERVAL 1 DAY)');

    $stmt = $db->prepare(
        'SELECT COUNT(*) FROM login_attempts
         WHERE ip_address = ? AND attempted_at > DATE_SUB(NOW(), INTERVAL 15 MINUTE)'
    );
    $stmt->execute([$ip]);
    if ((int) $stmt->fetchColumn() >= 10) return false;

    if ($email !== '') {
        $stmt = $db->prepare(
            'SELECT COUNT(*) FROM login_attempts
             WHERE email = ? AND attempted_at > DATE_SUB(NOW(), INTERVAL 15 MINUTE)'
        );
        $stmt->execute([$email]);
        if ((int) $stmt->fetchColumn() >= 5) return false;
    }
    return true;
}

function loginRateLimitRecord(string $email, string $ip): void {
    $stmt = getDB()->prepare('INSERT INTO login_attempts (ip_address, email) VALUES (?, ?)');
    $stmt->execute([$ip, $email ?: null]);
}

function loginRateLimitClear(string $email, string $ip): void {
    $stmt = getDB()->prepare('DELETE FROM login_attempts WHERE ip_address = ? OR email = ?');
    $stmt->execute([$ip, $email]);
}

function login(string $email, string $password): string {
    $ip = clientIp();
    if (!loginRateLimitOk($email, $ip)) return 'rate_limited';

    $stmt = getDB()->prepare('SELECT id, password_hash, is_active FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash']) || !(int) $user['is_active']) {
        loginRateLimitRecord($email, $ip);
        return 'invalid';
    }

    session_regenerate_id(true);
    $_SESSION['user_id']    = (int) $user['id'];
    $_SESSION['login_time'] = time();
    loginRateLimitClear($email, $ip);

    try {
        $stmt = getDB()->prepare('UPDATE users SET last_login_at = NOW() WHERE id = ?');
        $stmt->execute([(int) $user['id']]);
    } catch (Throwable $e) {
        // Columna todavía no migrada en instalaciones viejas: ignorar.
    }
    return 'ok';
}

function logout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

function changePassword(int $userId, string $current, string $new): bool {
    $stmt = getDB()->prepare('SELECT password_hash FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($current, $user['password_hash'])) {
        return false;
    }
    $hash = password_hash($new, PASSWORD_BCRYPT, ['cost' => 12]);
    try {
        $stmt = getDB()->prepare('UPDATE users SET password_hash = ?, must_change_password = 0 WHERE id = ?');
        $stmt->execute([$hash, $userId]);
    } catch (Throwable $e) {
        $stmt = getDB()->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
        $stmt->execute([$hash, $userId]);
    }
    session_regenerate_id(true);
    return true;
}

/* ========== Gestión de usuarios (admin → admin) ========== */
// El starter no tiene roles: cualquier usuario logueado es admin.
// Cualquier admin puede crear otros, resetearles la contraseña, activarlos
// o eliminarlos — con la única restricción de no operar sobre sí mismo en
// acciones destructivas.

function usersList(string $search = ''): array {
    $sql = 'SELECT id, email, name, is_active, must_change_password, last_login_at, created_at FROM users';
    $params = [];
    $search = trim($search);
    if ($search !== '') {
        $sql .= ' WHERE email LIKE ? OR name LIKE ?';
        $like = '%' . $search . '%';
        $params = [$like, $like];
    }
    $sql .= ' ORDER BY created_at ASC';
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function userGet(int $id): ?array {
    if ($id <= 0) return null;
    $stmt = getDB()->prepare('SELECT id, email, name, is_active, must_change_password, last_login_at, created_at FROM users WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

/**
 * Genera una contraseña fuerte y legible (12 chars, mezcla mayúsculas,
 * minúsculas y dígitos, sin caracteres ambiguos).
 */
function generateStrongPassword(int $len = 14): string {
    $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';
    $max = strlen($alphabet) - 1;
    $out = '';
    for ($i = 0; $i < $len; $i++) $out .= $alphabet[random_int(0, $max)];
    return $out;
}

/**
 * @return array{ok:bool, id?:int, error?:string}
 */
function userCreate(string $email, string $password, string $name = '', bool $mustChangePassword = true): array {
    $email = strtolower(trim($email));
    $name  = trim($name);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['ok' => false, 'error' => 'Email inválido.'];
    }
    if (strlen($password) < 8) {
        return ['ok' => false, 'error' => 'La contraseña debe tener al menos 8 caracteres.'];
    }
    if (mb_strlen($name) > 120) {
        return ['ok' => false, 'error' => 'El nombre no puede superar los 120 caracteres.'];
    }
    try {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = getDB()->prepare(
            'INSERT INTO users (email, name, password_hash, is_active, must_change_password)
             VALUES (?, ?, ?, 1, ?)'
        );
        $stmt->execute([$email, $name !== '' ? $name : null, $hash, $mustChangePassword ? 1 : 0]);
        return ['ok' => true, 'id' => (int) getDB()->lastInsertId()];
    } catch (PDOException $e) {
        return ['ok' => false, 'error' => 'No se pudo crear (¿email duplicado?).'];
    }
}

/**
 * Actualiza email y/o nombre de un usuario. Valida unicidad de email.
 * @return array{ok:bool, error?:string}
 */
function userUpdateProfile(int $userId, string $email, string $name): array {
    if ($userId <= 0) return ['ok' => false, 'error' => 'Usuario inválido.'];
    $email = strtolower(trim($email));
    $name  = trim($name);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['ok' => false, 'error' => 'Email inválido.'];
    }
    if (mb_strlen($name) > 120) {
        return ['ok' => false, 'error' => 'El nombre no puede superar los 120 caracteres.'];
    }
    try {
        $stmt = getDB()->prepare('UPDATE users SET email = ?, name = ? WHERE id = ?');
        $stmt->execute([$email, $name !== '' ? $name : null, $userId]);
        return ['ok' => true];
    } catch (PDOException $e) {
        return ['ok' => false, 'error' => 'No se pudo guardar (¿email duplicado?).'];
    }
}

/**
 * Reset de contraseña ejecutado por un admin sobre otro usuario.
 * No requiere la contraseña actual del destino.
 */
function adminResetPassword(int $targetUserId, string $newPassword): array {
    if (strlen($newPassword) < 8) {
        return ['ok' => false, 'error' => 'La nueva contraseña debe tener al menos 8 caracteres.'];
    }
    if (!userGet($targetUserId)) {
        return ['ok' => false, 'error' => 'Usuario no encontrado.'];
    }
    $hash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
    // El usuario destino deberá cambiarla en su próximo login.
    $stmt = getDB()->prepare('UPDATE users SET password_hash = ?, must_change_password = 1 WHERE id = ?');
    $stmt->execute([$hash, $targetUserId]);
    return ['ok' => true];
}

function userSetActive(int $userId, bool $active): bool {
    if ($userId <= 0) return false;
    $stmt = getDB()->prepare('UPDATE users SET is_active = ? WHERE id = ?');
    return $stmt->execute([$active ? 1 : 0, $userId]);
}

function userDelete(int $userId): bool {
    if ($userId <= 0) return false;
    $stmt = getDB()->prepare('DELETE FROM users WHERE id = ?');
    return $stmt->execute([$userId]);
}

/** Cantidad de usuarios activos. Útil para impedir borrar el último admin. */
function activeUserCount(): int {
    return (int) getDB()->query('SELECT COUNT(*) FROM users WHERE is_active = 1')->fetchColumn();
}
