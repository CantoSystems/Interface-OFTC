<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <p style="text-align: justify;">Estimado <b>{{ $data->Doctor }}</b>, 
       <br>
       Mediante el presente correo, anexamos el detalle de su consumo de insumos que utilizó con el paciente <b>{{ $data->paciente }}</b> el día <b>{{ $data->fechaElaboracion }}</b>.
       <br><br>
       Cualquier cuestión estamos a sus órdenes.
       <br><br>
       Atte: Martha
       <br>
       Administrativa
    </p>
</body>

</html>