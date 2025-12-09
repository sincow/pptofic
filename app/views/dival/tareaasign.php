<div class="content p-2 pt-10">
	<div class="row mb-4">
   	<div class="col-lg-8">
      	<h4 class="mb-0">Asignar Tarea</h4>
   	</div>
   	<div class="col-lg-4">
   		<nav class="mb-0" aria-label="breadcrumb">
      		<ol class="breadcrumb mb-2 float-sm-end">
         		<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
         		<li class="breadcrumb-item active">Asignar Tarea</li>
      		</ol>
      	</nav>
   	</div>
	</div>
	<form class="needs-validation form-search crearTareaForm" role="form" id="crearTareaForm" name="crearTareaForm" enctype="multipart/form-data" method="post" novalidate>
      <input type="hidden" id="modulo" name="modulo" value="admon">
      <input type="hidden" id="option" name="option" value="notificaciones">
      <input type="hidden" id="action" name="action" value="addnotify">
      <input type="hidden" id="fechaActual" name="fechaActual" value="<?php echo date('Y-m-d'); ?>">
      <input type="hidden" id="idtarea" name="idtarea" value=0>
      <input type="hidden" id="idTipo" name="idTipo" value = 1>
      <input type="hidden" id="cumplimiento" name="cumplimiento" value=0>
      <div class="row g-3">
         <div class="col-lg-5 mt-0">
            <div class="card mb-2">
               <div class="card-body">

                  <div class="row g-3">
                     <div class="col-12 mb-3">
                        <div class="form-group">
                           <label for="iduser" class="text-label fs-0 ps-0">Empleado *</label>
                           <select class="form-select select2" id="iduser" name="iduser" required>
                              <option value="">Seleccionar</option>
                           </select>
                        </div>
                     </div>
                  </div>

                  <div class="row g-3">
                     <div class="col-sm-6 col-lg-4 mb-3">
                        <div class="form-group">
                           <label for="fecha" class="text-label fs-0 ps-0">Fecha Asignación *</label>
                           <input type="text" class="form-control py-2 dp_fecha_ini" id="fecha" name="fecha" placeholder="<?php echo DATE_DISPLAY ?>" data-inputmask="'alias': '<?= DATE_DISPLAY ?>'" autocomplete="off" data-mask required>
                        </div>
                     </div>

                     <div class="col-sm-6 col-lg-4 mb-3">
                        <div class="form-group">
                           <label for="fechaentrega" class="text-label fs-0 ps-0">Fecha Entrega *</label>
                           <input type="text" class="form-control py-2 dp_fecha_fin" id="fechaentrega" name="fechaentrega" placeholder="<?php echo DATE_DISPLAY ?>" data-inputmask="'alias': '<?= DATE_DISPLAY ?>'" autocomplete="off" data-mask required>
                        </div>
                     </div>
                     <div class="col-sm-6 col-lg-4 mb-3">
                           <label for="prioridad" class="text-label fs-0 ps-0">Prioridad *</label>
                           <select class="form-select select2" id="prioridad" name="prioridad" required>
                              <option value="">Seleccionar</option>
                              <option value="1">Baja</option>
                              <option value="2">Media</option>
                              <option value="3">Alta</option>
                           </select>
                     </div>

                  </div>

                  <div class="row g-3">
                     <div class="col-12 mb-3">
                        <div class="form-group">
                           <label for="titulo" class="text-label fs-0 ps-0">Título *</label>
                           <input type="text" class="form-control p-2" id="titulo" name="titulo" maxlength="100" required>
                        </div>
                     </div>
                  </div>

                  <div class="row g-3">
                     <div class="col-12 mb-3">
                        <div class="form-group">
                           <label for="detalle" class="text-label fs-0 ps-0">Detalle *</label>
                           <textarea name="detalle" id="detalle" class="form-control" rows="3" required></textarea>
                        </div>
                     </div>
                  </div>

                  <div class="row g-3">
                     <div class="col-12 mb-3">
                        <div class="d-grid gap-2">
                           <button class="btn btn-phoenix-success" type="submit" id="btnAsigmTarea">GRABAR ASIGNACION</button>
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