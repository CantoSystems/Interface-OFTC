<div class="modal fade bs-example-modal-sm" tabindex="-1" id="usuarios" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title modalPersonalizado">Usuarios</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table  id="usuariosModal" name="usuarios" class="table table-bordered table-striped"> 
                    <thead>
                        <tr>
                            <th>Nombre de usuario</th>
                            <th>Email</th>
                            <th>Ver</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($usuarios)
                            @foreach($usuarios as $user)
                            <tr>
                                <td>{{$user->usuario_nombre}}</td>
                                <td>{{$user->usuario_email}}</td>
                                <td>
                                    <a href="{{ route('usuarios.edit',$user->id)}}">
                                        <button class="btn btn-block btn-outline-info btn-xs">
                                        <i class="far fa-eye"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        @endisset
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
            </div>
            </form>
        </div>
        <!--final content modal-->
    </div>
</div>