@extends('layouts.principal')
@section('content')
<div class="col">
    <div class="card">
        <div class="card-header modalPersonalizado">
            <h4>Generar Reporte</h4>
        </div>
        <div class="card-header col-12">
            <!--
                Siempre revisar que los formularios no estén anidados :) gracias!
            -->
            @canany(['comisiones','cobranzaReportes','auxiliarCobranzaReportes','optometria'])
            <form action="{{ route('importarCobranza.showData') }}" method="GET">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-6">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <label class="info-box-text">Selecciona Estudio:</label>
                                <select name="estudioSelect[]" id="estudioSelect" multiple="multiple"
                                    class="custom-select">
                                    @foreach ($estudios as $est)
                                    <option value="{{ $est->id }}" selected>
                                        {{ $est->descripcion }} ( {{ $est->nombretipo_ojo }} )
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-3">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <label class="info-box-text">Fecha Inicio:</label>
                                <input type="date" name="historialInicio" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-3">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <label class="info-box-text">Fecha Fin:</label>
                                <input type="date" name="historialFinal" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-3 col-3">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <button id="cargarCobranza" type="submit"
                                    class="btn btn-block btn-outline-secondary btn-xs" required>
                                    <span class="info-box-number">Generar</span>
                                </button>
                            </div>
                        </div>
                    </div>
            </form>
            @endcanany

            @canany(['comisiones','cobranzaReportes','optometria'])
            @if(!empty($cobranza))
                    <div class="col-md-2 col-sm-4 col-6">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <form action="{{ route('importarCobranza.export') }}" method="POST">
                                    @csrf
                                    <input type="text" name="clvEstudios" value="{{ json_encode($busquedaEstudios, true) }}" />
                                    <input type="hidden" name="inicio" value="{{$inicio}}">
                                    <input type="hidden" name="fin" value="{{$fin}}">
                                    <input class="btn btn-block btn-outline-secondary btn-xs" type="submit"
                                        value="Exportar a Excel">
                                </form>
                            </div>
                        </div>
                    </div>
            @endif
            @endcanany
            </div>
        </div>
        @canany(['comisiones','cobranzaReportes','auxiliarCobranzaReportes','optometria'])
        <div class="card-body">
            @if(!empty($cobranza))
            <table id="genReportes" name="genReportes" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="text-align: center;">Folio</th>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Estudio</th>
                        <th>Realizado Por</th>
                        <th style="text-align: center;">Tipo Ojos</th>
                        <th>Doctor</th>
                        <th style="text-align: center;">Interpretado</th>
                        <th style="text-align: center;">Transcrito</th>
                        <th style="text-align: center;">Escaneado</th>
                        <th style="text-align: center;">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($cobranza))
                    @foreach($cobranza as $cbr)
                    <tr>
                        <td style="text-align: center;">{{ $cbr->folio }}</td>
                        <td>{{ date('d-m-Y',strtotime($cbr->fecha)) }}</td>
                        <td>{{ $cbr->paciente }}</td>
                        <td>{{ $cbr->descripcion }}</td>
                        <td>{{ $cbr->EmpleadoRealiza }}</td>
                        <td style="text-align: center;">{{ $cbr->nombretipo_ojo }}</td>
                        <td>{{ $cbr->Doctor }}</td>
                        <td style="text-align: center;">{{ $cbr->Transcripcion }}</td>
                        <td style="text-align: center;">{{ $cbr->Interpretacion }}</td>
                        <td style="text-align: center;">{{ $cbr->Escaneado }}</td>
                        <td style="text-align: center;">$ {{ number_format($cbr->cantidadCbr,2) }}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
            @endif
        @elsecanany(['detalleConsumo','auxiliardetalleConsumo','invitado'])
            <div class="alert alert-danger" role="alert">
                    No cuenta con los privilegios para acceder a este módulo del sistema
            </div>
        @endcanany
        </div>
    </div>
</div>
@endsection