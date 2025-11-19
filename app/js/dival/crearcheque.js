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

   let fechaMinima = new Date();
   if (document.getElementById('fechaActual')) {
      document.getElementById('fechaActual').addEventListener('change', function() {
         document.getElementById('vencimiento').setAttribute('min', this.value);
      })
      document.getElementById('vencimiento').setAttribute('min', document.getElementById('fechaActual').value);
      fechaMinima = new Date(document.getElementById('fechaActual').value);
   }
   document.getElementById('vencimiento').setAttribute('minDate', fechaMinima.toISOString().split('T')[0]);


   //*********************************************************************************************
   document.querySelectorAll('.client-search').forEach(input => {
      // initAutocomplete(input, 245, 92);
      initAutocomplete(input);
   });


   //*********************************************************************************************
   document.getElementById('idCliente').addEventListener('change', function() {
      alert(this.value);
      loadCtaClient(this.value);
   });


   //*********************************************************************************************
   document.getElementById('numero_cuenta').addEventListener('change', function() {
      const option = this.options[this.selectedIndex];
      document.getElementById('datCuenta').innerHTML = 
         `Numero Cuenta: ${option.getAttribute('numero_cuenta')} <br> 
         ${option.getAttribute('banco')}
          Sucursal: ${option.getAttribute('sucursal')}
      `;
      //loadCtaClient(this.value);
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
   document.getElementById('impuesto_banco').addEventListener('change', function() {
      const dias = calcularSubtotal();
   });


   //*********************************************************************************************
   document.getElementById('clearSearch').addEventListener('click', function() {
      location.reload();
   });

   //*********************************************************************************************
   document.getElementById('clearSearch2').addEventListener('click', function() {
      document.getElementById('idCliente2').value = "";
      document.getElementById('TerDocId2').value = "";
   });

   //*********************************************************************************************
   document.getElementById('clearSearch3').addEventListener('click', function() {
      document.getElementById('idCliente3').value = "";
      document.getElementById('TerDocId3').value = "";
   });
   //*********************************************************************************************
   document.getElementById('clearSearch4').addEventListener('click', function() {
      document.getElementById('idCliente4').value = "";
      document.getElementById('TerDocId4').value = "";
   });


   //*********************************************************************************************
   document.getElementById('crearchequeForm').addEventListener('submit', function(e) {
      e.preventDefault();
      e.stopPropagation();
      createAcounting(paramsList);
      //return;
      const isValid = document.getElementById('valor_cheque').value != 0 && document.getElementById('valor_cheque').value !== null;
      document.getElementById('valor_cheque').classList.remove('is-invalid');
      document.getElementById('valor_cheque').classList.add(isValid ? 'is-valid' : 'is-invalid');
      const formData = new FormData(this);
      formData.append("modulo", "dival");
      formData.append("option", "cheques");
      formData.append("action", "create");
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
               // timer: 2000,
               showConfirmButton: true
            }).then(() => {
               if (response.reportUrl != null) {
                  window.open(response.reportUrl, '_blank');
               }
               location.reload();
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


//********************************************************************************************
function selectClient(selectedItem, inputElement) {
   const clientData = JSON.parse(selectedItem.dataset.clientData);
   if (inputElement.id != 'idCliente') {
      document.getElementById(inputElement.getAttribute('refer')).value = clientData.TerDocId;
      inputElement.value = `${clientData.TerDocId} ${clientData.TerNombr} (${clientData.TerEmail})`;
      if ([document.getElementById('TerDocId').value, document.getElementById('TerDocId2').value].includes(document.getElementById('TerDocId3').value) ||
         [document.getElementById('TerDocId2').value, document.getElementById('TerDocId3').value].includes(document.getElementById('TerDocId').value)
         ) {
         Swal.fire({
            icon: "error",
            title: "Error",
            text: "Los Firmantes deben ser diferentes"
         }).then(() => {
            document.getElementById(inputElement.id).value = "";
            inputElement.getAttribute('refer').value = "";
            document.getElementById(inputElement.id).focus();
         })
      }
      hideSuggestions();
      return;
   } else {
      inputElement.disabled = true;
   }
   document.getElementById('idCliente').value = clientData.id_dvcliente;
   document.getElementById('TerDocId').value = clientData.TerDocId;
   inputElement.value = `${clientData.TerDocId} ${clientData.TerNombr} (${clientData.TerEmail})`;
   const formData = new FormData();
   formData.append("modulo", "dival");
   formData.append("option", "clientes");
   formData.append("action", "getSaldo");
   formData.append("id", clientData.id_dvcliente);
   let SaldosHtml = '';
   let valorCartera = 0;
   let valorCheque = 0;
   let valorComision = 0;
   let interxCobrar = 0;
   document.getElementById('saldoCarteraBody').innerHTML = SaldosHtml;
   // let cupo = document.getElementById('valCupo').value.replace(/\,/g, '');
   let cupo = parseFloat(clientData.valor_cupo);
   // cupo = parseFloat(cupo);
   // let cupoTMP = document.getElementById('valCupoTmp').value.replace(/\,/g, '');
   let cupoTMP = parseFloat(clientData.valor_cupotemporal);
   // cupoTMP = parseFloat(cupoTMP);
   cupo = cupo + cupoTMP;
   fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(response => response.json())
   .then(saldos => {
      if (saldos.length === 0) {
         SaldosHtml += `
            <tr class="fw-semi-bold">
               <td class="fw-semi-bold p-2" colspan='9' style="text-align: center;">No hay saldos pendientes</td>
               <td>
            </tr>`
         document.getElementById('saldoCarteraBody').innerHTML = SaldosHtml;
      } else {
         saldos.forEach(saldo => {
            valorCartera += parseFloat(saldo.valor_cheque - saldo.capital_pagado);
            valorCheque += parseFloat(saldo.valor_cheque);
            valorComision += parseFloat(saldo.comision);
            interxCobrar += parseFloat(saldo.intereses_cobrados);
            SaldosHtml += `
               <tr>
                  <td class="fw-semi-bold py-2">${saldo.numero}</td>
                  <td class="fw-semi-bold py-2">${saldo.BanNombr}</td>
                  <td class="fw-semi-bold py-2">${saldo.fecha}</td>
                  <td class="fw-semi-bold py-2">${saldo.vencimiento}</td>
                  <td class="fw-semi-bold py-2 text-end">${saldo.dias_cobrados}</td>
                  <td class="fw-semi-bold py-2 text-end">${parseFloat(saldo.valor_cheque).toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                  <td class="fw-semi-bold py-2 text-end">${parseFloat(saldo.comision).toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                  <td class="fw-semi-bold py-2 text-end">${parseFloat(saldo.intereses_cobrados).toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                  <td class="fw-semi-bold py-2 text-end">${saldo.porcentaje_comision}</td>
               </tr>`;
            document.getElementById('saldoCarteraBody').innerHTML = SaldosHtml;
         });
         SaldosHtml += `
            <tr class="fw-bold table-light ">
               <td class="py-2"></td>
               <td class="py-2">TOTALES</td>
               <td class="py-2"></td>
               <td class="py-2"></td>
               <td class="py-2 text-end"></td>
               <td class="py-2 text-end">${parseFloat(valorCheque).toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
               <td class="py-2 text-end">${parseFloat(valorComision).toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
               <td class="py-2 text-end">${parseFloat(interxCobrar).toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
               <td class="py-2 text-end"></td>
            </tr>`;
         // document.getElementById('saldoCarteraBody').innerHTML = SaldosHtml;
         // let cupo = document.getElementById('valCupo').value.replace(/\,/g, '');
         // cupo = parseFloat(cupo);
         // let cupoTMP = document.getElementById('valCupoTmp').value.replace(/\,/g, '');
         // cupoTMP = parseFloat(cupoTMP);
         // cupo = cupo + cupoTMP;
         cupo = cupo - valorCartera;
      }
      document.getElementById('valSaldo').value = valorCartera.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
      document.getElementById('valDisponible').value = cupo.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
      document.getElementById('valor_cheque').setAttribute('valMax', cupo);
      // document.getElementById('saldo').value = saldo.saldo;
   })
   .catch(error => {
      console.error('Error:', error);
   });
   const cupoDisp = parseFloat(clientData.valor_cupo);
   document.getElementById('valCupo').value = cupoDisp.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
   document.getElementById('valCupoTmp').value = parseFloat(clientData.valor_cupotemporal).toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
   loadCtasByClient(clientData.id_dvcliente);
   hideSuggestions();
}


//*********************************************************************************************
loadCtasByClient = async function(id_dvcliente) {
   var listWhere = [];
   listWhere.push({
      "id": "id_dvcliente",
      "value": id_dvcliente
   })
   listWhere.push({
      "id": "status",
      "value": '1'
   })
   listWhere = JSON.stringify(listWhere);
   const selectElement = document.getElementById('numero_cuenta');
   const formData = new FormData();
   formData.append("modulo", "dival");
   formData.append("option", "ctaclien");
   formData.append("action", "getWhere");
   formData.append("listWhere", listWhere);
   fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(response => response.json())
   .then(options => {
      if (options.length === 0) {
         selectElement.innerHTML = '<option value="" disabled></option>';
      } else {
         options.forEach(option => {
            const optionResponse = document.createElement('option');
            optionResponse.value = option.id_bancli;
            optionResponse.textContent = option.numero_cuenta + ' ' + option.BanNomNa + ' ' + option.sucursal;
            optionResponse.setAttribute('numero_cuenta', option.numero_cuenta);
            optionResponse.setAttribute('banco', option.BanNomNa);
            optionResponse.setAttribute('sucursal', option.sucursal);
            selectElement.appendChild(optionResponse);
         });
         selectElement.disabled = false;
      }
   })
   .catch(error => {
      console.error('Error:', error);
   });
   //await getSelects('dival', 'ctaclien', document.getElementById('numero_cuenta'), 'id_bancli', textOpt = ['numero_cuenta', 'BanNomNa', 'sucursal'], listWhere);
}


//*********************************************************************************************
function calcularDias() {
   //const fechaInicio = new Date(document.getElementById('fechaActual').value);
   let fechaInicio = new Date();
   if (document.getElementById('fechaActual')) {
      fechaInicio = new Date(document.getElementById('fechaActual').value);
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
   return dias;
}


//*********************************************************************************************
function calcularSubtotal() {
   let valor_cheque = document.getElementById('valor_cheque').value.replace(/\,/g, '');
   let impuesto_banco = document.getElementById('impuesto_banco').value.replace(/\,/g, '');
   valor_cheque = parseFloat(valor_cheque);
   const comision = parseFloat(document.getElementById('porcentaje_comision').value);
   let dias_cobrados = calcularDias();
   if (!dias_cobrados) {
      dias_cobrados = 0;      
   }
   let subtotal = valor_cheque * comision / 100 * dias_cobrados;
   let valIva = 0;
   if ($("#ivaIncluido").val() != "2") {
      valIva = subtotal - subtotal / ($("#valorIva").val() / 100+1);
   } else {
      valIva = subtotal * ($("#valorIva").val() / 100);
   }
   let valImptoBco = valor_cheque * impuesto_banco / 100;
   let valEntregar = 0;
   if ($("#ivaIncluido").val() != "2") {
      valEntregar = valor_cheque - subtotal - valImptoBco;
   } else {
      valEntregar = valor_cheque - subtotal - valImptoBco - valIva;
   }
   // subtotal = subtotal.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
   // document.getElementById('valComision').value = subtotal;
   document.getElementById('valComision').value = subtotal.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
   document.getElementById('valImptoBco').value = valImptoBco.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
   document.getElementById('valIVA').value = valIva.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
   document.getElementById('valEntregar').value = valEntregar.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
   //document.getElementById('valComision').value = subtotal.toFixed(2).toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
   // this.value = number.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
}


//*********************************************************************************************
function createAcounting(paramsList) {
   const acountingList = [];
   const CompteParam = paramsList.find(param => param.ParCodig === "CO1");
   const Compte = CompteParam.ParValor;
   if (Compte != "") {
      const clase = document.getElementById('clase').value;
      let AsiDescr = "Emisión de Letra";
      if (clase == 3) {
         AsiDescr = "Emisión de Pagaré";         
      }
      document.getElementById('compte').value = Compte;
      let asiDes = "";
      let asiCue = "";
      const ctaCliente = paramsList.find(param => param.ParCodig === "CU1").ParValor;
      acountingList.push({
         "CueCodig": ctaCliente,
         "AsiDescr": clase == "1" ? "Cambio de Cheque Número: " + document.getElementById('numero').value : AsiDescr,
         "AsiNatur": "D",
         "AsiValor": parseFloat(document.getElementById('valor_cheque').value.replace(/,/g, '')).toFixed(2),
         "AsiVBase": 0
      })
      const ctaCaja = paramsList.find(param => param.ParCodig === "CU2").ParValor;
      acountingList.push({
         "CueCodig": ctaCaja,
         "AsiDescr": clase == "1" ? "Valor Entregado por Cambio de Cheque Número: " + document.getElementById('numero').value : AsiDescr,
         "AsiNatur": "C",
         "AsiValor": parseFloat(document.getElementById('valEntregar').value.replace(/,/g, '')).toFixed(2),
         "AsiVBase": 0
      })
      if (parseFloat(document.getElementById('valComision').value.replace(/,/g, '')).toFixed(2) > 0) {
         if (document.getElementById('mensajeria1').checked) {
            asiDes = clase == "1" ? "Mensajería por Cambio de Cheque Número: " + document.getElementById('numero').value : AsiDescr,
            asiCue = "CU8"
         } else {
            asiDes = "Comisión por Cambio de Cheque Número: " + document.getElementById('numero').value;
            asiCue = "CU6"
         }
         const ctaMensa = paramsList.find(param => param.ParCodig === asiCue).ParValor;
         acountingList.push({
            "CueCodig": ctaMensa,
            "AsiDescr": clase == "1" ? asiDes : AsiDescr,
            "AsiNatur": "C",
            "AsiValor": parseFloat(document.getElementById('valComision').value.replace(/,/g, '')).toFixed(2),
            "AsiVBase": 0
         })
      }
      if (parseFloat(document.getElementById('valImptoBco').value.replace(/,/g, '')).toFixed(2) > 0) {
         const ctaImBco = paramsList.find(param => param.ParCodig === "CU4").ParValor;
         acountingList.push({
            "CueCodig": ctaImBco,
            "AsiDescr": clase == "1" ? "Impuesto Bancario por Cambio de Cheque Número: " + document.getElementById('numero').value : AsiDescr,
            "AsiNatur": "C",
            "AsiValor": parseFloat(document.getElementById('valImptoBco').value.replace(/,/g, '')).toFixed(2),
            "AsiVBase": 0
         })
      }
      if (parseFloat(document.getElementById('valIVA').value.replace(/,/g, '')).toFixed(2) > 0) {
         const ctaIVA = paramsList.find(param => param.ParCodig === "CU3").ParValor;
         acountingList.push({
            "CueCodig": ctaIVA,
            "AsiDescr": clase == "1" ? "IVA por Cambio de Cheque Número: " + document.getElementById('numero').value : AsiDescr,
            "AsiNatur": "C",
            "AsiValor": parseFloat(document.getElementById('valIVA').value.replace(/,/g, '')).toFixed(2),
            "AsiVBase": parseFloat(document.getElementById('valComision').value.replace(/,/g, '')).toFixed(2)
         })
      }
      document.getElementById('acountingList').value = JSON.stringify(acountingList);
   }
}