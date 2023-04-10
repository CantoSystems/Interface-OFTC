@extends('layouts.principal')
@section('content')
<div class="col">
    <div class="card">
        <div class="card-header modalPersonalizado">
            <h4></h4>
        </div>
    @canany(['comisiones','cobranzaReportes'])
        <div class="col-md-3 col-sm-4 col-8">
            
        </div>
        <div class="card-body">
            <table id="catComisiones" name="catComisiones" class="table table-bordered table-hover">
                <thead>
                    <tr>
                       <th>ACTIVIDAD</th>
                    </tr>
                </thead>
                <tbody>

                        @isset($registrosExistentes)
                        @foreach($registrosExistentes as $reg )
                            <tr>
                                {{ $reg->nombreActividad }}
                            </tr>
                        @endforeach
                    @endisset
                   
                </tbody>
            </table>
        </div>
    </div>
</div>

@elsecanany(['cobranzaReportes','auxiliarCobranzaReportes','invitado'])
<div class="alert alert-danger" role="alert">
        No cuenta con los privilegios para acceder a este módulo del sistema
</div>
@endcanany
@endsection