<div id="modalChipAdd" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title fw-bolder" id="modalChipAddTit">Adicionar Codigo Chip</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" ara-label="Close"></button>
			</div>
			<form class="needs-validation" role="form" id="formChipAdd" method="post" novalidate>
            <input type="hidden" id="modulo" name="modulo" value="Presupuesto"/>
            <input type="hidden" id="option" name="option" value="chip"/>
            <input type="hidden" id="action" name="action" value="create"/>
				<div class="modal-body h-100 p-0">
               <div class="card mb-0">
                  <div class="card-body p-4">
                     <div class="row g-3">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="newCodigo" class="text-label fs-0 ps-1">Codigo *</label>
                              <input type="text" class="form-control" id="newCodigo" name="codigo" required>
                           </div>
                        </div>
                        <!-- <div class="col-md-6">
                           <div class="form-group">
                              <label for="newIniciales" class="text-label fs-0 ps-1">Iniciales</label>
                              <input type="text" class="form-control" id="newIniciales" name="iniciales">
                           </div>
                        </div> -->

                        <div class="col-md-12">
                           <div class="form-group">
                              <label for="newNombre" class="text-label fs-0 ps-1">Nombre *</label>
                              <input type="text" class="form-control" id="newNombre" name="nombre" required>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-phoenix-primary" id="btnChipAdd">Guardar</button>
               <button type="button" class="btn btn-phoenix-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
         </form>
      </div>
   </div>
</div>