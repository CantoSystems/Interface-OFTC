<div class="modal fade bs-example-modal-lg" tabindex="-1" id="nvacomision" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title modalPersonalizado">Agregar Comisión</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('nvaComision.create') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Estudio Específico 
                                        <strong style="color:red">*</strong>
                                    </label>
                                    <select name="estudioGral" id="estudioGral" class="custom-select combos">
                                        <option disabled selected>-- Selecciona una opción --</option>
                                        @foreach($listEstudios as $listEstudios)
                                        <option value="{{ $listEstudios->id }}">
                                            {{ $listEstudios->dscrpMedicosPro }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Empleado
                                    <strong style="color:red">*</strong>
                                    </label>
                                    <select name="empleadoComision" id="empleadoComision" class="custom-select combos">
                                        <option disabled selected>-- Selecciona una opción --</option>
                                        @foreach($listEmpleados as $listEmpleados)
                                        <option value="{{ $listEmpleados->id_emp }}">
                                            {{ $listEmpleados->Empleado }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Cantidad en MXN
                                    <strong style="color:red">*</strong>
                                    </label>
                                    <input type="number" step="0.01" id="cantidadComision" name="cantidadComision"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Porcentaje Comisión
                                    <strong style="color:red">*</strong>
                                    </label>
                                    <input type="number" step="0.01" id="porcentajeComision" name="porcentajeComision"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-4" id="divComision" name="divComision" style="display: none">
                                <div class="form-group">
                                    <label>Utilidad
                                    <strong style="color:red">*</strong>
                                    </label>
                                    <input type="number" step="0.01" id="utilidadComision" name="utilidadComision"
                                        class="form-control">
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