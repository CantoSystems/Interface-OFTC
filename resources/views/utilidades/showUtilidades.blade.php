@extends('layouts.principal')
@section('content')
<div class="col">
    <div class="card">
        @canany(['comisiones','cobranzaReportes','auxiliarCobranzaReportes','optometria'])
        <div class="card-header modalPersonalizado">
            <h4> Calcular Utilidades </h4>
            <h6 class="alert alert-warning" role="alert" style="color: white;border-color: yellow;border: 1px;">Fecha
                vigente de corte:
                @isset($fechaCorte)
                {{ date('d-m-Y',strtotime($fechaCorte->fechaCorte)); }}
                @endisset
                @empty($fechaCorte)
                Registrar fecha de corte
                @endempty
            </h6>
        </div>
        <!--Roles formulario de consulta PARA CÁLCULO DE COMISIONES -->
        <form action="{{ route('utilidades.calcular') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <label class="info-box-text">Selecciona Empleado:</label>
                        <select class="form-control" name="slctEmpleado" id="slctEmpleado">
                            <option selected disabled>-- Selecciona una opción --</option>
                            @foreach($drUtilidadInterpreta as $dr)
                            <option value="{{ $dr->id_emp }}">
                                {{ $dr->empleado }} ({{ $dr->puestos_nombre }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="info-box-text">Selecciona Estudio:</label>
                        <select class="form-control" name="slctEstudio[]" id="slctEstudio" multiple="multiple">
                            @foreach($estudios as $est)
                            <option value="{{ $est->id }}" selected>
                                {{ $est->dscrpMedicosPro }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <!--<div class="col-2">
                        <label class="info-box-text">Fecha Fin:</label>
                        <input class="form-control" type="date" name="fechaFin" id="fechaFin">
                    </div>-->
                    <div class="col-2">
                        <br>
                        <button id="cargarCobranza" type="submit" class="btn btn-block btn-outline-secondary btn-xs">
                            <span class="info-box-number">Calcular</span>
                        </button>
                    </div>
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
            @endisset
            @isset($result)
            @foreach($result as $result)
            <div class="alert alert-danger" role="alert">
                El folio {{ $result->folio }} asignado al estudio {{ $result->estudios}} no cuenta con todas las
                actividades autorizadas
            </div>
            @endforeach
            @endisset
            <form method="POST">
                <input type="hidden" name="_token" id="_token_" value="{{ csrf_token() }}" />
                <table id="catUtilidadesGral" name="catComisionesGral" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Folio</th>
                            <th>Estudio</th>
                            <th style="width: 40px; text-align: center;">Cantidad</th>
                            <th style="width: 40px; text-align: center;">Suma Actividades</th>
                            <th style="width: 40px; text-align: center;">Cantidad Restante</th>
                            <th style="width: 40px; text-align: center;">Porcentaje</th>
                            <th style="width: 40px; text-align: center;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($utilidadesDoctores) && !empty($totalComisionesUtilidades))
                        @foreach($utilidadesDoctores as $utilidad)
                        <tr>
                            <td>{{ date('d-M-Y',strtotime($utilidad->fechaEstudio)) }}</td>
                            <td>
                                {{ $utilidad->cobranza_folio ?? ''}}
                                <input type="hidden" value="{{ $utilidad->id_status_fk }}" class="id_status">
                            </td>
                            <td>{{ $utilidad->dscrpMedicosPro }}</td>
                            <td style="text-align: right;">$ {{ number_format($utilidad->cantidad,2) }}</td>
                            <td style="text-align: right;">$ {{ number_format($utilidad->totalsum_actividades,2) }}</td>
                            <td style="text-align: right;">$ {{ number_format($utilidad->restanteUtilidad,2) }}</td>
                            <td style="text-align: right;">{{ number_format($utilidad->porcentaje,2) }} %</td>
                            <td style="text-align: right;">$ {{ number_format($utilidad->total,2) }}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                <br>
                @if(isset($utilidadesDoctores) && !empty($totalComisionesUtilidades))
                <div class="row">
                    <div class="col-md-9">
                    </div>
                    <div class="col-md-3">
                        <table class="table table-bordered table-hover">
                            <tbody>
                                <tr style="font-size: 15px;">
                                    <td><b>Total:</b></td>
                                    <td style="text-align: right;"><b>$
                                            {{ number_format($totalComisionesUtilidades,2) }}</b></td>
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
        <div class="col-12 alert alert-success" id="autorizacionExitosa" role="alert"
            style="color: white;text-align:center;">
        </div>
        <div class="col-4">
            <a id="limpiarVista" type="button" href="{{ route('utilidades.index') }}"
                class="btn btn-block btn-outline-secondary btn-xs">
                <span class="info-box-number">Limpiar</span>
            </a>
        </div>
        <div class="col-4">
            @if(isset($utilidadesDoctores) && !empty($totalComisionesUtilidades) && !empty($fechaCorte))
            <a id="generaExcel" type="button" href="{{ route('exportarUtilidades.export') }}"
                class="btn btn-block btn-outline-secondary btn-xs">
                <span class="info-box-number">Generar Excel</span>
            </a>
             @else
            <button type="button" class="btn btn-block btn-outline-info btn-xs" disabled />
            Generar Excel
            </button>
            @endif
        </div>
        <div class="col-4">
            @canany(['comisiones','cobranzaReportes'])
            @if(isset($utilidadesDoctores) && !empty($totalComisionesUtilidades) && !empty($fechaCorte))
            <button id="autorizaUtilidades" type="button" class="btn btn-block btn-outline-secondary btn-xs">
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
@include('utilidades.modalFechaCorteU')
<!--
<div class="alert alert-danger" role="alert">
    No cuenta con los privilegios para acceder a este módulo del sistema
</div>
-->
@endsection