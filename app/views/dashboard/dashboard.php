<div class="content pt-10 px-3">
   <div class="row g-3 mb-2">
      <div class="col-12">
         <div class="d-flex flex-wrap justify-content-between align-items-center">
            <h3 class="mb-0">Dashboard</h3>
         </div>
      </div>
   </div>

   <div class="row g-2 mb-2">
      <div class="col-xl-3 col-md-6">
         <div class="card h-100">
            <div class="card-body">
               <div class="d-flex justify-content-between">
                  <div class="d-flex flex-column">
                     <div class="d-flex align-items-center">
                        <h4 class="mb-1" id="totalPets"></h4>
                     </div>
                     <span class="text-900 fw-semi-bold"><?= 'Clientes Registrados' ?></span>
                  </div>
                  <div class="avatar">
                     <div class="avatar-name rounded-circle bg-soft-primary">
                        <span class="fs-4 text-primary"><i class="fs-0 fa-solid fa-paw"></i></span>
                        <!-- <span class="fs-4 text-primary" data-feather="thumb-up"></span> -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="col-xl-3 col-md-6">
         <div class="card h-100">
            <div class="card-body">
               <div class="d-flex justify-content-between">
                  <div class="d-flex flex-column">
                     <div class="d-flex align-items-center">
                        <h4 class="mb-1" id="totalAppointments"></h4>
                     </div>
                     <span class="text-900 fw-semi-bold">Cheque Hoy</span>
                  </div>
                  <div class="avatar">
                     <div class="avatar-name rounded-circle bg-soft-info">
                        <span class="fs-4 text-primary"><i class="fs-0 fa-solid fa-calendar"></i></span>
                        <!-- <span class="fs-4 text-info" data-feather="calendar"></span> -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="col-xl-3 col-md-6">
         <div class="card h-100">
            <div class="card-body">
               <div class="d-flex justify-content-between">
                  <div class="d-flex flex-column">
                     <div class="d-flex align-items-center">
                        <h4 class="mb-1" id="totalAppointmentsMonth"></h4>
                     </div>
                     <span class="text-900 fw-semi-bold">Por Cobrar</span>
                  </div>
                  <div class="avatar">
                     <div class="avatar-name rounded-circle bg-soft-success">
                        <span class="fs-4 text-primary"><i class="fs-0 fa-solid fa-calendar"></i></span>
                        <!-- <span class="fs-4 text-primary"><i class="fs-0 fa-solid fa-dollar-sign"></i></span> -->
                        <!-- <span class="fs-4 text-success" data-feather="dollar-sign"></span> -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="col-xl-3 col-md-6">
         <div class="card h-100">
            <div class="card-body">
               <div class="d-flex justify-content-between">
                  <div class="d-flex flex-column">
                     <div class="d-flex align-items-center">
                        <h4 class="mb-1" id="totalVaccinations"></h4>
                     </div>
                     <span class="text-900 fw-semi-bold">Consignnaciones</span>
                  </div>
                  <div class="avatar">
                     <div class="avatar-name rounded-circle bg-soft-warning">
                        <span class="fs-4 text-primary"><i class="fs-0 fa-solid fa-syringe"></i></span>
                        <!-- <span class="fs-4 text-warning" data-feather="activity"></span> -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

   </div>

   <div class="row g-2">
      <div class="col-12 col-xl-8">
         <div class="card">
            <div class="card-header bg-100 py-3">
               <h5 class="mb-0">
                  <i class="fas fa-calendar-plus me-2"></i>
                  <?= 'servicios.appointments_today' ?>
               </h5>
            </div>
            <div class="card-body p-2">
               <?php
                  $permiModsw = "0";
                  $opcion = array_search("appointmentedit", array_column($_SESSION['permissionsvet'], 'OpcLink'));
                  if ($opcion !== NULL && $opcion !== FALSE) {
                     $permi = $_SESSION['permissionsvet'][$opcion]["UsuPermi"];
                     if ($permi != 0) {
                        $permiModsw = "1";
                     }
                  }
               ?>
               <input type="hidden" id="permiModsw" value="<?php echo $permiModsw; ?>">
               <div class="table-responsive">
                  <table class="table table-sm table-hover fs--1 mb-0 border-top border-200" style="position: relative; border-collapse: collapse; width: 99%;">
                     <thead id="appointmentsTable">
                        <tr>
                           <th class="align-middle ps-2" scope="col" style="width: 10%%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;"><?= 'general.time' ?></th>
                           <th class="align-middle ps-2" scope="col" style="width: 15%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;"><?= 'pacientes.pet' ?></th>
                           <th class="align-middle ps-2" scope="col" style="width: 20%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;"><?= 'pacientes.owner' ?></th>
                           <th class="align-middle ps-2" scope="col" style="width: 30%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;"><?= 'general.reason' ?></th>
                           <th class="align-middle ps-2" scope="col" style="width: 20%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;"><?= 'catalogos.staff' ?></th>
                           <th class="align-middle ps-2" scope="col" style="width: 5%; white-space: nowrap; position: sticky; top:-1px; background: #e3e6ed;"><?= 'general.actions' ?></th>
                        </tr>
                     </thead>
                     <tbody>
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
					<div class="card-header bg-100 py-3">
                  <h5 class="mb-0">
                     <i class="fa-solid fa-binoculars"></i>
                     <?= 'general.shortcuts' ?>
                  </h5>
               </div>
               <div class="card-body p-3">
                  <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                     <?php
                        $opcion = array_search("appointmentadd", array_column($_SESSION['permissionsvet'], 'OpcLink'));
                        if ($opcion !== NULL && $opcion !== FALSE) {
                           $permi = $_SESSION['permissionsvet'][$opcion]["UsuPermi"];
                           if ($permi != 0) {
                              echo '<a class="btn btn-phoenix-primary btn-sm p-2" href="appointmentadd"><span class="fas fa-calendar-plus me-2"></span>' . 'servicios.schedule_appointment' . '</a>';
                           }
                        }

                        $opcion = array_search("petdetails", array_column($_SESSION['permissionsvet'], 'OpcLink'));
                        if ($opcion !== NULL && $opcion !== FALSE) {
                           $permi = $_SESSION['permissionsvet'][$opcion]["UsuPermi"];
                           if ($permi != 0) {
                              echo '<a class="btn btn-sm btn-phoenix-primary btn-sm p-2" href="petdetails"><span class="fas fa-paw me-2"></span>' . 'pacientes.consult_pet' . '</a>';
                              echo '<a class="btn btn-sm btn-phoenix-primary btn-sm p-2" href="petdetails"><span class="fas fa-plus me-2"></span>' . 'pacientes.consult_pet' . '</a>';
                           }
                        }


                     ?>
                  </div>
               </div>
            </div>
         </div>

         <div class="col-12">
            <div class="card">
					<div class="card-header bg-100 py-3">
                  <h5 class="mb-0">
                     <!-- <i class="fa-solid fa-alarm-clock"></i> -->
                     <i class="fa-solid fa-bell"></i>
                     <?= 'general.reminders' ?>
                  </h5>
               </div>
               <div class="card-body p-3">
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