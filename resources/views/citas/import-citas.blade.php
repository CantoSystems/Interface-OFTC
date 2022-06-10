@extends('layouts.principal')
@section('content')
<div class="col">
    <div class="card">
        <div class="card-header modalPersonalizado">
            <h4>Citas</h4>
        </div>
        <div class="card-header">
            <div class="row">
                <div class="col-md-3 col-sm-4 col-8">
                    <div class="info-box shadow">
                        <span class="info-box-icon bg-info"><i class="far fa-copy"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Importar Reporte</span>
                            <button id="cargarCobranza" type="button" class="btn btn-block btn-outline-secondary btn-xs"
                                data-toggle="modal" data-target="#exampleModal2">
                                <span class="info-box-number">Subir</span>
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
@include('citas.reporteCitas');
@endsection