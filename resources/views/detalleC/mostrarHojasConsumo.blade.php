@extends('layouts.principal')
@section('content')
<div class="col">
    <div class="card">
        <div class="card-header modalPersonalizado">
            <h4>Histórico Hojas de Consumo</h4>
        </div>
        <div class="card-header col-12">
            @canany(['comisiones','detalleConsumo','auxiliardetalleConsumo'])
            <form action="{{ route('mostrarHojas.show') }}" method="GET">
                <div class="row">
                    <div class="col-md-2 col-sm-4 col-4">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <label class="info-box-text">Selecciona Dr.:</label>
                                <select class="form-control" name="slctDoctor" id="slctDoctor">
                                    <option selected disabled>-- Selecciona una opción --</option>
                                    <option value="TODOS">TODOS LOS DOCTORES</option>
                                    @foreach($doctores as $doc)
                                    <option value="{{ $doc->id }}">{{ $doc->doctor_titulo }} {{ $doc->doctor_nombre }}
                                        {{ $doc->doctor_apellidop }} {{ $doc->doctor_apellidom }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-6">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <label class="info-box-text">Selecciona Fecha Inicio</label>
                                <input class="form-control" type="date" name="fechaInicio" id="fechaInicio">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-6">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <label class="info-box-text">Selecciona Fecha Fin</label>
                                <input class="form-control" type="date" name="fechaFin" id="fechaFin">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-4">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <label class="info-box-text">Selecciona Status</label>
                                <select class="form-control" name="slctStatus" id="slctStatus">
                                    <option selected disabled>-- Selecciona una opción --</option>
                                    <option value="TODOS">TODAS LAS HOJAS DE CONSUMO</option>
                                    <option value="PENDIENTE">PENDIENTE DE PAGO</option>
                                    <option value="PAGADO">PAGADAS</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-6">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <button id="cargarCobranza" type="submit"
                                    class="btn btn-block btn-outline-secondary btn-xs">
                                    <span class="info-box-number">Visualizar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endcanany
                    @canany(['comisiones','detalleConsumo'])
                    <div class="col-md-2 col-sm-4 col-6">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <a id="cargarCobranza" type="button" href="{{ route('exportPDFGral.create') }}"
                                    class="btn btn-block btn-outline-secondary btn-xs">
                                    <span class="info-box-number">Generar PDF</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endcanany
            </form>
        </div>
        <div class="card-body">
            @canany(['comisiones','detalleConsumo','auxiliardetalleConsumo'])
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </div>
            @endif
            <table id="catEstudios" name="catEstudios" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="text-align: center;">Id</th>
                        <th style="text-align: center;">Fecha de Cirugía</th>
                        <th>Doctor</th>
                        <th>Paciente</th>
                        <th>Tipo de Cirugía</th>
                        <th style="text-align: center;">Importe Efectivo</th>
                        <th style="text-align: center;">Importe TPV</th>
                        <th style="text-align: center;">Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($hojasConsumo))
                    @foreach($hojasConsumo as $hojas)
                    <tr vertical-align="middle">
                        <td style="text-align: center;">{{ $hojas->id_detalle }}</td>
                        <td style="text-align: center;">{{ date('d-M-Y',strtotime($hojas->fechaElaboracion)) }}</td>
                        <td>{{ $hojas->Doctor }}</td>
                        <td>{{ $hojas->paciente }} ({{ $hojas->nombretipo_paciente }})</td>
                        <td>{{ $hojas->cirugia }}</td>
                        <td style="text-align: center;">$ {{ number_format($hojas->cantidadEfe,2) }}</td>
                        <td style="text-align: center;">$ {{ number_format($hojas->TPV,2) }}</td>
                        <td style="text-align: center;">{{ $hojas->statusHoja }}</td>
                        @canany(['comisiones','detalleConsumo','auxiliardetalleConsumo'])
                        <td>
                            <center>
                                <div class="btn-group">
                                    <div class="form-group">
                                        <a href="{{ route('exportPDF.create',$hojas->id_detalle) }}">
                                            <button type="button" class="btn btn-primary btn-sm">
                                                <i class="far fa-file-pdf"></i>
                                            </button>
                                        </a>
                                    </div>
                                    @endcanany
                                    @canany(['comisiones','detalleConsumo'])
                                    <div class="form-group">
                                        <a href="{{ route('editHojaConsumo.edit',$hojas->id_detalle) }}">
                                            <button type="button" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </center>
                        </td>
                        @endcanany
                    </tr>
                    @endforeach
                    @endif
                    @endcanany
                </tbody>
            </table>
        </div>
    </div>
</div>

@canany(['cobranzaReportes','auxiliarCobranzaReportes','invitado',''])
<div class="alert alert-danger" role="alert">
    No cuenta con los privilegios para acceder a este módulo del sistema
</div>
@endcanany
@endsection