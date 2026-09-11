@extends('layout/plantilla')

@section('titulo_pagina', 'Gestión de Personas')
@section('icono_titulo')
    <i class="fa-solid fa-users text-primary" aria-hidden="true"></i>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">People</li>
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

    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"
        integrity="sha384-POmQLDS+gc6Qd3ZalIVuutsaZQkTDU8foo3J6egQy4YmrNhxcGuIlKXsas5XaZNG" crossorigin="anonymous">
    </script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"
        integrity="sha384-R/1aieuV27Trs+cczprs/2SFjCwdqKQo/4qYM0SBHv/wla+LSTHWds8FCw6s51Bb" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"
        integrity="sha384-+mbV2IY1Zk/X1p/nWllGySJSUN8uMs+gUAN10Or95UBH0fpj6GfKgPmgC5EXieXG" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"
        integrity="sha384-VFQrHzqBh5qiJIU0uGU5CIW3+OWpdGGJM9LBnGbuIH2mkICcFZ7lPd/AAtI7SNf7" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"
        integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"
        integrity="sha384-HTrhO6m5QbXzKQm/28HT6tYIdf/GeCK8xucgDpUbyrlqDwVh5AN5il4gcRxyIF51" crossorigin="anonymous">
    </script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"
        integrity="sha384-m1bkDRTx51kIl1OC2S24Tnj0vEwit7u2Be60VaV392LbUhm9qrm5GBlraC3S2U/l" crossorigin="anonymous">
    </script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"
        integrity="sha384-h/SRPFzc2+BE+XfOqlAqiHb43fnY8jzXhQ0fI1JBfgrjbxUokMr9To2eLbSWEt1g" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('js/main.js') }}?v={{ filemtime(public_path('js/main.js')) }}"></script>
@endsection

@section('contenido')
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h2 class="h5 mb-0 text-primary fw-bold">
                <i class="fa-solid fa-list-check me-2" aria-hidden="true"></i>Lista de Personas
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('personas.trash') }}" class="btn btn-outline-danger btn-sm shadow-sm">
                    <i class="fa-solid fa-trash-can me-1" aria-hidden="true"></i> Papelera
                </a>
                <a href="{{ route('personas.create') }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="fa-solid fa-user-plus me-1" aria-hidden="true"></i> Nueva Persona
                </a>
            </div>
        </div>
        <div class="card-body">
            @if ($mensaje = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2" aria-hidden="true"></i> {{ $mensaje }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            <table class="table table-hover table-bordered" id="tabla-personas">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Apellido Paterno</th>
                        <th>Apellido Materno</th>
                        <th>Nombre</th>
                        <th>F. Nacimiento</th>
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
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Acciones">
                                    <a href="{{ route('personas.edit', $item->id) }}"
                                        class="btn btn-sm btn-outline-warning shadow-sm" title="Editar"
                                        aria-label="Editar a {{ $item->nombre }} {{ $item->paterno }}">
                                        <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger shadow-sm btn-delete"
                                        data-nombre="{{ $item->nombre }} {{ $item->paterno }}"
                                        data-url="{{ route('personas.destroy', $item->id) }}" title="Eliminar"
                                        aria-label="Eliminar a {{ $item->nombre }} {{ $item->paterno }}">
                                        <i class="fa-solid fa-trash" aria-hidden="true"></i>
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
