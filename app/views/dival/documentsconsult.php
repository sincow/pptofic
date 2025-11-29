<style>
	#activeFilters {
		vertical-align: middle;
	}
	.badge.filter-badge {
		font-size: 0.85rem;
		padding: 0.35em 0.65em;
	}
	.remove-filter {
		font-size: 0.6rem;
		padding: 0.2rem;
		cursor: pointer;
	}
	.remove-filter:hover {
		opacity: 0.8;
	}
</style>

<div class="content ps-2 pe-2 pt-10">
	<div class="row mb-0">
		<div class="col-auto">
			<h4 class="mb-0 ms-1">Consulta de Documentos</h4>
		</div>
		<div class="col-lg-6">
			<div class="gesAlert">
			</div>
		</div>
		<div class="col-auto ms-auto">
			<nav class="mb-2" aria-label="breadcrumb">
				<ol class="breadcrumb mb-0 float-sm-end">
					<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
					<li class="breadcrumb-item active">Consulta Documentos</li>
				</ol>
			</nav>
		</div>
	</div>
	<form role="form" class="chequesForm" id="chequesForm" method="post" action="purchasedetail">
		<input type="hidden" class="idPODetail" name="idPODetail" value=''>
		<input type="hidden" class="dateFormat" id="dateFormat" value='<?php echo DATE_DISPLAY ?>'>
      <!-- <input type="hidden" name="pyt" value="<?php //getCSRFToken() ?>"> -->
		<div class="mb-3">
			<div id="chequesTable" data-list='{"valueNames":["id","numero","cliente","fecha","vencim","valor","saldo","interes","sdoint","status"],"page":20,"pagination":true}'>
				<div class="mb-1">
					<div class="d-flex flex-wrap gap-1">
						<div class="search-box">
							<form class="position-relative" data-bs-toggle="search" data-bs-display="static">
								<input class="form-control search-input search" id="searchOrder" name="searchOrder" type="search" placeholder="Search Purchases" aria-label="Search" />
								<span class="fas fa-search search-box-icon"></span>
							</form>
						</div>
						<!-- <div class="me-lg-auto"> -->
						<div >
							<a type="button" class="btn btn-phoenix-primary d-lg-block border" style="position:relative; transform:none; border:none;" id="btnPurchasesFilter" href="#filterOffcanvas" data-bs-toggle="offcanvas"><span class="fas fa-filter me-2"></span>Filtrar</a>
						</div>
						<div class="d-inline-block col-12 col-xl-8">
							<div id="activeFilters" class="d-inline-block ms-0"></div>
						</div>
						<?php
							$permiUpd = '<button type="button" class="btn btn-success ms-4 py-2.5 px-4" id="btnPurchasesUpdate"><span class="fas fa-cloud-download me-2"></span>Actualizar Despachos</button>';
							$permiUpd = "";
							$permiAdd = "";
							$permiExp = "";
							$permiRec = "";
							$permiRecSw = "";
							$opcion = array_search("purchaseadd", array_column($_SESSION['permissionssin'], 'OpcLink'));
							if ($opcion !== NULL && $opcion !== FALSE) {
								$permi = $_SESSION['permissionssin'][$opcion]["UsuPermi"];
								if ($permi != 0) {
									$permiAdd = '<a class="btn btn-primary" href="purchaseadd"><span class="fas fa-plus me-2"></span>Add P.Order</a>';
								}
							}
							$opcion = array_search("poexport", array_column($_SESSION['permissionssin'], 'OpcLink'));
							if ($opcion !== NULL && $opcion !== FALSE) {
								$permi = $_SESSION['permissionssin'][$opcion]["UsuPermi"];
								if ($permi != 0) {
									$permiExp = '<button class="btn btn-link text-900 me-4 px-0"><span class="fa-solid fa-file-export fs--1 me-2"></span>Export</button>';
								}
							}
							$opcion = array_search("recsessionadd", array_column($_SESSION['permissionssin'], 'OpcLink'));
							if ($opcion !== NULL && $opcion !== FALSE) {
								$permi = $_SESSION['permissionssin'][$opcion]["UsuPermi"];
								if ($permi != 0) {
									$permiRecSw = "1";
								}
							}
						?>
						<input type="hidden" class="permiRecSw" id="permiRec" value="<?= $permiRecSw?>">
						<div class="ms-lg-auto">
							<?php echo $permiExp . $permiAdd . $permiUpd; ?>
						</div>
						<!--
						<div class="ms-lg-auto">
						<button class="btn btn-link text-900 me-4 px-0"><span class="fa-solid fa-file-export fs--1 me-2"></span>Exportar</button>
						<a class="btn btn-primary" id="addBtn" href="orderadd"><span class="fas fa-plus me-2"></span>Agregar Pedido</a>
						</div>
						-->
					</div>
				</div>
				<div class="mx-n4 px-4 mx-lg-0 px-lg-0 bg-white border-top border-bottom border-200 position-relative top-1">
					<div class="table-responsive scrollbar mx-n1 px-1">
						<form role="form" class="mb-1 poFormEdit" method="post" action="recsessionadd">
							<input type="hidden" class="idpoRec" name="idPo" value="">
							<table class="table table-striped table-sm fs--1 mb-0 documentsTable" id="documentsTable">
								<thead style="background-color: var(--phoenix-body-bg); backdrop-filter: blur(8px); opacity: 0.98;">
									<tr>
										<th class="sort align-middle text-end white-space-nowrap pe-3" scope="col" data-sort="id" style="width:7%;">Compte #</th>
										<th class="sort align-middle text-start ps-0" scope="col" data-sort="numero"  style="width:6%;">Nro Cheque</th>
										<th class="sort align-middle text-start ps-0" scope="col" data-sort="cliente" style="width:18%; min-width: 100px;">Cliente</th>
										<th class="sort align-middle text-start ps-0" scope="col" data-sort="fecha"   style="width:6%;">Fec.Cheque</th>
										<th class="sort align-middle text-start ps-0" scope="col" data-sort="vecim"   style="width:7%;">Fec.Vencim</th>
										<th class="sort align-middle text-end pe-2"   scope="col" data-sort="valor"   style="width:8%;">Vlr Cheque</th>
										<th class="sort align-middle text-end pe-2"   scope="col" data-sort="saldo"   style="width:8%;">Sdo Capital</th>
										<th class="sort align-middle text-end pe-2"   scope="col" data-sort="interes" style="width:8%;">Int Cobrado</th>
										<th class="sort align-middle text-end pe-2"   scope="col" data-sort="sdoint"  style="width:8%;">Sdo Interes</th>
										<th class="sort align-middle text-start ps-0" scope="col" data-sort="status"  style="width:7%;">Estado</th>
										<th class="sort align-middle text-start pe-1" scope="col" style="width:3%;">Acciones</th>
									</tr>
								</thead>
								<tbody class="list" id="chequesTable-body">
                           <!-- <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                              <td colspan="12" class="order align-middle white-space-nowrap py-2 text-center">
                                 No hay registros para mostrar
                              </td>
                           </tr> -->
                           <tr>
                              <td colspan="12" class="text-center">
                                 <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                 </div>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </form>  
               </div>

               <div class="row align-items-center justify-content-between py-2 pe-0 fs--2">
                  <div class="col-auto d-flex">
                     <p class="mb-0 d-none d-sm-block me-3 fw-semi-bold text-900 ps-2" data-list-info="data-list-info"></p>
							<a class="fw-semi-bold" href="#!" data-list-view="*">Ver todos<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
							<a class="fw-semi-bold d-none" id="btnVerMenos" href="#!" data-list-view="less">Ver menos<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
                  </div>
                  <div class="col-auto d-flex">
                     <button class="page-link" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                     <ul class="mb-0 pagination"></ul>
                     <button class="page-link pe-0" data-list-pagination="next"><span class="fas fa-chevron-right"></span></button>
                  </div>
               </div>

            </div>
         </div>
      </div>
   </form>
	<?php
      include "chequedetails.php";
		include APP_PATH.'/views/layouts/footer.php';
	?>
</div>

<div class="offcanvas offcanvas-end settings-panel border-0" id="filterOffcanvas" tabindex="-1" aria-labelledby="filterOffcanvas">
	<div class="offcanvas-header align-items-start border-bottom flex-column">
		<div class="pt-1 w-100 mb-2 d-flex justify-content-between align-items-start">
			<div>
				<h5 class="mb-2 me-2 lh-sm"><span class="fas fa-filter me-2 fs-0"></span>Establecer filtros</h5>
				<p class="mb-0 fs--1">Definir filtros para mostrar información</p>
			</div>
			<button class="btn p-1 fw-bolder" type="button" data-bs-dismiss="offcanvas" aria-label="Close"><span class="fas fa-times fs-0"> </span></button>
		</div>
		<button class="btn btn-phoenix-secondary w-100" data-theme-control="reset"><span class="fas fa-arrows-rotate me-2 fs--2"></span>Restablecer filtros</button>
	</div>
	<div class="offcanvas-body scrollbar px-card" id="themeController">
		<form role="form" id="frmDocumentsFilter" method="post">
			<input type="hidden" id="modulo" name="modulo" value="dival">
			<input type="hidden" id="option" name="option" value="cheques">
			<input type="hidden" id="action" name="action" value="filter">
			<!-- <input type="hidden" name="pyt" value="<?php //getCSRFToken() ?>"> -->
			<div class="row align-items-center g-1 text-sm-start">
				<div class="col-6 mt-0">
					<div class="form-control mb-3 p-0 border-0">
						<label for="numberSearch">Numero Cheque</label>
						<input type="text" class="form-control py-2" id="numberSearch" name="numberSearch" value="">
					</div>
				</div>
				<div class="col-6 mt-0">
					<div class="form-control mb-3 p-0 border-0">
						<label for="statusSearch">Estatus</label>
						<select class="form-select select2" name="statusSearch" id="statusSearch">
							<option value="">Seleccionar</option>
							<option value="*">Todos</option>
							<option value="1">Pendientes</option>
							<option value="C">Consignados</option>
							<option value="D">Devueltos</option>
						</select>
					</div>
				</div>
				<div class="col-12 text-center">
					<p class="mb-0 fs--1">Fecha Creación</p>
				</div>
				<div class="col-6 mt-0">
					<div class="form-control mb-3 p-0 border-0">
						<label for="fecCambioSearchFrom">desde</label>
						<input type="text" class="form-control datepicker py-2 dp_fecha_ini" id="fecCambioSearchFrom" name="fecCambioSearchFrom" placeholder="<?php echo DATE_DISPLAY ?>" data-inputmask="'alias': '<?= DATE_DISPLAY ?>'" autocomplete="off" data-mask>
					</div>
				</div>
				<div class="col-6 mt-0">
					<div class="form-control mb-3 p-0 border-0">
						<label for="fecCambioSearchTo">Hasta</label>
						<input type="text" class="form-control datepicker py-2 dp_fecha_fin" id="fecCambioSearchTo" name="fecCambioSearchTo" placeholder="<?php echo DATE_DISPLAY ?>" data-inputmask="'alias': '<?= DATE_DISPLAY ?>'" autocomplete="off" data-mask>
					</div>
				</div>

				<div class="col-6 mt-0">
					<div class="form-control mb-3 p-0 border-0">
						<label for="fecVencimSearch">Por Vencerse hasta</label>
						<input type="text" class="form-control datepicker fecVencimSearch py-2" id="fecVencimSearch" name="fecVencimSearch" placeholder="<?php echo DATE_DISPLAY ?>" data-inputmask="'alias': '<?= DATE_DISPLAY ?>'" autocomplete="off" data-mask>
					</div>
				</div>

            <div class="col-12">
					<div class="form-control mb-3 p-0 border-0">
						<label for="clienteSearch">Cliente</label>
						<select class="form-select clienteSearch" name="clienteSearch" id="clienteSearch">
							<option selected="selected" value="">Select</option>
						</select>
					</div>
				</div>

            <!--
            <div class="col-12 mb-3">
					<label for="poVendorSearch">Vendor</label>
					<select class="form-control select2 phoenix-select2 select2-required poVendorName" name="poVendorSearch" id="poVendorSearch">
						<option selected="selected" value="">Select</option>
					</select>
				</div>
				<div class="col-12 mb-3">
					<label for="poWarehouseSearch">Warehouse</label>
					<select class="form-control select2 phoenix-select2 select2-required poWhName" name="poWarehouseSearch" id="poWarehouseSearch">
						<option selected="selected" value="">Select</option>
					</select>
				</div>
				<div class="col-12 text-center">
					<p class="mb-0 fs-0">Order Date</p>
				</div>
				<div class="col-6 mt-0">
					<div class="form-control mb-3 p-0 border-0">
						<label for="poFromDateSearch">From</label>
						<input type="text" class="form-control datepicker py-2 dp_fecha_ini" id="poFromDateSearch" name="poFromDateSearch" placeholder="<?php echo DATE_DISPLAY ?>" data-inputmask="'alias': '<?= DATE_DISPLAY ?>'" autocomplete="off" data-mask>
					</div>
				</div>
				<div class="col-6 mt-0">
					<div class="form-control mb-3 p-0 border-0">
						<label for="poToDateSearch">To</label>
						<input type="text" class="form-control datepicker py-2 dp_fecha_fin" id="poToDateSearch" name="poToDateSearch" placeholder="<?php echo DATE_DISPLAY ?>" data-options='{"disableMobile":true}' autocomplete="off" value=<?php //echo date('m-d-Y', strtotime($fecha)); ?>>
					</div>
				</div>

				<div class="col-12 text-center">
					<p class="mb-0 fs-0">Expexted Date</p>
				</div>
				<div class="col-6 mt-0">
					<div class="form-control mb-3 p-0 border-0">
						<label for="poFromExpectedSearch">From</label>
						<input type="text" class="form-control datepicker py-2 dp_fecha_ini" id="poFromExpectedSearch" name="poFromExpectedSearch" placeholder="<?php echo DATE_DISPLAY ?>" data-inputmask="'alias': '<?= DATE_DISPLAY ?>'" autocomplete="off" data-mask>
					</div>
				</div>
				<div class="col-6 mt-0">
					<div class="form-control mb-3 p-0 border-0">
						<label for="poToExpectedSearch">To</label>
						<input type="text" class="form-control datepicker py-2 dp_fecha_fin" id="poToExpectedSearch" name="poToExpectedSearch" placeholder="<?php echo DATE_DISPLAY ?>" data-options='{"disableMobile":true}' autocomplete="off" value=<?php //echo date('m-d-Y', strtotime($fecha)); ?>>
					</div>
				</div>
            -->

				<div class="col-12 text-center">
					<p class="mb-0 fs--1">Saldo Capital</p>
				</div>
				<div class="col-6 mt-0">
					<div class="form-control mb-3 p-0 border-0 text-center">
						<label for="minCostSearch">Desde</label>
						<input type="number" class="form-control py-2 text-end" id="minCostSearch" name="minCostSearch" >
					</div>
				</div>
				<div class="col-6 mt-0">
					<div class="form-control mb-3 p-0 border-0 text-end">
						<label for="maxCostSearch">Hasta</label>
						<input type="number" class="form-control py-2 text-end" id="maxCostSearch" name="maxCostSearch" >
					</div>
				</div>
				<button type="submit" class="btn btn-phoenix-info w-100" id="btnFilterPurchases" data-bs-dismiss="offcanvas" aria-label="Close" id="btnFilterPurchases"><span class="fas fa-filter me-2 fs--2"></span>Establecer filtros</button>
				<!-- <button class="btn p-1 fw-bolder" type="button" data-bs-dismiss="offcanvas" aria-label="Close"><span class="fas fa-times fs-0"> </span></button> -->
			</div>
		</form>
	</div>
</div>
<script src="app/js/dival/cheques.js?v=1.0.0"></script>