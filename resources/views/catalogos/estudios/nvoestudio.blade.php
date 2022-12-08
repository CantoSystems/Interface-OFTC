<div class="modal fade bs-example-modal-lg" tabindex="-1" id="nvoestudio" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title modalPersonalizado">Agregar Estudio</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('nvoEstudio.create') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Estudio General<strong style="color:red">*</strong></label>
                                    <select name="estudioGral" id="estudioGral" class="custom-select combos">
                                        <option disabled selected>-- Selecciona una opción --</option>
                                        @foreach($catEstudios as $catEstudios)
                                        <option value="{{ $catEstudios->id }}">
                                            {{ $catEstudios->descripcion }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label>Tipo de Ojo <strong style="color:red">*</strong></label>
                                    <select name="tipoOjo" id="tipoOjo" class="custom-select combos">
                                        <option disabled selected>-- Selecciona una opción --</option>
                                        @foreach($catOjos as $catOjos)
                                        <option value="{{ $catOjos->id }}">
                                            {{ $catOjos->nombretipo_ojo }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Descripción Medicos Pro <strong style="color:red">*</strong></label>
                                    <input type="text" id="dscrpMedicosPro" name="dscrpMedicosPro" class="form-control"
                                        onkeyup="mayus(this);">
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