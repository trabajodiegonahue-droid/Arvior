<?php
/**
 * Formulario de cotización / contacto del portal.
 * Postea a la página actual (action=submit_lead) → leadCreate() → CRM.
 *
 * Variables opcionales (setear antes del require):
 *   $formSource       string  origen para `source` (ej. 'cotizacion', 'servicio:seo'). Default 'website'.
 *   $returnPath       string  path al que volver tras enviar. Default: URL actual.
 *   $preselectService string  etiqueta de servicio a preseleccionar en el <select>.
 *   $showService      bool    mostrar selector de servicio (default true).
 *   $showBudget       bool    mostrar selector de presupuesto (default true).
 *   $showCompany      bool    mostrar campo empresa (default true).
 *   $sent             bool    (de index.php) muestra banner de éxito.
 *   $error            string  (de index.php) muestra banner de error.
 */
$formSource       = $formSource       ?? 'website';
$returnPath       = $returnPath       ?? (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$preselectService = $preselectService ?? '';
$showService      = $showService      ?? true;
$showBudget       = $showBudget       ?? true;
$showCompany      = $showCompany      ?? true;
$sent             = $sent  ?? false;
$error            = $error ?? '';
?>
<?php if ($sent): ?>
    <div class="alert alert--success form-alert">
        <strong>¡Gracias!</strong> Recibimos tu solicitud. Te contactamos a la brevedad, dentro de las próximas 24 horas hábiles.
    </div>
<?php endif; ?>
<?php if ($error !== ''): ?>
    <div class="alert alert--error form-alert"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" class="lead-form lead-form--grid">
    <input type="hidden" name="action" value="submit_lead">
    <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
    <input type="hidden" name="form_started" value="<?= time() ?>">
    <input type="hidden" name="source" value="<?= htmlspecialchars($formSource) ?>">
    <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($returnPath) ?>">
    <input type="text" name="website" value="" class="lead-form__hp" tabindex="-1" autocomplete="off" aria-hidden="true">

    <p class="lead-form__field">
        <label>Nombre <span class="req">*</span><input name="name" required placeholder="Tu nombre"></label>
    </p>
    <?php if ($showCompany): ?>
    <p class="lead-form__field">
        <label>Empresa <input name="company" placeholder="Nombre de tu empresa"></label>
    </p>
    <?php endif; ?>
    <p class="lead-form__field">
        <label>Teléfono <input name="phone" placeholder="+56 9 ..."></label>
    </p>
    <p class="lead-form__field">
        <label>Correo <span class="req">*</span><input name="email" type="email" required placeholder="tu@empresa.cl"></label>
    </p>
    <?php if ($showService): ?>
    <p class="lead-form__field">
        <label>¿Qué necesitas?
            <select name="service">
                <option value="">Selecciona un servicio</option>
                <?php foreach (portalServiceOptions() as $opt): ?>
                    <option value="<?= htmlspecialchars($opt) ?>" <?= $preselectService === $opt ? 'selected' : '' ?>><?= htmlspecialchars($opt) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </p>
    <?php endif; ?>
    <?php if ($showBudget): ?>
    <p class="lead-form__field">
        <label>Presupuesto aproximado
            <select name="budget">
                <option value="">Prefiero que me orienten</option>
                <?php foreach (portalBudgetOptions() as $opt): ?>
                    <option value="<?= htmlspecialchars($opt) ?>"><?= htmlspecialchars($opt) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </p>
    <?php endif; ?>
    <p class="lead-form__field lead-form__field--full">
        <label>Cuéntanos tu proyecto <textarea name="message" rows="4" placeholder="¿Qué necesitas y para cuándo? Mientras más nos cuentes, mejor será la propuesta."></textarea></label>
    </p>
    <p class="lead-form__submit lead-form__field--full">
        <button type="submit" class="btn">Enviar solicitud</button>
    </p>
    <p class="lead-form__hint">Te respondemos en menos de 24 horas hábiles. Tus datos solo se usan para contactarte por tu solicitud.</p>
</form>
