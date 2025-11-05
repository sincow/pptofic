<div id="modalCtaclienAdd" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title fw-bolder" id="modalCtaclienAddTit">Adicionar Cuenta Bancaria Cliente</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" ara-label="Close"></button>
			</div>
			<form class="needs-validation form-search" role="form" id="formCtaclienAdd" method="post" novalidate>
            <input type="hidden" id="modulo" name="modulo" value="dival"/>
            <input type="hidden" id="option" name="option" value="ctaclien"/>
            <input type="hidden" id="action" name="action" value="create"/>
            <input type="hidden" id="idCliente" name="id_dvcliente">
				<div class="modal-body h-100 p-0">
               <div class="card mb-0">
                  <div class="card-body p-4">
                     <div class="row g-3">
                        <div class="col-12">
                           <label class="text-label">Buscar Cliente</label>
                           <div class="d-flex align-items-end">
                              <input type="text" class="form-control flex-grow-1 client-search" name="idCliente" autocomplete="off" required
                                 placeholder="Escribe nombre, email o doc Identidad...">
                              <button type="button" class="btn btn-phoenix-secondary btn-sm clear-search ms-2"
                                 style="height: 38px;">
                                 <i class="fas fa-times"></i>
                              </button>
                           </div>
                           <small class="text-muted">Buscar Clientes existentes</small>
                        </div>

                        <div class="col-md-5">
                           <div class="form-group">
                              <label for="newBanCodNa" class="text-label fs-0 ps-1">Codigo Nacional *</label>
                              <select class="form-control" style="width: 100%;" id="newBanCodNa" name="BanCodNa" required>
                                 <option value="">Seleccionar</option>
                              </select>
                           </div>
                        </div>

                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="newsucursal" class="text-label fs-0 ps-1">Sucursal *</label>
                              <!-- <input type="text" class="form-control" id="newBenFeApe" name="BanFeApe"> -->
                              <input type="text" class="form-control" id="newsucursal" name="sucursal" required>
                           </div>
                        </div>

                        <div class="col-md-3">
                           <div class="form-group">
                              <label for="newnumero_cuenta" class="text-label fs-0 ps-1">Número Cuenta *</label>
                              <input type="text" class="form-control" id="newnumero_cuenta" name="numero_cuenta" required>
                           </div>
                        </div>

                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-phoenix-primary" id="btnCtaclienAdd">Guardar</button>
               <button type="button" class="btn btn-phoenix-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
         </form>
      </div>
   </div>
</div>
<script>
   $(document).ready(function() {
      $('#newCueCodig').select2({
         dropdownParent: $('#modalCtaclienAdd'),
         width: '100%'
      });
   });
</script>