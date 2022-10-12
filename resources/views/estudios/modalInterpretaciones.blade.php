<div class="modal fade bs-example-modal-lg" tabindex="-1" id="modalInt" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title modalPersonalizado">Agregar Interpretaciones</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <meta name="csrf-token" content="{{ csrf_token() }}" />
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Selecciona Estudio:<strong style="color:red">*</strong></label>
                                    <select name="estudioInt" id="estudioInt" class="custom-select combos">
                                        <option disabled selected value="1">-- Selecciona una opción --</option>
                                        @foreach ($descripcionEstudios as $descripcion)
                                        <option value="{{ $descripcion->id }}">
                                            {{ $descripcion->dscrpMedicosPro }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Selecciona Doctor Que Interpretó<strong style="color:red">*</strong></label>
                                    <select name="doctorInt" id="doctorInt" class="custom-select combos">
                                        <option disabled selected value="1">-- Selecciona una opción --</option>
                                        @foreach($doctorInter as $doc)
                                        <option value="{{ $doc->id }}">
                                            {{ $doc->doctor_titulo }} {{ $doc->doctor_nombre }}
                                            {{ $doc->doctor_apellidop }}
                                            {{ $doc->doctor_apellidom }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="button" id="grdrInt" name="grdrInt"
                                    class="btn btn-block btn-outline-info btn-xs">Agregar
                                    Registro</button>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-12">
                                <table id="example13" class="table table-bordered table-striped example13">
                                    <thead>
                                        <tr>
                                            <th>Estudio</th>
                                            <th>Doctor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12">
                                <button type="button" id="grdrCompleto" name="grdrCompleto"
                                    class="btn btn-block btn-outline-info btn-xs">Guardar
                                    Registros</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>