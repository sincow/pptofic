<div class="content pt-10 px-3">
   <div class="row g-3 mb-2">
      <div class="col-12">
         <div class="d-flex flex-wrap justify-content-between align-items-center">
            <h4 class="mb-0">Dashboard</h4>
         </div>
      </div>
   </div>
   <div class="row g-2">
      <div class="col-12 col-xl-6">
         <?php
            echo '<div class="box box-success">
               <div class="box-header">
                  <h3>Bienvenid@ '.$_SESSION["user_name"].'</h3>
               </div>
            </div>';
         ?>
      </div>
      <div class="col-12 col-xl-6">
         <div class="col-12">
            <div class="card" style="min-height: 125px;">
               <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity: 70%;">
                  <i class="fa-solid fa-bell me-2"></i>Recordatorios
               </div>
               <div class="card-body p-3" id="remindersList"></div>
            </div>
         </div>
      </div>
   </div>
	<?php
		include APP_PATH.'/views/layouts/footer.php';
	?>
</div>
<script>
   document.addEventListener('DOMContentLoaded', function() {
      /****************************************************************************************/
		const formData = new FormData();
		formData.append("modulo", "admon");
		formData.append("option", "notificaciones");
		formData.append("action", "getMisNotificaciones");
		formData.append("status", "1");
		let innerHTML = "";
		const response = fetch('helpers/ajaxRouter.php', {
			method: 'POST',
			body: formData
		}).then(resp => resp.json())
		.then( data => {
			if (data.length > 0) {
				data.forEach(notificacion => {
					innerHTML += `
						<div class="px-2 px-sm-3 pb-2 border-300 notification-card position-relative read border-bottom">
							<div class="d-flex align-items-center justify-content-between position-relative">
								<div class="d-flex">
					`;
               let entrega = "";
               switch (notificacion.tipo) {
                  case 1:
                     innerHTML += `
                        <div class="avatar avatar-m me-1">
                           <span class='me-0 fs-2'>📋</span>
                        </div>
                     `;
                     break; 
                  case 2:
                     innerHTML += `
                        <div class="avatar avatar-m me-1">
                           <span class='me-0 fs-2'>💬</span>
                        </div>
                     `;
                     break;
                  case 3:
                     innerHTML += `
                        <div class="avatar avatar-m me-1">
                           <span class='me-0 fs-2'>📅</span>
                        </div>
                     `;
                     break;
                  case 4:
                     innerHTML += `
                        <div class="avatar avatar-m me-1">
                           <span class='me-0 fs-2'>📈</span>
                        </div>
                     `;
                     break;
                  default:
                     break;
               }
					innerHTML += `
						<div class="flex-1 me-sm-0">
					`;

               if (notificacion.tipo == "1") {
						let prioridad = "";
						let color = "";
						entrega = "para entregar: " + notificacion.fecha_entrega;
						switch (notificacion.prioridad) {
							case 1:
								prioridad = "Baja";
								color = "success";
								break;
							case 2:
								prioridad = "Media";
								color = "warning";
								break;
							case 3:
								prioridad = "Alta";
								color = "danger";
								break;
							default:
								break;
						}
						// innerHTML += `
						// 	<p class="fw-normal fs--2 ps-2 mb-0">Creó la siguiente tarea con prioridad  <span class="fs--1 fw-bold badge bg-${color}">${prioridad}</span>
						// 	</p>
						// `;
					}
               innerHTML += `
                              <p class="fw-bold fs--1 ps-2 mb-0">${notificacion.titulo}</p>
                              <p class="fw-normal fs--1 ps-2 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">${notificacion.fecha}</span><span class="ps-2 fw-bold">${entrega}</span></p>
                           </div>
                        </div>
                     </div>
                  </div>
               `;
            });
            document.getElementById('remindersList').innerHTML = innerHTML;
         }
      });
   });
</script>