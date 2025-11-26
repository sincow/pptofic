<div class="content p-2 pt-10">
	<div class="row mb-4">
   	<div class="col-lg-8">
      	<h4 class="mb-0">Anular Entrada de Efectivo</h4>
   	</div>
   	<div class="col-lg-4">
   		<nav class="mb-0" aria-label="breadcrumb">
      		<ol class="breadcrumb mb-2 float-sm-end">
         		<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
         		<li class="breadcrumb-item active">Anular Entrada Efectivo</li>
      		</ol>
      	</nav>
   	</div>
	</div>

   <form class="needs-validation form-search" role="form" id="formDocumentAnu" method="post" action="" novalidate>
		<input type="hidden" class="dateFormat" id="dateFormat" value='<?php echo DATE_DISPLAY ?>'>
      <input type="hidden" id="id_movimiento" name="id_movimiento" value=0>
      <input type="hidden" id="CompteBco" name="CompteBco" value="">
      <input type="hidden" id="tipoDoc" name="tipoDoc" value="2">
      <div class="row g-3">
         <div class="col-lg-5 mt-0">
            <div class="card mb-2">
               <div class="card-body" style="min-height: 450px;">
                  <div class="row mb-1 p-1">
                     <div class="col-6 col-xl-3 mb-0 mx-3 p-1">
                        <label for="numDocAnu" class="text-label fs-0 ps-1">Nro Documento</label>
                        <input type="text" class="form-control py-2" id="numDocAnu" name="numDocAnu" autocomplete="off" value="" autofocus required>
                     </div>
                     <div class="col-6 col-xl-3 ms-3 mb-0 p-1">
                        <button class="btn btn-danger py-2 mt-4 d-none" type="button" id="btnAnularDocum"><span class="fas fa-trash me-2"></span>Anular</button>
                     </div>
                  </div>

                  <div class="row g-3" id="documentDetails">
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
<script src="app/js/dival/anularmovcaja.js?v=1.0.0"></script>