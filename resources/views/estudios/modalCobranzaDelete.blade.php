<div class="modal fade bs-example-modal-sm" tabindex="-1" id="eliminar-{{$datosPaciente->id}}" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title modalPersonalizado">Eliminar Comisión</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('importarCobranza.eliminar',$datosPaciente->id) }}" method="POST">
                <div class="modal-body">
                    @method('DELETE')
                    @csrf
                    <p style="text-align: justify;">¿Estás seguro que deseas eliminar el registro
                    {{ $datosPaciente->folio }}
                    {{ $datosPaciente->servicio }}
                    </b>?
                    </p>
                    <input type="hidden" id="idComision" name="idComision" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal"
                        class="btn btn-outline-secondary btn-xs">Cancelar</button>
                    <button type="submit" class="btn btn-outline-danger btn-xs">Continuar</button>
                </div>
            </form>
        </div>
    </div>
</div>