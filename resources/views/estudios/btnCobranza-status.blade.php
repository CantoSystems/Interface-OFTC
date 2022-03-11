@if($estudiostemps_status == 0)
    <span class="badge badge-secondary">Pendiente</span>
@elseif($estudiostemps_status == 1)
<span class="badge badge-info">Actualizado</span>
@endif