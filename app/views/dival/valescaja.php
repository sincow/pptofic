<div class="content p-2 pt-10">
	<div class="row mb-4">
   	<div class="col-lg-8">
      	<h4 class="mb-0">Vale de Caja</h4>
   	</div>
   	<div class="col-lg-4">
   		<nav class="mb-0" aria-label="breadcrumb">
      		<ol class="breadcrumb mb-2 float-sm-end">
         		<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
         		<li class="breadcrumb-item active">Vale Caja</li>
      		</ol>
      	</nav>
   	</div>
	</div>
	<form class="needs-validation form-search documcajaForm" role="form" id="documcajaForm" name="documcajaForm" enctype="multipart/form-data" method="post" novalidate>
      <input type="hidden" id="modulo" name="modulo" value="dival">
      <input type="hidden" id="option" name="option" value="cajas">
      <input type="hidden" id="action" name="action" value="addDocumCaja">
      <input type="hidden" id="fechaActual" name="fechaActual" value="<?php echo date('Y-m-d'); ?>">
      <input type="hidden" id="TerDocId" name="TerDocId">
      <input type="hidden" id="CueCodig" name="CueCodig" value="">
      <input type="hidden" id="compte" name="compte" value="">
      <input type="hidden" id="CompteBco" name="CompteBco" value = "">
      <input type="hidden" id="entrValor" name="entrValor" value = 0>
      <input type="hidden" id="tipoDoc" name="tipoDoc" value = '3'>
      <input type="hidden" name="acountingList" id="acountingList" value="">
      <div class="row g-3">
         <div class="col-lg-5 mt-0">
            <div class="card mb-2">
               <div class="card-body">
                  <div class="row g-3">
                     <div class="col-12 mb-3">
                        <div class="form-group">
                           <label for="terceroVale" class="text-label fs-0 ps-0">Tercero *</label>
                           <select class="form-select select2" id="terceroVale" name="terceroVale" autofocus required>
                              <option value="">Seleccionar</option>
                           </select>
                        </div>
                     </div>
                  </div>

                  <div class="row g-3">
                     <div class="col-12 mb-3">
                        <div class="form-group">
                           <label for="valDetalle" class="text-label fs-0 ps-0">Detalle *</label>
                           <input type="text" class="form-control p-2" id="valDetalle" name="valDetalle" required>
                        </div>
                     </div>
                  </div>
                  <div class="row g-3">
                     <div class="col-sm-6 col-lg-4 mb-3">
                        <div class="form-group">
                           <label for="valeFecha" class="text-label fs-0 ps-0">Fecha Vale *</label>
                           <input type="date" class="form-control" id="valeFecha" name="valeFecha" placeholder="<?php echo DATE_DISPLAY ?>" data-inputmask="'alias': '<?= DATE_FORMAT ?>'" required>
                        </div>
                     </div>

                     <div class="col-sm-6 col-lg-4 mb-3">
                        <div class="form-group text-end">
                           <label for="valeValor" class="text-label fs-0 ps-0">Valor Vale *</label>
                           <input type="text" class="form-control p-2 text-end" id="valeValor" name="valeValor" valMax=0 maxlength="11" value="0" >
                        </div>
                     </div>
                  </div>

                  <div class="row g-3">
                     <div class="col-12 mb-3">
                        <div class="form-group">
                           <label for="cuentaVale" class="text-label fs-0 ps-0">Cuenta Contable *</label>
                           <select class="form-select select2" id="cuentaVale" name="cuentaVale" required>
                              <option value="">Seleccionar</option>
                           </select>
                        </div>
                     </div>
                  </div>

                  <div class="row g-3">
                     <div class="col-12 mb-3">
                        <div class="d-grid gap-2">
                           <button class="btn btn-phoenix-success" type="submit" id="btnValeCajaAdd">GRABAR VALE</button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </form>
	<?php
		include APP_PATH.'/views/layouts/footer.php';
	?>
</div>
<script src="app/js/dival/valescaja.js?v=1.0.0"></script>