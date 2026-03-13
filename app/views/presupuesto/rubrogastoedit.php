<div id="modalRubroGastoEdit" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title fw-bolder" id="modalRubroGastoEdit">Editar Rubro de Gasto</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form role="form" id="formRubroGastoEdit" method="post">
            <input type="hidden" id="modulo" name="modulo" value="presupuesto"/>
            <input type="hidden" id="option" name="option" value="rubrogasto"/>
            <input type="hidden" id="action" name="action" value="update"/>
            <input type="hidden" id="editId" name="id">
				<div class="modal-body h-100 p-0">
               <div class="card mb-0">
                  <div class="card-body p-4">
                     <div class="row g-3">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="editCodigo" class="text-label fs-0 ps-1">Código *</label>
                              <input type="text" class="form-control" id="editCodigo" name="codigo" readonly>
                           </div>
                        </div>

                             <div class="col-md-6 d-flex align-items-end">
                              <div class="form-check">
                                 <input class="form-check-input" id="editMovimiento" name="movimiento" type="checkbox" value="0" >
                                 <label class="form-check-label" for="editMovimiento">Codigo de Movimiento</label>
                              </div>
                            </div> 

                        <div class="col-md-12">
                           <div class="form-group">
                              <label for="editNombre" class="text-label fs-0 ps-1">Nombre *</label>
                              <input type="text" class="form-control" id="editNombre" name="nombre" maxlength="200">
                           </div>
                        </div>
                         <div class="col-md-12">
                           <div class="form-group">
                              <label for="editTipoFinanciacion" class="text-label fs-0 ps-1">Tipo de Financiación</label>
                              <select class="form-control" style="width: 100%;" id="editTipoFinanciacion" name="tipofinanciacion">
                                 <option value="">Seleccionar</option>
                              </select>
                           </div>
                        </div>

                        <div class="col-md-12">
                           <div class="form-group">
                              <label for="editCtaPucId" class="text-label fs-0 ps-1">Cta Puc</label>
                              <select class="form-control" style="width: 100%;" id="editCtaPucId" name="ctapucid">
                                 <option value="">Seleccionar</option>
                              </select>
                           </div>
                        </div>

                        

                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-phoenix-primary" id="btnRubroGastoEdit">Guardar</button>
               <button type="button" class="btn btn-phoenix-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
         </form>
      </div>
   </div>
</div>

<script>
   $(document).ready(function() {
      $('#editTipoFinanciacion').select2({
         dropdownParent: $('#modalRubroIngresoEdit'),
         width: '100%'
      });
      
      $('#editCtaPucId').select2({
         dropdownParent: $('#modalRubroGastoEdit'),
         width: '100%'
      });
      

   });
</script>