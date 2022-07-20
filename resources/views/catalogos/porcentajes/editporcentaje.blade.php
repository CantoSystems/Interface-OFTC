@extends('layouts.principal')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Información Porcentaje:
                            <b>{{ $porcentajeInfo->Doctor }}</b>
                        </h3>
                    </div>
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </div>
                    @endif
                    <form action="{{ route('updtPorcentaje.update') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Doctor</label>
                                        <select name="doctorId" id="doctorId" class="custom-select combos">
                                            <option disabled selected>-- Selecciona una opción --</option>
                                            @foreach($catDoctores as $catDoctores)
                                            @if($catDoctores->id == $porcentajeInfo->id_doctor_fk)
                                            <option selected value="{{ $catDoctores->id }}">
                                                {{ $catDoctores->Doctor }}
                                            </option>
                                            @else
                                            <option value="{{ $catDoctores->id }}">
                                                {{ $catDoctores->Doctor }}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Método Pago</label>
                                        <select name="metodoPago" id="metodoPago" class="custom-select combos">
                                            <option disabled selected>-- Selecciona una opción --</option>
                                            @foreach($catMetodoPago as $catMetodoPago)
                                            @if($catMetodoPago->id == $porcentajeInfo->id_metodoPago_fk)
                                            <option selected value="{{ $catMetodoPago->id }}">
                                                {{ $catMetodoPago->descripcion }}
                                            </option>
                                            @else
                                            <option value="{{ $catMetodoPago->id }}">
                                                {{ $catMetodoPago->descripcion }}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Tipo Paciente</label>
                                        <select name="tipoPaciente" id="tipoPaciente" class="custom-select combos">
                                            <option disabled selected>-- Selecciona una opción --</option>
                                            @foreach($catTipoPaciente as $catTipoPaciente)
                                            @if($catTipoPaciente->id == $porcentajeInfo->id_tipoPaciente_fk)
                                            <option selected value="{{ $catTipoPaciente->id }}">
                                                {{ $catTipoPaciente->nombretipo_paciente }}
                                            </option>
                                            @else
                                            <option value="{{ $catTipoPaciente->id }}">
                                                {{ $catTipoPaciente->nombretipo_paciente }}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Porcentaje</label>
                                        <input type="number" step="0.01" value="{{ $porcentajeInfo->porcentaje }}"
                                            id="porcentajeDoctor" name="porcentajeDoctor" class="form-control">
                                        <input type="hidden" value="{{ $porcentajeInfo->id }}" id="idComision"
                                            name="idComision">
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
                                        class="btn btn-block btn-outline-danger btn-xs"
                                        data-target="#eliminar-porcentaje" data-toggle="modal">Eliminar
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
@include('catalogos.porcentajes.modaldelete')
@endsection