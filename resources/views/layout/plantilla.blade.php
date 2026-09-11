@include('templates.header')

<div class="container mt-4">
    @sectionMissing('ocultar_titulo_pagina')
        <div class="row align-items-center mb-3">
            <div class="col-md-6">
                <h1 class="mb-0 fw-bold">
                    @yield('icono_titulo') @yield('titulo_pagina')
                </h1>
            </div>
            <div class="col-md-6 text-md-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 justify-content-md-end bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('home.index') }}" class="text-decoration-none"><i
                                    class="fa-solid fa-house me-1"></i>Inicio</a></li>
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>
        </div>
    @endif

    @yield('contenido')
</div>

@include('templates.footer')
