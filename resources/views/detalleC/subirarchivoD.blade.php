@extends('layouts.principal')
    @section('content') 
        <div class="col">
            <div class="card">
                <div class="card-header modalPersonalizado">
                    <h4>Detalle de Consumo</h4>
                </div>
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-3">
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
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box shadow">
                                <span class="info-box-icon bg-info"><i class="fas fa-edit"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Agregar Información Doctor</span>
                                    <button id="cargarCobranza" type="button" data-toggle="modal" data-target="#exampleModal_DatosEmp">
                                        <span class="info-box-number">Abrir</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tableDetalle" name="tableDetalle" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Importe</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        @include('detalleC.detalleconsumo');
        @include('detalleC.editarinfo');
    @endsection