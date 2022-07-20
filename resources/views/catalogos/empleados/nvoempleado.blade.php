<div class="modal fade bs-example-modal-lg" tabindex="-1" id="nvoestudio" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title modalPersonalizado">Agregar</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('nvoEmpleado.create') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label>Nombre(s)
                                    <strong style="color:red">*</strong>
                                    </label>
                                    <input type="text" id="nombreEmpleado" name="nombreEmpleado" class="form-control">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label>Apellido Paterno
                                    <strong style="color:red">*</strong>
                                    </label>
                                    <input type="text" id="appEmpleado" name="appEmpleado" class="form-control">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label>Apellido Materno
                                    <strong style="color:red">*</strong>
                                    </label>
                                    <input type="text" id="apmEmpleado" name="apmEmpleado" class="form-control">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label>Puesto
                                    <strong style="color:red">*</strong>
                                    </label>
                                    <select name="puestoEmp" id="puestoEmp" class="custom-select combos">
                                        <option disabled selected>-- Selecciona una opción --</option>
                                        @foreach($listPuestos as $listPuestos)
                                        <option value="{{ $listPuestos->id }}">
                                            {{ $listPuestos->puestos_nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" id="btnGuardar" name="btnGuardar"
                                    class="btn btn-block btn-outline-info btn-xs">Guardar
                                    Registro</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>