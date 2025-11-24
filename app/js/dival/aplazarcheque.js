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
   document.getElementById('vencimiento').addEventListener('change', function() {
      const dias = calcularDias();
      calcularSubtotal();
      document.getElementById('diasCobrados').textContent = `Días totales: ${dias}`;
   });


   //*********************************************************************************************
   $(".diasHabiles").change(function() {
      const dias = calcularDias();
      calcularSubtotal();
      document.getElementById('diasCobrados').textContent = `Días totales: ${dias}`;
   });


   //*********************************************************************************************
   document.getElementById('valor_cheque').addEventListener('change', function() {
      const diasMax = $(this).attr('valMax');
      if (parseFloat(this.value) > parseFloat(diasMax)) {
         this.value = diasMax;
      }
      const dias = calcularSubtotal();
   });
   document.getElementById('valor_cheque').addEventListener('input', function() {
      this.value = this.value.replace(/[^\d]/g, '');
   });
   document.getElementById('valor_cheque').addEventListener('blur', function() {
      if (this.value) {
         const number = parseInt(this.value);
         this.value = number.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
      }
   });
   document.getElementById('valor_cheque').addEventListener('focus', function() {
      this.value = this.value.replace(/\,/g, '');
   });


   //*********************************************************************************************
   document.getElementById('porcentaje_comision').addEventListener('change', function() {
      const dias = calcularSubtotal();
   });
   document.getElementById('porcentaje_comision').addEventListener('input', function() {
      const value = this.value;
      if (value.includes('.') && value.split('.')[0].length > 2) {
         const entera = value.split('.')[0].substring(0, 2);
         const decimal = value.split('.')[1] || '';
         this.value = entera + '.' + decimal;
      }
      if (value.includes('.') && value.split('.')[1].length > 8) {
         const entera = value.split('.')[0].substring(0, 2);
         const decimal = value.split('.')[1].substring(0, 8);
         this.value = entera + '.' + decimal;
      }
   });


   //*********************************************************************************************
   document.getElementById('aplazachequeForm').addEventListener('submit', function(e) {
      e.preventDefault();
      e.stopPropagation();
      createAcounting(paramsList);
      //return;
      const isValid = document.getElementById('valor_cheque').value != 0 && document.getElementById('valor_cheque').value !== null;
      document.getElementById('valor_cheque').classList.remove('is-invalid');
      document.getElementById('valor_cheque').classList.add(isValid ? 'is-valid' : 'is-invalid');
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

         // document.getElementById('diasCobrados').textContent = `Días totales: ${response.cheque.dias_cobrados}`;
         document.getElementById('diasCobrados').textContent = `Días totales: 0`;

         const valImptoBco = response.cheque.valor_cheque * response.cheque.impuesto_banco / 100;
         const subtotal = response.cheque.valor_cheque - response.cheque.valor_cheque * response.cheque.porcentaje_comision / 100 * response.cheque.dias_cobrados;
         const valEntregar = Number(subtotal) - Number(valImptoBco) - response.cheque.valor_iva;
         const totalAIC = response.cheque.comision + response.cheque.intereses_pagados;
         const capSaldo = response.cheque.valor_cheque - response.cheque.capital_pagado;
         const IntPendiente = response.cheque.intereses_cobrados - response.cheque.intereses_pagados;

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
         document.getElementById('vencimiento').value = response.cheque.vencimiento;
         document.getElementById('fechaVencim').value = response.cheque.vencimiento;
         document.getElementById('fechaAplaza').value = response.cheque.vencimiento;
         document.getElementById('CapPagado').value = parseFloat(response.cheque.capital_pagado).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         document.getElementById('CapSaldo').value = parseFloat(capSaldo).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         document.getElementById('valor_aplaza').value = Number(capSaldo);

         document.getElementById('IntCobrados').value = parseFloat(response.cheque.intereses_cobrados).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         document.getElementById('IntPagado').value = parseFloat(response.cheque.intereses_pagados).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         document.getElementById('IntPendiente').value = parseFloat(IntPendiente).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         document.getElementById('IntNuevo').value = parseFloat(0.00).toFixed(2);
         document.getElementById('IntTotal').value = parseFloat(IntPendiente).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

         if (response.aplaza.length > 0) {
            document.getElementById('vencimiento').value = response.aplaza[0].fecha;
            document.getElementById('fechaAplaza').value = response.aplaza[0].fecha;
            const fechaMinima = new Date(response.aplaza[0].fecha);

            document.getElementById('vencimiento').setAttribute('min', fechaMinima.toISOString().split('T')[0]);

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
function calcularDias() {
   //const fechaInicio = new Date(document.getElementById('fechaActual').value);
   let fechaInicio = new Date();
   if (document.getElementById('fechaAplaza')) {
      fechaInicio = new Date(document.getElementById('fechaAplaza').value);
      fechaInicio.setDate(fechaInicio.getDate() + 1);
   }
   fechaInicio.setHours(0, 0, 0, 0);
   const fechaFin = new Date(document.getElementById('vencimiento').value);
   fechaFin.setHours(0, 0, 0, 0);
   const diferencia = fechaFin.getTime() - fechaInicio.getTime();
   let dias = Math.ceil(diferencia / (1000 * 60 * 60 * 24)) +1;
   let finesDeSemana = 0;
   let festivosEnRango = 0;
   const fechaTemp = new Date(fechaInicio);
   for (let i = 0; i < dias; i++) {
      // Verificar fin de semana
      if (fechaTemp.getDay() === 0 || fechaTemp.getDay() === 6) {
         finesDeSemana++;
      } else {
         // Verificar si es festivo (solo días laborables)
         const fechaStr = fechaTemp.toISOString().split('T')[0];
         if (diasFestivos.includes(fechaStr)) {
            festivosEnRango++;
         }
      }
      fechaTemp.setDate(fechaTemp.getDate() + 1);
   }
   if (document.querySelector('input[name="diasHabiles"]:checked').value == "0") {
      dias = dias - finesDeSemana - festivosEnRango;
   }
   document.getElementById('dias_cobrados').value = dias;
   document.getElementById('dias_cobrar').value = dias;
   return dias;
}


//*********************************************************************************************
function calcularSubtotal() {
   let valor_cheque = document.getElementById('valor_cheque').value.replace(/\,/g, '');
   let CapPagado = document.getElementById('CapPagado').value.replace(/\,/g, '');
   let IntPendiente = document.getElementById('IntPendiente').value.replace(/\,/g, '');
   // let impuesto_banco = document.getElementById('impuesto_banco').value.replace(/\,/g, '');
   valor_cheque = parseFloat(valor_cheque);
   const comision = parseFloat(document.getElementById('porcentaje_comision').value);
   let dias_cobrados = calcularDias();
   if (!dias_cobrados) {
      dias_cobrados = 0;      
   }
   let subtotal = (valor_cheque - CapPagado) * comision / 100 * dias_cobrados;
   let IntTotal = Number(IntPendiente) + Number(subtotal);
   // let valIva = 0;
   // if ($("#ivaIncluido").val() != "2") {
   //    valIva = subtotal - subtotal / ($("#valorIva").val() / 100+1);
   // } else {
   //    valIva = subtotal * ($("#valorIva").val() / 100);
   // }
   // let valImptoBco = valor_cheque * impuesto_banco / 100;
   // let valEntregar = 0;
   // if ($("#ivaIncluido").val() != "2") {
   //    valEntregar = valor_cheque - subtotal - valImptoBco;
   // } else {
   //    valEntregar = valor_cheque - subtotal - valImptoBco - valIva;
   // }
   document.getElementById('valor_interes').value = Number(subtotal);
   document.getElementById('IntNuevo').value = subtotal.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
   document.getElementById('IntTotal').value = IntTotal.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

   // document.getElementById('valComision').value = subtotal.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
   // document.getElementById('valImptoBco').value = valImptoBco.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
   // document.getElementById('valIVA').value = valIva.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
   // document.getElementById('valEntregar').value = valEntregar.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });

}


//*********************************************************************************************
function createAcounting(paramsList) {
   const acountingList = [];
   const CompteParam = paramsList.find(param => param.ParCodig === "CO8");
   const Compte = CompteParam.ParValor;
   const TerDocId = document.getElementById('TerDocId').value;
   if (Compte != "") {
      let AsiDescr = "Aplazamiento del documento Nro " + document.getElementById('numero').value;
      document.getElementById('compte').value = Compte;
      const intCob = paramsList.find(param => param.ParCodig === "CU5").ParValor;
      acountingList.push({
         "CueCodig": intCob,
         "TerDocId": TerDocId,
         "AsiDescr": AsiDescr,
         "CenCodig": "",
         "CenCodAu": "",
         "AsiNatur": "D",
         "AsiValor": parseFloat(document.getElementById('IntNuevo').value.replace(/,/g, '')).toFixed(2),
         "AsiVBase": 0
      })
      const ingInt = paramsList.find(param => param.ParCodig === "CU7").ParValor;
      acountingList.push({
         "CueCodig": ingInt,
         "TerDocId": TerDocId,
         "CenCodig": "",
         "CenCodAu": "",
         "AsiDescr": AsiDescr,
         "AsiNatur": "C",
         "AsiValor": parseFloat(document.getElementById('IntNuevo').value.replace(/,/g, '')).toFixed(2),
         "AsiVBase": 0
      })
      document.getElementById('acountingList').value = JSON.stringify(acountingList);
   }
}