<?php
/** Requiere: $projects (array de portfolio_projects) */
$catLabels = portfolioCategories();
?>
<header class="admin-header">
    <div>
        <h1>Portafolio</h1>
        <div class="admin-header__sub">Tus proyectos reales, agrupados por rubro, visibles en /proyectos.</div>
    </div>
    <div class="admin-header__actions">
        <a class="btn" href="/admin/?view=project&amp;id=new">+ Nuevo proyecto</a>
    </div>
</header>

<?php if ($msg = flashGet('project_success')): ?>
    <div class="auth-alert auth-alert--success"><span><?= htmlspecialchars($msg) ?></span></div>
<?php endif; ?>

<?php if (empty($projects)): ?>
    <div class="empty">
        <h3>Sin proyectos</h3>
        <p>Sube tu primer proyecto para empezar a mostrar tu trabajo. Elige el rubro (landing, tienda, sitio corporativo…), agrega una captura y un par de líneas.</p>
        <p style="margin-top:1rem;"><a class="btn" href="/admin/?view=project&amp;id=new">+ Subir primer proyecto</a></p>
    </div>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th style="width:64px;"></th>
                <th>Título</th>
                <th style="width:170px;">Rubro</th>
                <th style="width:110px;">Estado</th>
                <th style="width:70px;">Orden</th>
                <th style="width:120px;"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($projects as $p): ?>
                <tr>
                    <td>
                        <?php if (!empty($p['cover_image'])): ?>
                            <img src="<?= htmlspecialchars($p['cover_image']) ?>" alt="" style="width:54px;height:36px;object-fit:cover;border-radius:6px;border:1px solid var(--color-border,#e1e6ef);">
                        <?php else: ?>
                            <span class="text-muted" style="font-size:.8rem;">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?= htmlspecialchars($p['title']) ?></strong>
                        <?php if (!empty($p['is_featured'])): ?> <span class="badge badge--qualified">destacado</span><?php endif; ?>
                        <div class="text-muted" style="font-size:.82rem;"><code><?= htmlspecialchars($p['slug']) ?></code></div>
                    </td>
                    <td><?= htmlspecialchars($catLabels[$p['category']] ?? $p['category']) ?></td>
                    <td>
                        <?php if ((int) $p['is_published']): ?>
                            <span class="badge badge--qualified">publicado</span>
                        <?php else: ?>
                            <span class="badge badge--closed">borrador</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-tabular"><?= (int) $p['sort_order'] ?></td>
                    <td>
                        <a href="/admin/?view=project&amp;id=<?= (int) $p['id'] ?>">Editar</a>
                        <?php if ((int) $p['is_published']): ?>
                             · <a href="/proyectos/<?= htmlspecialchars($p['slug']) ?>" target="_blank">Ver →</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
