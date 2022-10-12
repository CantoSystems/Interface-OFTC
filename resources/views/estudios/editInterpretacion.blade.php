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
                        <h3 class="card-title">Editar Interpretación</h3>
                    </div>
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </div>
                    @endif
                    <form action="{{ route('updateInterpretacion.updateInt') }}" method="POST">
                        @csrf
                        <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Selecciona Estudio<strong style="color:red">*</strong></label>
                                    <input type="hidden" name="idIntEst" value="{{ $interpretaciones->id }}">
                                    <input type="hidden" name="folioEst" value="{{ $interpretaciones->id_cobranza_fk }}" >
                                    <select name="estudioInt" id="estudioInt" class="custom-select combos">
                                        <option disabled selected value="1">-- Selecciona una opción --</option>
                                        @foreach ($descripcionEstudios as $descripcion)
                                        @if($interpretaciones->id_estudio_fk == $descripcion->id)
                                        <option selected value="{{ $descripcion->id }}">
                                            {{ $descripcion->dscrpMedicosPro }}
                                        </option>
                                        @else
                                        <option value="{{ $descripcion->id }}">
                                            {{ $descripcion->dscrpMedicosPro }}
                                        </option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Selecciona Doctor Que Interpretó<strong style="color:red">*</strong></label>
                                    <select name="doctorInt" id="doctorInt" class="custom-select combos">
                                        <option disabled selected value="1">-- Selecciona una opción --</option>
                                        @foreach($doctorInter as $doc)
                                        @if($interpretaciones->id_doctor_fk == $doc->id)
                                        <option selected value="{{ $doc->id }}">
                                            {{ $doc->doctor_titulo }} {{ $doc->doctor_nombre }}
                                            {{ $doc->doctor_apellidop }}
                                            {{ $doc->doctor_apellidom }}
                                        </option>
                                        @else
                                        <option value="{{ $doc->id }}">
                                            {{ $doc->doctor_titulo }} {{ $doc->doctor_nombre }}
                                            {{ $doc->doctor_apellidop }}
                                            {{ $doc->doctor_apellidom }}
                                        </option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" id="btnGuardar" name="btnGuardar"
                                        class="btn btn-block btn-outline-info btn-xs">Actualizar
                                        Registro</button>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('interpretaciones.delete',$interpretaciones->id) }}">
                                        <button type="button" id="btnEliminar" name="btnEliminar"
                                            class="btn btn-block btn-outline-danger btn-xs">Eliminar
                                            Registro
                                        </button>
                                    </a>
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
