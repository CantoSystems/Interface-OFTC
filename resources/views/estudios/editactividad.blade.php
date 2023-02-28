@extends('layouts.principal')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                @canany(['comisiones','cobranzaReportes','optometria'])
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Información Actividad ({{ $statusCobCom->nombreActividad }})
                        </h3>
                    </div>
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </div>
                    @endif
                    <form action="{{ route('importarCobranza.updateAct') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="alert alert-danger" style="text-align: center;">
                                <h6 style="text-align: center;"><b>RECUERDA QUE
                                        ESTE PROCESO AFECTARÁ AL
                                        REGISTRO PRINCIPAL.</b></h6>
                            </div>
                            <input type="hidden" name="nombreActividad" value="{{ $statusCobCom->nombreActividad }}">
                            @if($statusCobCom->nombreActividad != 'Entregado')
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Empleado Actual:</label>
                                        @foreach($empleados as $emp)
                                        @if($statusCobCom->id_emp == $emp->id_emp)
                                        <input readonly type="text" value="{{ $emp->empleado }}" class="form-control">
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Empleado Nuevo:
                                            <strong style="color:red">*</strong>
                                        </label>
                                        <input type="hidden" id="idActividad" name="idActividad"
                                            value="{{ $statusCobCom->id }}">
                                        <input type="hidden" id="idEstudios" name="idEstudios"
                                            value="{{ $statusCobCom->idEstudios }}">
                                        <select name="empNuevo" id="empNuevo" class="custom-select combos">
                                            <option disabled selected>-- Selecciona una opción --</option>
                                            @foreach($empleados as $emp)
                                            <option value="{{ $emp->id_emp }}">
                                                {{ $emp->empleado }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="row">
                                <div class="col-3">
                                    <label>Entregado<strong style="color:red">*</strong></label>
                                    <div class="form-group">
                                        @if($statusCobCom->entregado == 'S')
                                        <div class="icheck-primary d-inline">
                                            <input checked type="radio" value="S" name="entRd" class="entSi">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="N" name="entRd" class="entNo">
                                            <label>NO</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="P" name="entRd" class="entPen">
                                            <label>PENDIENTE</label>
                                        </div>
                                        @elseif($statusCobCom->entregado == 'N')
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="S" name="entRd" class="entSi">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input checked type="radio" value="N" name="entRd" class="entNo">
                                            <label>NO</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="P" name="entRd" class="entPen">
                                            <label>PENDIENTE</label>
                                        </div>
                                        @elseif($statusCobCom->entregado == 'P')
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="S" name="entRd" class="entSi">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="N" name="entRd" class="entNo">
                                            <label>NO</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input checked type="radio" value="P" name="entRd" class="entPen">
                                            <label>PENDIENTE</label>
                                        </div>
                                        @else
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="S" name="entRd" class="entSi">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="N" name="entRd" class="entNo">
                                            <label>NO</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="P" name="entRd" class="entPen">
                                            <label>PENDIENTE</label>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>
                                            Empleado Actual:<strong style="color:red">*</strong>
                                        </label>
                                        @if($statusCobCom->id_emp == 1)
                                        <input readonly type="text" value="NO SE SELECCIONÓ EMPLEADO"
                                            class="form-control">
                                        @else
                                        @foreach($empleados as $emp)
                                        @if($statusCobCom->id_emp == $emp->id_emp)
                                        <input readonly type="text" value="{{ $emp->empleado }}" class="form-control">
                                        @endif
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label>Empleado Nuevo:<strong style="color:red">*</strong>
                                        </label>
                                        <input type="hidden" id="idActividad" name="idActividad"
                                            value="{{ $statusCobCom->id }}">
                                        <input type="hidden" id="idEstudios" name="idEstudios"
                                            value="{{ $statusCobCom->idEstudios }}">
                                        <select name="empNuevo" id="empNuevo" class="custom-select combos">
                                            <option disabled selected>-- Selecciona una opción --</option>
                                            @foreach($empleados as $emp)
                                            <option value="{{ $emp->id_emp }}">
                                                {{ $emp->empleado }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-6">
                                    <a href="{{ route('importarCobranza.show',$statusCobCom->idEstudios) }}">
                                        <button type="button" id="btnGuardar" name="btnGuardar"
                                            class="btn btn-block btn-outline-secondary btn-xs">
                                            Regresar
                                        </button>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <button type="submit" id="btnGuardar" name="btnGuardar"
                                        class="btn btn-block btn-outline-primary btn-xs">Actualizar
                                        Registro</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@elsecanany(['detalleConsumo','auxiliarCobranzaReportes','auxiliardetalleConsumo','invitado'])
<div class="alert alert-danger" role="alert">
    No cuenta con los privilegios para acceder a este módulo del sistema
</div>
@endcanany
@endsection