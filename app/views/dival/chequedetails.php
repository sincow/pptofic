<div id="modalChequeDetails" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title fw-bolder" id="modalCtaclienAddTit">Detalles de un Documento</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" ara-label="Close"></button>
			</div>
			<form class="needs-validation form-search" role="form" id="formCtaclienAdd" method="post" novalidate>
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
                                 <!-- <button class="btn btn-sm btn-white ps-2 close-tab-btn" style="line-height: 1;">
                                    <i class="fas fa-times fs-0"></i>
                                 </button> -->
                              </a>
                           </li>

                           <!-- <li class="nav-item" style="display:none;" id="nav-contact"> -->
                           <li class="nav-item" id="nav-contact">
                              <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#tab-contact">
                                 Devoluciones
                                 <!-- <button class="btn btn-sm btn-white ps-2 close-tab-btn" style="line-height: 1;">
                                    <i class="fas fa-times fs-0"></i>
                                 </button> -->
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
         </form>
      </div>
   </div>
</div>

         




