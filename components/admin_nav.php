<?php
/** Sidebar del admin. Requiere: $user, $view (opcional). */
$view = $view ?? '';
$is = fn(string $v) => $view === $v;
$siteName = getSetting('site_name', 'Mi Sitio');
$initial  = strtoupper(mb_substr($siteName, 0, 1)) ?: 'A';
$userInitial = strtoupper(mb_substr($user['email'] ?? '?', 0, 1));

$navLogo = (string) getSetting('logo_image', '');
$navLogoExists = $navLogo !== '' && @file_exists(__DIR__ . '/..' . $navLogo);

$activeLeads = $view === '' || $view === 'leads';
?>

<div class="admin-sidebar__backdrop" id="sidebar-backdrop" aria-hidden="true"></div>

<aside class="admin-sidebar" id="admin-sidebar">
    <a href="/admin/" class="admin-sidebar__brand<?= $navLogoExists ? ' admin-sidebar__brand--logo' : '' ?>">
        <?php if ($navLogoExists): ?>
            <img class="admin-sidebar__brand-img" src="<?= htmlspecialchars($navLogo) ?>" alt="<?= htmlspecialchars($siteName) ?>">
        <?php else: ?>
            <span class="admin-sidebar__logo"><?= htmlspecialchars($initial) ?></span>
            <span><?= htmlspecialchars($siteName) ?></span>
        <?php endif; ?>
    </a>

    <div class="admin-sidebar__section">General</div>

    <a class="admin-sidebar__link<?= $activeLeads ? ' admin-sidebar__link--active' : '' ?>" href="/admin/">
        <svg class="admin-sidebar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        <span>Leads</span>
    </a>

    <a class="admin-sidebar__link<?= $is('pages') || $is('page') ? ' admin-sidebar__link--active' : '' ?>" href="/admin/?view=pages">
        <svg class="admin-sidebar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/></svg>
        <span>Páginas</span>
    </a>

    <a class="admin-sidebar__link<?= $is('media') ? ' admin-sidebar__link--active' : '' ?>" href="/admin/?view=media">
        <svg class="admin-sidebar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
        <span>Medios</span>
    </a>

    <div class="admin-sidebar__section">Sistema</div>

    <a class="admin-sidebar__link<?= $is('users') || $is('user') ? ' admin-sidebar__link--active' : '' ?>" href="/admin/?view=users">
        <svg class="admin-sidebar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        <span>Usuarios</span>
    </a>

    <a class="admin-sidebar__link<?= $is('business') ? ' admin-sidebar__link--active' : '' ?>" href="/admin/?view=business">
        <svg class="admin-sidebar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 9h1M9 13h1M9 17h1M14 9h1M14 13h1M14 17h1"/></svg>
        <span>Negocio</span>
    </a>

    <a class="admin-sidebar__link<?= $is('mailing') ? ' admin-sidebar__link--active' : '' ?>" href="/admin/?view=mailing">
        <svg class="admin-sidebar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        <span>Mailing</span>
    </a>

    <a class="admin-sidebar__link<?= $is('settings') ? ' admin-sidebar__link--active' : '' ?>" href="/admin/?view=settings">
        <svg class="admin-sidebar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
        <span>Configuración</span>
    </a>

    <a class="admin-sidebar__link<?= $is('account') ? ' admin-sidebar__link--active' : '' ?>" href="/admin/?view=account">
        <svg class="admin-sidebar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span>Mi cuenta</span>
    </a>

    <a class="admin-sidebar__viewsite" href="/" target="_blank" rel="noopener" title="Abrir el sitio público en una nueva pestaña">
        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        <span>Ver sitio</span>
    </a>

    <div class="admin-sidebar__footer">
        <span class="admin-sidebar__avatar"><?= htmlspecialchars($userInitial) ?></span>
        <span class="admin-sidebar__user" title="<?= htmlspecialchars($user['email']) ?>"><?= htmlspecialchars($user['email']) ?></span>
        <form method="post" style="margin:0;">
            <input type="hidden" name="action" value="logout">
            <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
            <button type="submit" class="admin-sidebar__logout" title="Cerrar sesión">Salir</button>
        </form>
    </div>
</aside>

<div class="admin-mobile-bar">
    <button class="admin-mobile-toggle" type="button" id="sidebar-toggle" aria-label="Abrir menú">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        <span>Menú</span>
    </button>
    <strong style="font-size:.95rem;"><?= htmlspecialchars($siteName) ?></strong>
</div>
