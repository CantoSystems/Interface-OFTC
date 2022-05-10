@extends('layouts.principal')
@section('content')
<div class="col">
    <div class="card">
        <div class="card-header modalPersonalizado">
            <h4>Estudios</h4>
        </div>
        <div class="card-header">
            <div class="row">
                <div class="col-md-3 col-sm-4 col-8">
                    <div class="info-box shadow">
                        <span class="info-box-icon bg-info"><i class="far fa-copy"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Importar Reporte</span>
                            <button id="cargarCobranza" type="button" class="btn btn-block btn-outline-secondary btn-xs"
                                data-toggle="modal" data-target="#exampleModal">
                                <span class="info-box-number">Subir</span>
                            </button>
                        </div>

                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="info-box shadow">
                        <div class="info-box-content">
                            <span class="info-box-text">Status: Actualizado</span>
                            <button type="button" class="btn btn-block btn-outline-secondary btn-xs" data-toggle="modal"
                                data-target="#eliminar-estudiostemps">
                                <span class="info-box-number">Eliminar</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @error('file')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <div class="card-body">
            <table id="reporteCobranza" name="reporteCobranza" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Paciente</th>
                        <th>Servicio</th>
                        <th>Fecha</th>
                        <th>Status</th>
                        <th>Ver</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@include('estudios.reportecobranza');
@include('estudios.eliminar-estudiostemps');
@endsection