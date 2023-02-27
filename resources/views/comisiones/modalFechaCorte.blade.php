<div class="modal fade bs-example-modal-sm" tabindex="-1" id="modal-fechaCorte" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title modalPersonalizado" id="myModalLabel">
                    Crear fecha de Corte
                </h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('comisiones.fechaCorte')}}">
                     @csrf
                <div class="row">
                        <center>
                            <div class="col-md-12">
                            <div class="form-group">
                                <label>Ingresa la fecha de Corte</label>
                                <input  type="date" 
                                        id="folioCbr" name="fechaCorte" class="form-control"
                                        >
                            </div>
                        </div>
                        </center>
                </div>



                <div class="modal-footer">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" data-dismiss="modal"
                                class="btn btn-block btn-outline-secondary btn-xs">Cancelar</button>
                        </div>
                        <div class="col-6">
                            <button type="submit" id="btnGuardarPaciente"
                                class="btn btn-block btn-outline-info btn-xs">Continuar</button>
                        </div>
                    </div>
                  </form>
                </div>
            
            </div>
    </div>
</div>