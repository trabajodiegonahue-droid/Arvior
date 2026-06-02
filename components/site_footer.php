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
            <h4><?= htmlspecialchars($siteName) ?></h4>
            <?php if ($about): ?><p><?= htmlspecialchars($about) ?></p><?php endif; ?>
            <?php if ($socials): ?>
                <div class="site-footer__socials">
                    <?php foreach ($socials as $s): ?>
                        <a href="<?= htmlspecialchars($s['url']) ?>" target="_blank" rel="noopener" aria-label="<?= htmlspecialchars($s['label']) ?>"><?= htmlspecialchars($s['label']) ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($pages): ?>
        <div class="site-footer__col">
            <h4>Sitio</h4>
            <ul>
                <li><a href="/">Inicio</a></li>
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
