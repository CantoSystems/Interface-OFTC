<div class="modal fade bs-example-modal-md" tabindex="-1" id="nvoUsuario" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title modalPersonalizado">Usuarios</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <!---->
            <form action="{{ route('usuarios.store') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Nombre de usuario</label>
                                <input type="text" name="usuario_nombre" class="form-control" onkeypress="return validar(event)">
                                @error('usuario_nombre')
                                <div class="alert alert-secondary">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="usuario_email" class="form-control">
                                @error('usuario_email')
                                <div class="alert alert-secondary">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label> Rol del usuario</label>
                                <select name="role_usuario" class="custom-select combos">
                                    <option disabled selected>-- Selecciona una opción --</option>
                                        @foreach($roles as $rol)
                                            <option value="{{ $rol->id}}">
                                                {{ $rol->descripcion_rol }}
                                            </option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Contraseña</label>
                                <input type="password" name="password" class="form-control">
                                @error('password')
                                <div class="alert alert-secondary">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Confirmar contraseña</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <button class="btn btn-block btn-outline-info btn-xs">Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>
            <!---->
            </div>
            <div class="modal-footer">
            </div>
            </form>
        </div>
        <!--final content modal-->
    </div>
</div>