<!doctype html>
<html lang="es">

<head>
    <title>Primer CRUD Laravel — @yield('titulo_pagina', 'Inicio')</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tema aplicado antes de pintar para evitar parpadeo -->
    <script>
        (function() {
            var tema;
            try { tema = localStorage.getItem('bs-theme'); } catch (e) { }
            if (!tema) tema = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' :
                'light';
            document.documentElement.setAttribute('data-bs-theme', tema);
            window.__bsTema = tema;
        })();
    </script>

    <!-- Bootstrap CSS v5.3.8 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha384-nRgPTkuX86pH8yjPJUAFuASXQSSl2/bBUiNV47vSYpKFxHJhbcrGnmlYpYJMeD7a" crossorigin="anonymous">

    @yield('styles_extra')

    <!-- Icon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('codigo-php.png') }}">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}?v={{ filemtime(public_path('css/main.css')) }}">
</head>

<body>
    <a class="skip-link visually-hidden-focusable position-absolute top-0 start-0 p-2 bg-primary text-white text-decoration-none"
        href="#contenido">Ir al contenido principal</a>
    <header>
        <!-- place navbar here -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark" data-bs-theme="dark" aria-label="Navegación principal">
<div class="container">
                <a class="navbar-brand" href="{{ route('home.index') }}">Laravel</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home.index') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('personas.index') }}">People</a>
                        </li>
                    </ul>
                </div>
                <button id="btn-tema" class="btn btn-sm btn-outline-light ms-3" type="button"
                    aria-label="Cambiar a tema oscuro">
                    <i class="fa-solid fa-moon" aria-hidden="true"></i>
                </button>
                <script>
                    // El botón se corrige en el primer paint (antes por parse-time): evita que el
                    // header muestre la luna cuando el tema persistido ya es oscuro tras un reload.
                    (function() {
                        var tema = window.__bsTema || 'light';
                        var btn = document.getElementById('btn-tema');
                        if (!btn) return;
                        var icono = btn.querySelector('i');
                        if (icono) icono.className = tema === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
                        btn.setAttribute('aria-label', tema === 'dark' ? 'Cambiar a tema claro' : 'Cambiar a tema oscuro');
                    })();
                </script>
            </div>
        </nav>
    </header>
    <main id="contenido">
