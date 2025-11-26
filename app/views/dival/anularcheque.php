<div class="content p-2 pt-10">
	<div class="row mb-0">
   	<div class="col-lg-8">
      	<h4 class="mb-0">Anular Cheque de Cliente</h4>
   	</div>
   	<div class="col-lg-4">
   		<nav class="mb-0" aria-label="breadcrumb">
      		<ol class="breadcrumb mb-2 float-sm-end">
         		<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
         		<li class="breadcrumb-item active">Anular Cheque</li>
      		</ol>
      	</nav>
   	</div>
	</div>

   <form class="needs-validation form-search" role="form" id="formChequeAnu" method="post" action="" novalidate>
		<input type="hidden" class="dateFormat" id="dateFormat" value='<?php echo DATE_DISPLAY ?>'>
      <input type="hidden" id="id_cheque" name="id_cheque" value="">
      <div class="row">
         <div class="col-12 col-xl-8">
            <div class="modal-body h-100 p-0">
               <div class="card mb-0">
                  <div class="card-body p-3">
                     <div class="row g-3">
                        <ul class="nav nav-underline ps-2 fs-9 mb-2" id="myTab" role="tablist">
                           <li class="nav-item">
                              <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#tab-home" role="tab" aria-controls="tab-home" aria-selected="true">
                                 <span class="fa-solid fa-home fa-lg me-0"></span>
                              </a>
                           </li>
                           <li class="nav-item" id="nav-profile">
                              <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#tab-profile">
                                 Aplazamientos
                              </a>
                           </li>
      
                           <li class="nav-item" id="nav-contact">
                              <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#tab-contact">
                                 Devoluciones
                              </a>
                           </li>
                        </ul>
                        <div class="tab-content mt-1" id="myTabContent">
                           <div class="tab-pane fade show active" id="tab-home" role="tabpanel" aria-labelledby="home-tab">
                              <div class="row g-2">
                                 <div class="col-lg-6 mt-0">
                                    <div class="card mb-3 h-lg-100">
                                       <div class="card-header fw-bold py-1 fs--1 text-start text-white bg-primary" style="opacity: 70%;">
                                          <i class="fa-solid fa-user me-2"></i>Información del Cliente
                                       </div>
                                       <div class="card-body p-2">
                                          <div class="mb-0">
                                             <div class="col-12 mb-3 p-1">
                                                <p class="text-label mb-0 fs-0" id="datCliente">
                                                </p>
                                             </div>
                                             <div class="col-12 px-0">
                                                <div class="card-header fw-bold py-1 fs--1 text-start text-white bg-primary" style="opacity: 70%;">
                                                   <i class="fa-solid fa-file-invoice me-2"></i>Información de la Cuenta Bancaria
                                                </div>
                                                <div class="card-body p-2">
                                                   <div class="mb-0">
                                                      <div class="col-12 mb-0 p-1">
                                                         <p class="text-label fs-0" id="datCuenta">
                                                         </p>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-12 px-0">
                                                <div class="card-header fw-bold py-1 fs--1 text-start text-white bg-primary" style="opacity: 70%;">
                                                   <i class="fa-solid fa-file-pen me-2"></i>Observaciones
                                                </div>
                                                <div class="card-body p-2">
                                                   <div class="mb-0">
                                                      <div class="col-12 mb-0 p-1">
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

                                 <div class="col-lg-6 mt-0">
                                    <div class="card mb-3 h-lg-100">
                                       <div class="card-header fw-bold py-1 fs--1 text-start text-white bg-primary" style="opacity: 70%;">
                                          <i class="fa-solid fa-file-signature me-2"></i>Datos del Documento
                                       </div>
                                       <div class="card-body p-2">
                                          <div class="mb-0">
                                             <div class="row mb-1 p-1">
                                                <div class="col-6 col-xl-3 mb-0 mx-3 p-1">
                                                   <label for="numDocAnu" class="text-label fs-0 ps-1">Nro Docum</label>
                                                   <input type="text" class="form-control py-2" id="numDocAnu" name="numDocAnu" autocomplete="off" value="" autofocus required>
                                                </div>
                                                <div class="col-6 col-xl-3 ms-3 mb-0 p-1">
                                                   <button class="btn btn-danger py-2 mt-4 d-none" type="button" id="btnAnularDocum"><span class="fas fa-trash me-2"></span>Anular</button>
                                                </div>
                                             </div>
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
      
                           <div class="tab-pane fade" id="tab-profile" role="tabpanel" aria-labelledby="profile-tab">
                              <div class="table-responsive scrollbar mx-n1 px-0" style="min-height:210px; max-height: 278px; overflow-y: auto;">
                                 <table class="table table-striped table-sm fs--1 mb-0 documentsTable" id="documentsTable">
                                    <thead style="background-color: var(--phoenix-body-bg); backdrop-filter: blur(8px); opacity: 0.98;">
                                       <tr>
                                          <th class="sort align-middle white-space-nowrap pe-2" scope="col" style="width:7%;">Número #</th>
                                          <th class="sort align-middle text-start ps-0" scope="col" style="width:11%;">Fecha</th>
                                          <th class="sort align-middle text-end pe-2"   scope="col" style="width:10%;">Dias a Cob</th>
                                          <th class="sort align-middle text-end pe-2"   scope="col" style="width:13%;">Vlr Aplazado</th>
                                          <th class="sort align-middle text-end pe-2"   scope="col" style="width:13%;">% Interes</th>
                                          <th class="sort align-middle text-end pe-2"   scope="col" style="width:12%;">Vlr Interes</th>
                                          <th class="sort align-middle text-start ps-0" scope="col" style="width:21%;">Motivo</th>
                                          <th class="sort align-middle text-start ps-0" scope="col" style="width:9%;">Estado</th>
                                       </tr>
                                    </thead>
                                    <tbody id="aplazaTable-body">
                                    </tbody>
                                 </table>
                              </div>
                           </div>
      
                           <div class="tab-pane fade" id="tab-contact" role="tabpanel" aria-labelledby="contact-tab">
                              <div class="table-responsive scrollbar mx-n1 px-0" style="min-height:210px; max-height: 278px; overflow-y: auto;">
                                 <table class="table table-striped table-sm fs--1 mb-0 documentsTable" id="documentsTable">
                                    <thead style="background-color: var(--phoenix-body-bg); backdrop-filter: blur(8px); opacity: 0.98;">
                                       <tr>
                                          <th class="sort align-middle white-space-nowrap pe-2" scope="col" style="width:7%;">Número #</th>
                                          <th class="sort align-middle text-start ps-0" scope="col" style="width:12%;">Fecha</th>
                                          <th class="sort align-middle text-start ps-0" scope="col" style="width:76%;">Motivo</th>
                                          <th class="sort align-middle text-start ps-0" scope="col" style="width:12%;">Estado</th>
                                       </tr>
                                    </thead>
                                    <tbody id="devolTable-body">
                                    </tbody>
                                 </table>
                              </div>
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
<script src="app/js/dival/anularcheque.js?v=1.0.0"></script>