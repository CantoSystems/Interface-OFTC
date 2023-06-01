@extends('layouts.plantillaEstudiosTemps')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!--Inicio Card Información Paciente-->
            @canany(['comisiones','cobranzaReportes','auxiliarCobranzaReportes','optometria'])
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            Información Paciente: {{ $datosPaciente->paciente }}
                        </h3>
                    </div>
                    <form action="{{ route('importarCobranza.update') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-1">
                                    <div class="form-group">
                                        <label>Folio</label>
                                        <input type="text" id="folioCbr" name="folioCbr" class="form-control"
                                            value="{{ $datosPaciente->folio }}" readonly>
                                        <input type="hidden" name="identificador" value="{{ $datosPaciente->id }}">
                                        <input type="hidden" name="countUtilidades" id="countUtilidades"
                                            value="{{ $totalStatusUtilidades }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Paciente</label>
                                        <input type="text" id="pacienteCbr" name="pacienteCbr" class="form-control"
                                            value="{{ $datosPaciente->paciente }}" readonly>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Fecha</label>
                                        <input type="date" id="fchCbr" name="fchCbr" class="form-control"
                                            value="{{ $datosPaciente->fecha }}" readonly>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <div class="form-group">
                                        <label>Cantidad</label>
                                        <input type="text" id="cantidadCbr" name="cantidadCbr" class="form-control"
                                            value="{{ $datosPaciente->total }}" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Estudio</label>
                                        <input type="text" id="estudioCbr" name="estudioCbr" class="form-control"
                                            value="{{ $datosPaciente->servicio }}" readonly>
                                        @if($datosPaciente->estudiostemps_status == 3)
                                        <select name="estudioCorregido" id="estudioCorregido"
                                            class="custom-select combos">
                                            <option selected disabled>-- Selecciona una opción --</option>
                                            @foreach ($descripcionEstudios as $descripcion)
                                            <option selected value="{{ $descripcion->dscrpMedicosPro }}">
                                                {{ $descripcion->dscrpMedicosPro }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Forma de Pago</label>
                                        <input type="text" id="formaPago" name="formaPago" class="form-control"
                                            value="{{ $datosPaciente->met_pago }}" readonly>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>PX INT. - EXT.<strong style="color:red">*</strong></label>
                                        <select name="tipoPaciente" id="tipoPaciente"
                                            class="custom-select combos chckUtilidad">
                                            <option disabled selected>-- Selecciona una opción --</option>
                                            @foreach($tipoPac as $tpaciente)
                                            @if($tpaciente->id==$datosPaciente->tipoPaciente)
                                            <option selected value="{{ $tpaciente->id }}">
                                                {{ $tpaciente->nombretipo_paciente }}
                                            </option>
                                            @else
                                            <option value="{{ $tpaciente->id }}">
                                                {{ $tpaciente->nombretipo_paciente }}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Dr. Que Requiere<strong style="color:red">*</strong></label>
                                        <select name="drRequiere" id="drRequiere"
                                            class="custom-select combos chckUtilidad">
                                            <option selected disabled>-- Selecciona una opción --</option>
                                            @foreach ($doctores as $dres)
                                            @if($dres->id==$datosPaciente->id_doctor_fk)
                                            <option selected value="{{ $dres->id }}">
                                                {{ $dres->doctor_titulo }} {{ $dres->doctor_nombre }}
                                                {{ $dres->doctor_apellidop }}
                                                {{ $dres->doctor_apellidom }}
                                            </option>
                                            @else
                                            <option value="{{ $dres->id }}">
                                                {{ $dres->doctor_titulo }} {{ $dres->doctor_nombre }}
                                                {{ $dres->doctor_apellidop }}
                                                {{ $dres->doctor_apellidom }}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>¿Quién Realizó el Estudio?<strong style="color:red">*</strong></label>
                                        <select name="empRealiza" id="empRealiza"
                                            class="custom-select combos chckUtilidad">
                                            <option disabled selected>-- Selecciona una opción --</option>
                                            @foreach($empRealiza as $empRe)
                                            @if($empRe->id_emp == $datosPaciente->id_empRea_fk)
                                            <option selected value="{{ $empRe->id_emp }}">
                                                {{ $empRe->empleado }}
                                            </option>
                                            @else
                                            <option value="{{ $empRe->id_emp }}">
                                                {{ $empRe->empleado }}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <label>Transcripción<strong style="color:red">*</strong></label>
                                    <div class="form-group">
                                        @if($datosPaciente->transcripcion == 'S')
                                        <div class="icheck-primary d-inline">
                                            <input checked type="radio" value="S" name="transRd"
                                                class="transRdS chckUtilidad">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="N" name="transRd" class="transRdN chckUtilidad">
                                            <label>NO</label>
                                        </div>
                                        @elseif($datosPaciente->transcripcion == 'N')
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="S" name="transRd" class="transRdS chckUtilidad">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input checked type="radio" value="N" name="transRd"
                                                class="transRdN chckUtilidad">
                                            <label>NO</label>
                                        </div>
                                        @else
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="S" name="transRd" class="transRdS chckUtilidad">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="N" name="transRd" class="transRdN chckUtilidad">
                                            <label>NO</label>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>¿Quién Realizó la Transcripción?</label>
                                        @if($datosPaciente->transcripcion == 'S')
                                        <select name="drTransc" id="drTransc" class="custom-select combos chckUtilidad">
                                            @else
                                            <select name="drTransc" disabled id="drTransc" class="custom-select combos">
                                                <option selected id="NA" value="N/A">-- Selecciona una opción
                                                    --
                                                </option>
                                                @endif
                                                @foreach($empTrans as $empT)
                                                @if($empT->id_emp==$datosPaciente->id_empTrans_fk)
                                                <option selected value="{{ $empT->id_emp }}">
                                                    {{ $empT->empleado_nombre }} {{ $empT->empleado_apellidop }}
                                                    {{ $empT->empleado_apellidom }}
                                                </option>
                                                @else
                                                <option value="{{ $empT->id_emp }}">
                                                    {{ $empT->empleado_nombre }} {{ $empT->empleado_apellidop }}
                                                    {{ $empT->empleado_apellidom }}
                                                </option>
                                                @endif
                                                @endforeach
                                            </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <label>Interpretación<strong style="color:red">*</strong></label>
                                    <div class="form-group">
                                        @if($datosPaciente->interpretacion == 'S')
                                        <div class="icheck-primary d-inline">
                                            <input checked type="radio" value="S" name="intRd"
                                                class="interSi chckUtilidad">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="N" name="intRd" class="interNo chckUtilidad">
                                            <label>NO</label>
                                        </div>
                                        @elseif($datosPaciente->interpretacion == 'N')
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="S" name="intRd" class="interSi chckUtilidad">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input checked type="radio" value="N" name="intRd"
                                                class="interNo chckUtilidad">
                                            <label>NO</label>
                                        </div>
                                        @else
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="S" name="intRd" class="interSi chckUtilidad">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="N" name="intRd" class="interNo chckUtilidad">
                                            <label>NO</label>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>¿Quién Realizó la Interpretación?</label>
                                        @if($datosPaciente->interpretacion == 'S')
                                        <select name="drInt" id="drInt" class="custom-select combos chckUtilidad">
                                            @else
                                            <select name="drInt" id="drInt" disabled
                                                class="custom-select combos chckUtilidad">
                                                <option disabled selected id="NA" value="N/A">-- Selecciona una opción
                                                    --
                                                </option>
                                                @endif
                                                @foreach($doctorInter as $dInt)
                                                @if($dInt->id_emp==$datosPaciente->id_empInt_fk)
                                                <option selected value="{{ $dInt->id_emp }}">
                                                    Dr. {{ $dInt->empleado }}
                                                </option>
                                                @else
                                                <option value="{{ $dInt->id_emp }}">
                                                    Dr. {{ $dInt->empleado }}
                                                    @endif
                                                    @endforeach
                                            </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-1">
                                    <label>Escaneado<strong style="color:red">*</strong></label>
                                    <div class="form-group">
                                        @if($datosPaciente->escaneado == 'S')
                                        <div class="icheck-primary d-inline">
                                            <input checked type="radio" value="S" name="escRd" class="chckUtilidad">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="N" name="escRd" class="chckUtilidad">
                                            <label>NO</label>
                                        </div>
                                        @elseif($datosPaciente->escaneado == 'N')
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="S" name="escRd" class="chckUtilidad">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input checked type="radio" value="N" name="escRd" class="chckUtilidad">
                                            <label>NO</label>
                                        </div>
                                        @else
                                        <div class="icheck-info d-inline">
                                            <input type="radio" value="S" name="escRd" class="chckUtilidad">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="N" name="escRd" class="chckUtilidad">
                                            <label>NO</label>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-2">
                                    <label>Entregado<strong style="color:red">*</strong></label>
                                    <div class="form-group">
                                        @if($datosPaciente->entregado == 'S')
                                        <div class="icheck-primary d-inline">
                                            <input checked type="radio" value="S" name="entRd"
                                                class="entSi chckUtilidad">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="N" name="entRd" class="entNo chckUtilidad">
                                            <label>NO</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="P" name="entRd" class="entPen chckUtilidad">
                                            <label>PENDIENTE</label>
                                        </div>
                                        @elseif($datosPaciente->entregado == 'N')
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="S" name="entRd" class="entSi chckUtilidad">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input checked type="radio" value="N" name="entRd"
                                                class="entNo chckUtilidad">
                                            <label>NO</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="P" name="entRd" class="entPen chckUtilidad">
                                            <label>PENDIENTE</label>
                                        </div>
                                        @elseif($datosPaciente->entregado == 'P')
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="S" name="entRd" class="entSi chckUtilidad">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="N" name="entRd" class="entNo chckUtilidad">
                                            <label>NO</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input checked type="radio" value="P" name="entRd"
                                                class="entPen chckUtilidad">
                                            <label>PENDIENTE</label>
                                        </div>
                                        @else
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="S" name="entRd" class="entSi chckUtilidad ">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="N" name="entRd" class="entNo chckUtilidad">
                                            <label>NO</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input checked type="radio" value="P" name="entRd"
                                                class="entPen chckUtilidad">
                                            <label>PENDIENTE</label>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Entregado Por:</label>
                                        @if($datosPaciente->entregado == 'N' || $datosPaciente->entregado == 'P')
                                        <select disabled name="empEnt" id="empEnt" class="custom-select combos">
                                            @else
                                            <select name="empEnt" id="empEnt" class="custom-select combos chckUtilidad">
                                                @endif
                                                <option disabled selected>-- Selecciona una opción --</option>
                                                @foreach($empEntrega as $empE)
                                                @if($empE->id_emp==$datosPaciente->id_empEnt_fk)
                                                <option selected value="{{ $empE->id_emp }}">
                                                    {{ $empE->empleado }}
                                                </option>
                                                @else
                                                <option value="{{ $empE->id_emp }}">
                                                    {{ $empE->empleado }}
                                                </option>
                                                @endif
                                                @endforeach
                                            </select>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label>Observaciones</label>
                                        <input type="text" value="{{ $datosPaciente->observaciones }}" id="obsCobranza"
                                            name="obsCobranza" class="form-control chckUtilidad">
                                        <input type="hidden" name="status" id="statusPaciente"
                                            value="{{$datosPaciente->estudiostemps_status}}">
                                    </div>
                                </div>
                                <div class="col-12" style="text-align: center;">
                                    <label>¿El registro contiene toda la información?</label>
                                    @if($datosPaciente->estudiostemps_status == 0)
                                    <div class="form-group">
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="S1" name="registroC"
                                                class="registroC chckUtilidad">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input checked type="radio" value="N1" name="registroC"
                                                class="registroC chckUtilidad">
                                            <label>NO</label>
                                        </div>
                                    </div>
                                    @else
                                    @if($datosPaciente->registroC == 'S')
                                    <div class="form-group">
                                        <div class="icheck-primary d-inline">
                                            <input checked type="radio" value="S" name="registroC"
                                                class="registroC chckUtilidad">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="N" name="registroC"
                                                class="registroC chckUtilidad">
                                            <label>NO</label>
                                        </div>
                                    </div>
                                    @elseif($datosPaciente->registroC == 'N')
                                    <div class="form-group">
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="S" name="registroC"
                                                class="registroC chckUtilidad">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input checked type="radio" value="N" name="registroC"
                                                class="registroC chckUtilidad">
                                            <label>NO</label>
                                        </div>
                                    </div>
                                    @else
                                    <div class="form-group">
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" value="S" name="registroC"
                                                class="registroC chckUtilidad">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input checked type="radio" value="N" name="registroC"
                                                class="registroC chckUtilidad">
                                            <label>NO</label>
                                        </div>
                                    </div>
                                    @endif
                                    @endif
                                </div>
                            </div>
                            <!--Cierre cuerpo del body-->
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-6">
                                        <a href="{{ route('importarCobranza.index')}}">
                                            <button type="button" id="btnRegresar" name="btnRegresar"
                                                class="btn btn-block btn-outline-secondary btn-xs">
                                                Regresar
                                            </button>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a data-target="#modal-paciente" data-toggle="modal">
                                            <button type="button" id="btnGuardar" name="btnGuardar"
                                                class="btn btn-block btn-outline-info btn-xs">Guardar
                                                Registro
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <!--Fin Card Información Paciente-->
            </div>
        </div>
</section>
@include('estudios.modalpaciente')
</form>
<section class="content">
    <div class="container-fluid">
        @canany(['comisiones','cobranzaReportes','auxiliarCobranzaReportes','optometria'])
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        Status
                    </h3>
                </div>
                <form action="{{ route('importarCobranza.update') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <table id="statusComisionCobrabza" name="reporteCobranza"
                            class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Estudio</th>
                                    <th>Actividad</th>
                                    <th>Empleado</th>
                                    <th>Status Comisión</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($statusCobCom as $status)
                                <tr>
                                    <th>{{ $status->dscrpMedicosPro }} ({{ $status->folio }})</th>
                                    <!--<th>{{ $status->nombreActividad }} ($ {{number_format($status->cobranza_total,2)}})
                                    </th>-->
                                    <th>{{ $status->nombreActividad }}</th>
                                    <th>
                                        @if($status->id_emp == 1)
                                        {{ 'No Aplica' }}
                                        @else
                                        {{ $status->empleado }}
                                        @endif
                                    <th>
                                        @if($status->statusComisiones == 'P')
                                        {{ 'PENDIENTE POR DEFINIR' }}
                                        @elseif($status->statusComisiones == '')
                                        {{ 'SIN PAGAR COMISIÓN' }}
                                        @else
                                        {{ strtoupper($status->statusComisiones) }}
                                        @endif
                                    </th>
                                    <th style="text-align: center;">
                                        @if($totalStatusUtilidades < 0) @switch($status->nombreActividad)
                                            @case('Adicional Administrativo')
                                            @case('Adicional Egresos')
                                            @case('Adicional Gestion')
                                            @case('Utilidad')
                                            <a class="btn btn-block btn-outline-secondary btn-xs">NO
                                                APLICA</a>
                                            @break
                                            @default
                                            <a class="btn btn-block btn-outline-secondary btn-xs"
                                                href="{{ route('importarCobranza.showActividad',$status->id) }}">VER</a>
                                            @break
                                            @endswitch
                                            @else
                                            @if($status->nombreActividad == 'Entregado' &&
                                            strtoupper($status->statusComisiones) != 'PAGADO')
                                            <a class="btn btn-block btn-outline-secondary btn-xs"
                                                href="{{ route('importarCobranza.showActividad',$status->id) }}">VER</a>
                                            @else
                                            <a class="btn btn-block btn-outline-secondary btn-xs">NO
                                                APLICA</a>
                                            @endif
                                            @endif
                                    </th>
                                    <!--<th><a class="btn btn-block btn-outline-secondary btn-xs">VER</a></th>-->
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
        @endcanany
    </div>
</section>
@elsecanany(['detalleConsumo','auxiliardetalleConsumo','invitado'])
<div class=" alert alert-danger" role="alert">No cuenta con los privilegios para acceder a este módulo del sistema
</div>
@endcanany
@canany(['comisiones','cobranzaReportes'])
<section class="content">
    <div class="card-body">
        <div class="row">
            <div class="col-12" style="text-align: center;">
                @if($totalStatusPagado >= 1 || $datosPaciente->estudiostemps_status === 1)
                <button class="btn btn-block btn-outline-danger btn-xs" disabled>Eliminar Registro</button>
                @else
                <button class="btn btn-block btn-outline-danger btn-xs" type="button"
                    data-target="#eliminar-{{$datosPaciente->id}}" data-toggle="modal">Eliminar Registro</button>
                @endif
            </div>
        </div>
    </div>
</section>
@include('estudios.modalCobranzaDelete')
@endcanany

@endsection