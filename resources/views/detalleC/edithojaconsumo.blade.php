@extends('layouts.principal')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Información Hoja Consumo</h3>
                    </div>
                    @canany(['comisiones','detalleConsumo'])
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </div>
                    @endif
                    <form action="{{ route('updtHoja.edit') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Fecha de Cirugía</label>
                                        <input type="date" value="{{ $data->fechaElaboracion }}" class="form-control"
                                            id="fechaHoja" name="fechaHoja">
                                        <input type="hidden" value="{{ $data->id }}" name="idHoja" id="idHoja">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Seleccionar Doctor</label>
                                        <select class="custom-select rounded-0 combos" id="doctorHoja"
                                            name="doctorHoja">
                                            <option disabled selected>-- Seleccionar una opción --</option>
                                            @foreach($doctores as $doc)
                                            @if($doc->id == $data->id_doctor_fk)
                                            <option selected value="{{ $doc->id }}">{{ $doc->doctor_titulo }}
                                                {{ $doc->doctor_nombre }} {{ $doc->doctor_apellidop }}
                                                {{ $doc->doctor_apellidom }}
                                            </option>
                                            @else
                                            <option value="{{ $doc->id }}">
                                                {{ $doc->doctor_nombre }} {{ $doc->doctor_apellidop }}
                                                {{ $doc->doctor_apellidom }}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Tipo de Cirugía</label>
                                        <input class="form-control" value="{{ $data->cirugia }}" type="text"
                                            name="cirugia" id="cirugia">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Nombre Paciente</label>
                                        <input type="text" value="{{ $data->paciente }}" class="form-control"
                                            id="pacienteHoja" name="pacienteHoja">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Tipo Paciente</label>
                                        <select class="custom-select rounded-0 combos" id="tipoPacienteHoja"
                                            name="tipoPacienteHoja">
                                            <option disabled selected>-- Seleccionar una opción --</option>
                                            @foreach($tipoPaciente as $tipoP)
                                            @if($tipoP->id == $data->tipoPaciente)
                                            <option selected value="{{ $tipoP->id }}">
                                                {{ $tipoP->nombretipo_paciente }}
                                            </option>
                                            @else
                                            <option value="{{ $tipoP->id }}">
                                                {{ $tipoP->nombretipo_paciente }}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Status Hoja de Consumo</label>
                                        <select class="custom-select rounded-0 combos" id="statusHoja"
                                            name="statusHoja">
                                            <option disabled selected>-- Seleccionar una opción --</option>
                                            @if($data->statusHoja == 'Pendiente')
                                            <option selected value="Pendiente">
                                                Pendiente
                                            </option>
                                            <option value="Pagado">
                                                Pagado
                                            </option>
                                            @else
                                            <option value="Pendiente">
                                                Pendiente
                                            </option>
                                            <option selected value="Pagado">
                                                Pagado
                                            </option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group" style="text-align: center;">
                                    <label>¿La cirugía es especial?</label>
                                    <div class="form-group">
                                        @if($data->tipoCirugia == "S")
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="S" checked name="registroC">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="N" name="registroC">
                                            <label>NO</label>
                                        </div>
                                        @else
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="S" name="registroC">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="N" checked name="registroC">
                                            <label>NO</label>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-4">
                                    <a href="{{ route('viewHojas.show')}}">
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
                                    <button type="button" data-target="#eliminar-hoja" data-toggle="modal"
                                        id="btnGuardar" name="btnGuardar"
                                        class="btn btn-block btn-outline-danger btn-xs">Eliminar
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
@include('detalleC.modaldeletehoja')
@elsecanany(['cobranzaReportes','auxiliarCobranzaReportes','auxiliardetalleConsumo','invitado'])
<div class="alert alert-danger" role="alert">
    No cuenta con los privilegios para acceder a este módulo del sistema
</div>
@endcanany
@endsection