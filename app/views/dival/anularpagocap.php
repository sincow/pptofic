<div class="content p-2 pt-10">
	<div class="row mb-3">
   	<div class="col-lg-8">
      	<h4 class="mb-0">Anular Pago de Capital</h4>
   	</div>
   	<div class="col-lg-4">
   		<nav class="mb-0" aria-label="breadcrumb">
      		<ol class="breadcrumb mb-2 float-sm-end">
         		<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
         		<li class="breadcrumb-item active">Anular Pago Capital</li>
      		</ol>
      	</nav>
   	</div>
	</div>
	<form class="needs-validation form-search formPagoCapAnu" role="form" id="formPagoCapAnu" name="formPagoCapAnu" enctype="multipart/form-data" method="post" novalidate>
		<input type="hidden" class="dateFormat" id="dateFormat" value='<?php echo DATE_DISPLAY ?>'>
      <input type="hidden" id="id_pago" name="id_pago" value=0>
      <input type="hidden" id="id_cheque" name="id_cheque" value=0>
      <input type="hidden" id="valor" name="valor" value=0>
      <div class="row g-3">
         <div class="col-12 col-xl-9">
            <div class="row g-3">
               <div class="col-lg-6 mt-0">
                  <!-- <div class="card mb-3 h-lg-100"> -->
                  <div class="card mb-3">
                     <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity: 70%;">
                        <i class="fa-solid fa-user me-2"></i>Información del Cliente
                     </div>
                     <div class="card-body pt-2">
                        <div class="row">
                           <div class="col-12 mb-3" style="min-height:110px;">
                              <p class="text-label fs-0" id="datCliente">
                              </p>
                           </div>
                           <div class="col-12 px-0" style="min-height:150px;">
                              <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity: 70%;">
                                 <i class="fa-solid fa-file-invoice me-2"></i>Información de la Cuenta Bancaria
                              </div>
                              <div class="card-body pt-2">
                                 <div class="row">
                                    <div class="col-12 mb-3">
                                       <p class="text-label fs-0 mb-0" id="datCuenta">
                                       </p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-12 px-0" style="min-height:135px;">
                              <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity: 70%;">
                                 <i class="fa-solid fa-file-pen me-2"></i>Observaciones
                              </div>
                              <div class="card-body pt-2">
                                 <div class="row">
                                    <div class="col-12 mb-3">
                                       <p class="text-label fs-0" id="observacion">
                                       </p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="col-xl-6 mt-0">
                  <div class="card mb-2" style="min-height: 478px;">
                     <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity: 70%;">
                        <i class="fa-solid fa-file-signature me-2"></i>Datos del Documento
                     </div>
                     <div class="card-body">
                        <div class="row g-lg-3">
                           <label for="numDocAnu" class="text-label fs-0 ps-2 mt-1 mb-0">Nro Pago</label>
                           <div class="col-6 col-lg-3 mt-0 mb-3">
                              <input type="text" class="form-control" id="numDocAnu" name="numDocAnu" autofocus required>
                           </div>
                           <div class="col-6 col-xl-3 ms-3 mt-0 mb-0 p-1">
                              <button class="btn btn-danger py-2 mt-0 d-none" type="button" id="btnAnularDocum"><span class="fas fa-trash me-2"></span>Anular</button>
                           </div>
                        </div>
                        <div class="row g-lg-4 mb-3">
                           <div class="col-12 mb-1 p-1">
                              <p class="text-label mb-0 fs-0" id="datDocument">
                              </p>
                           </div>
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
<script src="app/js/dival/anularpagocap.js?v=1.0.0"></script>