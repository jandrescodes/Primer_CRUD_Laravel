@extends('layout/plantilla')

@section('titulo_pagina', 'Papelera')
@section('icono_titulo')
    <i class="fa-solid fa-trash-can text-danger"></i>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('personas.index') }}" class="text-decoration-none"><i
                class="fa-solid fa-users me-1"></i> People</a></li>
    <li class="breadcrumb-item active" aria-current="page">Papelera</li>
@endsection

@section('contenido')
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <h5 class="mb-0 text-danger fw-bold">
                <i class="fa-solid fa-trash-can me-2"></i>Registros Eliminados
            </h5>
            <a href="{{ route('personas.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Volver a la lista
            </a>
        </div>
        <div class="card-body">
            @if ($mensaje = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ $mensaje }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <table class="table table-hover table-bordered" id="tabla-papelera" style="visibility: hidden;">
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
                                <div class="btn-group" role="group">
                                    <a href="{{ route('personas.restore', $item->id) }}"
                                        class="btn btn-sm btn-outline-success shadow-sm btn-restore"
                                        data-nombre="{{ $item->nombre }} {{ $item->paterno }}"
                                        title="Restaurar">
                                        <i class="fa-solid fa-rotate-left"></i>
                                    </a>
                                    <button type="button"
                                        class="btn btn-sm btn-outline-danger shadow-sm btn-force-delete"
                                        data-nombre="{{ $item->nombre }} {{ $item->paterno }}"
                                        data-url="{{ route('personas.forceDelete', $item->id) }}"
                                        title="Eliminar permanentemente">
                                        <i class="fa-solid fa-bomb"></i>
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
