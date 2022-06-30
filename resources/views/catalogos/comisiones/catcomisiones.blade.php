@extends('layouts.principal')
@section('content')
<div class="col">
    <div class="card">
        <div class="card-header modalPersonalizado">
            <h4>Catálogo Comisiones</h4>
        </div>
        <div class="col-md-3 col-sm-4 col-8">
            <div class="info-box shadow">
                <span class="info-box-icon bg-info"><i class="fas fa-user-plus"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"></span>
                    <button id="cargarCobranza" type="button" class="btn btn-block btn-outline-secondary btn-xs"
                        data-toggle="modal" data-target="#nvacomision">
                        <span class="info-box-number">Agregar Comisión</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="catComisiones" name="catComisiones" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Estudio</th>
                        <th>Cantidad</th>
                        <th>Porcentaje</th>
                        <th>Ver</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($lisComisiones))
                    @foreach($lisComisiones as $list)
                    <tr>
                        <td>{{ $list->Empleado }}</td>
                        <td>{{ $list->Estudio }}</td>
                        <td>$ {{ number_format($list->cantidadComision,2) }}</td>
                        <td>{{ number_format($list->porcentaje,2) }} %</td>
                        <th><a class="btn btn-block btn-outline-secondary btn-xs"
                                href="{{ route('editComision.show',$list->id) }}">VER</a></th>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@include('catalogos.comisiones.nvacomision');
@endsection