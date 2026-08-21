@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h1 class="fw-bold">Reportes</h1>
        <p class="text-muted">
            Seleccione el reporte que desea consultar.
        </p>
    </div>

    <div class="row g-4">

        {{-- Acta de apertura --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.reportes.acta_apertura') }}"
               class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Acta de Apertura</h5>
                        <p class="card-text text-muted">
                            Consultar el acta correspondiente a la apertura de la votación.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        {{-- Acta de cierre --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.reportes.acta_cierre') }}"
               class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Acta de Cierre</h5>
                        <p class="card-text text-muted">
                            Consultar el acta correspondiente al cierre de la votación.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        {{-- Acta oficial --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.reportes.acta_resultados') }}"
               class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Acta Oficial</h5>
                        <p class="card-text text-muted">
                            Consultar el acta oficial con los resultados de la elección.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        {{-- Padrón --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.reportes.padron') }}"
               class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Padrón</h5>
                        <p class="card-text text-muted">
                            Consultar el padrón de estudiantes habilitados para votar.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        {{-- Conteo cero --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.reportes.conteo_cero') }}"
               class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Conteo Cero</h5>
                        <p class="card-text text-muted">
                            Consultar el reporte de conteo inicial de votos.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        {{-- Incidentes --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.reportes.incidentes') }}"
               class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Incidentes</h5>
                        <p class="card-text text-muted">
                            Consultar los incidentes registrados durante la elección.
                        </p>
                    </div>
                </div>
            </a>
        </div>

    </div>
</div>
@endsection
