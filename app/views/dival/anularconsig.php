<div class="content p-2 pt-10">
	<div class="row mb-1">
   	<div class="col-lg-8">
      	<h4 class="mb-0">Anular Consignación de Cheques</h4>
   	</div>
   	<div class="col-lg-4">
   		<nav class="mb-0" aria-label="breadcrumb">
      		<ol class="breadcrumb mb-2 float-sm-end">
         		<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
         		<li class="breadcrumb-item active">Anular Consignación Cheques</li>
      		</ol>
      	</nav>
   	</div>
	</div>
	<form class="needs-validation form-search formConsigAnu" role="form" id="formConsigAnu" name="formConsigAnu" enctype="multipart/form-data" method="post" novalidate>
      <input type="hidden" id="modulo" name="modulo" value="dival">
      <input type="hidden" id="option" name="option" value="consigna">
      <input type="hidden" id="action" name="action" value="anular">
      <input type="hidden" id="fechaActual" name="fechaActual" value="<?php echo date('Y-m-d'); ?>">
      <input type="hidden" id="TerDocId" name="TerDocId">
      <input type="hidden" id="compte" name="compte" value="">
      <input type="hidden" id="CompteBco" name="CompteBco" value = "">
      <input type="hidden" id="idConsigna" name="idConsigna" value = 0>
      <input type="hidden" name="documConsigList" id="documConsigList" value="">
      <input type="hidden" name="canConsig" id="canConsig" value="0">
      <!-- <div class="row g-3"> -->
      <div class="row">
         <div class="col-xl-9 mt-0">
            <div class="card mb-2">
               <div class="card-body">
                  <div class="row g-1 mb-3">
                     <label for="numDocAnu" class="text-label fs-0 mb-0 ps-2">Nro Consignación</label>
                     <div class="col-6 col-lg-3 col-xl-2 mt-0 mb-3">
                        <input type="text" class="form-control" id="numDocAnu" name="numDocAnu" autofocus required>
                     </div>
                     <div class="col-5 col-lg-3 col-xl-3 ms-3 mt-0 mb-0 p-1">
                        <button class="btn btn-danger py-2 mt-0 d-none" type="button" id="btnAnularDocum"><span class="fas fa-trash me-2"></span>Anular</button>
                     </div>
                  </div>
                  <div class="row g-3 mb-4" id="datosCuenta">
                     <div class="col-lg-6 mb-3">
                        <div class="form-group">
                           <p id="totalesCondig"></p>
                        </div>
                     </div>
                  </div>
                  <div class="row g-3 mt-0 mb-0">
                     <div class="col-12 mt-0 mb-3">
                        <p class="text-center fs-0 fw-bold mb-0">Listado de Documentos Consignados</p>
                        <div class="table-responsive scrollbar mx-n1 px-0" style="min-height:210px; max-height: 278px; overflow-y: auto;">
                           <table class="table table-striped table-sm fs--1 mb-0 ConsigTable" id="ConsigTable">
                              <thead style="background-color: var(--phoenix-body-bg); backdrop-filter: blur(8px); opacity: 0.98;">
                                 <tr>
                                    <th class="sort align-middle white-space-nowrap pe-2" scope="col" style="width:7%;">Consec</th>
                                    <th class="sort align-middle text-start ps-0" scope="col" style="width:40%;">Nombre del Cliente</th>
                                    <th class="sort align-middle text-start ps-0" scope="col" style="width:8%;"># Cheque</th>
                                    <th class="sort align-middle text-start ps-0" scope="col" style="width:5%;">Banco</th>
                                    <th class="sort align-middle text-start ps-0" scope="col" style="width:8%;">Fecha</th>
                                    <th class="sort align-middle text-start ps-0" scope="col" style="width:8%;">Vencimiento</th>
                                    <th class="sort align-middle text-end pe-2"   scope="col" style="width:10%;">Vlr Documento</th>
                                 </tr>
                              </thead>
                              <tbody id="ConsigTable-body">
                              </tbody>
                           </table>
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
<script src="app/js/dival/anularconsig.js?v=1.0.0"></script>