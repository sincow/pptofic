<style>
	/* Estilos para Select2 válido */
	.select2-container.is-valid .select2-selection {
		border-color: #198754 !important;
	}

	.select2-container.is-valid .select2-selection:focus {
		border-color: #198754 !important;
		box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25) !important;
	}

	/* Estilos para Select2 inválido */
	/* .select2-container.is-invalid .select2-selection { */
	.select2-container--default .select2-selection--single.is-invalid {
		border-color: #dc3545 !important;
	}

	.select2-container.is-invalid .select2-selection:focus {
		border-color: #dc3545 !important;
		box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
	}
</style>
<div class="content p-2 pt-10">
	<div class="row mb-2">
   	<div class="col-lg-8">
      	<h3 class="mb-0">Cuentas Bancarias</h3>
   	</div>
   	<div class="col-lg-4">
   		<nav class="mb-0" aria-label="breadcrumb">
      		<ol class="breadcrumb mb-0 float-sm-end">
         		<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
         		<li class="breadcrumb-item active">Cuentas</li>
      		</ol>
      	</nav>
   	</div>
	</div>
	<form role="form" class="cuentaFormList" method="post" action="cuentaetails">
   	<div class="mb-3">
   		<div id="Cuentas" data-list='{"valueNames":["id", "name", "cuenta", "ctacontable", "status"],"page":15,"pagination":true}'>
      		<div class="mb-0">
         		<div class="d-flex flex-wrap gap-3">
         			<div class="search-box">
							<form class="position-relative" data-bs-toggle="search" data-bs-display="static">
								<input class="form-control search-input search" id="searchCuenta" name="searchCuenta" type="search" placeholder="Buscar Cuentas" aria-label="Search" />
								<span class="fas fa-search search-box-icon"></span>
							</form>
						</div>
						<?php
							$permiAdd = "";
							$permiExp = "";
							$opcion = array_search("cuentaadd", array_column($_SESSION['permissionssin'], 'OpcLink'));
							if ($opcion !== NULL && $opcion !== FALSE) {
								$permi = $_SESSION['permissionssin'][$opcion]["UsuPermi"];
								if ($permi != 0) {
									$permiAdd = '<a class="btn btn-primary" href="?action=create"><span class="fas fa-plus me-2"></span>Agregar Cuenta</a>';
									$permiAdd = '<a class="btn btn-primary" id="btnaddCuenta" href="cuentaadd"><span class="fas fa-plus me-2"></span>Adicionar Especie</a>';
									$permiAdd = '<a class="btn btn-primary" id="btnaddCuenta"><span class="fas fa-plus me-2"></span>Agregar Cuenta</a>';
								}
							}
							$opcion = array_search("cuentasexport", array_column($_SESSION['permissionssin'], 'OpcLink'));
							if ($opcion !== NULL && $opcion !== FALSE) {
								$permi = $_SESSION['permissionssin'][$opcion]["UsuPermi"];
								if ($permi != 0) {
									$permiExp = '<button class="btn btn-link text-900 me-4 px-0"><span class="fa-solid fa-file-export fs--1 me-2"></span>Exportar</button>';
								}
							}
                     $permiModsw = "0";
                     $permiDelsw = "0";
                     $permiMod = "";
                     $permiDel = "";
                     $opcion = array_search("cuentaedit", array_column($_SESSION['permissionssin'], 'OpcLink'));
                     if ($opcion !== NULL && $opcion !== FALSE) {
                        $permi = $_SESSION['permissionssin'][$opcion]["UsuPermi"];
                        if ($permi != 0) {
                           $permiModsw = "1";
                        }
                     }
                     $opcion = array_search("cuentadelete", array_column($_SESSION['permissionssin'], 'OpcLink'));
                     if ($opcion !== NULL && $opcion !== FALSE) {
                        $permi = $_SESSION['permissionssin'][$opcion]["UsuPermi"];
                        if ($permi != 0) {
                           $permiDelsw = "1";
                        }
                     }
                  ?>
                    <input type="hidden" id="permiModsw" value="<?php echo $permiModsw; ?>">
                    <input type="hidden" id="permiDelsw" value="<?php echo $permiDelsw; ?>">
						<div class="ms-lg-auto">
							<?php echo $permiExp.$permiAdd; ?>
						</div>
					</div>
				</div>
				<div class="mx-n4 px-4 mx-lg-0 px-lg-2 bg-white border-top border-bottom border-200 position-relative top-1">
					<div class="table-responsive scrollbar mx-n1 px-1">
						<form role="form" class="mb-1 especieFormEdit" method="post" action="especiemodify">
							<table class="table table-striped mb-0 cuentasTable" style="font-size: 12px;" id="cuentasTable">
								<thead style="background-color: var(--phoenix-body-bg); backdrop-filter: blur(8px); opacity: 0.98;">
									<tr>
										<th class="sort white-space-nowrap align-middle ps-2" scope="col" data-sort="id" style="width:8%;">id</th>
										<th class="sort white-space-nowrap align-middle ps-2" scope="col" data-sort="name" style="width:40%;">Nombre</th>
										<th class="sort white-space-nowrap align-middle ps-2" scope="col" data-sort="cuenta" style="width:15%;">Número Cuenta</th>
										<th class="sort white-space-nowrap align-middle ps-2" scope="col" data-sort="ctacontable" style="width:10%;">Cuenta Contable</th>
										<th class="sort white-space-nowrap align-middle ps-2" scope="col" data-sort="status" style="width:10%;">Estado</th>
										<th class="sort align-middle text-start p-0 ps-3" scope="col" style="width:15%;">Acciones</th>
									</tr>
								</thead>
								<tbody class="list" id="cuentas-table-body">
                           <tr>
                              <td class="text-center align-middle text-660 ps-2 py-1 id" colspan="4">
                                 <div class="text-center my-3">
                                    <div class="spinner-border text-primary" role="status">
                                       <span class="visually-hidden">Cargando...</span>
                                    </div>
                                 </div>
                              </td>
                           </tr>
                        </tbody>
							</table>
						</form>
					</div>
					<input type="hidden" name="idCli" value="" />
					<div class="row align-items-center justify-content-between py-2 pe-0 fs--1">
						<div class="col-auto d-flex">
							<p class="mb-0 d-none d-sm-block me-3 fw-semi-bold text-900" data-list-info="data-list-info"></p>
							<a class="fw-semi-bold" href="#!" data-list-view="*">Ver Todos<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
							<a class="fw-semi-bold d-none" href="#!" data-list-view="less">Ver Menos<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
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
		include "cuentaedit.php";
		include "cuentaadd.php";
		include APP_PATH.'/views/layouts/footer.php';
	?>
</div>
<script src="app/js/bancos/cuentas.js"></script>