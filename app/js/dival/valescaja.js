document.addEventListener("DOMContentLoaded", async function() {
   
   //*********************************************************************************************
   const cajaConsulForm = document.getElementById('cajaConsulForm');
   if (cajaConsulForm) {
      cargaMovCaja();
   }


   //*********************************************************************************************
   const paramsList = [];
   const formData = new FormData();
   formData.append("modulo", "admon");
   formData.append("option", "parametros");
   formData.append("action", "getAll");
   formData.append("modcodig", "21");
   const response = await fetch('helpers/ajaxRouter.php', {
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


   var listWhere = [];
   listWhere.push({
      "id": "TerEstad",
      "value": '1'
   })
   let selectQuery = 'terceroVale';
   let paramsQuery = [];
   paramsQuery.push({
      "modulo": "contabilidad",
      "option": "terceros",
      "action": "getByQuery",
      "listWhere": JSON.stringify(listWhere)
   })
   // if (document.getElementById('tipoDoc').value != '1') {
   if (document.getElementById('tipoDoc').value == '2' || document.getElementById('tipoDoc').value == '3') {
      initialSelect(selectQuery, paramsQuery);
   }
   // selectQuery = 'cuentaVale';
   // params = [];
   // params.push({
   //    "modulo": "contabilidad",
   //    "option": "cuentas",
   //    "action": "getByQuery",
   //    "listWhere": JSON.stringify(listWhere)
   // })
   // initialSelect(selectQuery, params);
   var listWhere = [];
   listWhere.push({
      "id": "CueMovim",
      "value": '1'
   });
   listWhere.push({
      "id": "CueEstad",
      "value": '1'
   });

   // if (document.getElementById('tipoDoc').value != '1') {
   if (document.getElementById('tipoDoc').value == '2' || document.getElementById('tipoDoc').value == '3') {
      const selectCuenta = document.getElementById('cuentaVale');
      getSelects('contabilidad', 'cuentas', selectCuenta, 'CueCodig', textOpt = ['CueCodig', 'CueNombr'], listWhere);
   }

   if (document.getElementById('tipoDoc').value == '1') {
      var listWhere = [];
      listWhere.push({
         "id": "BanEstad",
         "value": '1'
      });
      const selectElement = document.getElementById('BancoCodig');
      const value = 'BanCodig';
      const text = ['BanCodig', 'BanNombr'];
      listWhere = JSON.stringify(listWhere);
      const formDataTipDoc = new FormData();
      formDataTipDoc.append("modulo", 'bancos');
      formDataTipDoc.append("option", 'cuentas');
      formDataTipDoc.append("action", "getWhere");
      formDataTipDoc.append("listWhere", listWhere);
      const response = fetch('helpers/ajaxRouter.php', {
         method: 'POST',
         body: formDataTipDoc
      }).then(response => response.json())
      .then(options => {
         if (options.length === 0) {
            selectElement.innerHTML = '<option value="" disabled></option>';
         } else {
            options.forEach(option => {
               let optText = "";
               text.forEach(elemnt => {
                  optText += option[elemnt]+' ' || '';
               });
               const optionResponse = document.createElement('option');
               const attribute = document.createAttribute('data-cuecodig');
               attribute.value = option['CueCodig'];
               optionResponse.setAttributeNode(attribute);
               optionResponse.value = option[value];
               optionResponse.textContent = optText;
               // optionResponse.CueCodig = option['CueCodig'];
               selectElement.appendChild(optionResponse);
            });
            selectElement.disabled = false;
         }
      })
      .catch(error => {
         console.error('Error:', error);
      });
      let CompteBco = paramsList.find(param => param.ParCodig === "BA3");
      document.getElementById('CompteBco').value = CompteBco.ParValor;
      var listWhere = [];
      listWhere.push({
         "id": "ConCodig",
         "value": CompteBco.ParValor
      });
      listWhere = JSON.stringify(listWhere);
      const formDataEgreso = new FormData();
      formDataEgreso.append("modulo", 'bancos');
      formDataEgreso.append("option", 'tiposmovimiento');
      formDataEgreso.append("action", "getWhere");
      formDataEgreso.append("listWhere", listWhere);
      const responseEgreso = fetch('helpers/ajaxRouter.php', {
         method: 'POST',
         body: formDataEgreso
      }).then(response => response.json())
      .then(options => {
         if (options.length === 0) {
            document.getElementById('compte').value = "";
         } else {
            document.getElementById('compte').value = options[0]["ComCodig"];
         }
      })
      .catch(error => {
         console.error('Error:', error);
      });
   }


   //*********************************************************************************************
   document.getElementById('valeValor').addEventListener('input', function() {
      this.value = this.value.replace(/[^\d]/g, '');
   });
   document.getElementById('valeValor').addEventListener('blur', function() {
      if (this.value) {
         const number = parseInt(this.value);
         this.value = number.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
      }
   });
   document.getElementById('valeValor').addEventListener('focus', function() {
      this.value = this.value.replace(/\,/g, '');
   });


   //*********************************************************************************************
   document.getElementById('entrValor').addEventListener('input', function() {
      this.value = this.value.replace(/[^\d]/g, '');
   });
   document.getElementById('entrValor').addEventListener('blur', function() {
      if (this.value) {
         const number = parseInt(this.value);
         this.value = number.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
      }
   });
   document.getElementById('entrValor').addEventListener('focus', function() {
      this.value = this.value.replace(/\,/g, '');
   });


   //*********************************************************************************************
   document.getElementById('documcajaForm').addEventListener('submit', function(e) {
      e.preventDefault();
      e.stopPropagation();
      createAcounting(paramsList);
      //return;
      let isValid = "";
      let isRun = true;
      
      if (document.getElementById('tipoDoc').value == '3') {
         isValid = document.getElementById('valeValor').value != 0.00;
         document.getElementById('valeValor').classList.remove('is-invalid');
         document.getElementById('valeValor').classList.remove('is-valid');
         document.getElementById('valeValor').classList.add(isValid ? 'is-valid' : 'is-invalid');
         document.getElementById('valeValor').classList.contains('is-invalid') ? document.getElementById('valeValor').focus() : "";
         !isValid ? isRun = false : "";
      }
      
      if (document.getElementById('tipoDoc').value == '2' || document.getElementById('tipoDoc').value == '1') {
         isValid = document.getElementById('entrValor').value != 0.00;
         document.getElementById('entrValor').classList.remove('is-invalid');
         document.getElementById('entrValor').classList.remove('is-valid');
         document.getElementById('entrValor').classList.add(isValid ? 'is-valid' : 'is-invalid');
         document.getElementById('entrValor').classList.contains('is-invalid') ? document.getElementById('entrValor').focus() : "";
         !isValid ? isRun = false : "";
      }

      if (document.getElementById('tipoDoc').value != '1') {
         isValid = document.getElementById('cuentaVale').value != "" && document.getElementById('cuentaVale').value !== null;
         document.getElementById('cuentaVale').classList.remove('is-invalid');
         document.getElementById('cuentaVale').classList.add(isValid ? 'is-valid' : 'is-invalid');
         document.getElementById('cuentaVale').classList.contains('is-invalid') ? document.getElementById('cuentaVale').focus() : "";
         !isValid ? isRun = false : "";
   
         isValid = document.getElementById('terceroVale').value != "" && document.getElementById('terceroVale').value !== null;
         document.getElementById('terceroVale').nextElementSibling.classList.remove('is-invalid');
         document.getElementById('terceroVale').nextElementSibling.classList.add(isValid ? 'is-valid' : 'is-invalid');
         document.getElementById('terceroVale').nextElementSibling.classList.contains('is-invalid') ? document.getElementById('terceroVale').focus() : "";
         !isValid ? isRun = false : "";
      }

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


//*********************************************************************************************
function createAcounting(paramsList) {
   const acountingList = [];
   let CompteParam = paramsList.find(param => param.ParCodig === "CO6");
   if (document.getElementById('tipoDoc').value == '2') {
      CompteParam = paramsList.find(param => param.ParCodig === "CO7");
   }
   let Compte = CompteParam.ParValor;
   if (document.getElementById('tipoDoc').value == '1') {
      Compte = document.getElementById('compte').value;
   }
   const TerDocId = document.getElementById('terceroVale').value;
   if (Compte != "") {
      let AsiDescr = document.getElementById('valDetalle').value;
      let AsiValor = parseFloat(document.getElementById('valeValor').value.replace(/,/g, '')).toFixed(2);
      document.getElementById('compte').value = Compte;
      let CueCodig = "";
      if (document.getElementById('tipoDoc').value != '1') {
         CueCodig = document.getElementById('cuentaVale').value;
      } else {
         AsiDescr = "APORTE A CAJA DE BANCOS";
         var BancoCodig = document.getElementById("BancoCodig");
         CueCodig = BancoCodig.options[BancoCodig.selectedIndex].getAttribute('data-cuecodig');
         document.getElementById('cuentaVale').value = CueCodig;
         console.log(CueCodig);
      }
      let AsiNatur = 'D';
      if (document.getElementById('tipoDoc').value == '2' || document.getElementById('tipoDoc').value == '1') {
         AsiNatur = 'C';
         AsiValor = parseFloat(document.getElementById('entrValor').value.replace(/,/g, '')).toFixed(2);
      }
      acountingList.push({
         "CueCodig": CueCodig,
         "TerDocId": TerDocId,
         "CenCodig": "",
         "CenCodAu": "",
         "AsiDescr": AsiDescr,
         "AsiNatur": AsiNatur,
         "AsiValor": AsiValor,
         "AsiVBase": 0
      })
      CueCodig = paramsList.find(param => param.ParCodig === "CU2").ParValor;
      AsiNatur = 'C';
      if (document.getElementById('tipoDoc').value == '2' || document.getElementById('tipoDoc').value == '1') {
         AsiNatur = 'D';
      }
      acountingList.push({
         "CueCodig": CueCodig,
         "TerDocId": TerDocId,
         "CenCodig": "",
         "CenCodAu": "",
         "AsiDescr": AsiDescr,
         "AsiNatur": AsiNatur,
         "AsiValor": AsiValor,
         "AsiVBase": 0
      })
      document.getElementById('acountingList').value = JSON.stringify(acountingList);
   }
}


//*********************************************************************************************
function cargaMovCaja() {
   // Fetch initial data and populate the table
   const formData = new FormData();
   formData.append("modulo", "dival");
   formData.append("option", "cajas");
   formData.append("action", "filter");
   fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(response => response.json())
   .then(data => {
      const cajaTableBody = document.getElementById('cajaTable-body');
      cajaTableBody.innerHTML = ''; // Clear existing rows
      data["data"].forEach(funcionForEach);
      function funcionForEach(movimiento, index) {
         const valor = movimiento["valor_entrada"] != 0 ? parseFloat(movimiento["valor_entrada"]) : parseFloat(movimiento["valor_salida"]);
         let tipo = "";
         let colorTipo = ""
         switch (movimiento["tipo_movimiento"]) {
            case '1':
               tipo = "Aporte Caja";
               colorTipo = "green";
               break;
            case '2':
               tipo = "Ent Efectivo";
               colorTipo = "blue";
               break;
            case '3':
               tipo = "Vale Caja";
               colorTipo = "orange";
               break;
            default:
               break;
         }

         // data["data"].forEach(movimiento => {
         const row = document.createElement('tr');
         row.classList.add('hover-actions-trigger', 'btn-reveal-trigger', 'position-static');
         row.innerHTML = `
            <td class="id align-middle text-end white-space-nowrap pe-2">${movimiento["id_movimiento"]}</td>
            <td class="tipo    align-middle text-start ps-2" style="color:${colorTipo};">${tipo}</td>
            <td class="fecha   align-middle text-start ps-2">${movimiento["fecha"]}</td>
            <td class="valor   align-middle text-end pe-2">${formatCurrency(valor)}</td>
            <td class="cliente align-middle text-start ps-2">${movimiento["TerNombr"]}</td>
            <td class="banco   align-middle text-start ps-2">${movimiento["BanNombr"]}</td>
            <td class="cuenta  align-middle text-start ps-2">${movimiento["CueNombr"]}</td>
            <td class="descri  align-middle text-start ps-2">${movimiento["descripcion"]}</td>
         `;
            // <td class="status align-middle text-start ps-0">${movimiento["status"]}</td>
            // <td class="align-middle text-start pe-1">
            // <button class="btn btn-sm btn-primary view-movimiento" data-id="${movimiento.Id}" title="Ver Movimiento">
            //    <i class="fas fa-eye"></i>
            // </button>
            // </td>
         cajaTableBody.appendChild(row);
      };
      // Re-initialize any table plugins if necessary
   })
   .catch(error => {
      console.error('Error:', error);
   });
}