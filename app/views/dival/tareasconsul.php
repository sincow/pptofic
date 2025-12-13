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
			<h4 class="mb-0 ms-1">Consulta de Tareas</h4>
		</div>
		<div class="col-lg-6">
			<div class="gesAlert">
			</div>
		</div>
		<div class="col-auto ms-auto">
			<nav class="mb-2" aria-label="breadcrumb">
				<ol class="breadcrumb mb-0 float-sm-end">
					<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
					<li class="breadcrumb-item active">Consulta Tareas</li>
				</ol>
			</nav>
		</div>
	</div>
	<form role="form" class="tareasconsulForm" id="tareasconsulForm" method="post" action="tareadetail">
		<input type="hidden" class="idPODetail" name="idPODetail" value=''>
		<input type="hidden" class="dateFormat" id="dateFormat" value='<?php echo DATE_DISPLAY ?>'>
      <!-- <input type="hidden" name="pyt" value="<?php //getCSRFToken() ?>"> -->
		<div class="mb-3">
			<div id="notifiTable" data-list='{"valueNames":["id","numero","cliente","fecha","vencim","valor","saldo","interes","sdoint","status"],"page":20,"pagination":true}'>
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
										<th class="sort align-middle text-end white-space-nowrap pe-3" scope="col" data-sort="id" style="width:5%;">Número</th>
										<th class="sort align-middle text-start ps-0" scope="col" data-sort="empleado"  style="width:14%; min-width: 100px;">Empleado</th>
										<th class="sort align-middle text-start ps-0" scope="col" data-sort="fecha"     style="width:6%;">Fecha</th>
										<th class="sort align-middle text-start ps-0" scope="col" data-sort="entrega"   style="width:6%;">Fec.Entrega</th>
										<th class="sort align-middle text-start ps-0" scope="col" data-sort="reprogra"  style="width:6%;">Fec.Reprog</th>
										<th class="sort align-middle text-start ps-0" scope="col" data-sort="cierre"    style="width:6%;">Fec.Cierre</th>
										<th class="sort align-middle text-start ps-0" scope="col" data-sort="titulo"    style="width:30%;">Titulo</th>
										<th class="sort align-middle text-start pe-2" scope="col" data-sort="situacion" style="width:12%;">Situación</th>
										<th class="sort align-middle text-end pe-3"   scope="col" data-sort="cumplimi"  style="width:6%;">% Cumpl</th>
										<th class="sort align-middle text-start ps-0" scope="col" data-sort="status"    style="width:7%;">Estado</th>
										<!-- <th class="sort align-middle text-start pe-1" scope="col" style="width:3%;">Acciones</th> -->
									</tr>
								</thead>
								<tbody class="list" id="notifiTable-body">
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
      include "tareadetails.php";
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
			<input type="hidden" id="modulo" name="modulo" value="admon">
			<input type="hidden" id="option" name="option" value="notificaciones">
			<input type="hidden" id="action" name="action" value="filter">
			<!-- <input type="hidden" name="pyt" value="<?php //getCSRFToken() ?>"> -->
			<div class="row align-items-center g-1 text-sm-start">
				<div class="col-6 mt-0">
					<div class="form-control mb-3 p-0 border-0">
						<label for="numberSearch">Numero Tarea</label>
						<input type="text" class="form-control py-2" id="numberSearch" name="numberSearch" value="">
					</div>
				</div>
				<div class="col-6 mt-0">
					<div class="form-control mb-3 p-0 border-0">
						<label for="statusSearch">Estatus</label>
						<select class="form-select select2" name="statusSearch" id="statusSearch">
							<option value="">Seleccionar</option>
							<option value="*">Todas</option>
							<option value="1">Pendientes</option>
							<option value="9">Cerradas</option>
						</select>
					</div>
				</div>
            <?php
               if ($_SESSION["profile"] != 1) {
                  echo '
                     <div class="col-12 d-none">
                        <div class="form-control mb-3 p-0 border-0">
                           <label for="empleadoSearch">Empleado</label>
                           <select class="form-select empleadoSearch" name="empleadoSearch" id="empleadoSearch">
                              <option selected="selected" value="">Select</option>
                           </select>
                        </div>
                     </div>
                  ';
               } else {
                  echo '
                     <div class="col-12">
                        <div class="form-control mb-3 p-0 border-0">
                           <label for="empleadoSearch">Empleado</label>
                           <select class="form-select empleadoSearch" name="empleadoSearch" id="empleadoSearch">
                              <option selected="selected" value="">Select</option>
                           </select>
                        </div>
                     </div>
                  ';
               }
            ?>
            <div class="col-12 text-center">
					<p class="mb-0 fs--1">Fecha Creación</p>
				</div>
				<div class="col-6 mt-0">
					<div class="form-control mb-3 p-0 border-0">
						<label for="fechaSearchFrom">desde</label>
						<input type="text" class="form-control datepicker py-2 dp_fecha_ini" id="fechaSearchFrom" name="fechaSearchFrom" placeholder="<?php echo DATE_DISPLAY ?>" data-inputmask="'alias': '<?= DATE_DISPLAY ?>'" autocomplete="off" data-mask>
					</div>
				</div>
				<div class="col-6 mt-0">
					<div class="form-control mb-3 p-0 border-0">
						<label for="fechaSearchTo">Hasta</label>
						<input type="text" class="form-control datepicker py-2 dp_fecha_fin" id="fechaSearchTo" name="fechaSearchTo" placeholder="<?php echo DATE_DISPLAY ?>" data-inputmask="'alias': '<?= DATE_DISPLAY ?>'" autocomplete="off" data-mask>
					</div>
				</div>

            <div class="col-12 text-center">
					<p class="mb-0 fs--1">Fecha Entrega</p>
				</div>
				<div class="col-6 mt-0">
					<div class="form-control mb-3 p-0 border-0">
						<label for="entregaSearchFrom">desde</label>
						<input type="text" class="form-control datepicker py-2 dp_fecha_ini" id="entregaSearchFrom" name="entregaSearchFrom" placeholder="<?php echo DATE_DISPLAY ?>" data-inputmask="'alias': '<?= DATE_DISPLAY ?>'" autocomplete="off" data-mask>
					</div>
				</div>
				<div class="col-6 mt-0">
					<div class="form-control mb-3 p-0 border-0">
						<label for="entregaSearchTo">Hasta</label>
						<input type="text" class="form-control datepicker py-2 dp_fecha_fin" id="entregaSearchTo" name="entregaSearchTo" placeholder="<?php echo DATE_DISPLAY ?>" data-inputmask="'alias': '<?= DATE_DISPLAY ?>'" autocomplete="off" data-mask>
					</div>
				</div>

				<div class="col-12 mt-0">
					<div class="form-control mb-3 p-0 border-0 text-center">
						<label for="tituloSearch">Titulo</label>
						<input type="text" class="form-control py-2" id="tituloSearch" name="tituloSearch" >
					</div>
				</div>
				<!-- <div class="col-6 mt-0">
					<div class="form-control mb-3 p-0 border-0 text-end">
						<label for="maxCostSearch">Hasta</label>
						<input type="number" class="form-control py-2 text-end" id="maxCostSearch" name="maxCostSearch" >
					</div>
				</div> -->
				<button type="submit" class="btn btn-phoenix-info w-100" id="btnFilterPurchases" data-bs-dismiss="offcanvas" aria-label="Close" id="btnFilterPurchases"><span class="fas fa-filter me-2 fs--2"></span>Establecer filtros</button>
				<!-- <button class="btn p-1 fw-bolder" type="button" data-bs-dismiss="offcanvas" aria-label="Close"><span class="fas fa-times fs-0"> </span></button> -->
			</div>
		</form>
	</div>
</div>
<script src="app/js/admon/notificonsul.js?v=1.0.1"></script>