@extends('layout/plantilla')

@section('titulo_pagina', 'Inicio')
@section('icono_titulo')
    <i class="fa-solid fa-house text-primary"></i>
@endsection

@section('contenido')

    {{-- Hero --}}
    <div class="p-4 p-md-5 mb-4 rounded-3 text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
        <div class="d-flex align-items-center gap-4">
            <div class="d-none d-md-block">
                <i class="fa-solid fa-database fa-4x opacity-75"></i>
            </div>
            <div>
                <h1 class="fw-bold mb-1">Primer CRUD con Laravel</h1>
                <p class="mb-3 opacity-75 fs-5">Gestión de personas — create, read, update & delete.</p>
                <a href="{{ route('personas.index') }}" class="btn btn-light fw-semibold shadow-sm">
                    <i class="fa-solid fa-users me-2"></i>Ir al módulo People
                </a>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="rounded-3 p-3 bg-primary bg-opacity-10">
                        <i class="fa-solid fa-users fa-xl text-primary"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold text-primary">{{ $totalPersonas }}</div>
                        <div class="text-muted small">Personas registradas</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="rounded-3 p-3 bg-danger bg-opacity-10">
                        <i class="fa-solid fa-trash-can fa-xl text-danger"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold text-danger">{{ $enPapelera }}</div>
                        <div class="text-muted small">En papelera</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="rounded-3 p-3 bg-success bg-opacity-10">
                        <i class="fa-solid fa-circle-check fa-xl text-success"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold text-success">{{ $totalPersonas - $enPapelera }}</div>
                        <div class="text-muted small">Registros activos</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Acciones rápidas --}}
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-bolt text-warning me-2"></i>Acciones rápidas</h6>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('personas.create') }}" class="btn btn-outline-primary text-start">
                            <i class="fa-solid fa-user-plus me-2"></i>Nueva persona
                        </a>
                        <a href="{{ route('personas.index') }}" class="btn btn-outline-secondary text-start">
                            <i class="fa-solid fa-list me-2"></i>Ver todas las personas
                        </a>
                        <a href="{{ route('personas.trash') }}" class="btn btn-outline-danger text-start">
                            <i class="fa-solid fa-trash-can me-2"></i>Ver papelera
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-circle-info text-primary me-2"></i>Sobre este proyecto
                    </h6>
                    <ul class="list-unstyled text-muted mb-0 d-flex flex-column gap-2">
                        <li><i class="fa-solid fa-check text-success me-2"></i>CRUD completo de personas</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i>Validaciones con Form Requests</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i>Soft Deletes con papelera</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i>DataTables con exportar a PDF, Excel y CSV
                        </li>
                        <li><i class="fa-solid fa-check text-success me-2"></i>SweetAlert2 para confirmaciones</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i>21 Feature Tests</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
