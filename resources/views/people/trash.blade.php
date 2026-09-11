@extends('layout/plantilla')

@section('titulo_pagina', 'Papelera')
@section('icono_titulo')
    <i class="fa-solid fa-trash-can text-danger" aria-hidden="true"></i>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('personas.index') }}" class="text-decoration-none"><i
                class="fa-solid fa-users me-1"></i> People</a></li>
    <li class="breadcrumb-item active" aria-current="page">Papelera</li>
@endsection

@section('styles_extra')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css"
        integrity="sha384-760jVcHKEQ7zpIFhZXFECFibxtsaQSxVvecxbyuYKJI9zvZCZdEVfpjHmL/pNq9K" crossorigin="anonymous">
@endsection

@section('scripts_extra')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('js/main.js') }}?v={{ filemtime(public_path('js/main.js')) }}"></script>
@endsection

@section('contenido')
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h2 class="h5 mb-0 text-danger fw-bold">
                <i class="fa-solid fa-trash-can me-2" aria-hidden="true"></i>Registros Eliminados
            </h2>
            <a href="{{ route('personas.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                <i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i> Volver a la lista
            </a>
        </div>
        <div class="card-body">
            @if ($mensaje = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2" aria-hidden="true"></i> {{ $mensaje }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            <table class="table table-hover table-bordered" id="tabla-papelera">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Apellido Paterno</th>
                        <th>Apellido Materno</th>
                        <th>Nombre</th>
                        <th>F. Nacimiento</th>
                        <th>Eliminado el</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php $contador = 1; @endphp
                    @foreach ($datos as $item)
                        <tr>
                            <td class="text-center">{{ $contador++ }}</td>
                            <td>{{ $item->paterno }}</td>
                            <td>{{ $item->materno }}</td>
                            <td>{{ $item->nombre }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->fecha_nacimiento)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->deleted_at)->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Acciones">
                                    <button type="button" class="btn btn-sm btn-outline-success shadow-sm btn-restore"
                                        data-nombre="{{ $item->nombre }} {{ $item->paterno }}"
                                        data-url="{{ route('personas.restore', $item->id) }}" title="Restaurar"
                                        aria-label="Restaurar a {{ $item->nombre }} {{ $item->paterno }}">
                                        <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger shadow-sm btn-force-delete"
                                        data-nombre="{{ $item->nombre }} {{ $item->paterno }}"
                                        data-url="{{ route('personas.forceDelete', $item->id) }}"
                                        title="Eliminar permanentemente"
                                        aria-label="Eliminar permanentemente a {{ $item->nombre }} {{ $item->paterno }}">
                                        <i class="fa-solid fa-bomb" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
