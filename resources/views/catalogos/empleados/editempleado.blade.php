@extends('layouts.principal')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Información Empleado: <b>{{ $empleado->empleado }}</b></h3>
                    </div>
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </div>
                    @endif
                    <form action="{{ route('updtEmpleado.update') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Nombre(s)</label>
                                        <input type="text" value="{{ $empleado->empleado_nombre }}" id="nombreEmpleado"
                                            name="nombreEmpleado" class="form-control">
                                            <input type="hidden" name="idEmpleado" id="idEmpleado" value="{{ $empleado->id_emp }}">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Apellido Paterno</label>
                                        <input type="text" value="{{ $empleado->empleado_apellidop }}" id="appEmpleado"
                                            name="appEmpleado" class="form-control">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Apellido Materno</label>
                                        <input type="text" value="{{ $empleado->empleado_apellidom }}" id="apmEmpleado"
                                            name="apmEmpleado" class="form-control">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Puesto</label>
                                        <select name="puestoEmp" id="puestoEmp" class="custom-select combos">
                                            <option disabled selected>-- Selecciona una opción --</option>
                                            @foreach($listPuestos as $listPuestos)
                                            @if($listPuestos->id == $empleado->puesto_id)
                                            <option selected value="{{ $listPuestos->id }}">
                                                {{ $listPuestos->puestos_nombre }}
                                            </option>
                                            @else
                                            <option value="{{ $listPuestos->id }}">
                                                {{ $listPuestos->puestos_nombre }}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" id="btnGuardar" name="btnGuardar"
                                        class="btn btn-block btn-outline-info btn-xs">Guardar
                                        Registro</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" id="btnEliminar" name="btnEliminar"
                                        class="btn btn-block btn-outline-danger btn-xs" data-target="#eliminar-empleado"
                                        data-toggle="modal">Eliminar
                                        Registro</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@include('catalogos.empleados.modaldelete')
@endsection