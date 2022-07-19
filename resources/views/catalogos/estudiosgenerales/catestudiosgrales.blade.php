@extends('layouts.principal')
@section('content')
<div class="col">
    <div class="card">
        <div class="card-header modalPersonalizado">
            <h4>Catálogo Estudios Generales</h4>
        </div>
        <div class="col-md-3 col-sm-4 col-8">
            <div class="info-box shadow">
                <span class="info-box-icon bg-info"><i class="fas fa-user-plus"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"></span>
                    <button id="cargarCobranza" type="button" class="btn btn-block btn-outline-secondary btn-xs"
                        data-toggle="modal" data-target="#nvoestudiogral">
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
            <table id="catEstudios" name="catEstudios" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="text-align: center;">Folio</th>
                        <th>Descripción</th>
                        <th>Ver</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($listEstudiosGrales))
                    @foreach($listEstudiosGrales as $list)
                    <tr>
                        <td style="text-align: center;">{{ $list->id }}</td>
                        <td>{{ $list->descripcion }}</td>
                        <th><a class="btn btn-block btn-outline-secondary btn-xs"
                                href="{{ route('editCatalogoGral.update',$list->id) }}">VER</a></th>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@include('catalogos.estudiosgenerales.nvoestudiograles')
@endsection