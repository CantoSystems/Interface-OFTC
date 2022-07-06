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
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white bg-info" style="opacity: 0.6">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-info elevation-4" style="opacity: 0.6">
            <a href="#" class="brand-link">
                <img src="{{ asset('/AdminLTE-master/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: 0.8" />
                <span class="brand-text font-weight-light">Oftalmocenter</span>
            </a>

            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ asset('/AdminLTE-master/dist/img/user2-160x160.jpg') }}"
                            class="img-circle elevation-2" alt="User Image" />
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{ Auth::user()->usuario_nombre }}</a>
                        <form action="{{ route('usuarios.logout') }}" method="POST">
                            @csrf
                            <a href="#" onclick="this.closest('form').submit()" class="d-block">Cerrar sesión</a>
                        </form>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" ole="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Estudios
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('importarCobranza.index')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Subir Archivo Cobranza</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('importarCitas.index')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Subir Archivo Citas</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('importarCobranza.verTabla')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Generar Reporte</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" ole="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Detalle de Consumo
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('subirarchivoD.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Subir archivo Excel</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('mostrarHojas.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Ver Hojas de Consumo</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" ole="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Comisiones
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('comisionesGral.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Calcular Comisiones Generales</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('comisiones.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Calcular Comisiones Individuales</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" ole="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Catálogos
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('mostrarCatalogoGral.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Estudios Generales</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('mostrarCatalogo.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Estudios</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('mostrarEmpleados.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Empleados</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('mostrarComisiones.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Comisiones</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                        </div>
                    </div>
                </div>
            </section>

            <div class="content">
                @yield('content')
            </div>
        </div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block"><b>Version</b> 1.0</div>
            <strong>Canto Contadores &copy; 2022</strong>
            All rights reserved.
        </footer>
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
    <script type="text/javascript">
    $(document).ready(function() {
        /*$("#empleadoComision").change(function() {
            var texto = $(this).find('option:selected').text();
            if(texto.includes('DOCTOR')){
                $("#divComision").css("display", "block");
            }else{
                $("#divComision").css("display", "none");
            }
        });*/

        //Datatables
        $(function() {
            $("#reporteCobranza").DataTable({
                "responsive": true,
                "autoWidth": false,
                "language": {
                    "lengthMenu": "Mostrando _MENU_ registros por página",
                    "zeroRecords": "No existen registros en la tabla",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No existen registros en la tabla",
                    "infoFiltered": "(filtrado por _MAX_ registros totales)"
                },
                ajax: {
                    url: "{{ route('importarCobranza.create') }}",
                    dataSrc: 'data'
                },
                columns: [{
                        data: 'folio'
                    },
                    {
                        data: 'paciente'
                    },
                    {
                        data: 'servicio'
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'on-off'
                    },
                    {
                        data: 'btn'
                    }
                ]
            });
        });
        $(function() {
            $("#genReportes").DataTable({
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
        $(function() {
            $("#catComisiones").DataTable({
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
        $(function() {
            $("#reporteCitas").DataTable({
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
        $(function() {
            $("#catComisionesGral").DataTable({
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
        $(function() {
            $("#catEstudios").DataTable({
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
        $(function() {
            $("#tableDetalle").DataTable({
                "responsive": true,
                "autoWidth": false,
                "language": {
                    "lengthMenu": "Mostrando _MENU_ registros por página",
                    "zeroRecords": "No existen registros en la tabla",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No existen registros en la tabla",
                    "infoFiltered": "(filtrado por _MAX_ registros totales)"
                },
                ajax: {
                    url: "{{ route('extraerDetalle.show') }}",
                    dataSrc: 'data',
                },
                columns: [{
                        data: 'codigo'
                    },
                    {
                        data: 'descripcion'
                    },
                    {
                        data: 'um'
                    },
                    {
                        data: 'cantidad'
                    },
                    {
                        data: 'precio_unitario'
                    },
                    {
                        data: 'importe'
                    }
                ]
            });
        });
        $('.transRdS').click(function() {
            $('#drTransc').attr("disabled", false);
            $("option").remove(".nullable");
        });
        $('.transRdN').click(function() {
            if ($(".transRdN").is(':checked')) {
                $('#drTransc').attr("disabled", true);
                $('#drTransc').append($("<option class='nullable'></option>").attr("selected", true)
                    .text("-- Selecciona una opción --"));
            }
        });
        $('.interSi').click(function() {
            $('#drInterpreta').attr("disabled", false);
            $("option").remove(".nullableInterpreta");
        });
        $('.interNo').click(function() {
            $('#drInterpreta').attr("disabled", true);
            $('#drInterpreta').append($("<option class='nullableInterpreta'></option>").attr("selected",
                true).text("-- Selecciona una opción --"));
        });
    });
    </script>
</body>

</html>