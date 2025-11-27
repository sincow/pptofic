document.addEventListener("DOMContentLoaded", function() {

   //*********************************************************************************************
   const paramsList = [];
   const formData = new FormData();
   formData.append("modulo", "admon");
   formData.append("option", "parametros");
   formData.append("action", "getAll");
   formData.append("modcodig", "21");
   const response = fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(resp => resp.json())
   .then( data => {
      if (data.length > 0) {
			data.forEach(parametros => {
            paramsList.push({
               "ParCodig": parametros["ParCodig"],
               "ParNombr": parametros["ParNombr"],
               "ParValor": parametros["ParValor"],
               "ParObjeto": parametros["ParObjeto"]
            })
         });
      }
   });


   //*********************************************************************************************
   const form = document.getElementById('formConsigAnu');
   const numDocAnu = document.getElementById('numDocAnu');
   if (numDocAnu) {
      if (form) {
         form.addEventListener('submit', function(e) {
            e.preventDefault();
            return false;
         });
      }
      numDocAnu.addEventListener('change', () => {
         const formData = new FormData();
         formData.append("modulo", "dival");
         formData.append("option", "consigna");
         formData.append("action", "getConsigById");
         formData.append("idConsigna", document.getElementById('numDocAnu').value);
         const response = fetch('helpers/ajaxRouter.php', {
            method: 'POST',
            body: formData
         }).then(resp => resp.json())
         .then( data => {
            if (data.length == 0) {
               swal.fire({
                  title: 'Error',
                  text: 'Documento no encontrado',
                  icon: 'error',
                  confirmButtonText: 'Aceptar'
               });
               return;
            } else {
               if (data[0].status == 'A') {
                  swal.fire({
                     title: 'Error',
                     text: 'Documento ya se encuentra anulado',
                     icon: 'error',
                     confirmButtonText: 'Aceptar'
                  });
                  return;
               }
               getDocumentDetails(data)
            }
         })
         .catch(error => {
            console.error('Error:', error);
         });
      })
   }


   //*********************************************************************************************
   const btnAnularDocum = document.getElementById('btnAnularDocum');
   if (btnAnularDocum) {
      document.getElementById('btnAnularDocum').addEventListener('click', () => {
         swal.fire({
            title: '¿Está seguro de anular el Pago Intereses?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, anular',
            cancelButtonText: 'Cancelar'
         }).then((result) => {
            if (result.isConfirmed) {
               anularDocum(paramsList);
            }
         })
      })
   }

});


//*********************************************************************************************
function getDocumentDetails(data) {
   document.getElementById('btnAnularDocum').classList.remove('d-none');
   document.getElementById('id_consigna').value = data[0]['id_consigna'];
   const datosCuenta = document.getElementById('datosCuenta');
   datosCuenta.innerHTML = '';
   let innerHtml = `
      <div class="fw-600 fs-0 mb-0">Cuenta Bancaria: </div>
      <div class="fw-bold fs--1 mt-0">${data[0]['BanCodig']} ${data[0]['BanNombr']}</div>
   `;
      // ${data['cheque'].nivel_riezgo ? `<br><small class="text-label fs--1 badge badge-phoenix badge-phoenix-${getRiskBadgeClass(data['cheque'].nivel_riezgo)}">N.R. ${data['cheque'].nivel_riezgo}</small>` : ''}
   document.getElementById('datosCuenta').innerHTML = innerHtml;

   innerHtml = "";
   data.forEach(element => {
      innerHtml += `
      <tr>
         <td class="text-start ps-0">${element.consecutivo}</td>
         <td>${element.TerNombr}</td>
         <td>${element.numero}</td>
         <td>${element.banco_codigo}</td>
         <td>${element.fecha}</td>
         <td class="text-start">${element.UltVenci}</td>
         <td class="text-end">${formatCurrency(parseFloat(element.valor_cheque),0)}</td>
      </tr>
      `; 
   });
   document.getElementById('ConsigTable-body').innerHTML = innerHtml;
}


//*********************************************************************************************
function anularDocum(paramsList) {
   const CompteParam = paramsList.find(param => param.ParCodig === "CO2");
   const Compte = CompteParam.ParValor;
   const formData = new FormData();
   formData.append("modulo", "dival");
   formData.append("option", "consigna");
   formData.append("action", "anular");
   formData.append("id_consigna", document.getElementById('id_consigna').value);
   formData.append("compte", Compte);
   const response = fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   })
   .then(resp => resp.json())
   .then( data => {
      if (data.success) {
         swal.fire({
            title: 'Anulado',
            text: data.message,
            icon: 'success',
            confirmButtonText: 'Aceptar'
         }).then(() => {
            location.reload();
         });
      } else {
         swal.fire({
            title: 'Error',
            text: data.message,
            icon: 'error',
            confirmButtonText: 'Aceptar'
         });
      }
   })
   .catch(error => {
      console.error('Error:', error);
   });
}