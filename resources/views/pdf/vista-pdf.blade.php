<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Consumo - {{ $data->folio }}</title>
    <style>
    body {
        background-image: url("../resources/views/pdf/images/HOJA-OFTALMOCLINIC.jpg");
        background-size: cover;
        margin: 0px;
        padding: 0px;
        background-position: center center;
    }
    </style>
</head>
<header>
    <h1 style="text-align: center;">Detalle de Consumo - {{ $data->folio }}</h1>
</header>

<body>
    <dd>
        <p>
            <b>Fecha de Elaboración: </b>{{ $data->fechaElaboracion }}
            <br><b>Doctor: </b>{{ $data->Doctor }}
            <br><b>Paciente: </b>{{ $data->paciente }} ({{ $data->nombretipo_paciente }})
            <br><b>Método de Pago: </b>{{ $data->descripcion }}
        </p>
    </dd>
    <br>
    <table>
        <thead>
            <tr>
                <th style="text-align: center;">Código</th>
                <th>Descripción</th>
                <th>UM</th>
                <th style="text-align: center;">Cantidad</th>
                <th style="text-align: center;">Precio Unitario</th>
                <th style="text-align: center;">Importe</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($data2))
            @foreach($data2 as $productos)
            <tr>
                <td style="text-align: center;">{{ $productos->codigo }}</td>
                <td>{{ $productos->descripcion }}</td>
                <td style="text-align: center;">{{ $productos->um }}</td>
                <td style="text-align: center;">{{ number_format($productos->cantidad,2) }}</td>
                <td style="text-align: center;">$ {{ number_format($productos->precio_unitario,2) }}</td>
                <td style="text-align: center;">$ {{ number_format($productos->importe,2) }}</td>
            </tr>
            @endforeach
            @endif
            <tr>
                <td style="text-align: right;" colspan="5"><b>Total: </b></td>
                <td>$ {{ number_format($sumImporte,2) }}</td>
            </tr>
            <tr>
                <td style="text-align: right;" colspan="5"><b>Comisión: </b></td>
                <td>$ {{ number_format($sumImporte,2) }}</td>
            </tr>
            <tr>
                <td style="text-align: right;" colspan="5"><b>Total con Comisión (Efectivo): </b></td>
                <td>$ {{ number_format($finalPorcentaje,2) }}</td>
            </tr>
            <tr>
                <td style="text-align: right;" colspan="5"><b>Total con Comisión (Transferencia): </b></td>
                <td>$ {{ number_format($finalPorcentaje,2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
<footer>
</footer>

</html>