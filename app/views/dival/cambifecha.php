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
			<h4 class="mb-0 ms-1">Purchase Orders</h4>
		</div>
		<div class="col-lg-6">
			<div class="gesAlert">
			</div>
		</div>
		<div class="col-auto ms-auto">
			<nav class="mb-2" aria-label="breadcrumb">
				<ol class="breadcrumb mb-0 float-sm-end">
					<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
					<li class="breadcrumb-item active">Purchase Orders</li>
				</ol>
			</nav>
		</div>
	</div>
	<form role="form" class="frmPurchases" id="frmPurchasesPending" method="post" action="purchasedetail">
		<input type="hidden" class="idPODetail" name="idPODetail" value=''>
		<input type="hidden" class="dateFormat" id="dateFormat" value='<?php echo DATE_DISPLAY ?>'>
      <!-- <input type="hidden" name="pyt" value="<?php //getCSRFToken() ?>"> -->
		<div class="mb-3">
			<div id="poTable" data-list='{"valueNames":["po","customer","vendor","wh","created","expected","tracking","received","cost","status"],"page":20,"pagination":true}'>
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
							<a type="button" class="btn btn-phoenix-primary d-none d-lg-block border" style="position:relative; transform:none; border:none;" id="btnPurchasesFilter" href="#filterOffcanvas" data-bs-toggle="offcanvas"><span class="fas fa-filter me-2"></span>Filter</a>
						</div>
						<div class="d-inline-block col-7">
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
				<div class="mx-n4 px-4 mx-lg-0 px-lg-2 bg-white border-top border-bottom border-200 position-relative top-1">
					<div class="table-responsive scrollbar mx-n1 px-1">
						<form role="form" class="mb-1 poFormEdit" method="post" action="recsessionadd">
							<input type="hidden" class="idpoRec" name="idPo" value="">
							<table class="table table-striped table-sm fs--1 mb-0 purchasesTable" id="purchasesTable">
								<thead style="background-color: var(--phoenix-body-bg); backdrop-filter: blur(8px); opacity: 0.98;">
									<tr>
										<th class="sort align-middle white-space-nowrap pe-3" scope="col" data-sort="po" style="width:8%;">Compte #</th>
										<th class="sort align-middle text-start ps-0" scope="col" data-sort="customer" style="width:8%;">Nro Cheque</th>
										<th class="sort align-middle text-start ps-0" scope="col" data-sort="vendor"   style="width:15%; min-width: 100px;">Cliente</th>
										<th class="sort align-middle text-start ps-0" scope="col" data-sort="wh"       style="width:10%;">Fec.Cheque</th>
										<th class="sort align-middle text-start ps-0" scope="col" data-sort="created"  style="width:10%;">Fec.Vencim</th>
										<th class="sort align-middle text-end ps-2" scope="col" data-sort="expected"   style="width:10%;">Vlr Cheque</th>
										<th class="sort align-middle text-end ps-2" scope="col" data-sort="expected"   style="width:10%;">Cap Pagado</th>
										<th class="sort align-middle text-end ps-2" scope="col" data-sort="tracking"   style="width:10%;">Vlr Comisión</th>
										<th class="sort align-middle text-end ps-2" scope="col" data-sort="received"   style="width:10%;">Int Cobrado</th>
										<th class="sort align-middle text-end pe-2"   scope="col" data-sort="cost"     style="width:10%;">Int Pagado</th>
										<th class="sort align-middle text-start ps-1" scope="col" data-sort="status"   style="width:7%;">Sdo Capital</th>
										<th class="sort align-middle text-start pe-1" scope="col" style="width:3%;">Acciones</th>
									</tr>
								</thead>
								<tbody class="list" id="po-table-body">
                           <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                              <td colspan="12" class="order align-middle white-space-nowrap py-2 text-center">
                                 No hay registros para mostrar
                              </td>
                           </tr>
                           <!-- <tr>
                              <td colspan="8" class="text-center">
                                 <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                 </div>
                              </td>
                           </tr> -->
                        </tbody>
                     </table>
                  </form>  
               </div>

               <div class="row align-items-center justify-content-between py-2 pe-0 fs--2">
                  <div class="col-auto d-flex">
                     <p class="mb-0 d-none d-sm-block me-3 fw-semi-bold text-900" data-list-info="data-list-info"></p><a class="fw-semi-bold" href="#!" data-list-view="*">Ver todos<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a><a class="fw-semi-bold d-none" id="btnVerMenos" href="#!" data-list-view="less">Ver menos<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
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
		include APP_PATH.'/views/layouts/footer.php';
	?>
</div>