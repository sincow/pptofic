<div id="modalOrdenadorGastoEdit" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title fw-bolder" id="modalOrdenadorGastoEdit">Editar Ordenador de Gasto</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form role="form" id="formOrdenadorGastoEdit" method="post">
            <input type="hidden" id="modulo" name="modulo" value="presupuesto"/>
            <input type="hidden" id="option" name="option" value="ordenadorgasto"/>
            <input type="hidden" id="action" name="action" value="update"/>
            
				<div class="modal-body h-100 p-0">
               <div class="card mb-0">
                  <div class="card-body p-4">
                     <div class="row g-3">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="editId" class="text-label fs-0 ps-1">Identificación *</label>
                              <input type="text" class="form-control" id="editId" name="id" required maxlength="12">
                           </div>
                        </div>

                        <div class="col-md-6 d-flex align-items-end">
                           <div class="form-check">
                               <input class="form-check-input" id="editVigente" name="vigente" type="checkbox">
                               <label class="form-check-label" for="editVigente">Ordenador Vigente</label>
                           </div>
                        </div>   

                        <div class="col-md-12">
                           <div class="form-group">
                              <label for="editNombre" class="text-label fs-0 ps-1">Nombre </label>
                              <input type="text" class="form-control" id="editNombre" name="nombre" readonly >
                           </div>
                        </div>

                        <div class="col-md-12">
                           <div class="form-group">
                              <label for="editCargo" class="text-label fs-0 ps-1">Cargo *</label>
                              <input type="text" class="form-control" id="editCargo" name="cargo" required maxlength="150">
                           </div>
                        </div>

                       <div class="col-md-12">
                           <div class="form-group">
                              <label for="editDireccion" class="text-label fs-0 ps-1">Dirección </label>
                              <input type="text" class="form-control" id="editDireccion" name="direccion" readonly >
                           </div>
                        </div>

                         <div class="col-md-6">
                           <div class="form-group">
                              <label for="editTelefono1" class="text-label fs-0 ps-1">Telefonos</label>
                              <input type="text" class="form-control" id="editTelefono1" name="telefono1" readonly>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="editTelefono2" class="text-label fs-0 ps-1"></label>
                              <input type="text" class="form-control" id="editTelefono2" name="telefono2" readonly>
                           </div>
                        </div>
                        
                     </div>
                  </div>
                  
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-phoenix-primary" id="btnOrdenadorGastoEdit">Guardar</button>
               <button type="button" class="btn btn-phoenix-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
         </form>
      </div>
   </div>
</div>

