//Validar solo ingresen texto
function validar(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8) return true;
    patron =/^[a-zA-z\s\ñ\Ñ]+$/;
    te = String.fromCharCode(tecla);
    return patron.test(te);
  }
  //Funcionamiento convertir texto en mayusculas
  function mayus(e) {
    e.value = e.value.toUpperCase();
  }
  //validar tipo de sangre
  function validasangre(e) {
  tecla = (document.all) ? e.keyCode : e.which;
  if (tecla==8) return true;
    patron =/[A-Za-z-+\s]/;
    te = String.fromCharCode(tecla);
    return patron.test(te);
  }
  //validar solo números
  function numeros(event) {
    if(event.charCode >=48 && event.charCode <=57 ){
      return true;
    }
    return false;
  }