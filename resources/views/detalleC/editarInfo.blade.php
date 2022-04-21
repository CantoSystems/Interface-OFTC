<div class="modal fade" id="exampleModal_DatosEmp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title modalPersonalizado" id="exampleModalLabel" class="modalPersonalizado">Informacion
                    Adicional Hoja de Consumo</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('guardarDetalle.create') }}" method="get" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Folio Hoja de Consumo</label>
                                <input type="number" class="form-control" id="folioHoja" name="folioHoja">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Fecha de Elaboración</label>
                                <input type="date" class="form-control" id="fechaHoja" name="fechaHoja">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Seleccionar Doctor</label>
                                <select class="custom-select rounded-0" id="doctorHoja" name="doctorHoja">
                                    <option disabled selected>Seleccionar una opción...</option>
                                    @foreach($doctores as $doc)
                                    <option value="{{ $doc->id }}">
                                        {{ $doc->doctor_nombre }} {{ $doc->doctor_apellidop }}
                                        {{ $doc->doctor_apellidom }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Seleccionar Método de Pago</label>
                                <select class="custom-select rounded-0" id="metodoPagoHoja" name="metodoPagoHoja">
                                    <option disabled selected>Seleccionar una opción...</option>
                                    @foreach($metodoPago as $metP)
                                    <option value="{{ $metP->id }}">
                                        {{ $metP->descripcion }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nombre Paciente</label>
                                <input type="text" class="form-control" id="pacienteHoja" name="pacienteHoja">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Tipo Paciente</label>
                                <select class="custom-select rounded-0" id="tipoPacienteHoja" name="tipoPacienteHoja">
                                    <option disabled selected>Seleccionar una opción...</option>
                                    @foreach($tipoPaciente as $tipoP)
                                    <option value="{{ $tipoP->id }}">
                                        {{ $tipoP->nombretipo_paciente }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" id="btnGuardar" name="btnGuardar"
                                class="btn btn-block btn-outline-info btn-xs">Guardar y Enviar Detalle de
                                Consumo</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>