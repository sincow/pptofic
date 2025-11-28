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
   document.getElementById('idConsigna').value = data[0]['consecutivo'];
   const datosCuenta = document.getElementById('datosCuenta');
   datosCuenta.innerHTML = '';
   // <div class="fw-600 fs-0 mb-0">Cuenta Bancaria: </div>

   let valConsig = 0;
   let innerHtml = "";
   data.forEach(element => {
      valConsig += parseFloat(element.valor_cheque);
      innerHtml += `
      <tr>
         <td class="text-start ps-0">${element.id_cheque}</td>
         <td>${element.TerNombr}</td>
         <td>${element.numero}</td>
         <td>${element.banco_codigo}</td>
         <td>${element.fecha_cheque}</td>
         <td class="text-start">${element.UltVenci}</td>
         <td class="text-end">${formatCurrency(parseFloat(element.valor_cheque),0)}</td>
      </tr>
      `; 
   });
   document.getElementById('ConsigTable-body').innerHTML = innerHtml;

   innerHtml = `
      <div class="row">
         <div class="col-lg-6 mb-3">
            <label class="text-label fs-0 mb-0">Cuenta Bancaria</label>
            <div class="fw-bold fs--1 mt-0">${data[0]['BanCodig']} - ${data[0]['BanNombr']}</div>
         </div>
         <div class="col-6 col-lg-3 mb-3">
            <label class="text-label fs-0 mb-0">Fecha Consignación</label>
            <div class="fw-bold fs--1 mt-0">${data[0]['fecha']}</div>
         </div>
         <div class="col-6 col-lg-3 mb-3">
            <label class="text-label fs-0 mb-0">Valor Consignación</label>
            <div class="fw-bold fs--1 mt-0">$ ${formatCurrency(parseFloat(valConsig),0)}</div>
         </div>
      </div>
   `;
   document.getElementById('datosCuenta').innerHTML = innerHtml;



}


//*********************************************************************************************
function anularDocum(paramsList) {
   const CompteBcoParam = paramsList.find(param => param.ParCodig === "BA1");
   const CompteBco = CompteBcoParam.ParValor;
   const CompteParam = paramsList.find(param => param.ParCodig === "CO2");
   const Compte = CompteParam.ParValor;
   const formData = new FormData();
   formData.append("modulo", "dival");
   formData.append("option", "consigna");
   formData.append("action", "anular");
   formData.append("idConsigna", document.getElementById('idConsigna').value);
   formData.append("compte", Compte);
   formData.append("CompteBco", CompteBco);
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