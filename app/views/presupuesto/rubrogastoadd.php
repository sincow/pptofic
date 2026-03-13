<div id="modalRubroGastoAdd" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title fw-bolder" id="modalRubroGastoAddTit">Adicionar Codigo Presupuestal de Gasto</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form class="needs-validation" role="form" id="formRubroGastoAdd" method="post" novalidate>
            <input type="hidden" id="modulo" name="modulo" value="presupuesto"/>
            <input type="hidden" id="option" name="option" value="rubrogasto"/>
            <input type="hidden" id="action" name="action" value="create"/>
				<div class="modal-body h-100 p-0">
               <div class="card mb-0">
                  <div class="card-body p-4">
                     <div class="row g-3">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="newCodigo" class="text-label fs-0 ps-1">Codigo *</label>
                              <input type="text" class="form-control" id="newCodigo" name="codigo" required maxlength="20">
                           </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                              <div class="form-check">
                                 <input class="form-check-input" id="newMovimiento" name="movimiento" type="checkbox" value="0" >
                                 <label class="form-check-label" for="newMovimiento">Codigo de Movimiento</label>
                              </div>
                            </div>  

                        <div class="col-md-12">
                           <div class="form-group">
                              <label for="newNombre" class="text-label fs-0 ps-1">Nombre *</label>
                              <input type="text" class="form-control" id="newNombre" name="nombre" required maxlength="200">
                           </div>
                        </div>

                        <div class="col-md-12">
                           <div class="form-group">
                              <label for="newTipoFinanciacion" class="text-label fs-0 ps-1">Tipo de Financiación</label>
                              <select class="form-control" style="width: 100%;" id="newTipoFinanciacion" name="tipofinanciacion">
                                 <option value="">Seleccionar</option>
                              </select>
                           </div>
                        </div>

                        <div class="col-md-12">
                           <div class="form-group">
                              <label for="newCtaPucId" class="text-label fs-0 ps-1">Cta Puc</label>
                              <select class="form-control" style="width: 100%;" id="newCtaPucId" name="ctapucid">
                                 <option value="">Seleccionar</option>
                              </select>
                           </div>
                        </div>

                        

                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-phoenix-primary" id="btnRubroGastoAdd">Guardar</button>
               <button type="button" class="btn btn-phoenix-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
         </form>
      </div>
   </div>
</div>
<script>
   $(document).ready(function() {
      $('#newTipoFinanciacion').select2({
         dropdownParent: $('#modalRubroGastoAdd'),
         width: '100%'
      });

      $('#newCtaPucId').select2({
         dropdownParent: $('#modalRubroGastoAdd'),
         width: '100%'
      });

      
   });
</script>