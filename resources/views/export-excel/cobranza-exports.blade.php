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
        <tbody>
            <tr>
                <td style="text-align: center; font-size: 30px;" colspan="13">Oftalmo<b>Center</b></td>
            </tr>
            <tr>
                <td style="text-align: center; font-size: 15px;" colspan="13"><b>Reporte de Estudios</b></td>
            </tr>
        </tbody>
    </table>
    <table cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th><b>Folio</b></th>
                <th><b>Fecha</b></th>
                <th><b>Paciente</b></th>
                <th><b>Estudio</b></th>
                <th><b>Realizado Por</b></th>
                <th><b>Tipo de Ojo</b></th>
                <th><b>Doctor</b></th>
                <th><b>Transcrito</b></th>
                <th><b>Transcrito Por:</b></th>
                <th><b>Interpretado</b></th>
                <th><b>Interpretado Por:</b></th>
                <th><b>Escaneado</b></th>
                <th><b>Cantidad</b></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $dCobranza)
            <tr>
                <td>{{ $dCobranza->folio }}</td>
                <td>{{ date('d-m-Y',strtotime($dCobranza->fecha)) }}</td>
                <td>{{ $dCobranza->paciente }}</td>
                <td>{{ $dCobranza->descripcion}}</td>
                <td>{{ $dCobranza->EmpleadoRealiza}}</td>
                <td>{{ $dCobranza->nombretipo_ojo }}</td>
                <td>{{ $dCobranza->Doctor}}</td>
                <td>{{ $dCobranza->Transcripcion }}</td>
                <td>{{ $dCobranza->empleadoTrans }}</td>
                <td>{{ $dCobranza->Interpretacion }}</td>
                <td>{{ $dCobranza->empleadoInter }}</td>
                <td>{{ $dCobranza->Escaneado}}</td>
                <td>$ {{ $dCobranza->cantidadCbr}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>