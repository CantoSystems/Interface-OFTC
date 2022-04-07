@if($estudiostemps_status == 0)
<span class="badge badge-secondary">Pendiente</span>
@elseif($estudiostemps_status == 1)
<span class="badge badge-info">Completado</span>
@elseif($estudiostemps_status == 2)
<span class="badge badge-secondary">Faltan datos</span>
@elseif($estudiostemps_status == 3)
<span class="badge badge-danger">Estudio no procesado</span>
@endif