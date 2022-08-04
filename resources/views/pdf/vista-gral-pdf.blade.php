<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico Detalle de Consumo</title>
    
    <style>
        body {
            background-image: url("../resources/views/pdf/images/HOJA-OFTALMOCLINIC.jpg");
            background-size: cover;
            font-family: Verdana, Arial, Helvetica, sans-serif;
            margin: -15px;
            padding: -15px;
            background-position: center center;
        }

        table {
            border-collapse: collapse;
            border: #b2b2b2 1px solid;
        }

        td, th {
            border: black 1px solid;
        }
    </style>
</head>
<header>
    <h1 style="text-align: center;">Histórico Detalle de Consumo</h1>
</header>

<body>
    <br><br><br><br><br><br>
    <table width="100%">
        <thead>
            <tr>
                <th style="text-align: center;">Folio</th>
                <th>Fecha Cirugía</th>
                <th>Doctor</th>
                <th style="text-align: center;">Paciente</th>
                <th style="text-align: center;">Tipo Cirugía</th>
                <th style="text-align: center;">Status</th>
                <th style="text-align: center;">Importe</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($hojasConsumo))
            @foreach($hojasConsumo as $hojas)
            <tr>
                <td style="text-align: center;">{{ $hojas->folio }}</td>
                <td style="text-align: center;">{{ date('d-M-Y',strtotime($hojas->fechaElaboracion)) }}</td>
                <td style="text-align: center;">{{ $hojas->Doctor }}</td>
                <td style="text-align: center;">{{ $hojas->paciente }} ({{ $hojas->nombretipo_paciente }}) </td>
                <td style="text-align: center;">{{ $hojas->cirugia }}</td>
                <td style="text-align: center;">{{ $hojas->statusHoja }}</td>
                <td style="text-align: center;">${{ number_format($hojas->cantidadEfe,2) }}</td>
            </tr>
            @endforeach
            @endif
            <tr>
                <td style="text-align: right;" colspan="5"><b>Total Pendiente: </b></td>
                <td style="text-align: right;" colspan="2">${{ number_format($sumPendiente,2) }}</td>
            </tr>
            <tr>
                <td style="text-align: right;" colspan="5"><b>Total Pagado: </b></td>
                <td style="text-align: right;" colspan="2">${{ number_format($sumPagado,2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
<footer>
</footer>

</html>