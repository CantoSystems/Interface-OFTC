<!DOCTYPE html>
<html lang="es">

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
                <td style="text-align: center; font-size: 30px;" colspan="6">Oftalmo<b>Center - Cálculo de
                        Utilidades</b></td>
            </tr>
            <tr></tr>
            <tr>
                <td><b>Empleado:</b></td>
                <td>Dr. {{ $emp }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <table cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th><b>Estudio</b></th>
                <th><b>Paciente</b></th>
                <th style="text-align: center;"><b>Fecha de Estudio</b></th>
                <th style="text-align: center;"><b>Precio Estudio</b></th>
                <th style="text-align: center;"><b>Porcentaje Utilidad</b></th>
                <th style="text-align: center;"><b>Total</b></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $dComisiones)
            <tr>
                <td>{{ $dComisiones->dscrpMedicosPro }}</td>
                <td>{{ $dComisiones->paciente }}</td>
                <td style="text-align: center;">{{ date('d-M-Y',strtotime($dComisiones->fechaEstudio)) }}</td>
                <td style="text-align: right;">$ {{ number_format($dComisiones->cantidad,2) }}</td>
                <td style="text-align: right;">{{ number_format($dComisiones->porcentaje,2) }} %</td>
                <td style="text-align: right;">$ {{ number_format($dComisiones->total,2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <table>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right; font-size: 13px;"><b>Total:</b></td>
                <td style="text-align: right; font-size: 13px;"><b>$ {{ number_format($total,2) }}</b></td>
            </tr>
        </tbody>
    </table>
</body>

</html>