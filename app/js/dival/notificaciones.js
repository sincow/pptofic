document.addEventListener("DOMContentLoaded", async function() {
   
   //****************************************************************************************
   const crearTareaForm = document.getElementById('crearTareaForm');
   if (crearTareaForm) {
      document.getElementById('fecha').value = document.getElementById('fechaActual').value;   
      var listWhere = [];
      listWhere.push({
         "id": "status",
         "value": '1'
      });
      const selectCuenta = document.getElementById('iduser');
      getSelects('admon', 'users', selectCuenta, 'id_user', textOpt = ['name'], listWhere);

      document.getElementById('crearTareaForm').addEventListener('submit', function(e) {
         e.preventDefault();
         e.stopPropagation();
         let isValid = "";
         let isRun = true;
         isValid = document.getElementById('prioridad').value != "" && document.getElementById('prioridad').value !== null;
         document.getElementById('prioridad').nextElementSibling.classList.remove('is-invalid');
         document.getElementById('prioridad').nextElementSibling.classList.add(isValid ? 'is-valid' : 'is-invalid');
         document.getElementById('prioridad').nextElementSibling.classList.contains('is-invalid') ? document.getElementById('prioridad').focus() : "";
         !isValid ? isRun = false : "";
         isValid = document.getElementById('iduser').value != "" && document.getElementById('iduser').value !== null;
         document.getElementById('iduser').nextElementSibling.classList.remove('is-invalid');
         document.getElementById('iduser').nextElementSibling.classList.add(isValid ? 'is-valid' : 'is-invalid');
         document.getElementById('iduser').nextElementSibling.classList.contains('is-invalid') ? document.getElementById('iduser').focus() : "";
         !isValid ? isRun = false : "";
         if (!isRun) {
            return;
         }
         const formData = new FormData(this);
         fetch('helpers/ajaxRouter.php', {
            method: 'POST',
            body: formData
         }).then(response => response.json())
         .then(response => {
            if (response.success === true) {
               Swal.fire({
                  icon: "success",
                  title: "Éxito",
                  text: response.message,
                  showConfirmButton: true
               }).then(() => {
                  if (response.reportUrl != null) {
                     window.open(response.reportUrl, '_blank');
                  }
                  location.reload();
               });
            } else {
               Swal.fire({
                  icon: "error",
                  title: "Error",
                  text: response.message
               });
            }
         })
         .catch(error => {
            console.error('Error:', error);
         });
   
   
         // if (execQueryUpd(formData, 'cargarClientes', '#modalClienteAdd')) {
         //    if (response.reportUrl != null) {
         //       window.open(response.reportUrl, '_blank');
         //    }
         //    location.reload();
         // }
   
   
      });
   }


   //****************************************************************************************
   const seguimientoTareaForm = document.getElementById('seguimientoTareaForm');
   if (seguimientoTareaForm) {
      document.getElementById('seguimientoTareaForm').addEventListener('submit', async function(e) {
         e.preventDefault();
         e.stopPropagation();
         let isValid = "";
         let isRun = true;
         isValid = document.getElementById('comentario').value != "" && document.getElementById('comentario').value !== null;
         document.getElementById('comentario').classList.remove('is-invalid');
         document.getElementById('comentario').classList.add(isValid ? 'is-valid' : 'is-invalid');
         document.getElementById('comentario').classList.contains('is-invalid') ? document.getElementById('comentario').focus() : "";
         !isValid ? isRun = false : "";
         isValid = document.getElementById('fecha').value != "" && document.getElementById('fecha').value !== null;
         document.getElementById('fecha').classList.remove('is-invalid');
         document.getElementById('fecha').classList.add(isValid ? 'is-valid' : 'is-invalid');
         document.getElementById('fecha').classList.contains('is-invalid') ? document.getElementById('fecha').focus() : "";
         !isValid ? isRun = false : "";
         isValid = document.getElementById('idtarea').value != "" && document.getElementById('idtarea').value !== null;
         document.getElementById('idtarea').classList.remove('is-invalid');
         document.getElementById('idtarea').classList.add(isValid ? 'is-valid' : 'is-invalid');
         document.getElementById('idtarea').classList.contains('is-invalid') ? document.getElementById('idtarea').focus() : "";
         !isValid ? isRun = false : "";
         if (!isRun) {
            // await Swal.fire({
            //    icon: "error",
            //    title: "Error",
            //    text: "Faltan campos por llenar"
            // })
            return;
         }
         Swal.fire({
            title: 'Confirmación',
            text: "Guardar cambios?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
         }).then((result) => {
            if (result.isConfirmed) {
               const formData = new FormData(this);
               fetch('helpers/ajaxRouter.php', {
                  method: 'POST',
                  body: formData
               }).then(response => response.json())
               .then(response => {
                  if (response.success === true) {
                     Swal.fire({
                        icon: "success",
                        title: "Éxito",
                        text: response.message,
                        showConfirmButton: true
                     }).then(() => {
                        if (response.reportUrl != null) {
                           window.open(response.reportUrl, '_blank');
                        }
                        location.reload();
                     });
                  } else {
                     Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.message
                     });
                  }
               })
               .catch(error => {
                  console.error('Error:', error);
               });
            }
         });
      });
   }
});


//****************************************************************************************
document.getElementById('idtarea').addEventListener('change', async function() {
   if (seguimientoTareaForm) {
      seguimientoTareaForm.addEventListener('submit', function(e) {
         e.preventDefault();
         e.stopPropagation();
         document.getElementById('comentario').focus();
         return false;
      });
   }
   if (this.value != "") {
      const formData = new FormData();
      formData.append('modulo', 'admon');
      formData.append('option', 'notificaciones');
      formData.append('action', 'getNotificacion');
      formData.append('id_notifi', this.value);
      const response = await fetch('helpers/ajaxRouter.php', {
         method: 'POST',
         body: formData
      }).then(resp => resp.json());
      if (response != false) {
         if (response.status == 9) {
            await Swal.fire({
               icon: "error",
               title: "Error",
               text: "Tarea ya está cerrada",
               showConfirmButton: true
            })
            document.getElementById('idtarea').value = "";
            document.getElementById('idtarea').focus();
            return false;
         }
         switch (response['prioridad']) {
            case 1:
               response['prioridad'] = 'Baja';
               break;
            case 2:
               response['prioridad'] = 'Media';
               break;
            case 3:
               response['prioridad'] = 'Alta';
               break;
            default:
               break;
         }
         document.getElementById('reprogramada').classList.add('d-none');
         document.getElementById('prioridad').value = response['prioridad'];
         document.getElementById('fechatearea').value = response['fecha'];
         document.getElementById('fechaentrega').value = response['fecha_entrega'];
         if (response.fecha_entrega < response.fecha_reprogra) {
            document.getElementById('reprogramada').classList.remove('d-none');
            document.getElementById('fechaentrega').value = response['fecha_reprogra'];
         }
         document.getElementById('empleado').value = response['name'];
         document.getElementById('titulo').value = response['titulo'];
         document.getElementById('detalle').value = response['detalle'];
         var minDate = new Date(response['fecha'].valueOf());
         minDate.setDate(minDate.getDate() + 1);
         $('#fecha').datepicker('setStartDate', minDate);
         document.getElementById('fecha').value = document.getElementById('fechaActual').value;
         // document.getElementById('fecha').focus();
      } else {
         Swal.fire({
            icon: "error",
            title: "Error",
            text: "Nro de tarea No existe",
            showConfirmButton: true
         })
      }
   }
});