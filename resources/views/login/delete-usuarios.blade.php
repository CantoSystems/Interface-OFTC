<div class="modal fade" tabindex="-1" id="eliminar-{{$usuRol->identificadorUsuario}}" role="dialog" aria-hidden="true">
    <!--modal fade bs-example-modal-sm-->
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title modalPersonalizado" id="myModalLabel">Eliminar usuario: 
                    <strong> {{ $usuRol->usuario_nombre }}</strong>
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="" action="{{route('usuarios.destroy', $usuRol->identificadorUsuario)}}" method="post">
                    @method('DELETE')
                    @csrf
                    <div class="row">
                        <h3 class="modalPersonalizado">¿Estas seguro de eliminar este usuario?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button"  data-dismiss="modal" class="btn btn-block btn-outline-danger btn-xs">Cancelar</button>
                        <button  type="submit" class="btn btn-block btn-outline-info btn-xs">Continuar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>