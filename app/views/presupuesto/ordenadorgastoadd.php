<div id="modalOrdenadorGastoAdd" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title fw-bolder" id="modalOrdenadorGastoAddTit">Adicionar Ordenador del Gasto</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form class="needs-validation" role="form" id="formOrdenadorGastoAdd" method="post" novalidate>
            <input type="hidden" id="modulo" name="modulo" value="presupuesto"/>
            <input type="hidden" id="option" name="option" value="ordenadorgasto"/>
            <input type="hidden" id="action" name="action" value="create"/>
				<div class="modal-body h-100 p-0">
               <div class="card mb-0">
                  <div class="card-body p-4">
                     <div class="row g-3">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="newid" class="text-label fs-0 ps-1">Identificación *</label>
                              <input type="text" class="form-control" id="newid" name="id" required maxlength="12">
                           </div>
                        </div>   
                        
                        <div class="col-md-6 d-flex align-items-end">
                              <div class="form-check">
                                 <input class="form-check-input" id="newVigente" name="vigente" type="checkbox" value="0" >
                                 <label class="form-check-label" for="newVigente">Ordenador Vigente</label>
                              </div>
                            </div>   

                        <div class="col-md-12">
                           <div class="form-group">
                              <label for="newNombre" class="text-label fs-0 ps-1">Nombre </label>
                              <input type="text" class="form-control" id="newNombre" name="nombre" readonly require maxlength="100">
                           </div>
                        </div>

                        <div class="col-md-12">
                           <div class="form-group">
                              <label for="newCargo" class="text-label fs-0 ps-1">Cargo *</label>
                              <input type="text" class="form-control" id="newCargo" name="cargo" required maxlength="150">
                           </div>
                        </div>

                       <div class="col-md-12">
                           <div class="form-group">
                              <label for="newDireccion" class="text-label fs-0 ps-1">Dirección </label>
                              <input type="text" class="form-control" id="newDireccion" name="direccion" readonly>
                           </div>
                        </div>

                         <div class="col-md-6">
                           <div class="form-group">
                              <label for="newTelefono1" class="text-label fs-0 ps-1">Telefonos</label>
                              <input type="text" class="form-control" id="newTelefono1" name="telefono1" readonly>
                           </div>
                        </div>
                        <div class="col-md-6 align-items-end">
                           <div class="form-group">
                              <label for="newTelefono2" class="text-label fs-0 ps-1"></label>
                              <input type="text" class="form-control" id="newTelefono2" name="telefono2" readonly>
                           </div>
                        </div>
                        
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-phoenix-primary" id="btnOrdenadorGastoAdd">Guardar</button>
               <button type="button" class="btn btn-phoenix-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
         </form>
      </div>
   </div>
</div>
