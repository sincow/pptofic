<div class="content p-2 pt-10">
   <div class="row mb-3">
      <div class="col-lg-8">
         <h4 class="mb-0">Reimpresión de Liquidación</h4>
      </div>
      <div class="col-lg-4 pt-1">
         <nav class="mb-0" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 float-sm-end">
         		<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
               <li class="breadcrumb-item"><a href="reports">Reportes</a></li>
               <li class="breadcrumb-item active">>Reimpresión Liquidación</li>
            </ol>
         </nav>
      </div>
   </div>

   <form class="needs-validation frmReports" role="form" id="frmReports" method="post" action="" novalidate>
      <input type="hidden" id="modulo" name="modulo" value="dival">
      <input type="hidden" id="option" name="option" value="reports">
      <input type="hidden" id="action" name="action" value="repliquida">
      <input type="hidden" id="clase" name="clase" value="1">
      <div class="row g-3">
         <div class="col-12 mb-lg-3">
            <div class="row g-3 g-xxl-0 h-90">
               <div class="col-12 mb-lg-3">
                  <div class="card">
                     <div class="card-body d-flex flex-column justify-content-between pb-3">
                        <div class="row align-items-center g-5 text-center text-sm-start">
                           <div class="col-12 col-sm-auto flex-1 mt-0 mb-2">
                              <div class="row g-3 mt-3">
                                 <label class="text-label fs-0 ps-2" for="repNroDomum">Nro Liquidación</label>
                                 <div class="col-md-3 col-xl-1 mt-0 mb-3">
                                    <div class="form-control mb-0 p-0 border-0">
                                       <input type="text" class="form-control px-2" id="repNroDomum" name="repNroDomum" autofocus required>
                                    </div>
                                 </div>
                              </div>

                              <!-- <div class="col-sm-8 col-md-5 col-lg-4 mt-0 mb-3">
                                 <div class="form-control mb-3 p-0 border-0">
                                    <div class="form-check ms-2">
                                       <input class="form-check-input fs-1" id="GenHojCal" name="GenHojCal" type="checkbox" value="1"/>
                                       <label class="form-check-label fs-0 mt-1" for="GenHojCal">Generar Hoja de Cálculo</label>
                                    </div>
                                 </div>
                              </div> -->

                              <div class="col-sm-6 col-md-3 col-lg-2 mt-0 mb-3">
                                 <div class="row g-2 mt-0 mb-0 px-0">
                                    <div class="col-md-9 m-0">
                                       <div class="d-grid gap-2">
                                          <button class="btn btn-primary px-2" type="submit" id="btnReports"><span class="fas fa-print me-2"></span>Imprimir</button>
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
         </div>
      </div>
   </form>
   <?php
      include APP_PATH.'/views/layouts/footer.php';
   ?>
</div>
<script src="app/js/reports.js"></script>