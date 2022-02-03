//funcionaiento del datarange de los input tipo fecha
      //se encuentran en modal empleados
      $(document).ready(function(){
        $('#duracion').daterangepicker();
        $('#duracion2').daterangepicker();
        $('#duracion3').daterangepicker();
        $('#duracion4').daterangepicker();
        $('#duracion5').daterangepicker();
        $('#duracion6').daterangepicker();
        $('#duracion7').daterangepicker();
        $('#duracion8').daterangepicker();
        $('#duracion9').daterangepicker();
        //Máscara de Telefonos para empleados
        $('.telefono_referencia').mask('(00) 0000-0000');
        $('.telefono_referencia1').mask('(00) 0000-0000');
        $('.telefono_referencia2').mask('(00) 0000-0000');
        $('.telefono_empleado').mask('(00) 0000-0000');
        $('.telefono_compania').mask('(00) 0000-0000');
        $('.telefono1').mask('(00) 0000-0000');
        $('.telefono2').mask('(00) 0000-0000');
        $('.telefono3').mask('(00) 0000-0000');
    });