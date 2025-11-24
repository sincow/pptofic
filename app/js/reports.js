document.addEventListener("DOMContentLoaded", async function() {

   //*******************************************************************************************
   const imputCliet = document.getElementById("repIdCliente");
   if (imputCliet) {
      var listWhere = [];
      listWhere.push({
         "id": "status",
         "value": '1'
      });
      await getSelects('dival', 'clientes', imputCliet, 'id_dvcliente', textOpt = ['TerDocId', 'TerNombr'], listWhere);

      $(imputCliet).on('change', function(e) {
         e.preventDefault();
         const clienteId = this.value;
         if (document.getElementById('action').value == 'reppreliqui') {
            const formData = new FormData();
            formData.append('modulo', 'dival');
            formData.append('option', 'clientes');
            formData.append('action', 'getSaldo');
            formData.append('id', clienteId);
            fetch('helpers/ajaxRouter.php', {
               method: 'POST',
               body: formData
            })
            .then(response => response.json())
            .then(data => {
               if (data.length > 0) {
                  cargarEstadoCuenta(data);
               }
            })
            .catch(error => {
               console.error('Error loading calendar config:', error);
            });
         }
      });
   }

   const incluirEstCta = document.getElementById('incluirEstCta');
   if (incluirEstCta) {
      incluirEstCta.addEventListener('change', function(e) {
         e.preventDefault();
         if (this.checked) {
            document.querySelectorAll('.incluirEstCta').forEach(element => {
               element.checked = true;
            })
         } else {
            document.querySelectorAll('.incluirEstCta').forEach(element => {
               element.checked = false;
            })
         }
      });
   }


});

/*
document.getElementById('repIdCliente').addEventListener('change', function() {
   e.preventDefault();
   alert("cambios"); 
    const clienteId = $('#repIdCliente').val();
    console.log('Cliente seleccionado:', clienteId);
   if (document.getElementById('action').value == 'reppreliqui') {
      const selectedValue = this.value;
      alert(selectedValue);
   }

   // const clientData = clients.find(client => client.TerDocId === selectedValue);
   // document.getElementById('repIdCliente').value = clientData.id_dvcliente;
});
*/


//*******************************************************************************************
const formulario = document.getElementById("frmReports");
if (formulario) {
   formulario.addEventListener("submit", async function (e) {
      e.preventDefault();
      if (document.getElementById('action').value == 'reppreliqui') {
         createDocumPreliq();
      }
      const success = $('.success').val();
      const formData = new FormData(this);
      fetch('helpers/ajaxRouter.php', {
         method: 'POST',
         body: formData
      })
      .then(response => response.json())
      .then( async data => {
         if (data.success) {
            let win = window.open(data.url, '_blank');
            setTimeout(() => {
               win.close();
            }, 6000 * 1000 );
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


//*********************************************************************************************
function cargarEstadoCuenta(clienteId) {
   let tbody = document.getElementById("estCtaTable-body");
   tbody.innerHTML = "";
   let innerHtml = ``;
   clienteId.forEach(element => {
      innerHtml += `
      <tr>
         <td class="text-start ps-0">${element.numero}</td>
         <td>${element.fecha}</td>
         <td class="text-start">${element.UltVenci}</td>
         <td class="text-end">${formatCurrency(parseFloat(element.valor_cheque),0)}</td>
         <td class="text-end">${formatCurrency(parseFloat(element.valor_cheque - element.capital_pagado ),0)}</td>
         <td class="text-end">${formatCurrency(parseFloat(element.comision),0)}</td>
         <td class="text-start">
            <input type="checkbox" name="id_cheque[]" value="${element.id_cheque}" class="form-check-input incluirEstCta" id="id_cheque${element.id_cheque}">
         </td>
      </tr>
      `;

   });
   document.getElementById('estCtaTable-body').innerHTML = innerHtml;
}


//*********************************************************************************************
function createDocumPreliq() {
   const documList = [];
   document.querySelectorAll('.incluirEstCta').forEach(element => {
      if (element.checked) {
         documList.push({
            "id_cheque": element.value
      });
      }
   });
   document.getElementById('documPreliqList').value = JSON.stringify(documList);
}