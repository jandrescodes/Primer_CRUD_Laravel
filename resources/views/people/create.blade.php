@extends('layout/plantilla')

@section('titulo_pagina', 'Agregar Persona')
@section('icono_titulo')
    <i class="fa-solid fa-user-plus text-primary" aria-hidden="true"></i>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('personas.index') }}" class="text-decoration-none"><i
                class="fa-solid fa-users me-1"></i> People</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create</li>
@endsection

@section('contenido')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('personas.store') }}" method="POST">
                @csrf
                <div class="card mt-4 mb-4">
                    <div class="card-header bg-primary text-white">
                        <h2 class="h4 mb-0"><i class="fa-solid fa-user-plus me-2" aria-hidden="true"></i>Agregar Nueva Persona</h2>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="paterno" class="form-label fw-bold">Apellido Paterno:<span class="text-danger"
                                        aria-hidden="true"> *</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-font" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control @error('paterno') is-invalid @enderror"
                                        name="paterno" id="paterno" placeholder="Ej: Perez" value="{{ old('paterno') }}"
                                        required aria-describedby="paterno-error"
                                        @error('paterno') aria-invalid="true" @enderror>
                                    @error('paterno')
                                        <div id="paterno-error" class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="materno" class="form-label fw-bold">Apellido Materno:<span class="text-danger"
                                        aria-hidden="true"> *</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-font" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control @error('materno') is-invalid @enderror"
                                        name="materno" id="materno" placeholder="Ej: Garcia" value="{{ old('materno') }}"
                                        required aria-describedby="materno-error"
                                        @error('materno') aria-invalid="true" @enderror>
                                    @error('materno')
                                        <div id="materno-error" class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-bold">Nombre Completo:<span class="text-danger"
                                    aria-hidden="true"> *</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user" aria-hidden="true"></i></span>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    name="nombre" id="nombre" placeholder="Ej: Juan Antonio" value="{{ old('nombre') }}"
                                    required aria-describedby="nombre-error" @error('nombre') aria-invalid="true" @enderror>
                                @error('nombre')
                                    <div id="nombre-error" class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="fecha_nacimiento" class="form-label fw-bold">Fecha de Nacimiento:<span
                                    class="text-danger" aria-hidden="true"> *</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></span>
                                <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                    name="fecha_nacimiento" id="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                                    required max="{{ date('Y-m-d') }}" aria-describedby="fecha_nacimiento-error"
                                    @error('fecha_nacimiento') aria-invalid="true" @enderror>
                                @error('fecha_nacimiento')
                                    <div id="fecha_nacimiento-error" class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 col-sm-auto mb-2 mb-sm-0">
                                <a href="{{ route('personas.index') }}" class="btn btn-light border shadow-sm w-100">
                                    <i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i> Regresar
                                </a>
                            </div>
                            <div class="col-12 col-sm-auto">
                                <button type="submit" class="btn btn-primary shadow-sm w-100">
                                    <i class="fa-solid fa-save me-1" aria-hidden="true"></i> Guardar Registro
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
