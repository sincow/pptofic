<div class="content p-2 pt-10">
	<div class="row mb-4">
   	<div class="col-lg-8">
      	<h4 class="mb-0">Consignación de Cheques</h4>
   	</div>
   	<div class="col-lg-4">
   		<nav class="mb-0" aria-label="breadcrumb">
      		<ol class="breadcrumb mb-2 float-sm-end">
         		<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
         		<li class="breadcrumb-item active">Consignación Cheques</li>
      		</ol>
      	</nav>
   	</div>
	</div>
	<form class="needs-validation form-search consignaForm" role="form" id="consignaForm" name="consignaForm" enctype="multipart/form-data" method="post" novalidate>
      <input type="hidden" id="modulo" name="modulo" value="dival">
      <input type="hidden" id="option" name="option" value="cheques">
      <input type="hidden" id="action" name="action" value="consigna">
      <input type="hidden" id="fechaActual" name="fechaActual" value="<?php echo date('Y-m-d'); ?>">
      <input type="hidden" id="TerDocId" name="TerDocId">
      <input type="hidden" id="CueCodig" name="CueCodig" value="">
      <input type="hidden" id="compte" name="compte" value="">
      <input type="hidden" id="CompteBco" name="CompteBco" value = "">
      <input type="hidden" id="valeValor" name="valeValor" value = 0>
      <input type="hidden" id="tipoDoc" name="tipoDoc" value = '1'>
      <input type="hidden" id="valDetalle" name="valDetalle" value = 'APORTE A ACAJA DE BANCOS'>
      <input type="hidden" id="terceroVale" name="terceroVale" value = '<?= $_SESSION['companyid']?>'>
      <input type="hidden" id="cuentaVale" name="cuentaVale" value = ''>
      <input type="hidden" name="acountingList" id="acountingList" value="">
      <input type="hidden" name="documConsigList" id="documConsigList" value="">
      <input type="hidden" name="canConsig" id="canConsig" value="0">
      <div class="row g-3">
         <div class="col-lg-9 mt-0">
            <div class="card mb-2">
               <div class="card-body">
                  <div class="row g-3">
                     <div class="col-12 col-xl-7 mb-3">
                        <div class="form-group">
                           <label for="CueCodig" class="text-label fs-0 ps-0">Cta Bancaria *</label>
                           <select class="form-select select2" id="BancoCodig" name="BancoCodig" autofocus required>
                              <option value="">Seleccionar</option>
                           </select>
                        </div>
                     </div>
                  </div>

                  <div class="row g-3">
                     <div class="col-lg-6 mb-3">
                        <div class="form-group">
                           <label for="totalesCondig" class="text-label fs-0 ps-0">Totales a Consignar</label>
                           <p id="totalesCondig"></p>
                           <!-- <input type="date" class="form-control" id="valeFecha" name="valeFecha" placeholder="<?php echo DATE_DISPLAY ?>" data-inputmask="'alias': '<?= DATE_FORMAT ?>'" required> -->
                        </div>
                     </div>
                  </div>

                  <div class="row g-3 mt-0 mb-0">
                     <div class="col-12 mt-0 mb-0 pe-2 text-end">
                        <label for="incluirDocum" class="text-label fs--1 px-2 fw-bold">Todos</label>
                        <input type="checkbox" id="incluirDocum" name="incluirDocum">
                     </div>
                  </div>

                  <div class="row g-3 mt-0 mb-0">
                     <div class="col-12 mt-0 mb-3">
                        <div class="table-responsive scrollbar mx-n1 px-0" style="min-height:210px; max-height: 278px; overflow-y: auto;">
                           <table class="table table-striped table-sm fs--1 mb-0 ConsigTable" id="ConsigTable">
                              <thead style="background-color: var(--phoenix-body-bg); backdrop-filter: blur(8px); opacity: 0.98;">
                                 <tr>
                                    <th class="sort align-middle white-space-nowrap pe-2" scope="col" style="width:7%;">Consec</th>
                                    <th class="sort align-middle text-start ps-0" scope="col" style="width:40%;">Cliente</th>
                                    <th class="sort align-middle text-start ps-0" scope="col" style="width:8%;"># Cheque</th>
                                    <th class="sort align-middle text-start ps-0" scope="col" style="width:5%;">Banco</th>
                                    <th class="sort align-middle text-start ps-0" scope="col" style="width:8%;">Fecha</th>
                                    <th class="sort align-middle text-start ps-0" scope="col" style="width:8%;">Vencimiento</th>
                                    <th class="sort align-middle text-end pe-2"   scope="col" style="width:10%;">Vlr Documento</th>
                                    <th class="sort align-middle text-start ps-0" scope="col" style="width:5%;">Consig</th>
                                 </tr>
                              </thead>
                              <tbody id="ConsigTable-body">
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>


                  <!-- <div class="row g-3">
                     <div class="col-12 mb-3">
                        <div class="form-group">
                           <label for="cuentaVale" class="text-label fs-0 ps-0">Cuenta Contable *</label>
                           <select class="form-select select2" id="cuentaVale" name="cuentaVale" required>
                              <option value="">Seleccionar</option>
                           </select>
                        </div>
                     </div>
                  </div> -->

                  <div class="row g-3">
                     <div class="col-12 mb-0">
                        <div class="d-grid d-flex gap-2">
                           <button class="btn btn-phoenix-info" type="button" id="btnAporteCajaAdd">IMPRIMIR CONSIGNACIÓN</button>
                           <button class="btn btn-phoenix-success" type="submit" id="btnAporteCajaAdd">GRABAR CONSIGNACIÓN</button>
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
<script src="app/js/dival/consigcheque.js?v=1.0.0"></script>