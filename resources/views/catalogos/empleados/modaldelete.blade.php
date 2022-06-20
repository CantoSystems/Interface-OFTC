<div class="modal fade bs-example-modal-sm" tabindex="-1" id="eliminar-empleado" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title modalPersonalizado">Eliminar Empleado</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('dltEmpleado.destroy') }}" method="POST">
                <div class="modal-body">
                    @method('DELETE')
                    @csrf
                    <p>¿Estás seguro que deseas eliminar al empleado: <b id="nombreEstudio"
                            name="nombreEstudio">{{ $empleado->empleado }}</b>?
                    </p>
                    <input type="hidden" id="idEmpleadoDel" name="idEmpleadoDel" value="{{ $empleado->id_emp }}">
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