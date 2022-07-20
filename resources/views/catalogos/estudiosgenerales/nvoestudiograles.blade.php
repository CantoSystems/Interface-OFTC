<div class="modal fade bs-example-modal-sm" tabindex="-1" id="nvoestudiogral" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title modalPersonalizado">Agregar Estudio General</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('nvoEstudioGral.create') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Nombre del Estudio <strong style="color:red">*</strong></label>
                                <input type="text" id="descripcionGral" name="descripcionGral" class="form-control" required  onkeyup="mayus(this);">
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