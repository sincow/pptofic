document.addEventListener("DOMContentLoaded", async function() {

   //*******************************************************************************************
   const imputCliet = document.getElementById("repIdCliente");
   if (imputCliet) {
      var listWhere = [];
      listWhere.push({
         "id": "status",
         "value": '1'
      });
      const selectCuenta = document.getElementById('repIdCliente');
      getSelects('dival', 'clientes', selectCuenta, 'TerDocId', textOpt = ['TerDocId', 'TerNombr'], listWhere);
   }
});


//*******************************************************************************************
const formulario = document.getElementById("frmReports");
if (formulario) {
   formulario.addEventListener("submit", async function (e) {
      e.preventDefault();
      const success = $('.success').val();
      const formData = new FormData(this);
      fetch('helpers/ajaxRouter.php', {
         method: 'POST',
         body: formData
      })
      .then(response => response.json())
      .then( async data => {
         console.log("reponse",data);
         if (data.success) {
            let win = window.open(data.url, '_blank');
            setTimeout(() => {
               win.close();
            }, 600 * 1000 );
            Swal.fire({
               title: success,
               text: data.message,
               icon: 'success',
               confirmButtonColor: '#25a0e2'
            }).then(() => {
               //location.reload();
            });
         } else {
            Swal.fire('Error', data.message, 'error');
         }
      })
      .catch(error => {
         console.error('Error loading calendar config:', error);
      });
   });
}