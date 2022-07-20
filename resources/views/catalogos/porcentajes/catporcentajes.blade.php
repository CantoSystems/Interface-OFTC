@extends('layouts.principal')
@section('content')
<div class="col">
    <div class="card">
        <div class="card-header modalPersonalizado">
            <h4>Catálogo Porcentajes</h4>
        </div>
        <div class="col-md-3 col-sm-4 col-8">
            <div class="info-box shadow">
                <span class="info-box-icon bg-info"><i class="fas fa-user-plus"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"></span>
                    <button id="cargarCobranza" type="button" class="btn btn-block btn-outline-secondary btn-xs"
                        data-toggle="modal" data-target="#nvoporcentaje">
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
                        <th>Doctor</th>
                        <th>Tipo Paciente</th>
                        <th>Método Pago</th>
                        <th>Porcentaje</th>
                        <th>Ver</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($porcentajeDoctores))
                    @foreach($porcentajeDoctores as $porDoc)
                    <tr>
                        <td>{{ $porDoc->Doctor }}</td>
                        <td>{{ $porDoc->nombretipo_paciente }}</td>
                        <td>{{ $porDoc->descripcion }}</td>
                        <td>{{ number_format($porDoc->porcentaje,2) }} %</td>
                        <th><a class="btn btn-block btn-outline-secondary btn-xs"
                                href="{{ route('updtPorcentaje.show',$porDoc->id) }}">VER</a></th>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@include('catalogos.porcentajes.nvoporcentaje');
@endsection