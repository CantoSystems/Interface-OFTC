<div class="modal fade bs-example-modal-sm" tabindex="-1" id="eliminar-estudiostemps" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title modalPersonalizado">Eliminar</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('importarCobranza.destroy') }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <p>Para completar la acción Elimininar, confirme escribiendo la palabra 
                    <strong>OFTALMOCENTER</strong></p>   
                    <p>¿Desea eliminar los registros con status: Completado?</p>
                    <P><input type="text" class="form-control" name="palabra_clave" placeholder="Ingrese la palabra clave" required></P>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-outline-secondary btn-xs">Cancelar</button>
                <button type="submit" class="btn btn-outline-info btn-xs">Continuar</button>
            </div>
            </form>
        </div>
        <!--final content modal-->
    </div>
</div>