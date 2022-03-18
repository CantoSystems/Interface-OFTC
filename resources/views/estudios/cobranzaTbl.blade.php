@extends('layouts.principal')
@section('content')
<div class="col">
    <div class="card">
        <div class="card-header modalPersonalizado">
            <h4>Generar Reporte</h4>
        </div>
        <div class="card-header col-12">
            <form action="{{ route('importarCobranza.showData') }}" method="GET">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-6">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <label class="info-box-text">Selecciona Estudio:</label>
                                <select name="estudioSelect" id="estudioSelect" class="custom-select">
                                    <option selected disabled>-- Selecciona una opción --</option>
                                    @foreach ($estudios as $est)
                                    <option value="{{ $est->id }}">
                                        {{ $est->descripcion }} ( {{ $est->nombretipo_ojo }} )
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-6">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <label class="info-box-text">Selecciona Status:</label>
                                <select name="statusSelect" id="statusSelect" class="custom-select">
                                    <option selected disabled>-- Selecciona una opción --</option>
                                    <option value="Escaneado">Escaneado</option>
                                    <option value="Interpretado">Interpretado</option>
                                    <option value="Transcrito">Transcrito</option>
                                    <option value="Entregado">Entregado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-6">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <button id="cargarCobranza" type="submit"
                                    class="btn btn-block btn-outline-primary btn-xs">
                                    <span class="info-box-number">Generar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <table id="genReportes" name="genReportes" class="table table-bordered table-hover">
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
@endsection