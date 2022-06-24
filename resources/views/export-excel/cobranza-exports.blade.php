<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>Folio</th>
                <th>Fecha</th>
                <th>Paciente</th>
                <th>Estudio</th>
                <th>Tipo de Ojo</th>
                <th>Doctor</th>
                <th>Transcrito</th>
                <th>Transcrito Por:</th>
                <th>Interpretado</th>
                <th>Interpretado Por:</th>
                <th>Escaneado</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $dCobranza)
            <tr>
                <td>{{ $dCobranza->folio }}</td>
                <td>{{ date('d-m-Y',strtotime($dCobranza->fecha)) }}</td>
                <td>{{ $dCobranza->paciente }}</td>
                <td>{{ $dCobranza->descripcion}}</td>
                <td>{{ $dCobranza->nombretipo_ojo }}</td>
                <td>{{ $dCobranza->Doctor}}</td>
                <td>{{ $dCobranza->Transcripcion }}</td>
                <td>{{ $dCobranza->empleadoTrans }}</td>
                <td>{{ $dCobranza->Interpretacion }}</td>
                <td>{{ $dCobranza->empleadoInter }}</td>
                <td>{{ $dCobranza->Escaneado}}</td>
                <td>{{ $dCobranza->cantidadCbr}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>