<div class="content p-2 pt-10">
	<div class="row mb-4">
   	<div class="col-lg-8">
      	<h4 class="mb-0">Cerrar Tarea</h4>
   	</div>
   	<div class="col-lg-4">
   		<nav class="mb-0" aria-label="breadcrumb">
      		<ol class="breadcrumb mb-2 float-sm-end">
         		<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
         		<li class="breadcrumb-item active">Cerrar Tarea</li>
      		</ol>
      	</nav>
   	</div>
	</div>
	<form class="needs-validation form-search seguimientoTareaForm" role="form" id="seguimientoTareaForm" name="seguimientoTareaForm" enctype="multipart/form-data" method="post" novalidate>
      <input type="hidden" id="modulo" name="modulo" value="admon">
      <input type="hidden" id="option" name="option" value="notificaciones">
      <input type="hidden" id="action" name="action" value="follownotify">
      <input type="hidden" id="fechaActual" name="fechaActual" value="<?php echo date('Y-m-d'); ?>">
      <input type="hidden" id="idTipo" name="idTipo" value=3>
      <input type="hidden" id="cumplimiento" name="cumplimiento" value=0>
      <div class="row g-3">
         <div class="col-lg-5 mt-0">
            <div class="card mb-2">
               <div class="card-body">
                  <div class="row g-3">
                     <div class="col-sm-6 col-lg-4 mb-3 text-end">
                        <label for="idtarea" class="text-label fs-0 ps-0">Nro Tarea *</label>
                        <input type="number" class="form-control py-2 text-end" id="idtarea" name="idtarea" autocomplete="off" data-mask autofocus required>
                     </div>
                     <div class="col-sm-6 col-lg-4 mb-3 text-end">
                        <p class="mt-5 d-none fw-bold" id="reprogramada">REPROGRAMADA</p>
                     </div>
                  </div>

                  <div class="row g-3">
                     <div class="col-12 mb-3">
                        <div class="form-group">
                           <label for="empleado" class="text-label fs-0 ps-0">Empleado *</label>
                           <input type="text" class="form-control p-2" id="empleado" name="empleado" maxlength="100" disabled>
                        </div>
                     </div>
                  </div>

                  <div class="row g-3">
                     <div class="col-sm-6 col-lg-4 mb-3">
                        <div class="form-group">
                           <label for="fechatearea" class="text-label fs-0 ps-0">Fecha Asignación *</label>
                           <input type="text" class="form-control py-2 dp_fecha_ini" id="fechatearea" name="fechatearea" autocomplete="off" data-mask disabled>
                        </div>
                     </div>
                     <div class="col-sm-6 col-lg-4 mb-3">
                        <div class="form-group">
                           <label for="fechaentrega" class="text-label fs-0 ps-0">Fecha Entrega *</label>
                           <input type="text" class="form-control py-2" id="fechaentrega" name="fechaentrega" autocomplete="off" data-mask disabled>
                        </div>
                     </div>
                     <div class="col-sm-6 col-lg-4 mb-3">
                        <label for="prioridad" class="text-label fs-0 ps-0">Prioridad *</label>
                        <input type="text" class="form-control p-2" id="prioridad" name="prioridad" maxlength="100" disabled>
                     </div>
                  </div>

                  <div class="row g-3">
                     <div class="col-12 mb-3">
                        <div class="form-group">
                           <label for="titulo" class="text-label fs-0 ps-0">Título *</label>
                           <input type="text" class="form-control p-2" id="titulo" name="titulo" maxlength="100" disabled>
                        </div>
                     </div>
                  </div>

                  <div class="row g-3">
                     <div class="col-12 mb-3">
                        <div class="form-group">
                           <label for="detalle" class="text-label fs-0 ps-0">Detalle *</label>
                           <textarea name="detalle" id="detalle" class="form-control" rows="3" disabled></textarea>
                        </div>
                     </div>
                  </div>

                  <div class="row g-3">
                     <div class="col-sm-6 col-lg-4 mb-3">
                        <div class="form-group">
                           <label for="fecha" class="text-label fs-0 ps-0">Fecha Cierre *</label>
                           <input type="text" class="form-control py-2 dp_fecha_fin" id="fecha" name="fecha" placeholder="<?php echo DATE_DISPLAY ?>" data-inputmask="'alias': '<?= DATE_DISPLAY ?>'" autocomplete="off" data-mask required>
                        </div>
                     </div>
                     <div class="col-sm-6 col-lg-4 mb-3 text-end">
                        <label for="cumplimiento" class="text-label fs-0 ps-0">% Cumplimiento *</label>
                        <input type="number" class="form-control p-2 text-end" id="cumplimiento" name="cumplimiento" autocomplete="off" min="0" max="100" value=0 step="1" oninput="this.value = Math.min(100, Math.max(0, parseInt(this.value) || 0))" required>
                     </div>
                  </div>

                  <div class="row g-3">
                     <div class="col-12 mb-3">
                        <div class="form-group">
                           <label for="comentario" class="text-label fs-0 ps-0">Comentario *</label>
                           <textarea name="comentario" id="comentario" class="form-control" rows="3" required></textarea>
                        </div>
                     </div>
                  </div>

                  <div class="row g-3">
                     <div class="col-12 mb-3">
                        <div class="d-grid gap-2">
                           <button class="btn btn-phoenix-success" type="submit" id="btnSeguimTarea">GRABAR SEGUIMIENTO</button>
                        </div>
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
<script src="app/js/dival/notificaciones.js?v=1.0.0"></script>