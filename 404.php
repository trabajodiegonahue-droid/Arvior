<?php
require __DIR__ . '/lib/bootstrap.php';
http_response_code(404);

layoutStart([
    'title'       => 'Página no encontrada (404)',
    'description' => 'La página que buscás no existe o fue movida.',
]);
?>
<main class="container" style="padding:3rem 1.2rem;text-align:center;">
    <h1 style="font-size:2.4rem;margin:0 0 .5rem;">404</h1>
    <p style="font-size:1.1rem;color:#64748b;margin:0 0 1.5rem;">No encontramos la página que buscás.</p>
    <p><a href="/" class="btn">← Volver al inicio</a></p>
</main>
<?php layoutEnd(); ?>
