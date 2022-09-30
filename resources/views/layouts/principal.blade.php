<!DOCTYPE html>
<html lang="es">

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
                    @canany(['comisiones','cobranzaReportes','auxiliarCobranzaReportes','optometria'])
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
                                    <a href="{{ route('importarCobranza.verTabla')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Historial Cobranza de Estudios</p>
                                    </a>
                                </li>
                            </ul>
                            @endcanany
                            @canany(['comisiones','cobranzaReportes'])
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('importarCitas.index')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Subir Archivo Citas</p>
                                    </a>
                                </li>
                            </ul>

                        </li>
                    </ul>
                    @endcanany

                    @canany(['comisiones','detalleConsumo','auxiliardetalleConsumo'])
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
                                        <p>Subir Archivo Excel</p>
                                    </a>
                                </li>
                            </ul>

                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('viewHojas.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Historial Hojas de Consumo</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('mostrarPorcentajes.show') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Porcentajes Doctores</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    @endcanany

                    @canany(['comisiones'])
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
                                    <a href="{{ route('comisiones.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Calcular Comisiones</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    @endcan

                    @canany(['comisiones','cobranzaReportes','optometria'])
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
                                        <p>Estudios Especificos</p>
                                    </a>
                                </li>
                            </ul>
                            @endcanany
                            @canany(['comisiones','cobranzaReportes'])

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
                    @endcan

                    @canany(['administrador'])
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" ole="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Administrar usuarios
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('usuarios.administrar')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Roles</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    @endcanany
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
                @can('invitado')
                <div class="alert alert-danger" role="alert">
                    No cuenta con los privilegios para acceder a los módulos del sistema
                </div>
                @endcan
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
    function validar(e) {
        tecla = (document.all) ? e.keyCode : e.which;
        if (tecla == 8) return true;
        patron = /[A-Za-z\s]/;
        te = String.fromCharCode(tecla);
        return patron.test(te);
    }

    $(document).ready(function() {
        $('#porcentajeComision').val(0);
        $('#cantidadComision').val(0);
        $('#utilidadComision').val(0);
        $('#precioEstudio').val(0);
        $("#empleadoComision").change(function() {
            let texto = $(this).find('option:selected').text();
            if (texto.includes('DOCTOR')) {
                $("#divComision").css("display", "block");
            } else {
                $("#divComision").css("display", "none");
            }
        });

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

    let i = 1;
    $('#grdrInt').click(function(e) {
        i++;
        e.preventDefault();
        let idEstudio = $('#estudioInt').val();
        let idDoctor = $('#doctorInt').val();
        let folioEst = $('#folioCbr').val();
        let estudioInt = $('select[name="estudioInt"] option:selected').text();
        let doctorInt = $('select[name="doctorInt"] option:selected').text();

        if (idEstudio != "" && idDoctor != "" && estudioInt != "" && doctorInt != "" && estudioInt !=
            "-- Selecciona una opción --" && doctorInt != "-- Selecciona una opción --") {
            let htmlTags = '<tr>' +
                '<td>' +
                    '<input type="hidden" class="estudioI" value="' + idEstudio + '">' +
                    '<input type="hidden" class="folioEst" value="' + folioEst + '">' +
                    estudioInt +
                '</td>' +
                '<td>' +
                    '<input type="hidden" class="doctorI" value="' + idDoctor + '">' + 
                    doctorInt + 
                '</td>' +
                '<td class="elimina" style="text-align: center; width:40px; height:25px;">' + 
                    '<button class="borrar_int" type="button" style="width:40px; height:25px">' + 
                        '<i class="far fa-trash-alt"></i>' +
                    '</button>' +
                '</td>' +
                '</tr>'
            $('#example13 tbody').append(htmlTags);
            $('#estudioInt option').prop('selected', function() {
                return this.defaultSelected;
            });
            $('#doctorInt option').prop('selected', function() {
                return this.defaultSelected;
            });
        } else {
            alert("Falta seleccionar estudio y/o doctor.");
        }
    });

    $('#grdrCompleto').click(function (e){
        let myTableArrayInt = [];
        document.querySelectorAll('.example13 tbody tr').forEach(function(e){
            let filas = {
                estudioI: e.querySelector('.estudioI').innerText,
                doctorI: e.querySelector('.doctorI').innerText,
                folioE: e.querySelector('.folioEst').innerText
            };
            myTableArrayInt.push(filas);
        });
        let jsonStringa = JSON.stringify(myTableArrayInt);
        $.ajax({
            url: "{{ route('interpretaciones.store') }}",
            method: "POST",
            data: {
                _token: $("meta[name='csrf-token']").attr("content"),
                info : jsonStringa,
            },
            success: function(data){
                console.log(data);
                $(".example13 tbody tr").closest('tr').remove();
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                console.log(err.Message);
            }
        });
    });

    $(document).on('click', '.borrar_int', function (event) {
        event.preventDefault();
        $(this).closest('tr').remove();
        let fecha = new Date(); //Fecha actual
        let mes = fecha.getMonth()+1; //obteniendo mes
        let dia = fecha.getDate()-1; //obteniendo dia
        let ano = fecha.getFullYear(); //obteniendo año
        if(dia<10)
            dia='0'+dia; //agrega cero si el menor de 10
        if(mes<10)
            mes='0'+mes //agrega cero si el menor de 10
        document.getElementById('fecha_ausentismo').value=ano+"-"+mes+"-"+dia;
    });
    </script>

    <script>
    //Función para convertir en texto en mayusculas
    function mayus(e) {
        e.value = e.value.toUpperCase();
    }

    function borrarHoja() {
        $('#modalDoctor').append($('#doctorHoja').val());
        $('#idHojaConsumo').val($('#idHojaDlt').val());
        alert($('#idHojaConsumo').val());
    }
    </script>
</body>

</html>