<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Consumo - {{ $data->folio }}</title>
    <style >
        @page {
            margin: 0cm 0cm;
        }

        body {
            margin: 3cm 1cm 1cm;
        }

        table {
            border-collapse: collapse;
            border-width: 1px;
            border-style: solid;
            border-color: black;
        }

        th, td {
            border: 1px solid black;
        }

        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
            background-color: #2a0927;
            color: white;
            text-align: center;
            line-height: 30px;
        }
    </style>
</head>
<header>
<h1>Detalle de Consumo - {{ $data->folio }}</h1>
</header>
<body>
    <p>
        <b>Fecha de Elaboración: </b>{{ $data->fechaElaboracion }}
        <br><b>Doctor: </b>{{ $data->Doctor }}
        <br><b>Paciente: </b>{{ $data->paciente }} ({{ $data->nombretipo_paciente }})
        <br><b>Método de Pago: </b>{{ $data->descripcion }}
    </p>
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
                <td>$ {{ number_format($datosDC,2) }}</td>
            </tr>
            <tr>
                <td style="text-align: right;" colspan="5"><b>Total con Comisión: </b></td>
                <td>$ {{ number_format($finalPorcentaje,2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>