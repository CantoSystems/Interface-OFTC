<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title modalPersonalizado" id="exampleModalLabel" class="modalPersonalizado">Reporte de Cobranza</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <!--Contenido-->

            <form action="{{ route('subirReporte.import') }}" method="post" enctype="multipart/form-data">
                @csrf
            
                <div class="col-md-12">
                    <h5 class="card-title modalPersonalizado">Selecciona el archivo Excel:</h5>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-8">
                        <br>
                        <input type="file" name="file">
                    </div>
                    <div class="col-md-8">
                        <br>
                        <button>Importar Reporte</button>
                    </div>
                </div>
            </form>

        </div>
        <div class="modal-footer">
          
        </div>
            </form>
      </div>
    </div>
  </div>