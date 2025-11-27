<div class="content p-2 pt-10">
	<div class="row mb-3">
   	<div class="col-lg-8">
      	<h4 class="mb-0">Anular Aplazamiento de Cheque</h4>
   	</div>
   	<div class="col-lg-4">
   		<nav class="mb-0" aria-label="breadcrumb">
      		<ol class="breadcrumb mb-2 float-sm-end">
         		<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
         		<li class="breadcrumb-item active">Anular Aplazamiento</li>
      		</ol>
      	</nav>
   	</div>
	</div>
	<form class="needs-validation form-search formAplazaAnu" role="form" id="formAplazaAnu" name="formAplazaAnu" enctype="multipart/form-data" method="post" novalidate>
		<input type="hidden" class="dateFormat" id="dateFormat" value='<?php echo DATE_DISPLAY ?>'>
      <input type="hidden" id="id_aplaza" name="id_aplaza" value="">
      <input type="hidden" id="id_cheque" name="id_cheque" value=0>
      <input type="hidden" id="valor_interes" name="valor_interes" value=0>
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
                           <label for="numDocAnu" class="text-label fs-0 ps-2 mt-1 mb-0">Nro Aplazamiento</label>
                           <div class="col-6 col-lg-3 mt-0 mb-3">
                              <!-- <div class="form-group"> -->
                              <input type="text" class="form-control" id="numDocAnu" name="numDocAnu" autofocus required>
                              <!-- </div> -->
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

                        <!--
                        <div class="row g-lg-4 mb-3">
                           <div class="col-sm-6 col-md-4 col-lg-3 border">
                              <div class="col-12 mb-3">
                                 <div class="form-group" style="text-align: right;" >
                                    <label for="nroCompte" class="text-label fs-0 ps-0">Nro Compte</label>
                                    <input type="text" class="form-control p-1" style="text-align: right;" id="nroCompte" name="nroCompte" disabled required>
                                 </div>
                              </div>
                              <div class="col-12 mb-3">
                                 <div class="form-group" style="text-align: right;" >
                                    <label for="valCheque" class="text-label fs-0 ps-0">Vlr Cheqque</label>
                                    <input type="text" class="form-control p-1" style="text-align: right;" id="valCheque" name="valCheque" disabled required>
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

                           </div>

                           <div class="col-sm-6 col-md-4 col-lg-3 border">
                              <div class="col-12 mb-3">
                                 <div class="form-group" style="text-align: right;" >
                                    <label for="porcentaje_comision" class="text-label fs-0 ps-0" >% Comision *</label>
                                    <input type="number" class="form-control p-1" style="text-align: right;" id="porcentaje_comision" name="porcentaje_comision" maxlength="7" step="0.000001" max="99.999999" value="0.000000">
                                 </div>
                              </div>
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
                                    <input type="text" class="form-control p-1" style="text-align: right;" id="CapPagado" name="CapPagado"  disabled>
                                 </div>
                              </div>
                              <div class="col-12 mb-3">
                                 <div class="form-group" style="text-align: right;">
                                    <label for="CapSaldo" class="text-label fs-0 ps-0">Saldo Capital</label>
                                    <input type="text" class="form-control p-1" style="text-align: right;" id="CapSaldo" name="CapSaldo"  disabled>
                                 </div>
                              </div>
                           </div>

                           <div class="col-sm-6 col-md-4 col-lg-3 border">
                              <div class="col-12 mb-3">
                                 <div class="form-group" style="text-align: right;">
                                    <label for="IntCobrados" class="text-label fs-0 ps-0">Int Cobrado</label>
                                    <input type="text" class="form-control p-1" style="text-align: right;" id="IntCobrados" name="IntCobrados"  disabled>
                                 </div>
                              </div>
                              <div class="col-12 mb-3">
                                 <div class="form-group" style="text-align: right;" >
                                    <label for="IntPagado" class="text-label fs-0 ps-0">Int Pagado</label>
                                    <input type="text" class="form-control p-1" style="text-align: right;" id="IntPagado" name="IntPagado"  disabled>
                                 </div>
                              </div>
                              <div class="col-12 mb-3">
                                 <div class="form-group" style="text-align: right;" >
                                    <label for="IntPendiente" class="text-label fs-0 ps-0">Int Pendiente</label>
                                    <input type="text" class="form-control p-1" style="text-align: right;" id="IntPendiente" name="IntPendiente"  disabled>
                                 </div>
                              </div>
                              <div class="col-12 mb-3">
                                 <div class="form-group" style="text-align: right;" >
                                    <label for="IntNuevo" class="text-label fs-0 ps-0">Nuevo Interés</label>
                                    <input type="text" class="form-control p-1" style="text-align: right;" id="IntNuevo" name="IntNuevo"  disabled>
                                 </div>
                              </div>
                              <div class="col-12 mb-3">
                                 <div class="form-group" style="text-align: right;" >
                                    <label for="IntTotal" class="text-label fs-0 ps-0">Tot Deuda Interés</label>
                                    <input type="text" class="form-control p-1" style="text-align: right;" id="IntTotal" name="IntTotal"  disabled>
                                 </div>
                              </div>
                           </div>

                        </div>
                        <div class="row g-lg-3 mb-0">
                           <div class="col-12">
                              <div class="form-group">
                                 <label for="motivo" class="text-label fs-0 ps-1">Motivo del aplazamiento</label>
                                 <textarea type="text" class="form-control" id="motivo" name="motivo" rows="2"></textarea>
                              </div>
                           </div>
                        </div>
                        -->
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
<script src="app/js/dival/anularaplaza.js?v=1.0.0"></script>