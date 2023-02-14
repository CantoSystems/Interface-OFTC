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