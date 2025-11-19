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
   document.getElementById('fecha_pago').addEventListener('change', function() {
      // document.getElementById('diasCobrados').textContent = `Días totales: ${dias}`;
   });


   //*********************************************************************************************
   document.getElementById('capital_pagar').addEventListener('change', function() {
      const valMax = $(this).attr('valMax');
      if (parseFloat(this.value) > parseFloat(valMax)) {
         this.value = valMax;
      }
   });
   document.getElementById('capital_pagar').addEventListener('input', function() {
      this.value = this.value.replace(/[^\d]/g, '');
   });
   document.getElementById('capital_pagar').addEventListener('blur', function() {
      if (this.value) {
         const number = parseInt(this.value);
         this.value = number.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
      }
   });
   document.getElementById('capital_pagar').addEventListener('focus', function() {
      this.value = this.value.replace(/\,/g, '');
   });


   //*********************************************************************************************
   document.getElementById('pagocapitalForm').addEventListener('submit', function(e) {
      e.preventDefault();
      e.stopPropagation();
      createAcounting(paramsList);
      //return;
      let isValid = "";
      let isRun = true;
      isValid = document.getElementById('capital_pagar').value != 0.00;
      document.getElementById('capital_pagar').classList.remove('is-invalid');
      document.getElementById('capital_pagar').classList.remove('is-valid');
      document.getElementById('capital_pagar').classList.add(isValid ? 'is-valid' : 'is-invalid');
      document.getElementById('capital_pagar').classList.contains('is-invalid') ? document.getElementById('capital_pagar').focus() : "";
      !isValid ? isRun = false : "";

      isValid = document.getElementById('fecha_pago').value != "" && document.getElementById('fecha_pago').value !== null;
      document.getElementById('fecha_pago').classList.remove('is-invalid');
      document.getElementById('fecha_pago').classList.add(isValid ? 'is-valid' : 'is-invalid');
      document.getElementById('fecha_pago').classList.contains('is-invalid') ? document.getElementById('fecha_pago').focus() : "";
      !isValid ? isRun = false : "";

      isValid = document.getElementById('numero').value != "" && document.getElementById('numero').value !== null;
      document.getElementById('numero').classList.remove('is-invalid');
      document.getElementById('numero').classList.add(isValid ? 'is-valid' : 'is-invalid');
      document.getElementById('numero').classList.contains('is-invalid') ? document.getElementById('numero').focus() : "";
      !isValid ? isRun = false : "";
      if (!isRun) {
         // document.getElementById('pagocapitalForm').classList.remove('was-validated');
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
               //location.reload();
               // window.location.href = "/dashboard";
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
   formData.append("action", "getAplaza");
   formData.append("id_cheque", idDccument);
   fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(response => response.json())
   .then(response => {
      if (response != false) {
         if (response.cheque.status == 'C') {
            let text = "El cheque está consignado";
            Swal.fire({
               icon: "error",
               title: "error",
               text: text,
               showConfirmButton: true
            }).then(() => {
               return;
            });
         }

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
         // document.getElementById('fecha').value = response.cheque.fecha;
         document.getElementById('TerDocId').value = response.cheque.TerDocId;
         const innerHtmlObs = `
            <div class="fw-600">${response.cheque.observacion == null ? 'Sin Observaciones' : response.cheque.observacion}</div>
         `;
         document.getElementById('observacion').innerHTML = innerHtmlObs;

         const valImptoBco = response.cheque.valor_cheque * response.cheque.impuesto_banco / 100;
         const subtotal = response.cheque.valor_cheque - response.cheque.valor_cheque * response.cheque.porcentaje_comision / 100 * response.cheque.dias_cobrados;
         const valEntregar = Number(subtotal) - Number(valImptoBco) - response.cheque.valor_iva;
         const totalAIC = response.cheque.comision + response.cheque.intereses_pagados;
         const capSaldo = response.cheque.valor_cheque - response.cheque.capital_pagado;
         const IntPendiente = response.cheque.intereses_cobrados - response.cheque.intereses_pagados;

         document.getElementById('capital_pagar').value = parseFloat(0.00).toFixed(2);
         document.getElementById('capital_pagar').setAttribute('valMax', Number(capSaldo));

         document.getElementById('nroCompte').value = response.cheque.id_cheque;
         document.getElementById('valor_cheque').value = response.cheque.valor_cheque;
         document.getElementById('valCheque').value = parseFloat(response.cheque.valor_cheque).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         document.getElementById('porComision').value = parseFloat(response.cheque.porcentaje_comision).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 8 });
         document.getElementById('impuesto_banco').value = parseFloat(response.cheque.impuesto_banco).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         document.getElementById('valEntregar').value = parseFloat(valEntregar).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

         document.getElementById('valComision').value = parseFloat(response.cheque.comision).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         document.getElementById('valImptoBco').value = parseFloat(valImptoBco).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         document.getElementById('valIVA').value = parseFloat(response.cheque.valor_iva).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         document.getElementById('totalAIC').value = parseFloat(totalAIC).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

         document.getElementById('fechaCheque').value = response.cheque.fecha;
         // document.getElementById('fecha_pago').value = response.cheque.vencimiento;
         document.getElementById('fechaVencim').value = response.cheque.vencimiento;
         document.getElementById('fechaAplaza').value = response.cheque.vencimiento;
         document.getElementById('CapPagado').value = parseFloat(response.cheque.capital_pagado).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         document.getElementById('CapSaldo').value = parseFloat(capSaldo).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         document.getElementById('valor_aplaza').value = Number(capSaldo);

         document.getElementById('IntCobrados').value = parseFloat(response.cheque.intereses_cobrados).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         document.getElementById('IntPagado').value = parseFloat(response.cheque.intereses_pagados).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         document.getElementById('IntPendiente').value = parseFloat(IntPendiente).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         // document.getElementById('IntTotal').value = parseFloat(IntPendiente).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

         if (response.aplaza.length > 0) {
            // document.getElementById('fecha_pago').value = response.aplaza[0].fecha;
            document.getElementById('fechaAplaza').value = response.aplaza[0].fecha;
            const fechaMinima = new Date(response.aplaza[0].fecha);
            document.getElementById('fecha_pago').setAttribute('min', fechaMinima.toISOString().split('T')[0]);
         }
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
   const CompteParam = paramsList.find(param => param.ParCodig === "CO4");
   const Compte = CompteParam.ParValor;
   if (Compte != "") {
      let AsiDescr = "Pago de capital del documento Nro " + document.getElementById('numero').value;
      document.getElementById('compte').value = Compte;
      const intCob = paramsList.find(param => param.ParCodig === "CU2").ParValor;
      acountingList.push({
         "CueCodig": intCob,
         "AsiDescr": AsiDescr,
         "AsiNatur": "D",
         "AsiValor": parseFloat(document.getElementById('capital_pagar').value.replace(/,/g, '')).toFixed(2),
         "AsiVBase": 0
      })
      const ingInt = paramsList.find(param => param.ParCodig === "CU1").ParValor;
      acountingList.push({
         "CueCodig": ingInt,
         "AsiDescr": AsiDescr,
         "AsiNatur": "C",
         "AsiValor": parseFloat(document.getElementById('capital_pagar').value.replace(/,/g, '')).toFixed(2),
         "AsiVBase": 0
      })
      document.getElementById('acountingList').value = JSON.stringify(acountingList);
   }
}