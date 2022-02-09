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
                    <button id="cargarCobranza" class="btn-flat" type="button" data-toggle="modal" data-target="#exampleModal">
                        <span class="info-box-number">Subir</span>
                    </button>
                   
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
        </div>
        <div class="card-body">
            <table id="example1" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Paciente</th>
                        <th>Servicio</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($estudioCobranza))
                        
           
                    @foreach ($estudioCobranza as $cobranza)
                    <tr>
                        <td>{{ $cobranza->folio }}</td>
                        <td>{{ $cobranza->paciente }}</td>
                        <td>{{ $cobranza->servicio }}</td>
                        <td>{{ $cobranza->fecha}}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@include('estudios.reportecobranza');
    @endsection