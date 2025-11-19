<style>
   /* Estilos para el autocomplete */
   #clientSuggestions {
      background: white;
      border: 1px solid #dee2e6;
      border-radius: 0.375rem;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
   }

   #clientSuggestions .list-group-item {
      border: none;
      border-bottom: 1px solid #dee2e6;
      text-align: left;
      white-space: normal;
   }

   #clientSuggestions .list-group-item:last-child {
      border-bottom: none;
   }

   #clientSuggestions .list-group-item:hover,
   #clientSuggestions .list-group-item.active {
      background-color: #0d6efd;
      color: white;
   }

   #clientSuggestions .list-group-item:hover small,
   #clientSuggestions .list-group-item.active small {
      color: #e6f0ff !important;
   }
</style>
<?php
   $tabla = "NoFestiv";
   $order = "FecFesti";
   $where = "EmpCodig = '".$_SESSION["empdef"]."' AND FecFesti >= CURDATE() AND FecEstad = 1";
   $festivos = GeneralModel::getAll($tabla, $order, $where);
   echo "<script>const diasFestivos = " . json_encode($festivos) . ";</script>";
?>

<div class="content p-2 pt-10">
	<div class="row mb-4">
   	<div class="col-lg-8">
      	<h4 class="mb-0">Creación de Pagaré</h4>
   	</div>
   	<div class="col-lg-4">
   		<nav class="mb-0" aria-label="breadcrumb">
      		<ol class="breadcrumb mb-2 float-sm-end">
         		<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
         		<li class="breadcrumb-item active">Creación Pagaré</li>
      		</ol>
      	</nav>
   	</div>
	</div>
	<form class="needs-validation form-search crearchequeForm" role="form" id="crearchequeForm" name="crearchequeForm" enctype="multipart/form-data" method="post" novalidate>
      <input type="hidden" id="idCliente" name="id_dvcliente">
      <!-- <input type="hidden" id="fechaActual" name="fechaActual" value="<?php //echo date('Y-m-d'); ?>"> -->
      <input type="hidden" id="ivaIncluido" name="ivaIncluido" value=<?= $_SESSION['ivaIncluido']?>>
      <input type="hidden" id="clase" name="clase" value='3'>
      <input type="hidden" id="valorIva" name="valorIva" value=<?= $_SESSION['valorIva']?>>
      <input type="hidden" id="TerDocId" name="TerDocId" value="">
      <input type="hidden" id="TerDocId2" name="TerDocId2" value="">
      <input type="hidden" id="TerDocId3" name="TerDocId3" value="">
      <input type="hidden" id="TerDocId4" name="TerDocId4" value="">
      <input type="hidden" id="compte" name="compte" value="">
      <input type="hidden" name="acountingList" id="acountingList" value="">
      <input type="hidden" id="impuesto_banco" name="impuesto_banco" value="0.00">
      <input type="hidden" id="valImptoBco" name="valImptoBco" value="0">
      <input type="hidden" id="numero_cuenta" name="numero_cuenta" value="0">
      <input type="hidden" id="numero" name="numero" value="000000">
      <input class="form-check-input d-none" id="mensajeria1" type="radio" name="mensajeria" value=1>
      <div class="row g-3">
         <div class="col-lg-7 mt-0">
            <div class="card mb-3 h-lg-100">
               <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity: 70%;">
                  <i class="fa-solid fa-user me-2"></i>Información del Cliente
               </div>
               <div class="card-body">
                  <div class="row">
                     <div class="col-12 mb-3">
                        <label class="text-label">Buscar Cliente</label>
                        <div class="d-flex align-items-end">
                           <input type="text" class="form-control flex-grow-1 client-search" id="idCliente" name="idCliente" autocomplete="off" required
                              placeholder="Escribe nombre, email o doc Identidad...">
                           <button type="button" class="btn btn-phoenix-secondary btn-sm clear-search ms-2"
                              id="clearSearch" style="height: 38px;">
                              <i class="fas fa-times"></i>
                           </button>
                        </div>
                        <small class="text-muted">Buscar Clientes existentes</small>
                     </div>

                     <div class="col-12 mb-3">
                        <label class="text-label" for="idCliente2">2do Firmante</label>
                        <div class="d-flex align-items-end">
                           <input type="text" class="form-control flex-grow-1 client-search" refer="TerDocId2" id="idCliente2" name="idCliente2" autocomplete="off" required
                              placeholder="Escribe nombre, email o doc Identidad...">
                           <button type="button" class="btn btn-phoenix-secondary btn-sm clear-search ms-2"
                              id="clearSearch2" style="height: 38px;">
                              <i class="fas fa-times"></i>
                           </button>
                        </div>
                     </div>

                     <div class="col-12 mb-3">
                        <label class="text-label" for="idCliente3">3er Firmante</label>
                        <div class="d-flex align-items-end">
                           <input type="text" class="form-control flex-grow-1 client-search" refer="TerDocId3" id="idCliente3" name="idCliente3" autocomplete="off" required
                              placeholder="Escribe nombre, email o doc Identidad...">
                           <button type="button" class="btn btn-phoenix-secondary btn-sm clear-search ms-2"
                              id="clearSearch3" style="height: 38px;">
                              <i class="fas fa-times"></i>
                           </button>
                        </div>
                     </div>
                     <div class="col-12 mb-3">
                        <label class="text-label" for="idCliente3">4to Firmante</label>
                        <div class="d-flex align-items-end">
                           <input type="text" class="form-control flex-grow-1 client-search" refer="TerDocId4" id="idCliente4" name="idCliente4" autocomplete="off" 
                              placeholder="Escribe nombre, email o doc Identidad...">
                           <button type="button" class="btn btn-phoenix-secondary btn-sm clear-search ms-2"
                              id="clearSearch4" style="height: 38px;">
                              <i class="fas fa-times"></i>
                           </button>
                        </div>
                     </div>

                     <div class="col-12 px-0">
                        <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity: 70%;">
                           <i class="fa-solid fa-file-invoice me-2"></i>Cartera Pendiente
                        </div>
                        <div class="table-responsive scrollbar" style="min-height:100px; max-height: 490px; overflow-y: auto;">
                           <!-- <table class="table table-hover"> -->
                           <table class="table table-sm table-hover mb-0 border-top border-200" id="saldoCarteraTable" style="position: relative; border-collapse: collapse; width: 100%; font-size: 11px;">
                              <thead>
                                 <tr>
                                    <th class="align-middle ps-2" scope="col" data-sort="stock" style="width: 8%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Cheque</th>
                                    <th class="align-middle ps-2" scope="col" data-sort="stock" style="width: 18%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Banco</th>
                                    <th class="align-middle ps-2" scope="col" data-sort="stock" style="width: 11%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Fecha Cheque</th>
                                    <th class="align-middle ps-2" scope="col" data-sort="stock" style="width: 11%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Vencimiento</th>
                                    <th class="align-middle ps-2 text-end" scope="col" data-sort="stock" style="width: 6%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Dias</th>
                                    <th class="align-middle ps-2 text-end" scope="col" data-sort="stock" style="width: 12%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Vlr Cheque</th>
                                    <th class="align-middle ps-2 text-end" scope="col" data-sort="stock" style="width: 12%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Vlr Comisión</th>
                                    <th class="align-middle ps-2 text-end" scope="col" data-sort="stock" style="width: 12%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Int x Cobrar</th>
                                    <th class="align-middle pe-2 text-end" scope="col" data-sort="stock" style="width: 10%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">% Int</th>
                                 </tr>
                              </thead>
                              <tbody id="saldoCarteraBody">
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="col-lg-5 mt-0">
            <div class="card mb-3">
               <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity: 70%;">
                  <i class="fa-solid fa-file-signature me-2"></i>Datos del Pagaré
               </div>
               <div class="card-body">
                  <div class="row g-lg-3">
                     <div class="col-sm-6 col-lg-3 mb-3">
                        <div class="form-group">
                           <label for="fechaActual" class="text-label fs-0 ps-1">Fecha Pagaré *</label>
                           <input type="date" class="form-control" id="fechaActual" name="fechaActual" placeholder="<?php echo DATE_DISPLAY ?>" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                     </div>

                     <div class="col-sm-6 col-lg-3 mb-3">
                        <div class="form-group">
                           <label for="vencimiento" class="text-label fs-0 ps-1">Vencimiento *</label>
                           <!-- <input type="text" class="form-control dp_fecha_fin" id="vencimiento" name="vencimiento" required> -->
									<input type="date" class="form-control " id="vencimiento" name="vencimiento" placeholder="<?php echo DATE_DISPLAY ?>" data-inputmask="'alias': '<?= DATE_DISPLAY ?>'" autocomplete="off" data-mask required>
                        </div>
                     </div>
                     <div class="col-sm-6 col-lg-3 mb-3">
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
                     <div class="col-sm-6 col-lg-3 mb-3">
                        <label for="newnumero_cuenta" class="text-label fs-0 ps-1">Días a Cobrar
                           <p id="diasCobrados"></p>
                        </label>
                        <input type="hidden" id="dias_cobrados" name="dias_cobrados">
                     </div>
                  </div>

                  <div class="row g-lg-3">
                     <div class="col-lg-4">
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="valCupo" class="text-label fs-0 ps-1">Valor Cupo</label>
                              <input type="text" class="form-control p-1" style="text-align: right;" id="valCupo" name="valCupo" value="0" disabled>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="valCupoTmp" class="text-label fs-0 ps-1">Valor Cupo TMP</label>
                              <input type="text" class="form-control p-1" style="text-align: right;" id="valCupoTmp" name="valCupoTmp" value="0" disabled>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="valSaldo" class="text-label fs-0 ps-1">Saldo Cartera</label>
                              <input type="text" class="form-control p-1" style="text-align: right;" id="valSaldo" name="valSaldo" value="0" disabled>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="valDisponible" class="text-label fs-0 ps-1">Cupo Disponible</label>
                              <input type="text" class="form-control p-1" style="text-align: right;" id="valDisponible" name="valDisponible" value="0" disabled>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-4">
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="valor_cheque" class="text-label fs-0 ps-1">Valor Pagaré *</label>
                              <input type="text" class="form-control p-1" style="text-align: right;" id="valor_cheque" name="valor_cheque" valMax=0 maxlength="11" value="0" required>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="porcentaje_comision" class="text-label fs-0 ps-1" >% Comision *</label>
                              <input type="number" class="form-control p-1" style="text-align: right;" id="porcentaje_comision" name="porcentaje_comision" maxlength="9" step="0.00000001" max="99.99999999" value="0.00000000">
                           </div>
                        </div>
                        <!-- <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="impuesto_banco" class="text-label fs-0 ps-1">% Impto Bco (X1000)</label>
                              <input type="number" class="form-control p-1" style="text-align: right;" id="impuesto_banco" name="impuesto_banco" maxlength="4" step="0.01" value="0.00">
                           </div>
                        </div> -->

                        <!-- <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: center;">
                              <label for="newnumero_cuenta" class="text-label fs-0 ps-1">Mensajería</label>
                              <div class="form-check">
                                 <label class="form-check-label me-5" for="mensajeria1">
                                    <input class="form-check-input" id="mensajeria1" type="radio" name="mensajeria" value=1>
                                    Si
                                 </label>
                                 <label class="form-check-label" for="mensajeria2">
                                    <input class="form-check-input" id="mensajeria2" type="radio" name="mensajeria" value=2 checked="">
                                    No
                                 </label>
                              </div>
                           </div>
                        </div> -->
                     </div>

                     <div class="col-lg-4">
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="valComision" class="text-label fs-0 ps-1">Valor Comisión</label>
                              <input type="text" class="form-control p-1" style="text-align: right;" id="valComision" name="valComision" maxlength="11" value="0" readonly>
                           </div>
                        </div>
                        <!-- <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="valImptoBco" class="text-label fs-0 ps-1">Valor Imp Bco</label>
                              <input type="text" class="form-control p-1" style="text-align: right;" id="valImptoBco" name="valImptoBco" maxlength="11" value="0" readonly>
                           </div>
                        </div> -->
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="valIVA" class="text-label fs-0 ps-1">Valor IVA</label>
                              <input type="text" class="form-control p-1" style="text-align: right;" id="valIVA" name="valIVA" maxlength="11" value="0" readonly>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="valEntregar" class="text-label fs-0 ps-1">Valor a Entregar</label>
                              <input type="text" class="form-control p-1" style="text-align: right;" id="valEntregar" name="valEntregar" maxlength="11" value="0" disabled>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- <div class="row g-lg-3 mb-3">
                     <label for="imagen" class="text-label fs-0 ps-1">Imagen del Documento</label>
                     <div class="col-12 mt-1 mb-0">
                        <input type="file" class="form-control" id="imagen" name="imagen" accept=".pdf" required>
                     </div>
                  </div> -->

                  <div class="row g-lg-3 mb-0">
                     <div class="col-12">
                        <div class="form-group">
                           <label for="observacion" class="text-label fs-0 ps-1">Observaciones</label>
                           <textarea type="text" class="form-control" id="observacion" name="observacion" rows="2"></textarea>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="card mb-0">
               <div class="card-body p-3">
                  <div class="d-grid gap-2">
                     <button class="btn btn-phoenix-success" type="submit" id="btnChequeAdd">GRABAR PAGARÉ</button>
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
<script src="app/js/dival/crearcheque.js?v=1.0.0"></script>