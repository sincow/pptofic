<div class="content p-2 pt-10">
	<div class="row mb-4">
   	<div class="col-lg-8">
      	<h4 class="mb-0">Registro Presupuestal</h4>
   	</div>
   	<div class="col-lg-4">
   		<nav class="mb-0" aria-label="breadcrumb">
      		<ol class="breadcrumb mb-2 float-sm-end">
         		<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
         		<li class="breadcrumb-item active">CDP</li>
      		</ol>
      	</nav>
   	</div>
	</div>

   <form class="needs-validation form-search crearCRPForm" role="form" id="crearCRPForm" name="crearCRPForm" enctype="multipart/form-data" method="post" novalidate>

      <input type="hidden" id="dependenciaid" name="dependenciaid">
      <input type="hidden" id="tipodocumentoid" name="tipodocumentoid">
      <input type="hidden" id="ordenadorgastoid" name="ordenadorgastoid">

      
      <div class="row g-3">
         
         <!-- card datos -->
         <div class="col-12 mt-0">
            <div class="card mb-3 h-lg-100">
               <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity: 70%;">
                  <i class="fa-solid fa-user me-2"></i>Datos
               </div>
               
               <div class="card-body">
                     <!-- Fila 1 -->
                  <div class="row g-3">
                     <div class="col-md-2">
                        <div class="form-group">
                           <label for="fecha" class="text-label fs-0 ps-1">Fecha</label>
                           <input type="text" class="form-control text-center" id="fecha" name="fecha" readonly>
                        </div>
                     </div>

                     <div class="col-md-2">
                        <div class="form-group">
                           <label for="plazo" class="text-label fs-0 ps-1">Plazo (días) *</label>
                           <input type="text" class="form-control " id="plazo" name="plazo" required maxlength="3" inputmode="numeric">
                        </div>
                     </div>

                     <div class="col-md-2">
                        <div class="form-group">
                           <label for="fechaplazo" class="text-label fs-0 ps-1">Fecha</label>
                           <input type="text" class="form-control text-center" id="fechaplazo" name="fechaplazo" readonly>
                        </div>
                     </div>
                     
                     <div class="col-md-2">  
                        <div class="form-group">
                           <label for="periodofiscal" class="text-label fs-0 ps-1">Per. Fiscal *</label>
                           <input type="text" class="form-control text-right" id="periodofiscal" name="periodofiscal" required maxlength="4">
                        </div>
                     </div>

                     <div class="col-md-2">  
                        <div class="form-group">
                           <label for="nrocdp" class="text-label fs-0 ps-1">Nro CDP *</label>
                           <input type="text" class="form-control text-right" id="nrocdp" name="nrocdp" required maxlength="8" inputmode="numeric"> 
                        </div>
                     </div>

                  </div>

                  <!-- Fila 2 -->
                  <div class="row g-3">
                     
                  <!-- pendiente para cambiarlo por un select2 con búsqueda de terceros -->
                     <div class="col-md-4">   
                        <div class="form-group">
                           <label for="tercerocrp" class="text-label fs-0 ps-1">Tercero *</label>
                           <select class="form-control select2" style="width: 100%;" id="tercerocrp" name="tercerocrp">
                              <option value="">Seleccionar</option>
                           </select>
                        </div>
                     </div>   
                  
                     
                     <div class="col-md-4">
                        <div class="form-group">
                           <label for="dependencia" class="text-label fs-0 ps-1">Dependencia</label>
                           <input type="text" class="form-control" id="dependencia" name="dependencia" readonly>
                        </div>
                     </div>  
                     
                     <div class="col-md-4">
                        <div class="form-group">
                           <label for="ordenadorgasto" class="text-label fs-0 ps-1">Ordenador del Gasto</label>
                           <input type="text" class="form-control" id="ordenadorgasto" name="ordenadorgasto" readonly>
                        </div>
                     </div>

                  </div>
                     
                     <!-- Fila 3 -->
                  <div class="row g-3">
                     <div class="col-md-4">
                        <div class="form-group">
                           <label for="tipocontrato" class="text-label fs-0 ps-1">Tipo Contrato *</label>
                           <select class="form-control" style="width: 100%;" id="tipocontrato" name="tipocontrato" required>
                              <option value="">Seleccionar</option>
                           </select>
                        </div>
                     </div>  
                     
                     <div class="col-md-2">
                        <div class="form-group">
                           <label for="contratonro" class="text-label fs-0 ps-1">Nro Contrato *</label>
                           <input type="text" class="form-control" id="contratonro" name="contratonro" required>
                        </div>                     
                     </div>

                     <div class="col-md-4">
                        <div class="form-group">
                           <label for="tipodocumento" class="text-label fs-0 ps-1">Documento Soporte</label>
                           <input type="text" class="form-control" id="tipodocumento" name="tipodocumento" readonly>
                        </div>
                     </div>  
                     
                     <div class="col-md-2">
                        <div class="form-group">
                           <label for="documentonro" class="text-label fs-0 ps-1">Numero Doc</label>
                           <input type="text" class="form-control" id="documentonro" name="documentonro" readonly>
                        </div>                     
                     </div>

                  </div>

                  <!-- fila 4 -->
                  <div class="row g-3">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label for="concepto" class="text-label fs-0 ps-1">Descripción *</label>
                           <input type="text" class="form-control" id="concepto" name="concepto" maxlength="255" required>
                        </div>
                     </div>
                  </div>

               </div>
            </div>
         </div>
          <!-- fin card datos  -->

         <!-- CARD DETALLE -->
         <div class="col-12">
            <div class="card mb-1">
               <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity:70%;">
                  <i class="fa-solid fa-table-list me-2"></i>DETALLE
               </div>

               <div class="card-body p-0">
                  <!-- TABLA -->
                  <!-- <div class="table-responsive mt-2"> -->
                  <div class="table-responsive scrollbar" style="min-height:100px; max-height: 490px; overflow-y: auto;">
                     <table class="table table-sm table-hover mb-0 border-top border-200" id="tablaDetalleCRP" style="position: relative; border-collapse: collapse; width: 100%; font-size: 11px;">
                        <thead class="table-light">
                           <tr>
                              <th class="align-middle ps-2" scope="col" data-sort="stock" style="width: 20%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Código</th>
                              <th class="align-middle ps-2" scope="col" data-sort="stock" style="width: 20%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Tipo Financiación</th>
                              <th class="align-middle ps-2" scope="col" data-sort="stock" style="width: 30%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Detalle</th>
                              <th class="align-middle ps-2 text-end" scope="col" data-sort="stock" style="width: 15%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Saldo CDP</th>
                              <th class="align-middle ps-2 text-end" scope="col" data-sort="stock" style="width: 15%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Valor</th>
                           </tr>
                        </thead>
                        <tbody class="list" id="tbodyDetalle">
                        <!-- Aquí se agregan los registros dinámicamente -->
                        </tbody>


                        <tfoot>
                           <tr>
                              <th colspan="4" class="text-end">Total Reserva</th>
                              <th class="text-end fw-bold text-primary fs-1" id="totalReserva">0</th>
                              <th></th>
                           </tr>
                        </tfoot>    
                     </table>
                  </div>

               </div>
            </div> 
         </div> 

         <!-- Card final  -->
         <div class="col-12">
            <div class="card mb-0">
               <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity:70%;">
                  <i class="fa-solid fa-gears me-2"></i>ACCIONES
               </div>

               <div class="card-body p-3">
                  <div class="row g-3 align-items-end">
            
                     <div class="col-md-3">
                        
                     </div>

                     <div class="col-md-9">
                        <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                           <button class="btn btn-phoenix-secondary" type="button" id="btnLimpiar">
                              LIMPIAR
                           </button>

                           <button class="btn btn-phoenix-success" type="submit" id="btnGrabar">
                              GRABAR
                           </button>

                           <button class="btn btn-phoenix-danger" type="button" id="btnSalir">
                              SALIR
                           </button>
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
<script src="app/js/presupuesto/regispresupuestal.js"></script> 
<script>
   $(document).ready(function() {
      
      $('#tipocontrato').select2({
         width: '100%'
      });
      
      // $('#tercerocrp').select2({
      //    width: '100%'
      // });

   });
</script>

