(function () {
    'use strict';
    var html = document.documentElement;
    var btn = document.getElementById('btn-tema');

    function aplicarTema(tema) {
        var icono = btn ? btn.querySelector('i') : null;
        html.setAttribute('data-bs-theme', tema);
        try { localStorage.setItem('bs-theme', tema); } catch (e) { }
        if (icono) icono.className = tema === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
        if (btn) btn.setAttribute('aria-label', tema === 'dark' ? 'Cambiar a tema claro' : 'Cambiar a tema oscuro');
    }

    aplicarTema(html.getAttribute('data-bs-theme'));

    if (btn) {
        btn.addEventListener('click', function () {
            var actual = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            aplicarTema(actual);
        });
    }
})();