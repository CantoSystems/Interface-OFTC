<div class="modal fade bs-example-modal-sm" tabindex="-1" id="eliminar-comision" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title modalPersonalizado">Eliminar Comisión</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('dltComision.delete') }}" method="POST">
                <div class="modal-body">
                    @method('DELETE')
                    @csrf
                    <p style="text-align: justify;">¿Estás seguro que deseas eliminar la comisión de <b>{{ $comision->empleado }}</b> por el estudio
                        <b>{{ $comision->dscrpMedicosPro }}</b>?
                    </p>
                    <input type="hidden" id="idComision" name="idComision" value="{{ $comision->id }}">
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