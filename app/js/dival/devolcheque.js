//***************************************************************************************
document.addEventListener("DOMContentLoaded", function() {
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
   document.getElementById('devolchequeForm').addEventListener('submit', function(e) {
      e.preventDefault();
      e.stopPropagation();
      createAcounting(paramsList);
      //return;
      let isValid = document.getElementById('motivo').innerHtml != "" && document.getElementById('motivo').innerHtml !== null;
      document.getElementById('motivo').classList.remove('is-invalid');
      document.getElementById('motivo').classList.add(isValid ? 'is-valid' : 'is-invalid');
      // document.getElementById('motivo').focus();
      document.getElementById('motivo').classList.contains('is-invalid') ? document.getElementById('motivo').focus() : "";

      isValid = document.getElementById('numero').value != "" && document.getElementById('numero').value !== null;
      document.getElementById('numero').classList.remove('is-invalid');
      document.getElementById('numero').classList.add(isValid ? 'is-valid' : 'is-invalid');
      document.getElementById('numero').classList.contains('is-invalid') ? document.getElementById('numero').focus() : "";

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

   });

});


//***************************************************************************************
function queryDocument(idDccument) {
   const formData = new FormData();
   formData.append("modulo", "dival");
   formData.append("option", "cheques");
   formData.append("action", "getDevol");
   formData.append("id_cheque", idDccument);
   fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(response => response.json())
   .then(response => {
      if (response != false) {
         document.getElementById('datCliente').innerHTML = '';
         document.getElementById('datCuenta').innerHTML = '';
         const innerHtml = `
            <div class="fw-bold">${response.cheque.TerDocId} ${response.cheque.TerNombr}</div>
            <div class="fw-600">${response.cheque.TerDirec} Teléfono: ${response.cheque.TerTele1}</div>
            <small class="text-label">${response.cheque.TerEmail}</small>
            ${response.cheque.nivel_riezgo ? `<br><small class="text-label">N.R.: ${response.cheque.nivel_riezgo}</small>` : ''}
         `;
         document.getElementById('datCliente').innerHTML = innerHtml;

         const innerHtmlCta = `
            <div class="fw-bold">Numero Cuenta: ${response.cheque.numero_cuenta}</div>
            <div class="fw-600">${response.cheque.banco_nombre}</div>
            <small class="text-label">Sucursal: ${response.cheque.sucursal}</small>
         `;
         document.getElementById('datCuenta').innerHTML = innerHtmlCta;

         document.getElementById('id_cheque').value = response.cheque.id_cheque;
         document.getElementById('fecha').value = response.cheque.fecha;
         document.getElementById('vencimiento').value = response.cheque.vencimiento;
         document.getElementById('TerDocId').value = response.cheque.TerDocId;
         const innerHtmlObs = `
            <div class="fw-600">${response.cheque.observacion == null ? 'Sin Observaciones' : response.cheque.observacion}</div>
         `;
         document.getElementById('observacion').innerHTML = innerHtmlObs;

         document.getElementById('diasCobrados').textContent = `Días totales: ${response.cheque.dias_cobrados}`;
         document.getElementById('valor_cheque').value = response.cheque.valor_cheque;
         document.getElementById('valCheque').value = parseFloat(response.cheque.valor_cheque).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

         const valImptoBco = response.cheque.valor_cheque * response.cheque.impuesto_banco / 100;
         const subtotal = response.cheque.valor_cheque - response.cheque.valor_cheque * response.cheque.porcentaje_comision / 100 * response.cheque.dias_cobrados;
         let valEntregar = Number(subtotal) - Number(valImptoBco) - response.cheque.valor_iva;

         document.getElementById('porcentaje_comision').value = parseFloat(response.cheque.porcentaje_comision).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 8 });
         document.getElementById('impuesto_banco').value = parseFloat(response.cheque.impuesto_banco).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         document.getElementById('valIVA').value = parseFloat(response.cheque.valor_iva).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         document.getElementById('valComision').value = parseFloat(response.cheque.comision).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         document.getElementById('valImptoBco').value = parseFloat(valImptoBco).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         document.getElementById('valEntregar').value = parseFloat(valEntregar).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         if (response.consignacion == false) {
            let text = "El cheque no ha sido consignado";
            if (response.cheque.status == 'D') {
               text = "El cheque fue devuelto y No se ha reconsignado";
            }
            Swal.fire({
               icon: "error",
               title: "error",
               text: text,
               showConfirmButton: true
            }).then(() => {
               return;
            });
         } else {
            document.getElementById('BanCodig').value = response.consignacion.BanCodig;
            document.getElementById('CueCodig').value = response.consignacion.CueCodig;
            document.getElementById('id_consigna').value = response.consignacion.id_consigna;
            document.getElementById('fechaCon').value = response.consignacion.fecha;
            document.getElementById('numeroCon').value = response.consignacion.numero;
            document.getElementById('CapPagado').value = parseFloat(response.cheque.capital_pagado).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('IntPagado').value = parseFloat(response.cheque.intereses_pagados).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('btnDevolAdd').disabled = false;
         }
         // document.getElementById('valCupo').value = parseFloat(response.valor_cupo).toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
         // document.getElementById('valCupoTmp').value = parseFloat(response.valor_cupotemporal).toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
      } else {
         Swal.fire({
            icon: "error",
            title: "error",
            text: "Documento no encontrado",
            showConfirmButton: true
         }).then(() => {
         });
      }  
   });
}


//*********************************************************************************************
function createAcounting(paramsList) {
   const acountingList = [];
   const CompteParam = paramsList.find(param => param.ParCodig === "CO3");
   const CompteBco = paramsList.find(param => param.ParCodig === "BA2");
   document.getElementById('CompteBco').value = CompteBco.ParValor;
   const Compte = CompteParam.ParValor;
   let AsiDescr = "Devolución de Cheque ";
   if (Compte != "") {
      document.getElementById('compte').value = Compte;
      const ctaCliente = paramsList.find(param => param.ParCodig === "CU1").ParValor;
      acountingList.push({
         "CueCodig": ctaCliente,
         "AsiDescr": AsiDescr + document.getElementById('numero').value,
         "AsiNatur": "D",
         "AsiValor": parseFloat(document.getElementById('valor_cheque').value.replace(/,/g, '')).toFixed(2),
         "AsiVBase": 0
      })
      // const ctaCaja = paramsList.find(param => param.ParCodig === "CU2").ParValor;
      const CueCodig = document.getElementById('CueCodig').value;
      acountingList.push({
         "CueCodig": CueCodig,
         "AsiDescr": AsiDescr + document.getElementById('numero').value,
         "AsiNatur": "C",
         "AsiValor": parseFloat(document.getElementById('valor_cheque').value.replace(/,/g, '')).toFixed(2),
         "AsiVBase": 0
      })
      document.getElementById('acountingList').value = JSON.stringify(acountingList);
   }
}