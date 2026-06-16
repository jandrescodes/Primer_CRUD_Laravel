@extends('layout/plantilla')

@section('titulo_pagina', 'Gestión de Personas')
@section('icono_titulo')
    <i class="fa-solid fa-users text-primary"></i>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">People</li>
@endsection

@section('contenido')
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <h5 class="mb-0 text-primary fw-bold">
                <i class="fa-solid fa-list-check me-2"></i>Lista de Personas
            </h5>
            <div class="d-flex gap-2">
                <a href="{{ route('personas.trash') }}" class="btn btn-outline-danger btn-sm shadow-sm">
                    <i class="fa-solid fa-trash-can me-1"></i> Papelera
                </a>
                <a href="{{ route('personas.create') }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="fa-solid fa-user-plus me-1"></i> Nueva Persona
                </a>
            </div>
        </div>
        <div class="card-body">
            @if ($mensaje = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ $mensaje }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <table class="table table-hover table-bordered" id="tabla-personas" style="visibility: hidden;">
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
                                        class="btn btn-sm btn-outline-warning shadow-sm" title="Editar">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger shadow-sm btn-delete"
                                        data-nombre="{{ $item->nombre }} {{ $item->paterno }}"
                                        data-url="{{ route('personas.destroy', $item->id) }}" title="Eliminar">
                                        <i class="fa-solid fa-trash"></i>
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
