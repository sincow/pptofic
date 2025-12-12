<div id="modalTareaDetails" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title fw-bolder" id="modalTareaDetTit">Detalles de la Tarea</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" ara-label="Close"></button>
			</div>
			<form class="needs-validation form-search" role="form" id="formTareaDet" method="post" novalidate>
				<div class="modal-body h-100 p-0">
               <div class="card mb-0">
                  <div class="card-body p-3">
                     <div class="row g-2">
                        <div class="col-lg-6 mt-0">
                           <div class="card mb-3 h-lg-100">
                              <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity: 70%;">
                                 <i class="fa-solid fa-file-invoice me-2"></i>Información de la Tarea
                              </div>
                              <div class="table-responsive scrollbar mx-n1 px-0" style="min-height:380px; max-height: 590px; overflow-y: auto;">
                                 <div class="card-body p-2">
                                    <div class="mb-0">
                                       <div class="col-12 mb-3 p-1">
                                          <p class="text-label mb-0 fs-0" id="datTarea">
                                          </p>
                                       </div>
                                       <div class="col-12 px-0">
                                          <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity: 70%;">
                                             <i class="fa-solid fa-check me-2"></i>Información del Cierre
                                          </div>
                                          <div class="card-body p-2">
                                             <div class="mb-0">
                                                <div class="col-12 mb-0 p-1">
                                                   <p class="text-label fs-0" id="datCierre">
                                                   </p>
                                                </div>
                                             </div>
                                          </div>
                                       </div>

                                       <!-- <div class="col-12 px-0">
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
                                       </div> -->
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>

                        <div class="col-lg-6 mt-0">
                           <div class="card mb-3 h-lg-100">
                              <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity: 70%;">
                                 <i class="fa-solid fa-file-signature me-2"></i>Datos de los Seguimientos
                              </div>
                              <div class="card-body p-2">
                                 <div class="mb-0">
                                    <div class="table-responsive scrollbar mx-n1 px-0" style="max-height: 590px; overflow-y: auto;">
                                       <div class="col-12 mb-1 p-1">
                                          <p class="text-label mb-0 fs-0" id="datSeguim">
                                          </p>
                                       </div>
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
      </div>
   </div>
</div>