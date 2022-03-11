@extends('layouts.principal')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2"></div>
            <!--Inicio Card Información Paciente-->
            <div class="col-md-8">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Información paciente: {{ $datosPaciente->paciente }}</h3>
                    </div>
                    <form action="" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Folio</label>
                                        <input type="text" class="form-control" value="{{ $datosPaciente->folio }}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label>Paciente</label>
                                        <input type="text" class="form-control" value="{{ $datosPaciente->paciente }}"
                                            disabled>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Fecha</label>
                                        <input type="date" class="form-control" value="{{ $datosPaciente->fecha }}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Cantidad</label>
                                        <input type="text" class="form-control" value="{{ $datosPaciente->total }}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label>Dr. Que Requiere</label>
                                        <input type="text" class="form-control" value="">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>PX INT. - EXT.</label>
                                        <select name="" id="" class="custom-select">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Forma de pago</label>
                                        <input type="text" class="form-control" value="{{ $datosPaciente->met_pago }}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <label>Transcripción</label>
                                    <div class="form-group">
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" name="r1">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" name="r1">
                                            <label>NO</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Quién realiza la transcripción</label>
                                        <input type="text" class="form-control" value="">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <label>Interpretación</label>
                                    <div class="form-group">
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" name="r2">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" name="r2">
                                            <label>NO</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Quién realiza la interpretación</label>
                                        <input type="text" class="form-control" value="">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <label>Escaneado</label>
                                    <div class="form-group">
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" name="r3">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" name="r3">
                                            <label>NO</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-10">
                                    <div class="form-group">
                                        <label>Observaciones</label>
                                        <input type="text" class="form-control" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-block btn-outline-secondary btn-xs">Guardar
                                registro</button>
                        </div>
                    </form>
                </div>
            </div>
            <!--Fin Card Información Paciente-->
            <div class="col-md-2"></div>
        </div>
    </div>
</section>
@endsection