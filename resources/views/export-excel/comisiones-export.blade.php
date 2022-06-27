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
                <td><b>Empleado:</b></td>
                <td>{{ $emp }}</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <br>
    <table>
        <thead>
            <tr>
                <th><b>Estudio</b></th>
                <th><b>Paciente</b></th>
                <th style="text-align: center;"><b>Fecha de Estudio</b></th>
                <th style="text-align: center;"><b>Cantidad</b></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $dComisiones)
            <tr>
                <td>{{ $dComisiones->dscrpMedicosPro }}</td>
                <td>{{ $dComisiones->paciente }}</td>
                <td style="text-align: center;">{{ date('d-M-Y',strtotime($dComisiones->fechaEstudio)) }}</td>
                <td style="text-align: right;">$ {{ number_format($dComisiones->cantidad,2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <table>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td style="text-align: right;"><b>Total:</b></td>
                <td style="text-align: right;">$ {{ number_format($total,2) }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>