<div class="content pt-10 px-3">
   <div class="row g-3 mb-2">
      <div class="col-12">
         <div class="d-flex flex-wrap justify-content-between align-items-center">
            <h4 class="mb-0">Dashboard</h4>
         </div>
      </div>
   </div>

   <div class="row g-2 mb-2">
      <div class="col-md-6 col-xl-3">
         <div class="card h-100">
            <div class="card-body">
               <div class="d-flex justify-content-between">
                  <div>
                     <span class="fs-0 text-primary me-2"><i class="fs-0 fa-solid fa-file-signature"></i></span>
                     <span class="text-900 fw-semi-bold">Documentos Mes</span>
                  </div>
                  <div class="d-flex flex-column" id="totalDocumentsMonth">
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="col-md-6 col-xl-3">
         <div class="card h-100">
            <div class="card-body">
               <div class="d-flex justify-content-between">
                  <div>
                     <span class="fs-0 text-primary me-2"><i class="fs-0 fa-solid fa-calendar"></i></span>
                     <span class="text-900 fw-semi-bold">Documentos Hoy</span>
                  </div>
                  <div class="d-flex flex-column" id="totalADocumentsToday">
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="col-md-6 col-xl-3">
         <div class="card h-100">
            <div class="card-body">
               <div class="d-flex justify-content-between">
                  <div>
                     <span class="fs-0 text-primary me-2"><i class="fs-0 fa-solid fa-dollar"></i></span>
                     <span class="text-900 fw-semi-bold">Por Cobrar</span>
                  </div>
                  <div class="d-flex flex-column" id="totalDocumentsOpen">
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="col-md-6 col-xl-3">
         <div class="card h-100">
            <div class="card-body">
               <div class="d-flex justify-content-between">
                  <div>
                     <span class="fs-0 text-primary me-2"><i class="fs-0 fa-solid fa-file-signature"></i></span>
                     <span class="text-900 fw-semi-bold">Vencidos hasta hoy</span>
                  </div>
                  <div class="d-flex flex-column" id="totalVencimiento">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <div class="row g-2">
      <div class="col-12 col-xl-8">
         <div class="card">
            <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity: 70%;">
               <i class="fa-solid fa-calendar-plus me-2"></i>Cheques a Consignar Hoy
            </div>
            <!-- <div class="card-header bg-100 py-3">
               <h5 class="mb-0">
                  <i class="fas fa-calendar-plus me-2"></i>
                  Cheques a Consignar Hoy
               </h5>
            </div> -->
            <div class="card-body p-0">
               <?php
                  $permiModsw = "0";
                  $opcion = array_search("appointmentedit", array_column($_SESSION['permissionssin'], 'OpcLink'));
                  if ($opcion !== NULL && $opcion !== FALSE) {
                     $permi = $_SESSION['permissionssin'][$opcion]["UsuPermi"];
                     if ($permi != 0) {
                        $permiModsw = "1";
                     }
                  }
               ?>
               <input type="hidden" id="permiModsw" value="<?php echo $permiModsw; ?>">
               <!-- <div class="table-responsive"> -->
               <div class="table-responsive scrollbar" style="min-height: 250px; max-height: 490px; overflow-y: auto;">
                  <table class="table table-sm table-hover fs--1 mb-0 border-top border-200" style="position: relative; border-collapse: collapse; width: 100%;">
                     <thead id="appointmentsTable">
                        <tr>
                           <th class="align-middle ps-2" scope="col" style="width: 7%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Consecutivo</th>
                           <th class="align-middle ps-0" scope="col" style="width: 40%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Cliente</th>
                           <th class="align-middle ps-0" scope="col" style="width: 8%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;"># Cheque</th>
                           <th class="align-middle ps-0" scope="col" style="width: 5%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Banco</th>
                           <th class="align-middle ps-0" scope="col" style="width: 8%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Fecha</th>
                           <th class="align-middle ps-0" scope="col" style="width: 8%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Vencimiento</th>
                           <th class="align-middle ps-0" scope="col" style="width: 8%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;">Vlr Documento</th>
                        </tr>
                     </thead>
                     <tbody id="ConsigTable-body">
                        <!-- <tr>
                           <td>10:30 AM</td>
                           <td>Luna</td>
                           <td>María González</td>
                           <td>Vacunación</td>
                           <td>
                              <button class="btn btn-sm btn-outline-primary">Ver</button>
                           </td>
                        </tr> -->
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>

      <div class="col-12 col-xl-4">
         <div class="col-12 mb-2">
            <div class="card">
               <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity: 70%;">
                  <i class="fa-solid fa-binoculars me-2"></i>Atajos
               </div>
               <!-- <div class="card-header bg-100 py-3">
                  <h5 class="mb-0">
                     <i class="fa-solid fa-binoculars"></i>
                     Atajos
                  </h5>
               </div> -->
               <div class="card-body p-3">
                  <div class="d-flex flex-wrap justify-content-between align-items-center mb-0">
                     <?php
                        $opcion = array_search("crearcheque", array_column($_SESSION['permissionssin'], 'OpcLink'));
                        if ($opcion !== NULL && $opcion !== FALSE) {
                           $permi = $_SESSION['permissionssin'][$opcion]["UsuPermi"];
                           if ($permi != 0) {
                              echo '<a class="btn btn-sm btn-phoenix-primary p-2 mb-2" href="crearcheque"><span class="fas fa-file-signature me-2"></span>Crear Cheque</a>';
                           }
                        }

                        $opcion = array_search("crearletra", array_column($_SESSION['permissionssin'], 'OpcLink'));
                        if ($opcion !== NULL && $opcion !== FALSE) {
                           $permi = $_SESSION['permissionssin'][$opcion]["UsuPermi"];
                           if ($permi != 0) {
                              echo '<a class="btn btn-sm btn-phoenix-primary p-2 mb-2" href="crearletra"><span class="fas fa-plus me-2"></span>Crear Letra</a>';
                           }
                        }
                        $opcion = array_search("crearpagare", array_column($_SESSION['permissionssin'], 'OpcLink'));
                        if ($opcion !== NULL && $opcion !== FALSE) {
                           $permi = $_SESSION['permissionssin'][$opcion]["UsuPermi"];
                           if ($permi != 0) {
                              echo '<a class="btn btn-sm btn-phoenix-primary p-2 mb-2" href="crearpagare"><span class="fas fa-plus me-2"></span>Crear Pagaré</a>';
                           }
                        }
                        $opcion = array_search("consigcheque", array_column($_SESSION['permissionssin'], 'OpcLink'));
                        if ($opcion !== NULL && $opcion !== FALSE) {
                           $permi = $_SESSION['permissionssin'][$opcion]["UsuPermi"];
                           if ($permi != 0) {
                              echo '<a class="btn btn-sm btn-phoenix-primary p-2 mb-2" href="consigcheque"><span class="fas fa-plus me-2"></span>Consignar Cheques</a>';
                           }
                        }
                     ?>
                  </div>
               </div>
            </div>
         </div>

         <div class="col-12">
            <div class="card">
               <div class="card-header fw-bold py-1 fs-0 text-start text-white bg-primary" style="opacity: 70%;">
                  <i class="fa-solid fa-bell me-2"></i>Recordatorios
               </div>

               <!-- <div class="card-header bg-100 py-3">
                  <h5 class="mb-0">
                     <i class="fa-solid fa-alarm-clock"></i>
                     <i class="fa-solid fa-bell"></i>
                     Recordatorios
                  </h5>
               </div> -->
               <div class="card-body p-3" id="remindersList">
                  <div class="d-flex align-items-start mb-2">
                     <span class="fas fa-bell text-warning me-2 mt-1"></span>
                     <div class="flex-1">
                        <h6 class="fs--1 text-1000 mb-0">Control anual para Max</h6>
                        <p class="fs--2 mb-0 text-600">15 de Octubre, 2025</p>
                     </div>
                  </div>
                  <div class="d-flex align-items-start mb-2">
                     <span class="fas fa-syringe text-info me-2 mt-1"></span>
                     <div class="flex-1">
                        <h6 class="fs--1 text-1000 mb-0">Vacuna antirrábica para Luna</h6>
                        <p class="fs--2 mb-0 text-600">20 de Octubre, 2025</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- Modal para detalles de cita -->
   <div class="modal fade" id="appointmentModal" reason="<?= 'servicios.appointment_reason' ?>" tabindex="-1">
      <div class="modal-dialog modal-lg">
         <form role="form" class="mb-1 appFormEdit" method="post" action="appointmentedit">
            <input type="hidden" class="idAppUpdt" name="idAppUpdt" value="">
            <input type="hidden" class="idOwnerUpdt" name="idOwnerUpdt" value="">
            <input type="hidden" class="notes" name="notes" value="<?= 'servicios.appointment_notes' ?>">
            <div class="modal-content">
               <div class="modal-header appointmentHeader" infoPet="<?= 'pacientes.pet_information' ?>">
                  <h5 class="modal-title titleDetail"><?= 'servicios.appointment_details' ?></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
               </div>
               <div class="modal-body" id="appointmentDetails">
                  <!-- Los detalles se cargarán aquí -->
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-phoenix-secondary" data-bs-dismiss="modal">
                     <?= 'general.close' ?>
                  </button>
                  <button type="button" class="btn btn-phoenix-primary" id="editAppointmentBtn">
                     <i class="fas fa-edit me-2"></i><?= 'general.edit' ?>
                  </button>
               </div>
            </div>
         </form>
      </div>
   </div>
	<?php
		include APP_PATH.'/views/layouts/footer.php';
	?>
</div>
<script>
   document.addEventListener('DOMContentLoaded', function() {
      const formDataDash = new FormData();
      formDataDash.append('modulo', 'dival');
      formDataDash.append('option', 'cheques');
      formDataDash.append('action', 'getDashborad');
      fetch('helpers/ajaxRouter.php', {
         method: 'POST',
         body: formDataDash
      })
      .then(response => response.json())
      .then(data => {
         if (data) {
            document.getElementById('totalDocumentsMonth').innerHTML = `
               <span class="mb-0">$${formatCurrency(parseFloat(data.valor_month,0))}</span>
               <span class="mb-0">${data.count_month}</span>
            `;
            document.getElementById('totalADocumentsToday').innerHTML = `
               <span class="mb-0">$${formatCurrency(parseFloat(data.valor_today,0))}</span>
               <span class="mb-0">${data.count_today}</span>
            `;
            document.getElementById('totalDocumentsOpen').innerHTML = `
               <span class="mb-0">$${formatCurrency(parseFloat(data.valor_pendiente,0))}</span>
               <span class="mb-0">${data.count_pendiente}</span>`;
            document.getElementById('totalVencimiento').innerHTML = `
               <span class="mb-0">$${formatCurrency(parseFloat(data.valor_vencim,0))}</span>
               <span class="mb-0">${data.count_vencim}</span>`;
         } else {
            document.getElementById('totalADocumentsToday').textContent = 0;
         }
      })
      .catch(error => {
         console.error('Error loading calendar config:', error);
      });

      const formDataDoc = new FormData();
      formDataDoc.append('modulo', 'dival');
      formDataDoc.append('option', 'consigna');
      formDataDoc.append('action', 'getPorConsig');
      fetch('helpers/ajaxRouter.php', {
         method: 'POST',
         body: formDataDoc
      })
      .then(response => response.json())
      .then(data => {
         // tbody.innerHTML = "";
         let innerHtml = ``;
         if (data.length > 0) {
            data.forEach(element => {
               innerHtml += `
               <tr>
                  <td class="text-end pe-3">${element.id_cheque}</td>
                  <td>${element.TerNombr}</td>
                  <td>${element.numero}</td>
                  <td>${element.codigo}</td>
                  <td>${element.fecha}</td>
                  <td class="text-start">${element.UltVenci}</td>
                  <td class="text-end pe-2">${formatCurrency(parseFloat(element.valor_cheque),0)}</td>
               </tr>
               `; 
            });
         } else {
            innerHtml += `
               <tr>
                  <td class="text-center ps-0" colspan="7">No hay Documentos para consignar el día de hoy</td>
               </tr>
            `; 
         }
         document.getElementById('ConsigTable-body').innerHTML = innerHtml;
      })
      .catch(error => {
         console.error('Error loading calendar config:', error);
      });

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
						<div class="px-2 px-sm-3 py-3 border-300 notification-card position-relative read border-bottom">
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
      })
      /*
      const formDataTipDoc = new FormData();
      const permiModsw = $("#permiModsw").val();
      formDataTipDoc.append("option", "dashboards");
      formDataTipDoc.append("action", "getDashboardGeneral");
      fetch('helpers/ajaxRouter.php', {
         method: 'POST',
         body: formDataTipDoc
      }).then(response => response.json())
      .then(dashboard => {
         document.getElementById('totalPets').textContent = dashboard.countPets;
         document.getElementById('totalAppointments').textContent = dashboard.countAppointments;
         document.getElementById('totalAppointmentsMonth').textContent = dashboard.appointmentsMonth;
         document.getElementById('totalVaccinations').textContent = dashboard.countVaccinations;
         const appointments = dashboard.appointments;
         appointments.forEach(appointment => {
            const appointmentJson = JSON.stringify(appointment).replace(/"/g, '&quot;');
            if (permiModsw == 1) {
               btn = `<button class="btn btn-sm btn-outline-primary" onclick="showAppointmentEdit(${appointmentJson})" >Ver</button>`
               // document.getElementById('editAppointmentBtn').setAttribute('onclick', `showAppointmentEdit(${appointmentJson})`);
            } else {
               btn = "";
            }
            const row = document.createElement('tr');
            row.innerHTML = `
               <td>${appointment.appointment_time}</td>
               <td>${appointment.pet_name}</td>
               <td>${appointment.owner_name}</td>
               <td>${appointment.reason}</td>
               <td>${appointment.staff_name}</td>
               <td>
                  ${btn}
               </td>
            `;
            document.getElementById('appointmentsTable').appendChild(row);
            
         });
      })
      .catch(error => {
         console.error('Error:', error);
      });
      */


      // Editar cita
      document.getElementById('editAppointmentBtn').addEventListener('click', function() {
         const appointmentId = this.dataset.appointmentId;
         const idOwner = this.dataset.idOwner;
         if (appointmentId) {
            // var idApp = $(this).attr("data-id");
            $(".idAppUpdt").val(appointmentId);
            $(".idOwnerUpdt").val(idOwner);
            $(".appFormEdit").submit();
         }
      });

   });


   /****************************************************************************************/
   function showAppointmentDetails(event) {
      console.log(event);
      // const props = event.extendedProps;
      // const startDate = event.start;
      // const dateFormatter = new Intl.DateTimeFormat(calendar.currentData.dateEnv.locale.codes[0] || 'es-ES', {
      //    weekday: 'long',
      //    year: 'numeric',
      //    month: 'long',
      //    day: 'numeric'
      // });
      
      // // const timeFormatter = new Intl.DateTimeFormat(calendar.getOption('locale'), {
      // const timeFormatter = new Intl.DateTimeFormat(calendar.currentData.dateEnv.locale.codes[0] || 'es-ES', {
      //    hour: '2-digit',
      //    minute: '2-digit'
      // });

      // <p><strong>Fecha:</strong> ${event.start.toLocaleDateString($_SESSION['current_lang'])}</p>
      // <p><strong>Hora:</strong> ${event.start.toLocaleTimeString('es-ES', {hour: '2-digit', minute:'2-digit'})}</p>

      // <p class="mb-2"><strong>${$(".date").val()}:</strong> ${dateFormatter.format(event.appintment_date)}</p>
      // <p class="mb-2"><strong>${$(".time").val()}:</strong> ${timeFormatter.format(event.appointment_time)}</p>

      const detailsHtml = `
         <div class="row">
            <div class="col-md-6">
               <h6 class="text-800">${$(".appointmentHeader").attr('infoPet')}</h6>
               <p><strong>${$(".pet").val()}:</strong> ${event.pet_name}</p>
               <p><strong>${$(".owner").val()}:</strong> ${event.owner_name}</p>
            </div>
            <div class="col-md-6">
               <h6 class="text-800">${$(".titleDetail").text()}</h6>
               <p class="mb-2"><strong>${$(".service").val()}:</strong> ${event.reason}</p>
               <p class="mb-2"><strong>${$(".staff").val()}:</strong> ${event.staff_name}</p>
               <p class="mb-2"><strong>${$(".status").val()}:</strong> <span class="badge badge-phoenix badge-phoenix-${getStatusBadgeClass(event.status_code)}">${event.status_name}</span></p>
               <p class="mb-2"><strong>${$(".date").val()}:</strong> ${event.appointment_date}</p>
               <p class="mb-2"><strong>${$(".time").val()}:</strong> ${event.appointment_time}</p>
            </div>
         </div>
         <div class="row mt-3">
            <div class="col-12">
               <h6 class="text-800">${$("#appointmentModal").attr('reason')}</h6>
               <p>${event.reason || '---'}</p>
            </div>
            <div class="col-12">
               <h6 class="text-800">${$(".notes").val()}</h6>
               <p>${event.notes || '---'}</p>
            </div>
         </div>
      `;
      document.getElementById('appointmentDetails').innerHTML = detailsHtml;
      document.getElementById('editAppointmentBtn').dataset.appointmentId = event.id_appointment;
      document.getElementById('editAppointmentBtn').dataset.idOwner = event.id_owner;
      const modal = new bootstrap.Modal(document.getElementById('appointmentModal'));
      modal.show();
   }

   /****************************************************************************************/
   function getStatusBadgeClass(status) {
      const statusClasses = {
         '01': 'primary',    //scheduled
         '02': 'info',       //confirmed
         'in_progress': 'warning',
         '03': 'success',    //completed
         '04': 'danger',     //cancelled
         '05': 'secondary'   //no_show
      };
      return statusClasses[status] || 'secondary';
   }
</script>

<!--// Definir variables para el layout -->
<!-- $title = "Dashboard - Sistema Veterinario";
$current_page = "dashboard"; -->

<!-- Incluir el layout -->
<!-- require_once '../app/views/layouts/base.php'; -->