@extends('layouts.principal')
@section('content')
<div class="col">
    <div class="card">
        <div class="card-header modalPersonalizado">
            <h4>Calcular Comisiones</h4>
        </div>
        @canany(['comisiones','cobranzaReportes','detalleConsumo','auxiliarCobranzaReportes','auxiliardetalleConsumo'])
        <div class="card-header col-12">
            <form action="{{ route('comisiones.show') }}" method="GET">
                <div class="row">
                    <div class="col-md-2 col-sm-4 col-4">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <label class="info-box-text">Selecciona Empleado:</label>
                                <select class="form-control" name="slctEmpleado" id="slctEmpleado">
                                    <option selected disabled>-- Selecciona una opción --</option>
                                    @foreach($empleados as $emp)
                                    <option value="{{ $emp->id_emp }}">{{ $emp->empleado }} ({{ $emp->puestos_nombre }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-4">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <label class="info-box-text">Selecciona Estudio:</label>
                                <select class="form-control" name="slctEstudio" id="slctEstudio">
                                    <option selected disabled>-- Selecciona una opción --</option>
                                    <option value="TODOS">TODOS LOS ESTUDIOS</option>
                                    @foreach($estudios as $est)
                                    <option value="{{ $est->id }}">{{ $est->dscrpMedicosPro }}
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
                    <div class="col-md-2 col-sm-4 col-6">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <button id="cargarCobranza" type="submit"
                                    class="btn btn-block btn-outline-secondary btn-xs">
                                    <span class="info-box-number">Calcular</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @if(isset($comisiones) && !empty($totalComisiones))
                    <div class="col-md-2 col-sm-4 col-6">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <a id="cargarCobranza" type="button" href="{{ route('exportarComisiones.export') }}"
                                    class="btn btn-block btn-outline-secondary btn-xs">
                                    <span class="info-box-number">Generar Excel</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </form>
        </div>
        <div class="card-body">
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </div>
            @endif
            <table id="catComisionesGral" name="catComisionesGral" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Estudio</th>
                        <th>Comisión</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($comisiones) && !empty($totalComisiones))
                    @foreach($comisiones as $com)
                    <tr>
                        <td>{{ date('d-M-Y',strtotime($com->fechaEstudio)) }}</td>
                        <td>{{ $com->paciente }}</td>
                        <td>{{ $com->dscrpMedicosPro }}</td>
                        <td>$ {{ number_format($com->total,2) }}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
            <br>
            @if(isset($totalComisiones) && !empty($totalComisiones))
            <div class="row">
                <div class="col-md-9">
                </div>
                <div class="col-md-3">
                    <table class="table table-bordered table-hover">
                        <tbody>
                            <tr>
                                <td><b>Total:</b></td>
                                <td>$ {{ number_format($totalComisiones,2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@elsecanany('invitado')
<div class="alert alert-danger" role="alert">
        No cuenta con los privilegios para acceder a este módulo del sistema
</div>
@endsection