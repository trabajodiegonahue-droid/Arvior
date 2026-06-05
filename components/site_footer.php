<?php
/** Footer público reutilizable. */
$siteName    = (string) getSetting('site_name', 'Mi Sitio');
$about       = trim((string) getSetting('footer_about', '')) ?: trim((string) getSetting('business_description', ''));
$copyright   = trim((string) getSetting('footer_copyright', ''));
if ($copyright === '') $copyright = '© ' . date('Y') . ' ' . $siteName;

$bizPhone    = trim((string) getSetting('business_phone', ''));
$bizEmail    = trim((string) getSetting('business_email', ''));
$bizAddress  = trim((string) getSetting('business_address', ''));
$bizCity     = trim((string) getSetting('business_city', ''));
$bizHours    = trim((string) getSetting('business_hours', ''));
$mapsUrl     = trim((string) getSetting('business_maps_url', ''));
$waLink      = whatsappLink((string) getSetting('business_whatsapp', ''), (string) getSetting('business_whatsapp_text', ''));

$termsUrl    = trim((string) getSetting('legal_terms_url', ''));
$privacyUrl  = trim((string) getSetting('legal_privacy_url', ''));

$socials     = socialLinks();
$pages       = publishedPagesMenu();
try { $branches = branchesActive(); } catch (Throwable $e) { $branches = []; }
?>
<footer class="site-footer">
    <div class="site-footer__inner">

        <div class="site-footer__col">
            <h4 class="site-footer__brand"><img src="/assets/logo-k.svg" alt="" width="26" height="26" aria-hidden="true"><span><?= htmlspecialchars($siteName) ?></span></h4>
            <?php if ($about): ?><p><?= htmlspecialchars($about) ?></p><?php endif; ?>
            <?php if ($socials): ?>
                <div class="site-footer__socials">
                    <?php foreach ($socials as $s): ?>
                        <a href="<?= htmlspecialchars($s['url']) ?>" target="_blank" rel="noopener" aria-label="<?= htmlspecialchars($s['label']) ?>"><?= htmlspecialchars($s['label']) ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if (function_exists('portalServices')): ?>
        <div class="site-footer__col">
            <h4>Servicios</h4>
            <ul>
                <?php foreach (portalServices() as $svc): ?>
                    <li><a href="/servicios/<?= htmlspecialchars($svc['slug']) ?>"><?= htmlspecialchars($svc['title']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if (function_exists('portalNav')): ?>
        <div class="site-footer__col">
            <h4>Navegación</h4>
            <ul>
                <?php foreach (portalNav() as $n): ?>
                    <li><a href="<?= htmlspecialchars($n['path']) ?>"><?= htmlspecialchars($n['label']) ?></a></li>
                <?php endforeach; ?>
                <li><a href="/cotizacion">Cotización</a></li>
                <?php foreach ($pages as $p): ?>
                    <li><a href="/<?= htmlspecialchars($p['slug']) ?>"><?= htmlspecialchars($p['title']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="site-footer__col">
            <h4>Contacto</h4>
            <ul>
                <?php if ($bizPhone): ?>
                    <li><a href="tel:<?= htmlspecialchars(preg_replace('/\s+/', '', $bizPhone)) ?>"><?= htmlspecialchars($bizPhone) ?></a></li>
                <?php endif; ?>
                <?php if ($waLink): ?>
                    <li><a href="<?= htmlspecialchars($waLink) ?>" target="_blank" rel="noopener">WhatsApp</a></li>
                <?php endif; ?>
                <?php if ($bizEmail): ?>
                    <li><a href="mailto:<?= htmlspecialchars($bizEmail) ?>"><?= htmlspecialchars($bizEmail) ?></a></li>
                <?php endif; ?>
                <?php if ($bizAddress || $bizCity): ?>
                    <li>
                        <?php if ($mapsUrl): ?><a href="<?= htmlspecialchars($mapsUrl) ?>" target="_blank" rel="noopener"><?php endif; ?>
                            <?= htmlspecialchars(trim($bizAddress . ($bizCity ? ', ' . $bizCity : ''))) ?>
                        <?php if ($mapsUrl): ?></a><?php endif; ?>
                    </li>
                <?php endif; ?>
                <?php if ($bizHours): ?><li><?= htmlspecialchars($bizHours) ?></li><?php endif; ?>
            </ul>
        </div>

        <?php if ($branches): ?>
        <div class="site-footer__col">
            <h4>Sucursales</h4>
            <ul>
                <?php foreach ($branches as $b): ?>
                    <li>
                        <strong><?= htmlspecialchars($b['name']) ?></strong><br>
                        <?php if (!empty($b['address']) || !empty($b['city'])): ?>
                            <span class="text-muted">
                                <?= htmlspecialchars(trim(($b['address'] ?? '') . (($b['city'] ?? '') ? ', ' . $b['city'] : ''))) ?>
                            </span><br>
                        <?php endif; ?>
                        <?php if (!empty($b['phone'])): ?>
                            <a href="tel:<?= htmlspecialchars(preg_replace('/\s+/', '', $b['phone'])) ?>"><?= htmlspecialchars($b['phone']) ?></a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

    </div>

    <div class="site-footer__bottom">
        <span><?= htmlspecialchars($copyright) ?></span>
        <?php if ($termsUrl || $privacyUrl): ?>
            <span class="site-footer__legal">
                <?php if ($termsUrl): ?><a href="<?= htmlspecialchars($termsUrl) ?>">Términos</a><?php endif; ?>
                <?php if ($termsUrl && $privacyUrl): ?> · <?php endif; ?>
                <?php if ($privacyUrl): ?><a href="<?= htmlspecialchars($privacyUrl) ?>">Privacidad</a><?php endif; ?>
            </span>
        <?php endif; ?>
    </div>
</footer>

<!-- Barra de acción fija — solo móvil (CTA siempre a la vista) -->
<div class="mobile-cta">
    <a href="/cotizacion" class="mobile-cta__primary">Solicitar cotización</a>
    <?php if ($waLink): ?>
        <a href="<?= htmlspecialchars($waLink) ?>" target="_blank" rel="noopener" class="mobile-cta__wa" aria-label="WhatsApp">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true"><path d="M20 3.5A11 11 0 0 0 3.6 18.2L2 22l3.9-1.6A11 11 0 1 0 20 3.5zm-8 18a9 9 0 0 1-4.6-1.3l-.3-.2-2.3 1 .8-2.3-.2-.3a9 9 0 1 1 6.6 3zm5-7c-.3-.1-1.7-.8-1.9-.9-.3-.1-.4-.1-.6.1-.2.2-.7.9-.9 1.1-.2.2-.3.2-.6.1-.3-.1-1.2-.4-2.3-1.4a8.6 8.6 0 0 1-1.5-2c-.2-.3 0-.4.1-.6.1-.1.3-.4.4-.5.1-.2.2-.3.3-.5 0-.2 0-.4 0-.5 0-.1-.6-1.4-.8-2-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3-.3.3-.9.9-.9 2.2 0 1.3.9 2.6 1 2.8.1.2 1.8 2.8 4.5 3.9a15 15 0 0 0 1.6.6c.7.2 1.3.2 1.7.1.5-.1 1.7-.7 2-1.4.2-.6.2-1.2.2-1.4-.1-.1-.3-.2-.6-.3z"/></svg>
        </a>
    <?php endif; ?>
</div>
