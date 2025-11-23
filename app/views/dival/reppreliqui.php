<div class="content p-2 pt-10">
   <div class="row mb-3">
      <div class="col-lg-8">
         <h4 class="mb-0">Preliquidación de Documentos</h4>
      </div>
      <div class="col-lg-4 pt-1">
         <nav class="mb-0" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 float-sm-end">
         		<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
               <li class="breadcrumb-item"><a href="reports">Reportes</a></li>
               <li class="breadcrumb-item active">>Preliquidación</li>
            </ol>
         </nav>
      </div>
   </div>

   <form class="needs-validation frmReports" role="form" id="frmReports" method="post" action="" novalidate>
      <input type="hidden" id="modulo" name="modulo" value="dival">
      <input type="hidden" id="option" name="option" value="reports">
      <input type="hidden" id="action" name="action" value="reppreliqui">
      <input type="hidden" name="documPreliqList" id="documPreliqList" value="">
      <div class="row g-3">
         <div class="col-12 mb-lg-3">
            <div class="row g-3 g-xxl-0 h-90">
               <div class="col-12 mb-lg-3">
                  <div class="card">
                     <div class="card-body d-flex flex-column justify-content-between pb-3">
                        <div class="row align-items-center g-5 text-center text-sm-start">
                           <div class="col-12 col-sm-auto flex-1 mt-0 mb-2">

                              <div class="row g-3 mt-3">
                                 <div class="col-md-7 col-xl-4 mt-0 mb-0">
                                    <label class="text-label fs-0 ps-2" for="repIdCliente">Cliente</label>
                                    <div class="form-control mb-0 p-0 border-0">
                                       <select class="form-control select2" style="width: 100%;" name="repIdCliente" id="repIdCliente" required>
                                          <option value="" idCliente="">Seleccione Cliente</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>

                              <div class="row g-3 mt-1 mb-0">
                                 <label class="text-label fs-0 ps-2" for="repFecPreliq">Fecha Proyectada</label>
                                 <div class="col-md-3 col-xl-1 mt-0 mb-3">
                                    <div class="form-control mb-0 p-0 border-0">
                                       <input type="text" class="form-control datepicker py-2 dp_fecha_ini" id="repFecPreliq" name="repFecPreliq" placeholder="<?php echo DATE_DISPLAY ?>" data-inputmask="'alias': '<?= DATE_DISPLAY ?>'" autocomplete="off" data-mask value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="row g-3 mt-1 mb-0">
                                 <div class="col-md-6 col-xl-2 mt-0 mb-3">
                                    <div class="form-group">
                                       <label for="diasHabiles1" class="text-label fs-0 ps-1">Sólo días hábiles</label>
                                       <div class="form-check">
                                          <label class="form-check-label me-5" for="diasHabiles1">
                                             <input class="form-check-input diasHabiles" id="diasHabiles1" type="radio" name="diasHabiles" value=0 checked="">
                                             Si
                                          </label>
                                          <label class="form-check-label" for="diasHabiles2">
                                             <input class="form-check-input diasHabiles" id="diasHabiles2" type="radio" name="diasHabiles" value=1>
                                             No
                                          </label>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-6 col-xl-2 mt-0 mb-3">
                                    <label for="mostrarEstCta" class="text-label fs-0 px-2">Mostrar Estado de Cta</label>
                                    <input type="checkbox" id="mostrarEstCta" name="mostrarEstCta">
                                 </div>
                              </div>
                              <div class="row g-3 mt-0 mb-3">
                                 <div class="col-xl-6 mt-0 mb-0">
                                    <label class="text-label fs-0 ps-2" for="obserPreliq">Observaciones</label>
                                    <textarea class="form-control mb-0" name="obserPreliq" id="obserPreliq" rows="2"></textarea>
                                 </div>
                              </div>

                              <div class="row g-3 mt-0 mb-0">
                                 <div class="col-md-6 col-xl-6 mt-0 mb-0 pe-5 text-end">
                                    <label for="incluirEstCta" class="text-label fs--1 px-2 fw-bold">Todos</label>
                                    <input type="checkbox" id="incluirEstCta" name="incluirEstCta">
                                 </div>
                              </div>

                              <div class="row g-3 mt-0 mb-0">
                                 <div class="col-md-9 col-xl-6 mt-0 mb-3">
                                    <div class="table-responsive scrollbar mx-n1 px-0" style="min-height:210px; max-height: 278px; overflow-y: auto;">
                                       <table class="table table-striped table-sm fs--1 mb-0 estCtaTable" id="estCtaTable">
                                          <thead style="background-color: var(--phoenix-body-bg); backdrop-filter: blur(8px); opacity: 0.98;">
                                             <tr>
                                                <th class="sort align-middle white-space-nowrap pe-2" scope="col" style="width:8%;">Número</th>
                                                <th class="sort align-middle text-start ps-0" scope="col" style="width:11%;">Fecha</th>
                                                <th class="sort align-middle text-start ps-0"   scope="col" style="width:11%;">Vencimiento</th>
                                                <th class="sort align-middle text-end pe-2"   scope="col" style="width:15%;">Vlr Documento</th>
                                                <th class="sort align-middle text-end pe-2"   scope="col" style="width:15%;">Saldo Documento</th>
                                                <th class="sort align-middle text-end pe-2"   scope="col" style="width:12%;">% Comisión</th>
                                                <th class="sort align-middle text-start ps-0" scope="col" style="width:10%;">Incluir</th>
                                             </tr>
                                          </thead>
                                          <tbody id="estCtaTable-body">
                                          </tbody>
                                       </table>
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