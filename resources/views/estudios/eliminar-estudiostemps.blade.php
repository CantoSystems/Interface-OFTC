<div class="modal fade bs-example-modal-sm" tabindex="-1" id="eliminar-estudiostemps" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title modalPersonalizado">ELIMINAR REGISTROS</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('importarCobranza.destroy') }}" method="POST" style="text-align: justify;">
                    @method('DELETE')
                    @csrf
                    <div id="palabraClaveDiv" name="palabraClaveDiv" class="alert alert-danger" style="display: none;">
                        <p><b>La palabra clave no es correcta.</b></p>
                    </div>
                    <p>Para completar la acción <b>ELIMINAR</b>, confirme escribiendo la palabra 
                    <strong>OFTALMOCENTER</strong></p>   
                    <p>¿Estás seguro que desea eliminar los registros con status: <b>COMPLETADO</b>?</p>
                    <P><input type="text" class="form-control" name="palabra_clave" id="palabra_clave" placeholder="Ingrese la palabra clave" required></P>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-outline-secondary btn-xs">Cancelar</button>
                <button type="submit" class="btn btn-outline-info btn-xs" disabled id="btnCheck" name="btnCheck">Continuar</button>
            </div>
            </form>
        </div>
    </div>
</div>