//*********************************************************************************************
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
   const form = document.getElementById('formDocumentAnu');
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
         formData.append("option", "cajas");
         formData.append("action", "getByNum");
         formData.append("tipoDoc", document.getElementById('tipoDoc').value);
         formData.append("numDocum", document.getElementById('numDocAnu').value);
         const response = fetch('helpers/ajaxRouter.php', {
            method: 'POST',
            body: formData
         }).then(resp => resp.json())
         .then( data => {
            if (data) {
               getDocumentDetails(data);
            } else {
               swal.fire({
                  title: 'Error',
                  text: 'Documento no encontrado',
                  icon: 'error',
                  confirmButtonText: 'Aceptar'
               });
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
            title: '¿Está seguro de anular el documento?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, anular',
            cancelButtonText: 'Cancelar'
         }).then((result) => {
            if (result.isConfirmed) {
               anularDocument(paramsList);
            }
         })
      })
   }

});


//*********************************************************************************************
function getDocumentDetails(data) {
   if (data.status == 'A') {
      swal.fire({
         title: 'Error',
         text: 'El documento ya se encuentra anulado',
         icon: 'error',
         confirmButtonText: 'Aceptar'
      })
      return;
   }
   if (data.status == '1') {
      document.getElementById('btnAnularDocum').classList.remove('d-none');
   }
   document.getElementById('id_movimiento').value = data.id_movimiento;
   const documentDetails = document.getElementById('documentDetails');
   documentDetails.innerHTML = '';
   let innerHtml = '';
   if (document.getElementById('tipoDoc').value == '1') {
      innerHtml = `
         <div class="col-12 mb-3">
            <p class="text-label fs-0 ps-0 mb-0"><strong>Cuenta Bancaria: </strong></p> <span>${data.BanNombr}</span>
         </div>
         <div class="col-12 mb-3">
            <p class="text-label fs-0 ps-0 mb-0"><strong>Fecha: </strong></p> <span>${data.fecha}</span>
         </div>
         <div class="col-12 mb-3">
            <p class="text-label fs-0 ps-0 mb-0"><strong>Valor del Aporte:</strong></p> <span>$ ${formatCurrency(parseFloat(data.valor_entrada),0,2)}</span>
         </div>
      `;
   } else {
      innerHtml = `
         <div class="col-12 mb-3">
            <p class="text-label fs-0 ps-0 mb-0"><strong>Tercero:</strong></p> <span>${data.TerDocId} ${data.TerNombr}</span>
         </div>
         <div class="col-12 mb-3">
            <p class="text-label fs-0 ps-0 mb-0"><strong>Detalle:</strong></p> <span>${data.descripcion}</span>
         </div>
         <div class="col-12 mb-3">
            <p class="text-label fs-0 ps-0 mb-0"><strong>Fecha: </strong></p> <span>${data.fecha}</span>
         </div>
         <div class="col-12 mb-3">
            <p class="text-label fs-0 ps-0 mb-0"><strong>Valor del Documento:</strong></p> <span>$ ${formatCurrency(parseFloat(data.valor_entrada),0,2)}</span>
         </div>
         <div class="col-12 mb-3">
            <p class="text-label fs-0 ps-0 mb-0"><strong>Cuenta Contable: </strong></p> <span>${data.CueNombr}</span>
         </div>
      `;
   }
   document.getElementById('documentDetails').innerHTML = innerHtml;
}


//*********************************************************************************************
function anularDocument(paramsList) {
   let CompteParam = "";
   let Compte = "";
   if (document.getElementById('tipoDoc').value == '2') {
      CompteParam = paramsList.find(param => param.ParCodig === "CO7");
      Compte = CompteParam.ParValor;
   }
   if (document.getElementById('tipoDoc').value == '3') {
      CompteParam = paramsList.find(param => param.ParCodig === "CO6");
      Compte = CompteParam.ParValor;
   }
   let CompteBco = "";
   if (document.getElementById('tipoDoc').value == '1') {
      const CompteBcoParam = paramsList.find(param => param.ParCodig === "BA3");
      CompteBco = CompteBcoParam.ParValor;
   }

   const formData = new FormData();
   formData.append("modulo", "dival");
   formData.append("option", "cajas");
   formData.append("action", "anular");
   formData.append("tipoDoc", document.getElementById('tipoDoc').value);
   formData.append("id_movimiento", document.getElementById('id_movimiento').value);
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