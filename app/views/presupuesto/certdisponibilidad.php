
<div class="content p-2 pt-10">
	<div class="row mb-4">
   	<div class="col-lg-8">
      	<h4 class="mb-0">Certificado de Disponibilidad Presupuestal</h4>
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

   <form class="needs-validation form-search crearCDPForm" role="form" id="crearCDPForm" name="crearCDPForm" enctype="multipart/form-data" method="post" novalidate>
      <!-- Verificar los objetos hidden para que queden los que necesito  -->
      <input type="hidden" id="nombretipofinanciacion" name="nombretipofinanciacion" value=''>


      <!-- <input type="hidden" id="idCliente" name="id_dvcliente">
      <input type="hidden" id="fechaActual" name="fechaActual" value="<?php echo date('Y-m-d'); ?>">
      <input type="hidden" id="ivaIncluido" name="ivaIncluido" value=<?= $_SESSION['ivaIncluido']?>>
      <input type="hidden" id="clase" name="clase" value='1'>
      <input type="hidden" id="valorIva" name="valorIva" value=<?= $_SESSION['valorIva']?>>
      <input type="hidden" id="TerDocId" name="TerDocId">
      <input type="hidden" id="TerDocId2" name="TerDocId2" value=0>
      <input type="hidden" id="TerDocId3" name="TerDocId3" value=0>
      <input type="hidden" id="TerDocId4" name="TerDocId4" value=0>
      <input type="hidden" id="clearSearch2" name="clearSearch2" value="">
      <input type="hidden" id="clearSearch3" name="clearSearch3" value="">
      <input type="hidden" id="clearSearch4" name="clearSearch4" value="">
      <input type="hidden" id="compte" name="compte" value="">
      <input type="hidden" name="acountingList" id="acountingList" value=""> -->

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
                     <div class="col-md-4">
                        <div class="form-group">
                           <label for="fecha" class="text-label fs-0 ps-1">Fecha *</label>
                           <input type="date" class="form-control" id="fecha" name="fecha" placeholder="<?php echo DATE_DISPLAY ?>" data-inputmask="'alias': '<?= DATE_FORMAT ?>'" required>
                        </div>
                     </div>

                        
                     <div class="col-md-4">
                        <div class="form-group">
                           <label for="expiracion" class="text-label fs-0 ps-1">Expiración *</label>
                           <input type="date" class="form-control" id="expiracion" name="expiracion" placeholder="<?php echo DATE_DISPLAY ?>" data-inputmask="'alias': '<?= DATE_FORMAT ?>'" required>
                        </div>
                     </div>

                     
                     <div class="col-md-4">  
                        <div class="form-group">
                           <label for="peridofiscal" class="text-label fs-0 ps-1">Periodo Fiscal *</label>
                           <input type="text" class="form-control" id="peridofiscal" name="peridofiscal" required maxlength="4">
                        </div>
                     </div>

                  </div>

                  <!-- Fila 2 -->
                  <div class="row g-3">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="dependencia" class="text-label fs-0 ps-1">Dependencia *</label>
                           <select class="form-control" style="width: 100%;" id="dependencia" name="dependencia">
                              <option value="">Seleccionar</option>
                           </select>
                        </div>
                     </div>  
                     
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="ordenadorgasto" class="text-label fs-0 ps-1">Ordenador del Gasto *</label>
                           <select class="form-control" style="width: 100%;" id="ordenadorgasto" name="ordenadorgasto" required>
                              <option value="">Seleccionar</option>
                           </select>
                        </div>
                     </div>


                  </div>
                     
                     <!-- Fila 3 -->
                  <div class="row g-3">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="tipodocumento" class="text-label fs-0 ps-1">Documento Soporte *</label>
                           <select class="form-control" style="width: 100%;" id="tipodocumento" name="tipodocumento">
                              <option value="">Seleccionar</option>
                           </select>
                        </div>
                     </div>  
                     
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="documentonro" class="text-label fs-0 ps-1">Numero  *</label>
                           <input type="text" class="form-control" id="documentonro" name="documentonro" required>
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
            <div class="card mb-3">
               <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity:70%;">
                  <i class="fa-solid fa-table-list me-2"></i>DETALLE
               </div>

               <div class="card-body">

                  <!-- BLOQUE PARA AGREGAR ITEM -->
                  <div class="row g-3 align-items-end">
                     <div class="col-md-3">
                        <label for="detalleCodigo" class="text-label">Código *</label>
                        <select class="form-control select2 w-100" id="detalleCodigo" name="detalleCodigo">
                           <option value="">Seleccionar</option>
                        </select>
                     </div>

                     <div class="col-md-2">
                        <label for="detalleTipoFinanciacion" class="text-label">T.Financiación</label>
                        <select class="form-control select2 w-100" id="detalleTipoFinanciacion" name="detalleTipoFinanciacion">
                           <option value="">Seleccionar</option>
                        </select>
                     </div>

                     <div class="col-md-2">
                        <label for="detalleDescripcion" class="text-label">Detalle</label>
                        <input type="text" class="form-control" id="detalleDescripcion" name="detalleDescripcion" readonly>
                     </div>

                     <div class="col-md-2">
                        <label for="detalleSaldo" class="text-label">Saldo</label>
                        <input type="text" class="form-control text-end" id="detalleSaldo" name="detalleSaldo" readonly>
                     </div>

                     <div class="col-md-2">
                        <label for="detalleValor" class="text-label">Valor *</label>
                        <input type="text" class="form-control text-end" id="detalleValor" name="detalleValor">
                     </div>

                     <div class="col-md-1 d-grid">
                        <button type="button" class="btn btn-primary" id="btnAgregarDetalle">
                           <i class="fa-solid fa-plus"></i>
                        </button>
                     </div>
                  </div>

                  <!-- TABLA -->
                  <div class="table-responsive mt-4">
                  <!-- <div class="table-responsive scrollbar" style="min-height:100px; max-height: 490px; overflow-y: auto;"> -->
                     <!-- <table class="table table-bordered table-striped align-middle" id="tablaDetalleCDP"> -->
                     <table class="table table-sm table-hover mb-0 border-top border-200" id="tablaDetalleCDP" style="position: relative; border-collapse: collapse; width: 100%; font-size: 11px;">
                        <thead class="table-light">
                           <tr>
                              <th class="align-middle ps-2" scope="col" data-sort="stock" style="width: 10%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Código</th>
                              <th class="align-middle ps-2" scope="col" data-sort="stock" style="width: 20%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Tipo Financiación</th>
                              <th class="align-middle ps-2" scope="col" data-sort="stock" style="width: 20%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Detalle</th>
                              <th class="align-middle ps-2 text-end" scope="col" data-sort="stock" style="width: 10%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Saldo</th>
                              <th class="align-middle ps-2 text-end" scope="col" data-sort="stock" style="width: 10%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Valor</th>
                              <th class="align-middle ps-2 text-center" scope="col" data-sort="stock" style="width: 10%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;" >Acciones</th>
                           </tr>
                        </thead>
                        <tbody class="list" id="tbodyDetalle">
                        <!-- Aquí se agregan los registros dinámicamente -->
                        </tbody>


                        <tfoot>
                           <tr>
                              <th colspan="4" class="text-end">Total Certificado</th>
                              <th class="text-end fw-bold text-primary fs-1" id="totalCertificado">0</th>
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
                        <label for="numeroCopia" class="text-label fs-0 ps-1">Número de Copia</label>
                        <input type="text" class="form-control" id="numeroCopia" name="numeroCopia">
                     </div>

                     <div class="col-md-9">
                        <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                           <button class="btn btn-phoenix-secondary" type="reset" id="btnLimpiar">
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
<script src="app/js/presupuesto/certdisponibilidad.js"></script>
<script>
   $(document).ready(function() {
      $('#dependencia').select2({
         //dropdownParent: $('#modalRubroGastoAdd'),
         width: '100%'
      });

      $('#ordenadorgasto').select2({
         //dropdownParent: $('#modalRubroGastoAdd'),
         width: '100%'
      });

      $('#tipodocumento').select2({
         width: '100%'
      });
      
      $('#detallecodigo').select2({
         width: '100%'
      });

      $('#detalleTipoFinanciacion').select2({
         width: '100%'
      });
   });
</script>

