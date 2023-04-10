@canany(['comisiones','cobranzaReportes'])
<a class="btn btn-block btn-outline-secondary btn-xs" href="{{ route('importarCobranza.eliminar',$id) }}">Eliminar</a>
@elsecanany(['auxiliarCobranzaReportes','optometria'])
<button class="btn btn-block btn-outline-secondary btn-x" disabled>Eliminar</button>
@endcanany