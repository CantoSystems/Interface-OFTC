<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-ms">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title modalPersonalizado" id="exampleModalLabel" class="modalPersonalizado">Reporte de Citas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('importarCitas.import') }}" method="post" enctype="multipart/form-data">
          @csrf
          <div class="col-md-12">
              <h5 class="card-title modalPersonalizado">Selecciona el archivo Excel:</h5>
          </div>
          <br>
          <div class="row">
            <div class="col-md-2">
            </div>
            <div class="col-md-8">
                <br>
                <input type="file" accept=".xlsx" class="form-control" name="file">
            </div>
            <div class="col-md-2">
            </div>
            <div class="col-md-2">
            </div>
            <div class="col-md-8">
                <br>
                <button class="btn btn-block btn-outline-secondary btn-xs" name="accion" value="importar">Importar Reporte</button>
            </div>
            <div class="col-md-2">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>