<style>
	.hover-border-info:hover .card {
    border-color: #25B0D3 !important;
    box-shadow: 0 0 0 1px #25B0D3;
	}

	.hover-border-info:hover .text-900 {
		color: #25B0D3 !important;
	}

	.phoenix-hover-info .phoenix-card-hover {
		transition: all 0.2s ease-in-out;
	}

	/*****/
	.phoenix-hover-info:hover .phoenix-card-hover {
		background-color: var(--badge-phoenix-primary) !important;
		border-color: var(--badge-phoenix-primary) !important;
		/* background-color: #25B0D3 !important; */
		/* border-color: #25B0D3 !important; */
	}

	/*
	.phoenix-hover-info:hover .text-900,
	.phoenix-hover-info:hover .text-700,
	.phoenix-hover-info:hover .fw-semi-bold {
		color: white !important;
	}

	.phoenix-hover-info:hover .reportsby {
		color: rgba(255, 255, 255, 0.9) !important;
	}
	*/

	/* Agregar este CSS */
	.hover-info:hover .hover-bg-info {
		background-color: var(--phoenix-info) !important;
		border-color: var(--phoenix-info) !important;
	}

	.hover-info:hover .text-900,
	.hover-info:hover .text-700 {
		color: white !important;
	}

	.hover-info:hover .reportsby {
		color: white !important;
	}
</style>

<?php
	$table = "reports";
	$order = "priority_report, last_generate_report DESC, name_report";
	$where = "status_report = '1'";
	$reports = GeneralModel::getAll($table, $order, $where);
?>
<div class="content pt-10">
	<div class="row mb-3">
		<div class="col-lg-8">
			<h4 class="mb-0">Reportes</h4>
		</div>
		<div class="col-lg-4">
			<nav class="mb-2" aria-label="breadcrumb">
				<ol class="breadcrumb mb-0 float-sm-end">
					<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
					<li class="breadcrumb-item active">Reportes</li>
				</ol>
			</nav>
		</div>
	</div>
	<div class="pb-2">
		<div id="reports" data-list='{"valueNames":["title","text","priority","reportsby","reports","date"],"page":9,"pagination":true}'>
			<div class="row g-3 justify-content-between mb-2">
				<div class="col-12">
					<div class="d-md-flex justify-content-between">
						<div class="d-flex mb-0">
							<div class="search-box me-2">
								<form class="position-relative" data-bs-toggle="search" data-bs-display="static">
									<input class="form-control search-input search" type="search" placeholder="Buscar por nombre o descripción" aria-label="Search" />
									<span class="fas fa-search search-box-icon"></span>
								</form>
							</div>
							<!-- <button class="btn px-3 py-2 btn-phoenix-secondary" type="button" data-bs-toggle="modal" data-bs-target="#reportsFilterModal" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fa-solid fa-filter text-primary" data-fa-transform="down-3"></span></button> -->
							<div class="modal fade" id="reportsFilterModal" tabindex="-1">
								<div class="modal-dialog modal-dialog-centered">
									<div class="modal-content border">
										<form id="addEventForm" autocomplete="off">
											<div class="modal-header border-200 p-4">
												<h5 class="modal-title text-1000 fs-2 lh-sm">Filtro</h5>
												<button class="btn p-1 text-danger" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs--1"> </span></button>
											</div>
											<div class="modal-body pt-4 pb-2 px-4">
												<div class="mb-3">
													<label class="fw-bold mb-2 text-1000" for="priority">Prioridad</label>
													<select class="form-select" id="priority">
														<option value="1" selected="selected">Alto</option>
														<option value="2">Medio </option>
														<option value="3">Bajo</option>
													</select>
												</div>
												<div class="mb-3">
													<label class="fw-bold mb-2 text-1000" for="createDate">Create Date</label>
													<select class="form-select" id="createDate">
														<option value="today" selected="selected">Today</option>
														<option value="last7Days">Last 7 Days</option>
														<option value="last30Days">Last 30 Days</option>
														<option value="chooseATimePeriod">Choose a time period</option>
													</select>
												</div>
												<div class="mb-3">
													<label class="fw-bold mb-2 text-1000" for="category">Categoria</label>
													<select class="form-select" id="category">
														<option value="1" selected="selected">Listados Generales</option>
														<option value="2">Clientes</option>
														<option value="3">Pedidos</option>
														<option value="4">Otros</option>
													</select>
												</div>
											</div>
											<div class="modal-footer d-flex justify-content-end align-items-center px-4 pb-4 border-0 pt-3">
												<button class="btn btn-sm btn-phoenix-primary px-4 fs--2 my-0" type="submit"> <span class="fas fa-arrows-rotate me-2 fs--2"></span>Resetear</button>
												<button class="btn btn-sm btn-primary px-9 fs--2 my-0" type="submit">Filtrar</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row g-3 list" id="reportsList">
				<?php
					foreach ($reports as $report => $value) {
						$opcion = array_search($value["link_report"], array_column($_SESSION['permissionssin'], 'OpcLink'));
						if ($opcion !== NULL && $opcion !== FALSE) {
							$permi = $_SESSION['permissionssin'][$opcion]["UsuPermi"];
							if ($permi == 0) {
							  continue;
							}
						}
						$category = $value["category_report"];
						switch ($category) {
							case '1':
								$categoryName = "Listados Generales";
								break;
							case '2':
								$categoryName = "Clientes";
								break;
							case '3':
								$categoryName = "Ventas";
								break;
							case '4':
								$categoryName = "Otros";
								break;
							default:
								$categoryName = "";
								break;
						}
						$priority = $value["priority_report"];
						switch ($priority) {
							case '1':
								$priorityName = "Alto";
								$priorityClass = "text-danger";
								break;
							case '2':
								$priorityName = "Medio";
								$priorityClass = "text-warning";
								break;
							case '3':
								$priorityName = "Bajo";
								$priorityClass = "text-success";
								break;
							default:
								$priorityName = "";
								$priorityClass = "";
								break;
						}
						$genForm = $value["generate_form_report"];
						switch ($genForm) {
							case '1':
								$genFormName = "Descargar PDF";
								break;
							case '2':
								$genFormName = "PDF / Hoja de Cálculo";
								break;
							default:
								$genFormName = "";
								break;
						}
						if ($value["last_generate_report"] == null) {
							$value["last_generate_report"] = "No Generado";
						}
						echo '
							<div class="col-12 col-md-6 col-xl-3"><a class="text-decoration-none fw-bold fs-0 lh-sm title line-clamp-1 me-sm-0 phoenix-hover-info" href="'.$value["link_report"].'">
					        <div class="card h-100 phoenix-card-hover">
									<div class="card-body p-3">
										<div class="border-bottom">
											<div class="d-flex align-items-start mb-1">
												<div class="col-9 d-sm-flex align-items-center ps-0">'.$value["name_report"].'</div>
												<div class="d-flex align-items-center"><span class="fa-solid fa-circle me-1 '.$priorityClass.'" data-fa-transform="shrink-6 up-1"></span><span class="fw-bold priority fs--1 text-900 lh-2 '.$priorityClass.'">'.$priorityName.'</span></div>
											</div>
											<p class="fs--1 fw-semi-bold text-900 ms-1 text mb-4 ps-0">'.$value["description_report"].'</p>
										</div>
										<div class="row g-1 g-sm-3 mt-2 lh-1">
											<div class="col-12 col-sm-auto flex-1 text-truncate fs--1"><span class="fa-regular fa-folder me-2 reportsby"></span>'.$genFormName.'</div>
											<div class="col-12 col-sm-auto">
												<div class="d-flex align-items-center"><span class="me-2" data-feather="clock" style="stroke-width:2;"></span>
													<p class="mb-0 fs--1 fw-semi-bold text-700 date">'.substr($value["last_generate_report"],0,16).'</p>
												</div>
											</div>
										</div>
									</div>
								</div></a>
							</div>
						';
							// <div class="col-12 col-sm-auto">
							// 	<div class="d-flex align-items-center"><span class="me-2" data-feather="grid" style="stroke-width:2;"></span>
							// 		<p class="mb-0 fs--1 fw-semi-bold text-700 reports">'.$categoryName.'</p>
							// 	</div>
							// </div>
					}
				?>
				<!--
				<div class="col-12 col-xl-6">
					<div class="card h-100">
						<div class="card-body">
							<div class="border-bottom">
								<div class="d-flex align-items-start mb-1">
									<div class="form-check mb-0">
										<input class="form-check-input" type="checkbox" />
									</div>
									<div class="d-sm-flex align-items-center ps-2"><a class="fw-bold fs-1 lh-sm title line-clamp-1 me-sm-4" href="../../apps/crm/reports-details.html">Descargar pedido de cliente</a>
										<div class="d-flex align-items-center"><span class="fa-solid fa-circle me-1 text-danger" data-fa-transform="shrink-6 up-1"></span><span class="fw-bold fs--1 text-900 lh-2">Urgent</span></div>
									</div>
								</div>
								<p class="fs--1 fw-semi-bold text-900 ms-4 text mb-4 ps-2">Purchasing-Related Vendors</p>
							</div>
							<div class="row g-1 g-sm-3 mt-2 lh-1">
								<div class="col-12 col-sm-auto flex-1 text-truncate"><a class="fw-semi-bold fs--1" href="#!"><span class="fa-regular fa-folder me-2 reportsby"></span>Reports by email</a></div>
								<div class="col-12 col-sm-auto">
									<div class="d-flex align-items-center"><span class="me-2" data-feather="grid" style="stroke-width:2;"></span>
										<p class="mb-0 fs--1 fw-semi-bold text-700 reports">Sales Reports</p>
									</div>
								</div>
								<div class="col-12 col-sm-auto">
									<div class="d-flex align-items-center"><span class="me-2" data-feather="clock" style="stroke-width:2;"></span>
										<p class="mb-0 fs--1 fw-semi-bold text-700 date">Dec 30, 2022</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				-->
			</div>
			<div class="row align-items-center justify-content-between py-2 pe-0 fs--1 mt-2">
				<div class="col-auto d-flex">
					<p class="mb-0 d-none d-sm-block me-3 fw-semi-bold text-900" data-list-info="data-list-info"></p><a class="fw-semi-bold" href="#!" data-list-view="*">Ver todos<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a><a class="fw-semi-bold d-none" href="#!" data-list-view="less">Ver menos<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
				</div>
				<div class="col-auto d-flex">
					<button class="page-link" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
					<ul class="mb-0 pagination"></ul>
					<button class="page-link pe-0" data-list-pagination="next"><span class="fas fa-chevron-right"></span></button>
				</div>
			</div>
		</div>
	</div>
	<?php
		include APP_PATH."/views/layouts/footer.php";
	?>
</div>