@extends('layouts.principal')
@section('content')
<div class="col">
    <div class="card">
        <div class="card-header modalPersonalizado">
            <h4>Ver Hojas de Consumo</h4>
        </div>
        <div class="card-body">
            <table id="catEstudios" name="catEstudios" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Fecha de Elaboración</th>
                        <th>Doctor</th>
                        <th>Paciente</th>
                        <th>Método de Pago</th>
                        <th>Importe</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($hojasConsumo))
                    @foreach($hojasConsumo as $hojas)
                    <tr>
                        <td>{{ $hojas->folio }}</td>
                        <td>{{ date('d-m-Y',strtotime($hojas->fechaElaboracion)) }}</td>
                        <td>{{ $hojas->Doctor }}</td>
                        <td>{{ $hojas->paciente }} ({{ $hojas->nombretipo_paciente }})</td>
                        <td>{{ $hojas->descripcion }}</td>
                        <td>$ {{ number_format($hojas->cantidadTotal,2) }}</td>
                        <td>
                            <div>
                                <center>
                                    <a href="{{ route('exportPDF.create',$hojas->id_detalle) }}">
                                        <button type="button" class="botones">
                                            <i class="far fa-file-pdf"></i>
                                        </button>
                                    </a>
                                </center>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection