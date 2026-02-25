<div class="content p-2 pt-10">
	<div class="row mb-2">
   	<div class="col-lg-8">
      	<h4 class="mb-0">Codigos CHIP</h4>
   	</div>
   	<div class="col-lg-4">
   		<nav class="mb-0" aria-label="breadcrumb">
      		<ol class="breadcrumb mb-0 float-sm-end">
         		<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
         		<li class="breadcrumb-item active">Codigo CHIP</li>
      		</ol>
      	</nav>
   	</div>
	</div>
	<form role="form" class="ChipFormList" method="post" action="chipdetails">
   	<div class="mb-3">
   		<div id="Chip" data-list='{"valueNames":["id", "name"],"page":15,"pagination":true}'>
      		<div class="mb-0">
         		<div class="d-flex flex-wrap gap-3">
         			<div class="search-box">
							<form class="position-relative" data-bs-toggle="search" data-bs-display="static">
								<input class="form-control search-input search" id="searchChip" name="searchChip" type="search" placeholder="Buscar Codigo CHIP" aria-label="Search" />
								<span class="fas fa-search search-box-icon"></span>
							</form>
						</div>
						<?php
							$permiAdd = "";
							$permiExp = "";
							$opcion = array_search("chipadd", array_column($_SESSION['permissionssin'], 'OpcLink'));
							if ($opcion !== NULL && $opcion !== FALSE) {
								$permi = $_SESSION['permissionssin'][$opcion]["UsuPermi"];
								if ($permi != 0) {
									// $permiAdd = '<a class="btn btn-primary" href="?action=create"><span class="fas fa-plus me-2"></span>Agregar Codigo Chip</a>';
									// $permiAdd = '<a class="btn btn-primary" id="btnaddChip" href="chipadd"><span class="fas fa-plus me-2"></span>Adicionar Codigo Chip</a>';
									$permiAdd = '<a class="btn btn-primary" id="btnaddChip"><span class="fas fa-plus me-2"></span>Agregar Codigo Chip</a>';
								}
							}
							$opcion = array_search("chipexport", array_column($_SESSION['permissionssin'], 'OpcLink'));
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
                     $opcion = array_search("chipedit", array_column($_SESSION['permissionssin'], 'OpcLink'));
                     if ($opcion !== NULL && $opcion !== FALSE) {
                        $permi = $_SESSION['permissionssin'][$opcion]["UsuPermi"];
                        if ($permi != 0) {
                           $permiModsw = "1";
                        }
                     }
                     $opcion = array_search("chipdelete", array_column($_SESSION['permissionssin'], 'OpcLink'));
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
							<table class="table table-striped mb-0 chipTable" style="font-size: 12px;" id="chipTable">
								<thead style="background-color: var(--phoenix-body-bg); backdrop-filter: blur(8px); opacity: 0.98;">
									<tr>
										<th class="sort white-space-nowrap align-middle ps-2" scope="col" data-sort="id" style="width:8%;">id</th>
										<th class="sort white-space-nowrap align-middle ps-2" scope="col" data-sort="name" style="width:30%;">Nombre</th>
										<th class="sort align-middle text-start p-0 ps-3" scope="col" style="width:15%;">Acciones</th>
									</tr>
								</thead>
								<tbody class="list" id="chip-table-body">
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
							<p class="mb-0 d-none d-sm-block me-3 fw-semi-bold text-900 ps-2" data-list-info="data-list-info"></p>
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
		include "chipedit.php";
		include "chipadd.php";
		include APP_PATH.'/views/layouts/footer.php';
	?>
</div>
<script src="app/js/presupuesto/chip.js"></script>