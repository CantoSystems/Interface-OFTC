@extends('layouts.principal')
    @section('content') 
        <div class="card">
            <div class="card-header modalPersonalizado">
                <h4>Empresas</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('subirReporte.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if(Session::has('message'))
                        <p>{{ Session::get('message') }}</p>
                    @endif
                    <div class="col-md-12">
                        <h5 class="card-title modalPersonalizado">Selecciona el archivo Excel:</h5>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-8">
                            <br>
                            <input type="file" name="file">
                        </div>
                        <div class="col-md-8">
                            <br>
                            <button>Importar Reporte</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endsection