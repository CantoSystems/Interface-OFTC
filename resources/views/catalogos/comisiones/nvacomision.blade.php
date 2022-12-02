<div class="modal fade bs-example-modal-xl" tabindex="-1" id="nvacomision" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
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
                        <div class="alert alert-success" id="divAlerta" name="divAlerta"
                            style="display: none; text-align: justify;">
                            <h6><b>RECUERDA:</b></h6> En el caso del <b>OPTOMETRISTA</b>, el campo <b>PORCENTAJE
                                COMISIÓN</b> es <i><b>por la realización del estudio</b></i> y el campo <b>PORCENTAJE
                                ADICIONAL</b> es <i><b>por la transcripción</b></i> del
                            mismo.
                        </div>
                        <div class="row">
                            <div class="col-md-4">
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
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Porcentaje Comisión
                                        <strong style="color:red">*</strong>
                                    </label>
                                    <input type="number" step="0.01" id="porcentajeComision" name="porcentajeComision"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" id="porcentajeAdicional" name="porcentajeAdicional"
                                    style="display: none">
                                    <label>Porcentaje Adicional</label>
                                    <input type="number" step="0.01" id="cantidadComision" name="cantidadComision"
                                    class="form-control porcentajeAdicionalInput">
                                </div>
                            </div>
                            <div class="col-md-4" id="divComision" name="divComision" style="display: none">
                                <div class="form-group">
                                    <label>Porcentaje Utilidad
                                        <strong style="color:red">*</strong>
                                    </label>
                                    <input type="number" step="0.01" id="utilidadComision" name="utilidadComision"
                                        class="form-control divComisionInput">
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