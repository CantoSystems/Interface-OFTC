@extends('layouts.principal')
@section('content')
<div class="col">
    <div class="card">
        <div class="card-header modalPersonalizado">
            <h4>Generar Reporte</h4>
        </div>
        <div class="card-header col-12">
            <form action="{{ route('importarCobranza.showData') }}" method="GET">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-6">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <label class="info-box-text">Selecciona Estudio:</label>
                                <select name="estudioSelect" id="estudioSelect" class="custom-select">
                                    <option selected disabled>-- Selecciona una opción --</option>
                                    @foreach ($estudios as $est)
                                    <option value="{{ $est->id }}">
                                        {{ $est->descripcion }} ( {{ $est->nombretipo_ojo }} )
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-6">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <label class="info-box-text">Selecciona Status:</label>
                                <select name="statusSelect" id="statusSelect" class="custom-select">
                                    <option selected disabled>-- Selecciona una opción --</option>
                                    <option value="Escaneado">Escaneado</option>
                                    <option value="Interpretado">Interpretado</option>
                                    <option value="Transcrito">Transcrito</option>
                                    <option value="Entregado">Entregado</option>
                                    <option value="Todos">Todos</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-6">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <button id="cargarCobranza" type="submit"
                                    class="btn btn-block btn-outline-secondary btn-xs">
                                    <span class="info-box-number">Generar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @if(!empty($cobranza))
                    <div class="col-md-2 col-sm-4 col-6">
                        <div class="info-box shadow">
                            <div class="info-box-content">
                                <button id="cargarCobranza" type="button"
                                    class="btn btn-block btn-outline-info btn-xs">
                                    <a href="{{ route('importarCobranza.export') }}" class="info-box-number">Exportar
                                        a Excel</a>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </form>
        </div>
        <div class="card-body">
            @if(!empty($cobranza))
            <table id="genReportes" name="genReportes" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Estudio</th>
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
                        <td>{{ $cbr->folio }}</td>
                        <td>{{ $cbr->fecha }}</td>
                        <td>{{ $cbr->paciente }}</td>
                        <td>{{ $cbr->descripcion }}</td>
                        <td style=" text-align: center;">{{ $cbr->nombretipo_ojo }}</td>
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
        </div>
    </div>
</div>
@endsection