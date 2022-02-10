@extends('layouts.principal')
    @section('content') 
        <div class="col">
            <div class="card">
                <div class="card-header modalPersonalizado">
                    <h4>Estudios</h4>
                </div>
                <div class="card-header">
                    <div class="col-md-3 col-sm-4 col-8">
                        <div class="info-box shadow">
                            <span class="info-box-icon bg-info"><i class="far fa-copy"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Importar Reporte</span>
                                <button id="cargarCobranza" type="button" data-toggle="modal" data-target="#exampleModal_DC">
                                    <span class="info-box-number">Subir</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($estudioDetalle))
                                @foreach ($estudioDetalle as $detalle)
                                    <tr>
                                        <td>{{ $detalle->descripcion }}</td>
                                        <td>{{ $detalle->cantidad }}</td>
                                        <td>{{ $detalle->precio_unitario }}</td>
                                        <td>{{ $detalle->importe}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @include('detalleC.detalleconsumo');
    @endsection