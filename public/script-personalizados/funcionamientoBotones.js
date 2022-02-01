 //Funcionamiento de los botones de los catalogos Actualizar y registrar
 $(function(){
    $('#nuevo').click(function(){
      $('#actualizar_reg').hide();
      $('#guardar_falso').hide();
      $('#buscar').attr("disabled", true);
      $('#nuevo_reg').show();
      $('#cancelar_reg').attr("disabled", false);
      $('input[type="text"]').val('');
      $('input[type="date"]').val('');
      $('input[type="number"]').val('');
      $("input:checkbox").removeAttr("checked");
      $("#naturaleza > option[value=1]").attr("selected",true);
      $("#manejo > option[value=1]").attr("selected",true);
      $('#primero').attr("disabled", true);
      $('#atras').attr("disabled", true);
      $('#siguiente').attr("disabled", true);
      $('#ultimo').attr("disabled", true);
      $('#principal').attr("disabled", true);
      $('#opcion').show();
      $("#areas > option[value=1]").attr("selected",true);
      $('#first_areas').hide();
      $('#actualizar').attr("disabled", true);
      $('#eliminar').addClass("not-active");
      $('#buscar').attr("disabled", true);
    });
    $('#actualizar').click(function(){
      $('#nuevo_reg').attr("disabled", true);
      $('#actualizar_reg').show();
      $('#guardar_falso').hide();
      $('#cancelar_reg').attr("disabled", false);
      $('#primero').attr("disabled", true);
      $('#atras').attr("disabled", true);
      $('#siguiente').attr("disabled", true);
      $('#ultimo').attr("disabled", true);
      $('#nuevo').attr("disabled", true);
      $('#eliminar').addClass("not-active");
      $('#buscar').attr("disabled", true);
    });
    $('.actualizarporid').click(function(){
      $('.prueba').attr("disabled", false)
      $('.actualizarporid').attr("disabled", true);
      $('#primero').attr("disabled", true);
      $('#atras').attr("disabled", true);
      $('#siguiente').attr("disabled", true);
      $('#ultimo').attr("disabled", true);
    });
  })