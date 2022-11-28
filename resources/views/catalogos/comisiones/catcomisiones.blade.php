@extends('layouts.principal')
@section('content')
<div class="col">
    <div class="card">
        <div class="card-header modalPersonalizado">
            <h4>Catálogo Comisiones</h4>
        </div>
        @canany(['comisiones','cobranzaReportes'])
        <div class="col-md-3 col-sm-4 col-8">
            <div class="info-box shadow">
                <span class="info-box-icon bg-info"><i class="fas fa-user-plus"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"></span>
                    <button id="cargarCobranza" type="button" class="btn btn-block btn-outline-secondary btn-xs"
                        data-toggle="modal" data-target="#nvacomision">
                        <span class="info-box-number">Agregar</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </div>
            @endif
            @if(session()->has('duplicados'))
            <div class="alert alert-danger" role="alert">
                {{ session('duplicados')}}
            </div>
            @endif
            <table id="catComisiones" name="catComisiones" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Estudio</th>
                        <th>Porcentaje Comisión</th>
                        <th>Porcentaje Utilidad</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($lisComisiones))
                    @foreach($lisComisiones as $list)
                    <tr>
                        <td>{{ $list->Empleado }}</td>
                        <td>{{ $list->Estudio }}</td>
                        <td>{{ number_format($list->porcentajeComision,2) }} %</td>
                        <td>{{ number_format($list->cantidadUtilidad,2) }} %</td>
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
@elsecanany(['detalleConsumo','auxiliarCobranzaReportes','auxiliardetalleConsumo','invitado'])
<div class="alert alert-danger" role="alert">
    No cuenta con los privilegios para acceder a este módulo del sistema
</div>
@endcanany
@endsection