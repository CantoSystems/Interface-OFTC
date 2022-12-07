@extends('layouts.principal')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                @canany(['comisiones','cobranzaReportes'])
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Información Comisión:
                            </br>
                            <b> {{ $comision->empleado }} - {{ $comision->puestos_nombre}}
                            </br>
                                {{ $comision->dscrpMedicosPro }}</b>
                        </h3>
                    </div>
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </div>
                    @endif
                    <form action="{{ route('updtComision.update') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                @if($comision->puesto_id == 2)
                                <div class="alert alert-success" style="text-align: justify;">
                                    <h6><b>RECUERDA:</b></h6> En el caso del <b>OPTOMETRISTA</b>, el campo <b>PORCENTAJE
                                        COMISIÓN</b> es <i><b>por la realización del estudio</b></i> y el campo
                                    <b>PORCENTAJE
                                        ADICIONAL</b> es <i><b>por la transcripción</b></i> del
                                    mismo.
                                </div>
                                @endif
                                <div class="col-8">
                                    <div class="form-group">
                                        <label>Estudio
                                            <strong style="color:red">*</strong>
                                        </label>
                                        <select name="estudioGral" id="estudioGral" class="custom-select combos">
                                            <option disabled selected>-- Selecciona una opción --</option>
                                            @foreach($listEstudios as $listEstudios)
                                            @if($listEstudios->id == $comision->id_estudio_fk)
                                            <option selected value="{{ $listEstudios->id }}">
                                                {{ $listEstudios->dscrpMedicosPro }}
                                            </option>
                                            @else
                                            <option value="{{ $listEstudios->id }}">
                                                {{ $listEstudios->dscrpMedicosPro }}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label name="lblporCom">Porcentaje Comisión
                                            <strong style="color:red">*</strong>
                                        </label>
                                        <input type="number" step="0.01" value="{{ $comision->porcentajeComision }}"
                                            name="porcentajeComision" class="form-control" required>
                                        <input type="hidden" name="idComision" id="idComision"
                                            value="{{ $comision->id }}">
                                    </div>
                                </div>
                               
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Porcentaje Adicional</label>
                                        @if($comision->puesto_id == 2)
                                                <input type="number" step="0.01" value="{{ $comision->porcentajeAdicional }}"
                                                name="cantidadComision" class="form-control" required>
                                        @elseif($comision->puesto_id == 4)
                                                <input type="number" class="form-control" 
                                                value="{{ $comision->porcentajeAdicional }}" disabled>
                                        @else
                                                <input type="number" class="form-control" 
                                                value="{{ $comision->porcentajeAdicional }}" disabled>
                                        @endif
                                    </div>
                                </div>
                   

                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Porcentaje Utilidad
                                            <strong style="color:red">*</strong>
                                        </label>
                                        @if($comision->puesto_id == 4)
                                                <input type="number" step="0.01" value="{{ $comision->porcentajeUtilidad }}"
                                                id="cantidadUtilidad" name="cantidadUtilidad" class="form-control" required>
                                                <input type="hidden" name="idComision" id="idComision"
                                                value="{{ $comision->id }}">
                                        @elseif($comision->puesto_id == 2)
                                                <input type="number" value="{{ $comision->porcentajeUtilidad}}" class="form-control" disabled>
                                        @else        
                                                <input type="number" value="{{ $comision->porcentajeUtilidad}}" class="form-control" disabled>
                                        @endif
                                                
                                         
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-4">
                                    <a href="{{ route('mostrarComisiones.index')}}">
                                            <button type="button" id="btnGuardar" name="btnGuardar"
                                                class="btn btn-block btn-outline-secondary btn-xs">
                                                Regresar
                                            </button>
                                    </a>
                                </div>
                                <div class="col-4">
                                    <button type="submit" id="btnGuardar" name="btnGuardar"
                                        class="btn btn-block btn-outline-info btn-xs">Actualizar
                                        Registro</button>
                                </div>
                                <div class="col-4">
                                    <button type="button" id="btnEliminar" name="btnEliminar"
                                        class="btn btn-block btn-outline-danger btn-xs" data-target="#eliminar-comision"
                                        data-toggle="modal">Eliminar
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
@include('catalogos.comisiones.modaldelete')
@elsecanany(['detalleConsumo','auxiliarCobranzaReportes','auxiliardetalleConsumo','invitado'])
<div class="alert alert-danger" role="alert">
    No cuenta con los privilegios para acceder a este módulo del sistema.
</div>
@endcanany
@endsection