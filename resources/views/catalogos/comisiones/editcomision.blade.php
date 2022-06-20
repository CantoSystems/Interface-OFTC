@extends('layouts.principal')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Información Comisión:
                            <b>{{ $comision->empleado }} - {{ $comision->dscrpMedicosPro }}</b>
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
                        <div class="card-body" style="text-align: center;">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Estudio</label>
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
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Cantidad</label>
                                        <input type="number" step="0.01" value="{{ $comision->cantidad }}"
                                            id="cantidadComision" name="cantidadComision" class="form-control">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Porcentaje</label>
                                        <input type="number" step="0.01" value="{{ $comision->porcentaje }}"
                                            id="porcentajeComision" name="porcentajeComision" class="form-control">
                                        <input type="hidden" name="idComision" id="idComision"
                                            value="{{ $comision->id }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" id="btnGuardar" name="btnGuardar"
                                        class="btn btn-block btn-outline-info btn-xs">Guardar
                                        Registro</button>
                                </div>
                                <div class="col-6">
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
@endsection