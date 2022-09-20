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
                        <h3 class="card-title">Información Estudio: <b>{{ $estudio->dscrpMedicosPro }}</b></h3>
                    </div>
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </div>
                    @endif
                    <form action="{{ route('updateEstudio.update') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Estudio General
                                            <strong style="color:red">*</strong>
                                        </label>
                                        <select name="estudioGral" id="estudioGral" class="custom-select combos">
                                            <option disabled selected>-- Selecciona una opción --</option>
                                            @foreach($catEstudios as $catEstudios)
                                            @if($catEstudios->id == $estudio->id_estudio_fk)
                                            <option selected value="{{ $catEstudios->id }}">
                                                {{ $catEstudios->descripcion }}
                                            </option>
                                            @else
                                            <option value="{{ $catEstudios->id }}">
                                                {{ $catEstudios->descripcion }}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Tipo de Ojo
                                            <strong style="color:red">*</strong>
                                        </label>
                                        <select name="tipoOjo" id="tipoOjo" class="custom-select combos">
                                            <option disabled selected>-- Selecciona una opción --</option>
                                            @foreach($catOjos as $catOjos)
                                            @if($catOjos->id == $estudio->id_ojo_fk)
                                            <option selected value="{{ $catOjos->id }}">
                                                {{ $catOjos->nombretipo_ojo }}
                                            </option>
                                            @else
                                            <option value="{{ $catOjos->id }}">
                                                {{ $catOjos->nombretipo_ojo }}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Descripción Medicos Pro
                                            <strong style="color:red">*</strong>
                                        </label>
                                        <input type="text" id="dscrpMedicosPro" name="dscrpMedicosPro"
                                            class="form-control" value="{{ $estudio->dscrpMedicosPro }}" required
                                            onkeyup="mayus(this);">
                                    </div>
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
                                    <button type="button" id="btnEliminar" name="btnEliminar"
                                        class="btn btn-block btn-outline-danger btn-xs" data-target="#eliminar-estudio"
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
@include('catalogos.estudios.modaldelete')
@elsecanany(['detalleConsumo','auxiliarCobranzaReportes','auxiliardetalleConsumo','invitado'])
<div class="alert alert-danger" role="alert">
    No cuenta con los privilegios para acceder a este módulo del sistema
</div>
@endcanany
@endsection