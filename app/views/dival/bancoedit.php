<div id="modalBancoEdit" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title fw-bolder" id="modalBancoEdit">Editar Banco</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" ara-label="Close"></button>
			</div>
			<form role="form" id="formBancoEdit" method="post">
            <input type="hidden" id="modulo" name="modulo" value="dival"/>
            <input type="hidden" id="option" name="option" value="bancos"/>
            <input type="hidden" id="action" name="action" value="update"/>
            <input type="hidden" id="editId" name="id">
				<div class="modal-body h-100 p-0">
               <div class="card mb-0">
                  <div class="card-body p-4">
                     <div class="row g-3">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="editCodigo" class="text-label fs-0 ps-1">Código *</label>
                              <input type="text" class="form-control" id="editCodigo" name="codigo">
                           </div>
                        </div>

                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="editIniciales" class="text-label fs-0 ps-1">Iniciales</label>
                              <input type="text" class="form-control" id="editIniciales" name="iniciales">
                           </div>
                        </div>
                        <div class="col-md-12">
                           <div class="form-group">
                              <label for="editNombre" class="text-label fs-0 ps-1">Nombre *</label>
                              <input type="text" class="form-control" id="editNombre" name="nombre">
                           </div>
                        </div>

                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-phoenix-primary" id="btnBancoEdit">Guardar</button>
               <button type="button" class="btn btn-phoenix-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
         </form>
      </div>
   </div>
</div>