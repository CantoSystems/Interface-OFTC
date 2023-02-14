@extends('layouts.principal')
@section('content')
<div class="col">
    <div class="card">
        <div class="card-header modalPersonalizado">
            <h6>Calcular Comisiones</h6>
        </div>
        @canany(['comisiones','cobranzaReportes','auxiliarCobranzaReportes'])

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
                                @foreach($actividades as $act)
                                    <option value="{{ $act->nombreActividad }}">   
                                        {{ $act->nombreActividad }}
                                    </option>
                                @endforeach
                            <option value="adicionales">Cálculos Adicionales y Gastos Administrativos</option>
                        </select>
                    </div>
                    <div class="col-1">
                        <br>
                        <button     id="cargarCobranza" type="submit"
                                    class="btn btn-block btn-outline-secondary btn-xs">
                            <span class="info-box-number">Calcular</span>
                        </button>
                    </div>
                    
                </div>
            </form>            

        @endcanany






        <div class="card-body">
        @canany(['comisiones','cobranzaReportes','auxiliarCobranzaReportes'])
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
            <table id="catComisionesGral" name="catComisionesGral" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Estudio</th>
                        <th>Cantidad</th>
                        <th>Porcentaje</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($comisiones) && !empty($totalComisiones))
                    @foreach($comisiones as $com)
                    <tr>
                        <td>{{ date('d-M-Y',strtotime($com->fechaEstudio)) }}</td>
                        <td>{{ $com->paciente }}</td>
                        <td>{{ $com->dscrpMedicosPro }}</td>
                        <td>{{ $com->cantidad }}</td>
                        <td>{{ $com->porcentaje }} %</td>
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


                        <div class="card-footer">
                            <div class="row">
                                <div class="col-3">
                                    <br>
                                </div>
                                <div class="col-3">
                                        <a  id="cargarCobranza" type="button" 
                                            href="{{ route('comisiones.index') }}"
                                            class="btn btn-block btn-outline-secondary btn-xs">
                                                <span class="info-box-number">Limpiar</span>
                                         </a>
                                </div>
                                <div class="col-3">
                                        <a  id="cargarCobranza" type="button" 
                                            href="{{ route('exportarComisiones.export') }}"
                                            class="btn btn-block btn-outline-secondary btn-xs">
                                                <span class="info-box-number">Generar Excel</span>
                                         </a>
                                </div>
                                <div class="col-3">
                                        <a  id="cargarCobranza" type="button" 
                                            href="#"
                                            class="btn btn-block btn-outline-secondary btn-xs">
                                                <span class="info-box-number">Autorizar</span>
                                         </a>
                                    </div>
                                </div>
                            </div>
                        </div>


@elsecanany('invitado','detalleConsumo','auxiliardetalleConsumo')
<div class="alert alert-danger" role="alert">
        No cuenta con los privilegios para acceder a este módulo del sistema
</div>
@endcanany
@endsection