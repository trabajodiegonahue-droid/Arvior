<?php /* Script de aparición al scroll (compartido por las vistas del portal). Con fallback: si no hay IntersectionObserver, muestra todo. */ ?>
<script>
(function () {
    var els = document.querySelectorAll('.reveal, .reveal-stagger');
    if (!('IntersectionObserver' in window) || !els.length) {
        els.forEach(function (e) { e.classList.add('is-in'); });
        return;
    }
    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
            if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });
    els.forEach(function (e) { io.observe(e); });
    document.querySelectorAll('.s-card').forEach(function (card) {
        card.addEventListener('pointermove', function (ev) {
            var r = card.getBoundingClientRect();
            card.style.setProperty('--mx', (ev.clientX - r.left) + 'px');
            card.style.setProperty('--my', (ev.clientY - r.top) + 'px');
        });
    });
})();
</script>
