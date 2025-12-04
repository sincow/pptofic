document.addEventListener("DOMContentLoaded", async function() {
   var listWhere = [];
   listWhere.push({
      "id": "status",
      "value": '1'
   });
   const selectCuenta = document.getElementById('iduser');
   getSelects('admon', 'users', selectCuenta, 'id_user', textOpt = ['name'], listWhere);

   document.getElementById('fecha').value = document.getElementById('fechaActual').value;


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

});