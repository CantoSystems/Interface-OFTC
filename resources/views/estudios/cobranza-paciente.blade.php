@extends('layouts.principal')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2"></div>
            <!--Inicio Card Información Paciente-->
            <div class="col-md-8">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Información paciente: {{ $datosPaciente->paciente }}</h3>
                    </div>
                    <form action="{{ route('importarCobranza.update') }}" method="GET">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Folio</label>
                                        <input type="text" id="folioCbr" name="folioCbr" class="form-control" value="{{ $datosPaciente->folio }}" readonly>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label>Paciente</label>
                                        <input type="text" id="pacienteCbr" name="pacienteCbr" class="form-control" value="{{ $datosPaciente->paciente }}" readonly>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Fecha</label>
                                        <input type="date" id="fchCbr" name="fchCbr" class="form-control" value="{{ $datosPaciente->fecha }}" readonly>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Cantidad</label>
                                        <input type="text" id="cantidadCbr" name="cantidadCbr" class="form-control" value="{{ $datosPaciente->total }}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Estudio</label>
                                        <input type="text" id="estudioCbr" name="estudioCbr" class="form-control" value="{{ $datosPaciente->servicio }}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Dr. Que Requiere</label>
                                        <select name="drRequiere" id="drRequiere" class="custom-select" required>
                                            <option disabled selected>-- Selecciona una opción --</option>
                                            @foreach ($doctores as $dres)
                                            <option value="{{ $dres->id }}">
                                                {{ $dres->doctor_nombre }} {{ $dres->doctor_apellidop }}
                                                {{ $dres->doctor_apellidom }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>PX INT. - EXT.</label>
                                        <select name="tipoPaciente" id="tipoPaciente" class="custom-select">
                                            <option disabled selected>-- Selecciona una opción --</option>
                                            @foreach($tipoPac as $tpaciente)
                                            <option value="{{ $tpaciente->id }}">
                                                {{ $tpaciente->nombretipo_paciente }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Forma de Pago</label>
                                        <input type="text" id="formaPago" name="formaPago" class="form-control" value="{{ $datosPaciente->met_pago }}" readonly>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <label>Transcripción</label>
                                    <div class="form-group">
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" name="transRd" id="transRdS" onChange="selectTrans()">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" name="transRd" id="transRdN" onChange="selectTrans()">
                                            <label>NO</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Quién Realiza la Transcripción</label>
                                        <select name="drTransc" id="drTransc" class="custom-select">
                                            <option disabled selected>-- Selecciona una opción --</option>
                                            @foreach($empTrans as $empT)
                                            <option value="{{ $empT->id }}">
                                                {{ $empT->empleado_nombre }} {{ $empT->empleado_apellidop }}
                                                {{ $empT->empleado_apellidom }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <label>Interpretación</label>
                                    <div class="form-group">
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" name="intRd">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" name="intRd">
                                            <label>NO</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Quién Realiza la Interpretación</label>
                                        <select name="drInterpreta" id="drInterpreta" class="custom-select">
                                            <option disabled selected>-- Selecciona una opción --</option>
                                            @foreach($doctorInter as $doc)
                                            <option value="{{ $doc->id }}">
                                                {{ $doc->doctor_nombre }} {{ $doc->doctor_apellidop }}
                                                {{ $doc->doctor_apellidom }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <label>Escaneado</label>
                                    <div class="form-group">
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" name="escRd">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" name="escRd">
                                            <label>NO</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-10">
                                    <div class="form-group">
                                        <label>Observaciones</label>
                                        <input type="text" id="obsCobranza" name="obsCobranza" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" id="btnGuardar" name="btnGuardar" class="btn btn-block btn-outline-secondary btn-xs">Guardar
                                registro</button>
                        </div>
                    </form>
                </div>
            </div>
            <!--Fin Card Información Paciente-->
            <div class="col-md-2"></div>
        </div>
    </div>
</section>
@endsection