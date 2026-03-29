<div class="content p-2 pt-10">
	<div class="row mb-4">
   	<div class="col-lg-8">
      	<h4 class="mb-0">Parámetros Generales</h4>
   	</div>
   	<div class="col-lg-4">
   		<nav class="mb-0" aria-label="breadcrumb">
      		<ol class="breadcrumb mb-2 float-sm-end">
         		<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
         		<li class="breadcrumb-item active">Parámetros</li>
      		</ol>
      	</nav>
   	</div>
	</div>
	<form class="needs-validation form-search paramsForm" role="form" id="paramsForm" name="paramsForm" enctype="multipart/form-data" method="post" novalidate>
      <input type="hidden" name="parametersList" id="parametersList" value="">
      <div class="row g-3">
         <div class="col-lg-6 mt-0">
            <div class="card mb-3 h-lg-100">
               <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity: 70%;">
                  <i class="fa-solid fa-file-invoice me-2"></i>Comprobantes Contables
               </div>
               <div class="card-body">
                  <!-- <div class="form-check">
                     <input class="form-check-input" type="checkbox" value="1" id="DvComChe" name="DvComChe" checked>
                        Cheque
                  </div> -->
                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-12 col-md-3 col-xl-2 p-1">
                        <label for="DvComChe" class="d-inline-block">Cambio Cheque</label>
                     </div>
                     <div class="col ps-1">
                        <select class="form-select select2 param" id="DvComChe" name="DvComChe" ParCodig="CO1" ParNombr="Compte contable por cambio de cheque">
                           <option value="">Seleccionar</option>
                        </select>
                     </div>
                  </div>
                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-12 col-md-3 col-xl-2 p-1">
                        <label for="DvComChe" class="d-inline-block">Aplazamiento</label>
                     </div>
                     <div class="col ps-1">
                        <select class="form-select select2 param" id="DvComApl" name="DvComApl" ParCodig="CO8" ParNombr="Compte contable por Aplazamiento">
                           <option value="">Seleccionar</option>
                        </select>
                     </div>
                  </div>
                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-12 col-md-3 col-xl-2 p-1">
                        <label for="DvComChe" class="d-inline-block">Consignación</label>
                     </div>
                     <div class="col ps-1">
                        <select class="form-select select2 param" id="DvComCon" name="DvComCon" ParCodig="CO2" ParNombr="Compte contable por Consignación">
                           <option value="">Seleccionar</option>
                        </select>
                     </div>
                  </div>
                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-12 col-md-3 col-xl-2 p-1">
                        <label for="DvComChe" class="d-inline-block">Devolución</label>
                     </div>
                     <div class="col ps-1">
                        <select class="form-select select2 param" id="DvComDev" name="DvComDev" ParCodig="CO3" ParNombr="Compte contable por Devolución">
                           <option value="">Seleccionar</option>
                        </select>
                     </div>
                  </div>
                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-12 col-md-3 col-xl-2 p-1">
                        <label for="DvComChe" class="d-inline-block">Pago Capital</label>
                     </div>
                     <div class="col ps-1">
                        <select class="form-select select2 param" id="DvComCap" name="DvComCap" ParCodig="CO4" ParNombr="Compte contable por Pago Capital">
                           <option value="">Seleccionar</option>
                        </select>
                     </div>
                  </div>
                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-12 col-md-3 col-xl-2 p-1">
                        <label for="DvComChe" class="d-inline-block">Pago Intereses</label>
                     </div>
                     <div class="col ps-1">
                        <select class="form-select select2 param" id="DvComInt" name="DvComInt" ParCodig="CO5" ParNombr="Compte contable por Pago Intereses">
                           <option value="">Seleccionar</option>
                        </select>
                     </div>
                  </div>
                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-12 col-md-3 col-xl-2 p-1">
                        <label for="DvComChe" class="d-inline-block">Vales de Caja</label>
                     </div>
                     <div class="col ps-1">
                        <select class="form-select select2 param" id="DvComVal" name="DvComVal" ParCodig="CO6" ParNombr="Compte contable por Vale de Caja">
                           <option value="">Seleccionar</option>
                        </select>
                     </div>
                  </div>
                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-12 col-md-3 col-xl-2 p-1">
                        <label for="DvComChe" class="d-inline-block">Recibo Efectivo</label>
                     </div>
                     <div class="col ps-1">
                        <select class="form-select select2 param" id="DvComEfe" name="DvComEfe" ParCodig="CO7" ParNombr="Compte contable por Recibo de Efectivo">
                           <option value="">Seleccionar</option>
                        </select>
                     </div>
                  </div>
               </div>

               <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity: 70%;">
                  <i class="fa-solid fa-file-invoice me-2"></i>Conceptos Banco
               </div>
               <div class="card-body py-3">
                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-12 col-md-3 col-xl-2 p-1">
                        <label for="DvComChe" class="d-inline-block">Consignación</label>
                     </div>
                     <div class="col ps-1">
                        <select class="form-select select2 param" id="DvConCon" name="DvConCon" ParCodig="BA1" ParNombr="Concepto de Consignación">
                           <option value="">Seleccionar</option>
                        </select>
                     </div>
                  </div>

                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-12 col-md-3 col-xl-2 p-1">
                        <label for="DvComChe" class="d-inline-block">Devolución</label>
                     </div>
                     <div class="col ps-1">
                        <select class="form-select select2 param" id="DvConDev" name="DvConDev" ParCodig="BA2" ParNombr="Concepto de Devolución">
                           <option value="">Seleccionar</option>
                        </select>
                     </div>
                  </div>

                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-12 col-md-3 col-xl-2 p-1">
                        <label for="DvComChe" class="d-inline-block">Egreso</label>
                     </div>
                     <div class="col ps-1">
                        <select class="form-select select2 param" id="DvConEgr" name="DvConEgr" ParCodig="BA3" ParNombr="Concepto de Egreso">
                           <option value="">Seleccionar</option>
                        </select>
                     </div>
                  </div>

               </div>
            </div>       
         </div>

         <div class="col-lg-6 mt-0">
            <div class="card mb-3 h-lg-100">
               <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity: 70%;">
                  <i class="fa-solid fa-book me-2"></i>Cuentas Contables
               </div>
               <div class="card-body">
                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-12 col-md-3 col-xl-2 p-1">
                        <label for="DvComChe" class="d-inline-block">Clientes</label>
                     </div>
                     
                     <div class="col ps-1">
                        <select class="form-select select2 param" id="DvCueCli" name="DvCueCli" ParCodig="CU1" ParNombr="Cuenta Contable Cliente por Cambio de cheque">
                           <option value="">Seleccionar</option>
                        </select>
                     </div>
                  </div>
                  
                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-12 col-md-3 col-xl-2 p-1">
                        <label for="DvComChe" class="d-inline-block">Caja</label>
                     </div>
                     <div class="col ps-1">
                        <select class="form-select select2 param" id="DvCueCaj" name="DvCueCaj" ParCodig="CU2" ParNombr="Cuenta Contable Caja por Cambio de cheque">
                           <option value="">Seleccionar</option>
                        </select>
                     </div>
                  </div>
                  
                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-12 col-md-3 col-xl-2 p-1">
                        <label for="DvComChe" class="d-inline-block">IVA</label>
                     </div>
                     <div class="col ps-1">
                        <select class="form-select select2 param" id="DvCueIva" name="DvCueIva" ParCodig="CU3" ParNombr="Cuenta Contable IVA por Cambio de cheque">
                           <option value="">Seleccionar</option>
                        </select>
                     </div>
                  </div>

                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-12 col-md-3 col-xl-2 p-1">
                        <label for="DvComChe" class="d-inline-block">Impto Banco</label>
                     </div>
                     <div class="col ps-1">
                        <select class="form-select select2 param" id="DvCueIba" name="DvCueIba" ParCodig="CU4" ParNombr="Cuenta Contable Impto Banco por Cambio de cheque">
                           <option value="">Seleccionar</option>
                        </select>
                     </div>
                  </div>

                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-12 col-md-3 col-xl-2 p-1">
                        <label for="DvComChe" class="d-inline-block">Int X Cobrar</label>
                     </div>
                     <div class="col ps-1">
                        <select class="form-select select2 param" id="DvCueIco" name="DvCueIco" ParCodig="CU5" ParNombr="Cuenta Contable Int X Cobrar por Cambio de cheque">
                           <option value="">Seleccionar</option>
                        </select>
                     </div>
                  </div>
                 
                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-12 col-md-3 col-xl-2 p-1">
                        <label for="DvComChe" class="d-inline-block">Comisión</label>
                     </div>
                     <div class="col ps-1">
                        <select class="form-select select2 param" id="DvCueCom" name="DvCueCom" ParCodig="CU6" ParNombr="Cuenta Contable Comisión por Cambio de cheque">
                           <option value="">Seleccionar</option>
                        </select>
                     </div>
                  </div>

                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-12 col-md-3 col-xl-2 p-1">
                        <label for="DvComChe" class="d-inline-block">Ingr Intereses</label>
                     </div>
                     <div class="col ps-1">
                        <select class="form-select select2 param" id="DvCueInt" name="DvCueInt" ParCodig="CU7" ParNombr="Cuenta Contable Intereses por Cambio de cheque">
                           <option value="">Seleccionar</option>
                        </select>
                     </div>
                  </div>

                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-12 col-md-3 col-xl-2 p-1">
                        <label for="DvComChe" class="d-inline-block">Ingr Mensajería</label>
                     </div>
                     <div class="col ps-1">
                        <select class="form-select select2 param" id="DvCueMen" name="DvCueMen" ParCodig="CU8" ParNombr="Cuenta Contable Mensajería por Cambio de cheque">
                           <option value="">Seleccionar</option>
                        </select>
                     </div>
                  </div>

                  <!-- <hr style="border: 1px solid; margin: 20px 0;"> -->

                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-4 col-md-3 col-xl-2 p-1">
                        <label for="DvIvaInc" class="d-inline-block">IVA Incluido</label>
                     </div>
                     <div class="col-6 col-md-4 col-xl-3 ps-1">
                        <input class="form-radio param" id="DvIvaInc_1" name="DvIvaInc" ParCodig="IVI" ParNombr="IVA Incluido 1= SI 2 = NO por Cambio de cheque" type="radio" value="1" />
                        <label class="fs-0 pe-3" for="DvIvaInc_1"> <span class="label-text">SI</span></label>
                        <input class="form-radio param" id="DvIvaInc_2" name="DvIvaInc" ParCodig="IVI" ParNombr="IVA Incluido 1= SI 2 = NO por Cambio de cheque" type="radio" value="2"  >
                        <label class="fs-0 pe-5" for="DvIvaInc_2"> <span class="label-text">NO</span></label>
                     </div>

                     <div class="col-4 col-md-2 col-xl-1 px-1">
                        <label for="DvPorIva" class="d-inline-block">% IVA</label>
                     </div>
                     <div class="col-4 col-md-3 col-xl-2 ps-1">
                        <input type="number" class="form-control p-1 text-end param" id="DvPorIva" name="DvPorIva" ParCodig="IVA" ParNombr="Porcentaje de IVA por Cambio de cheque" maxlength="4" step="0.01" value="0.00">
                     </div>
                  </div>

               </div>

               <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity: 70%;">
                  <i class="fa-solid fa-sitemap me-2"></i>Estructura Codigos Presupuestales
               </div>
                     
               <div class="card-body">
                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-12 col-md-3 col-xl-2 p-1">
                        <label for="DvNivRubIng" class="d-inline-block">Ingreso</label>
                     </div>
                     
                     <div class="col ps-1">
                        <input type="text" class="form-control param" id="DvNivRubIng" name="DvNivRubIng" ParCodig="RUI" ParNombr="Estructura de niveles para rubro de ingreso"
                                 placeholder="Ej: 1,2,4,6,9,12,14,16,18,20" maxlength="100">
                              <small class="text-muted">Separados por coma, en orden ascendente. Ejemplo: 1,2,4,6,9,12,14,16,18,20</small>
                     </div>
                  </div>

                  <div class="row align-items-center mb-3 mb-md-1">
                     <div class="col-12 col-md-3 col-xl-2 p-1">
                        <label for="DvNivRubGas" class="d-inline-block">Gastos</label>
                     </div>
                     
                     <div class="col ps-1">
                        <input type="text" class="form-control param" id="DvNivRubGas" name="DvNivRubGas" ParCodig="PRG" ParNombr="Estructura de niveles para rubro de gastos"
                                 placeholder="Ej: 1,2,4,6,9,12,14,16,18,20" maxlength="100">
                              <small class="text-muted">Separados por coma, en orden ascendente. Ejemplo: 1,2,4,6,9,12,14,16,18,20</small>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- <hr class="my-3"> -->
         <div class="row g-3">
            <div class="col-12">
               <div class="card mt-2 mb-0">
                  <div class="card-body p-3">
                     <div class="d-grid gap-2">
                        <button class="btn btn-phoenix-success" type="button" id="btnSaveParams">Grabar Parámetros</button>
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
<script src="app/js/dival/interface.js?v=1.0.0"></script>