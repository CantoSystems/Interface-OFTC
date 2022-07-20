<div class="modal fade bs-example-modal-lg" tabindex="-1" id="nvoporcentaje" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title modalPersonalizado">Agregar Porcentaje</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('nvoPorcentaje.create') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Doctor</label>
                                    <select name="doctorId" id="doctorId" class="custom-select combos">
                                        <option disabled selected>-- Selecciona una opción --</option>
                                        @foreach($catDoctores as $catDoctores)
                                        <option value="{{ $catDoctores->id }}">
                                            {{ $catDoctores->Doctor }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Método Pago</label>
                                    <select name="metodoPago" id="metodoPago" class="custom-select combos">
                                        <option disabled selected>-- Selecciona una opción --</option>
                                        @foreach($catMetodoPago as $catMetodoPago)
                                        <option value="{{ $catMetodoPago->id }}">
                                            {{ $catMetodoPago->descripcion }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Tipo Paciente</label>
                                    <select name="tipoPaciente" id="tipoPaciente" class="custom-select combos">
                                        <option disabled selected>-- Selecciona una opción --</option>
                                        @foreach($catTipoPaciente as $catTipoPaciente)
                                        <option value="{{ $catTipoPaciente->id }}">
                                            {{ $catTipoPaciente->nombretipo_paciente }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Porcentaje</label>
                                    <input type="number" step="0.01" id="porcentajeDoctor" name="porcentajeDoctor"
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