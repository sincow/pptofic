<div id="modalCtaclienEdit" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title fw-bolder" id="modalCtaclienEditTit">Editar Cuenta Bancaria Cliente</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" ara-label="Close"></button>
			</div>
			<form class="needs-validation" role="form" id="formCtaclienEdit" method="post" novalidate>
            <input type="hidden" id="modulo" name="modulo" value="dival"/>
            <input type="hidden" id="option" name="option" value="ctaclien"/>
            <input type="hidden" id="action" name="action" value="update"/>
            <input type="hidden" id="id_bancli" name="id_bancli">
            <input type="hidden" id="id_dvcliente" name="id_dvcliente">
				<div class="modal-body h-100 p-0">
               <div class="card mb-0">
                  <div class="card-body p-4">
                     <div class="row g-3">
                        <div class="col-12">
                           <label for="editTerNombr" class="text-label">Cliente</label>
                           <input type="text" class="form-control" id="editTerNombr" name="TerNombr" disabled>
                           <!-- <div class="d-flex align-items-end">
                              <input type="text" class="form-control flex-grow-1 client-search" name="id_dvcliente" required
                                 placeholder="Escribe nombre, email o doc Identidad...">
                              <button type="button" class="btn btn-phoenix-secondary btn-sm clear-search ms-2"
                                 style="height: 38px;">
                                 <i class="fas fa-times"></i>
                              </button>
                           </div>
                           <input type="hidden" id="id_dvcliente" name="id_dvcliente">
                           <small class="text-muted">Buscar Clientes existentes</small> -->
                        </div>

                        <div class="col-md-5">
                           <div class="form-group">
                              <label for="editBanCodNa" class="text-label fs-0 ps-1">Codigo Nacional *</label>
                              <select class="form-control" style="width: 100%;" id="editBanCodNa" name="BanCodNa" required>
                                 <option value="">Seleccionar</option>
                              </select>
                           </div>
                        </div>

                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="editsucursal" class="text-label fs-0 ps-1">Sucursal *</label>
                              <!-- <input type="text" class="form-control" id="editBenFeApe" name="BanFeApe"> -->
                              <input type="text" class="form-control" id="editsucursal" name="sucursal" required>
                           </div>
                        </div>

                        <div class="col-md-3">
                           <div class="form-group">
                              <label for="editnumero_cuenta" class="text-label fs-0 ps-1">Número Cuenta *</label>
                              <input type="text" class="form-control" id="editnumero_cuenta" name="numero_cuenta" required>
                           </div>
                        </div>

                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-phoenix-primary" id="btnCtaclienEdit">Guardar</button>
               <button type="button" class="btn btn-phoenix-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
         </form>
      </div>
   </div>
</div>
<script>
   // $(document).ready(function() {
   //    $('#editCueCodig').select2({
   //       dropdownParent: $('#modalCtaclienEdit'),
   //       width: '100%'
   //    });
   // });
</script>