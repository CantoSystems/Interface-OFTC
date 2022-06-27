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
                <th><b>Estudio</b></th>
                <th><b>Paciente</b></th>
                <th><b>Fecha de Estudio</b></th>
                <th><b>Cantidad</b></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $dComisiones)
            <tr>
                <td>{{ $dComisiones->dscrpMedicosPro }}</td>
                <td>{{ $dComisiones->paciente }}</td>
                <td>{{ date('d-m-Y',strtotime($dComisiones->fechaEstudio)) }}</td>
                <td>$ {{ number_format($dComisiones->cantidad,2) }}</td>
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
                <td><b>Total:</b></td>
                <td>$ {{ number_format($total,2) }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>