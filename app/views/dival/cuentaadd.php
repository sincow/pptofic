<div id="modalCuentaAdd" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title fw-bolder" id="modalCuentaAddTit">Adicionar Cuenta Bancaria</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" ara-label="Close"></button>
			</div>
			<form class="needs-validation" role="form" id="formCuentaAdd" method="post" novalidate>
            <input type="hidden" id="modulo" name="modulo" value="bancos"/>
            <input type="hidden" id="option" name="option" value="cuentas"/>
            <input type="hidden" id="action" name="action" value="create"/>
				<div class="modal-body h-100 p-0">
               <div class="card mb-0">
                  <div class="card-body p-4">
                     <div class="row g-3">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="newBanCodNa" class="text-label fs-0 ps-1">Codigo Nacional *</label>
                              <select class="form-control" style="width: 100%;" id="newBanCodNa" name="BanCodNa" required>
                                 <option value="">Seleccionar</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label for="newBanCuent" class="text-label fs-0 ps-1">Número Cuenta *</label>
                              <input type="text" class="form-control" id="newBanCuent" name="BanCuent" required>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label for="newBenFeApe" class="text-label fs-0 ps-1">Fecha Apertura *</label>
                              <!-- <input type="text" class="form-control" id="newBenFeApe" name="BanFeApe"> -->
                              <input type="date" class="form-control" id="newBenFeApe" name="BanFeApe" required>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label for="newBanCodig" class="text-label fs-0 ps-1">Código Interno *</label>
                              <input type="text" class="form-control" id="newBanCodig" name="BanCodig" maxlength="2"  required>
                           </div>
                        </div>
                        <div class="col-md-9">
                           <div class="form-group">
                              <label for="newNombre" class="text-label fs-0 ps-1">Nombre *</label>
                              <input type="text" class="form-control" id="newNombre" name="BanNombr" maxlength="40" required>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <div class="form-group">
                              <label for="newCueCodig" class="text-label fs-0 ps-1">Cuenta Contable *</label>
                              <select class="form-control" style="width: 100%;" id="newCueCodig" name="CueCodig">
                                 <option value="">Seleccionar</option>
                              </select>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-phoenix-primary" id="btnCuentaAdd">Guardar</button>
               <button type="button" class="btn btn-phoenix-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
         </form>
      </div>
   </div>
</div>
<script>
   $(document).ready(function() {
      $('#newCueCodig').select2({
         dropdownParent: $('#modalCuentaAdd'),
         width: '100%'
      });
   });
</script>