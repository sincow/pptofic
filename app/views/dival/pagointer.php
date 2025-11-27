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
      	<h4 class="mb-0">Pago de Intereses</h4>
   	</div>
   	<div class="col-lg-4">
   		<nav class="mb-0" aria-label="breadcrumb">
      		<ol class="breadcrumb mb-2 float-sm-end">
         		<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
         		<li class="breadcrumb-item active">Pago Intereses</li>
      		</ol>
      	</nav>
   	</div>
	</div>
	<form class="needs-validation form-search pagointeresForm" role="form" id="pagointeresForm" name="pagointeresForm" enctype="multipart/form-data" method="post" novalidate>
      <input type="hidden" id="modulo" name="modulo" value="dival">
      <input type="hidden" id="option" name="option" value="cheques">
      <input type="hidden" id="action" name="action" value="pagointeres">
      <input type="hidden" id="idCliente" name="id_dvcliente">
      <input type="hidden" id="id_cheque" name="id_cheque">
      <input type="hidden" id="fechaActual" name="fechaActual" value="<?php echo date('Y-m-d'); ?>">
      <input type="hidden" id="ivaIncluido" name="ivaIncluido" value=<?= $_SESSION['ivaIncluido']?>>
      <input type="hidden" id="valor_cheque" name="valor_cheque" value=0>
      <input type="hidden" id="valorIva" name="valorIva" value=<?= $_SESSION['valorIva']?>>
      <input type="hidden" id="TerDocId" name="TerDocId">
      <input type="hidden" id="id_consigna" name="id_consigna" value=0>
      <input type="hidden" id="BanCodig" name="BanCodig" value="">
      <input type="hidden" id="CueCodig" name="CueCodig" value="">
      <input type="hidden" id="clearSearch3" name="clearSearch3" value="">
      <input type="hidden" id="compte" name="compte" value="">
      <input type="hidden" id="CompteBco" name="CompteBco" value="">
      <input type="hidden" id="dias_cobrar" name="dias_cobrar" value=0>
      <input type="hidden" id="valor_aplaza" name="valor_aplaza" value=0>
      <input type="hidden" id="valor_interes" name="valor_interes" value=0>
      <input type="hidden" name="acountingList" id="acountingList" value="">
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
            <div class="card mb-2">
               <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity: 70%;">
                  <i class="fa-solid fa-file-signature me-2"></i>Datos del Documento
               </div>
               <div class="card-body">
                  <div class="row g-lg-3">
                     <div class="col-sm-6 col-lg-3 mb-3">
                        <div class="form-group">
                           <label for="numero" class="text-label fs-0 ps-0">Nro Cheque *</label>
                           <input type="text" class="form-control" id="numero" name="numero" onchange="queryDocument(this.value)" autofocus required>
                        </div>
                     </div>
                     <div class="col-sm-6 col-lg-3 mb-3">
                        <div class="form-group">
                           <label for="fecha_pago" class="text-label fs-0 ps-0">Fecha Pago *</label>
                           <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" placeholder="<?php echo DATE_DISPLAY ?>" data-inputmask="'alias': '<?= DATE_FORMAT ?>'" required>
                        </div>
                     </div>

                     <div class="col-sm-6 col-lg-3 mb-3">
                        <div class="form-group" style="text-align: right;" >
                           <label for="interes_pagar" class="text-label fs-0 ps-0">Intereses a Pagar *</label>
                           <!-- <input type="number" class="form-control p-1" style="text-align: right;" id="porcentaje_comision" name="porcentaje_comision" maxlength="7" step="0.000001" max="99.999999" value="0.000000"> -->
                           <input type="text" class="form-control p-2" style="text-align: right;" id="interes_pagar" name="interes_pagar" valMax=0 maxlength="11" value="0" >
                        </div>
                     </div>

                     <!--
                     <div class="col-sm-6 col-lg-3 mb-3">
                        <div class="form-group">
                           <label for="diasHabiles1" class="text-label fs-0 ps-0">Sólo días hábiles</label>
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
                     -->
                  </div>

                  <div class="row g-lg-4 mb-3">
                     <!--
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
                     -->
                     <div class="col-sm-6 col-md-4 col-lg-3 border">
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="nroCompte" class="text-label fs-0 ps-0">Nro Compte</label>
                              <input type="text" class="form-control p-1" style="text-align: right;" id="nroCompte" name="nroCompte" disabled>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="valCheque" class="text-label fs-0 ps-0">Vlr Cheqque</label>
                              <input type="text" class="form-control p-1" style="text-align: right;" id="valCheque" name="valCheque" disabled>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="porComision" class="text-label fs-0 ps-0" >% Comision</label>
                              <input type="number" class="form-control p-1" style="text-align: right;" id="porComision" name="porComision" disabled>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="impuesto_banco" class="text-label fs-0 ps-0">%Imp Bco X Mil</label>
                              <input type="number" class="form-control p-1" style="text-align: right;" id="impuesto_banco" name="impuesto_banco" disabled>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="valEntregar" class="text-label fs-0 ps-0">Vlr Entregado</label>
                              <input type="text" class="form-control p-1" style="text-align: right;" id="valEntregar" name="valEntregar"  disabled>
                           </div>
                        </div>

                        <!--
                        <div class="col-12 mb-3">
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
                        </div>
                        -->
                     </div>

                     <div class="col-sm-6 col-md-4 col-lg-3 border">
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="valComision" class="text-label fs-0 ps-0">Vlr Comisión</label>
                              <input type="text" class="form-control p-1" style="text-align: right;" id="valComision" name="valComision"  disabled>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="valImptoBco" class="text-label fs-0 ps-0">Valor Imp Bco</label>
                              <input type="text" class="form-control p-1" style="text-align: right;" id="valImptoBco" name="valImptoBco" disabled>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="valIVA" class="text-label fs-0 ps-0">Valor IVA</label>
                              <input type="text" class="form-control p-1" style="text-align: right;" id="valIVA" name="valIVA" disabled>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="totalAIC" class="text-label fs-0 ps-0">Total AIC</label>
                              <input type="text" class="form-control p-1" style="text-align: right;" id="totalAIC" name="totalAIC"  disabled>
                           </div>
                        </div>
                     </div>

                     <div class="col-sm-6 col-md-4 col-lg-3 border">
                        <div class="col-12 mb-3">
                           <div class="form-group">
                              <label for="fechaCheque" class="text-label fs-0 ps-0">Fecha Cheque</label>
                              <input type="text" class="form-control p-1" id="fechaCheque" name="fechaCheque" disabled>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-group">
                              <label for="fechaVencim" class="text-label fs-0 ps-0">Fec Vencim</label>
                              <input type="text" class="form-control p-1" id="fechaVencim" name="fechaVencim" disabled>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-group">
                              <label for="fechaAplaza" class="text-label fs-0 ps-0">Ult Aplazamiento</label>
                              <input type="text" class="form-control p-1" id="fechaAplaza" name="fechaAplaza" disabled>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="CapPagado" class="text-label fs-0 ps-0">Capital Pagado</label>
                              <input type="text" class="form-control p-1 text-end" style="text-align: right;" id="CapPagado" name="CapPagado"  disabled>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-group text-end fw-bold">
                              <label for="CapSaldo" class="text-label fs-0 ps-0">Saldo Capital</label>
                              <input type="text" class="form-control p-1 text-end fw-bold" id="CapSaldo" name="CapSaldo"  disabled>
                           </div>
                        </div>
                     </div>

                     <div class="col-sm-6 col-md-4 col-lg-3 border">
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;">
                              <label for="IntCobrados" class="text-label fs-0 ps-0">Int Cobrado</label>
                              <input type="text" class="form-control p-1 text-end" id="IntCobrados" name="IntCobrados"  disabled>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="IntPagado" class="text-label fs-0 ps-0">Int Pagado</label>
                              <input type="text" class="form-control p-1 text-end" id="IntPagado" name="IntPagado"  disabled>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="IntPendiente" class="text-label fs-0 ps-0">Int Pendiente</label>
                              <input type="text" class="form-control p-1 text-end" id="IntPendiente" name="IntPendiente"  disabled>
                           </div>
                        </div>
                        <!-- <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="IntNuevo" class="text-label fs-0 ps-0">Nuevo Interés</label>
                              <input type="text" class="form-control p-1 text-end" id="IntNuevo" name="IntNuevo"  disabled>
                           </div>
                        </div>
                        <div class="col-12 mb-3">
                           <div class="form-group" style="text-align: right;" >
                              <label for="IntTotal" class="text-label fs-0 ps-0">Tot Deuda Interés</label>
                              <input type="text" class="form-control p-1 text-end" id="IntTotal" name="IntTotal"  disabled>
                           </div>
                        </div> -->
                     </div>

                  </div>
                  <!-- <div class="row g-lg-3 mb-0">
                     <div class="col-12">
                        <div class="form-group">
                           <label for="motivo" class="text-label fs-0 ps-1">Motivo del aplazamiento</label>
                           <textarea type="text" class="form-control" id="motivo" name="motivo" rows="2"></textarea>
                        </div>
                     </div>
                  </div> -->
               </div>
            </div>
            <div class="card mb-0">
               <div class="card-body p-3">
                  <div class="d-grid gap-2">
                     <button class="btn btn-phoenix-success" type="submit" id="btnDevolAdd">GRABAR APLZAMIENTO</button>
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
<script src="app/js/dival/pagointer.js?v=1.0.0"></script>