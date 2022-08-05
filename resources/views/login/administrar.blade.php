<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Interfaz | Oftalmocenter</title>

    <link rel="stylesheet" href="{{ asset('/AdminLTE-master/plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="{{ asset('/AdminLTE-master/plugins/fontawesome-free/css/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{ asset('/AdminLTE-master/dist/css/adminlte.min.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css')}}">
    <link rel="stylesheet"
        href="{{ asset('/AdminLTE-master/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('/AdminLTE-master/plugins/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet"
        href="{{ asset('/AdminLTE-master/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('estilos-personalizados/estilos.css')}}">
    <style>
        select.custom-select{
            width: 100%;
            height: calc(1.75rem + 0.5px);
            padding: .375rem 1.75rem .375rem .75rem;
            font-size: 0.9em;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-info">
            <div class="card-header text-center">
                <a href="#" class="h6"><b>OftalmoCenter</a>
            </div>
            <div class="card-header text-center">
            <div>
                <button type="button" class="btn btn-block btn-outline-info btn-xs" data-toggle="modal" data-target="#nvoUsuario">
                        Agregar
                </button>
                <button type="button" class="btn btn-block btn-outline-secondary btn-xs" data-toggle="modal" data-target="#usuarios">
                        Consultar
                </button>
               
            </div>
            </div>
            <div class="card-body">
                <form action="{{ route('usuarios.update',$usuRol->identificadorUsuario ?? '') }}" method="POST" autocomplete="off">
                    @csrf
		            @method('PATCH')
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Nombre de usuario</label>
                                <input type="text" name="usuario_nombre" class="form-control" 
                                    onkeypress="return validar(event)" value="{{ $usuRol->usuario_nombre ?? ''}}">
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
                                <input type="email" name="usuario_email" class="form-control" value="{{ $usuRol->usuario_email ?? ''}}">
                                @error('usuario_email')
                                <div class="alert alert-secondary">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                @if(isset($usuRol))
                                <label>Seleccionar Rol del usuario</label>
                                    <select class="custom-select" name="role_usuario">
                                        <option disabled selected>Seleccionar una opción...</option>
                                            @foreach($roles as $rol)
                                            @if($rol->id == $usuRol->identificadorRol)
                                                <option selected value="{{ $rol->id}}">
                                                    {{ $rol->descripcion_rol }}
                                                </option>
                                            @else
                                                <<option value="{{ $rol->id}}">
                                                    {{ $rol->descripcion_rol }}
                                                </option>
                                            @endif
                                            @endforeach
                                    </select>
                                @else
                                    <label>Selecciona Rol del usuario</label>
                                    <select class="custom-select">
                                        <option disabled selected>-- Selecciona una opción --</option>
                                            @foreach($roles as $rol)
                                                <option value="{{ $rol->id}}">
                                                    {{ $rol->descripcion_rol }}
                                                </option>
                                            @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            
                                <div class="form-group">
                                    @if(isset($usuRol))
                                    <label>Activar / desactivar usuario   </label>
                                        @if($usuRol->usuario_status == 1)
                                        <div class="icheck-secondary d-inline">
                                            <input checked type="radio" value="1" name="usuario_status" >
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-secondary d-inline">
                                            <input type="radio" value="0" name="usuario_status" >
                                            <label>NO</label>
                                        </div>
                                        @elseif($usuRol->usuario_status == '0')
                                        <div class="icheck-secondary d-inline">
                                            <input type="radio" value="1" name="usuario_status" class="usuario_statusS">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-secondary d-inline">
                                            <input checked type="radio" value="0" name="usuario_status" class="usuario_statusN">
                                            <label>NO</label>
                                        </div>
                                        @else
                                        <div class="icheck-secondary d-inline">
                                            <input type="radio" value="1" name="usuario_status" class="usuario_statusS">
                                            <label>SI</label>
                                        </div>
                                        <div class="icheck-secondary d-inline">
                                            <input type="radio" value="0" name="usuario_status" class="usuario_statusN">
                                            <label>NO</label>
                                        </div>
                                        @endif
                                    @else
                                    @endif
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
                               <div class="row">
                                    <div class="col">
                                    <button class="btn btn-block btn-outline-info btn-xs">Actualizar</button>
                                    @if(isset($usuRol))
                                        <a href="#" data-target="#eliminar-{{$usuRol->identificadorUsuario}}" data-toggle="modal">
                                            <button class="btn btn-block btn-outline-danger btn-xs">Eliminar</button>
                                        </a>
                                    @endif
                                    </div>
                               </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
   

    <script src="{{ asset('/AdminLTE-master/plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('/AdminLTE-master/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('/AdminLTE-master/dist/js/adminlte.min.js')}}"></script>
    <script src="{{ asset('/AdminLTE-master/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('/AdminLTE-master/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{ asset('/AdminLTE-master/plugins/moment/moment.min.js')}}"></script>
    <script src="{{ asset('/AdminLTE-master/plugins/inputmask/jquery.inputmask.min.js')}}"></script>
    <script src="{{ asset('/AdminLTE-master/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    <script src="{{ asset('/AdminLTE-master/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('/AdminLTE-master/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('/AdminLTE-master/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $(function () {
            $("#usuariosModal").DataTable({
                "responsive": true,
                "autoWidth": false,
                "language": {
                    "lengthMenu": "Mostrando _MENU_ registros por página",
                    "zeroRecords": "No existen registros en la tabla",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No existen registros en la tabla",
                    "infoFiltered": "(filtrado por _MAX_ registros totales)"
                }
            });
        });

        });
         
    </script>
</body>
@include('login.usuarios')
@include('login.registro')
@if(isset($usuRol))
    @include('login.delete-usuarios')
@endif


</html>