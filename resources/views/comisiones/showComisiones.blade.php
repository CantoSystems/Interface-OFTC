@extends('layouts.principal')
@section('content')
<div class="col">
    <div class="card">
        <div class="card-header modalPersonalizado">
            <h6>Calcular Comisiones</h6>
        </div>
        @canany(['comisiones','cobranzaReportes','auxiliarCobranzaReportes','optometria'])
        <!--Roles formulario de consulta PARA CÁLCULO DE COMISIONES -->
        <form action="{{ route('comisiones.show') }}" method="GET">
            <div class="row">
                <div class="col-3">
                    <label class="info-box-text">Selecciona Empleado:</label>
                    <select class="form-control" name="slctEmpleado" id="slctEmpleado">
                        <option selected disabled>-- Selecciona una opción --</option>
                        @foreach($empleados as $emp)
                        <option value="{{ $emp->id_emp }}">
                            {{ $emp->empleado }} ({{ $emp->puestos_nombre }})
                        </option>
                        @endforeach
                        @foreach($drUtilidadInterpreta as $dr)
                        <option value="{{ $dr->id_emp }}">
                            {{ $dr->empleado }} ({{ $dr->puestos_nombre }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4">
                    <label class="info-box-text">Selecciona Estudio:</label>
                    <select class="form-control" name="slctEstudio[]" id="slctEstudio" multiple="multiple">
                        @foreach($estudios as $est)
                        <option value="{{ $est->id }}" selected>
                            {{ $est->dscrpMedicosPro }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-2">
                    <label class="info-box-text">Fecha Fin:</label>
                    <input class="form-control" type="date" name="fechaFin" id="fechaFin">
                </div>
                <div class="col-2">
                    <label>Tipo de cálculo</label>
                    <select class="form-control" name="selectCalculo" id="selectCalculo">
                        <option selected disabled>-- Selecciona una optión--</option>
                        <option value="adicionales">Cálculos Adicionales y Gastos Administrativos</option>
                        @foreach($actividades as $act)
                        <option value="{{ $act->nombreActividad }}">
                            {{ $act->nombreActividad }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-1">
                    <br>
                    <button id="cargarCobranza" type="submit" class="btn btn-block btn-outline-secondary btn-xs">
                        <span class="info-box-number">Calcular</span>
                    </button>
                </div>
            </div>
        </form>
        <!-- Fin Roles formulario de consulta PARA CÁLCULO DE COMISIONES -->
        @endcanany

        @canany(['comisiones','cobranzaReportes','auxiliarCobranzaReportes','optometria'])
        <div class="card-body">
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </div>
            @endif
            @isset($fallo)
            @foreach($fallo as $fatal)
            <div class="alert alert-danger" role="alert">
                El empleado {{ $fatal['empleado'] }} no tiene asignado una comisión
                para el estudio {{ $fatal['descripcion']}}
            </div>
            @endforeach
            @endif
            <form method="POST">
                <input type="hidden" name="_token" id="_token_" value="{{ csrf_token() }}" />
                <table id="catComisionesGral" name="catComisionesGral" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Folio</th>
                            <th>Paciente</th>
                            <th>Estudio</th>
                            <th style="width: 40px; text-align: center;">Cantidad</th>
                            <th style="width: 40px; text-align: center;">Porcentaje</th>
                            <th style="width: 40px; text-align: center;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($comisiones) && !empty($totalComisiones))
                        @foreach($comisiones as $com)
                        <tr>
                            <td>{{ date('d-M-Y',strtotime($com->fechaEstudio)) }}</td>
                            <td>
                                {{ $com->cobranza_folio ?? ''}}
                                <input type="hidden" value="{{ $com->id_status_fk }}" class="id_status">
                            </td>
                            <td>{{ strtoupper($com->paciente) }}</td>
                            <td>{{ $com->dscrpMedicosPro }}</td>
                            <td style="text-align: right;">$ {{ number_format($com->cantidad,2) }}</td>
                            <td style="text-align: right;">{{ $com->porcentaje }} %</td>
                            <td style="text-align: right;">$ {{ number_format($com->total,2) }}</td>
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
                                <tr style="font-size: 15px;">
                                    <td><b>Total:</b></td>
                                    <td style="text-align: right;"><b>$ {{ number_format($totalComisiones,2) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
        </div>
        @endcanany
    </div>
</div>

<div class="card-footer">
    <div class="row">
        <div class="col-3">
            @canany(['comisiones','cobranzaReportes'])
            <a data-target="#modal-fechaCorte" data-toggle="modal">
                <button type="button" class="btn btn-block btn-outline-info btn-xs">
                    Crear fecha corte
                </button>
            </a>
            @elsecanany(['auxiliarCobranzaReportes','optometria'])
            <button type="button" class="btn btn-block btn-outline-info btn-xs" disabled />
            Crear fecha corte
            </button>
            @endcanany
        </div>
        <div class="col-3">
            <a id="limpiarVista" type="button" href="{{ route('comisiones.index') }}"
                class="btn btn-block btn-outline-secondary btn-xs">
                <span class="info-box-number">Limpiar</span>
            </a>
        </div>
        <div class="col-3">
            <a id="generaExcel" type="button" href="{{ route('exportarComisiones.export') }}"
                class="btn btn-block btn-outline-secondary btn-xs">
                <span class="info-box-number">Generar Excel</span>
            </a>
        </div>
        <div class="col-3">
            @canany(['comisiones','cobranzaReportes'])
            @if(isset($totalComisiones) && !empty($totalComisiones))
            <button id="autorizaComisiones" type="button" class="btn btn-block btn-outline-secondary btn-xs">
                <span class="info-box-number">Autorizar</span>
            </button>
            @else
            <button type="button" class="btn btn-block btn-outline-info btn-xs" disabled />
            Autorizar
            </button>
            @endif
            @elsecanany(['auxiliarCobranzaReportes','optometria'])
            <button type="button" class="btn btn-block btn-outline-info btn-xs" disabled />
            Autorizar
            </button>
            @endcanany
        </div>
    </div>
</div>
</form>
</div>
@include('comisiones.modalFechaCorte')
<!--
<div class="alert alert-danger" role="alert">
    No cuenta con los privilegios para acceder a este módulo del sistema
</div>
-->
@endsection