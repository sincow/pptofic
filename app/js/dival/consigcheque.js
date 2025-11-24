
//*********************************************************************************************
document.addEventListener("DOMContentLoaded", async function() {
   const paramsList = [];
   const formData = new FormData();
   formData.append("modulo", "admon");
   formData.append("option", "parametros");
   formData.append("action", "getAll");
   formData.append("modcodig", "21");
   const resp = await fetch('helpers/ajaxRouter.php', {
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


   //*********************************************************************************************
   const formDataDoc = new FormData();
   formDataDoc.append('modulo', 'dival');
   formDataDoc.append('option', 'cheques');
   formDataDoc.append('action', 'getPorConsig');
   fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formDataDoc
   })
   .then(response => response.json())
   .then(data => {
      cargarDocumentos(data);
   })
   .catch(error => {
      console.error('Error loading calendar config:', error);
   });


   document.addEventListener('change', function(e) {
      if (e.target.classList.contains('incluirDocum')) {
         calcularTotales();
      }
   });


   //*********************************************************************************************
   const documInclui = document.querySelectorAll('.incluirDocum');
   if (documInclui) {
      documInclui.forEach(element => {
         element.addEventListener('change', function(e) {
            e.preventDefault();
            calcularTotales()
         });
      });
   }


   //*********************************************************************************************
   const incluirDocum = document.getElementById('incluirDocum');
   if (incluirDocum) {
      incluirDocum.addEventListener('change', function(e) {
         e.preventDefault();
         if (this.checked) {
            document.querySelectorAll('.incluirDocum').forEach(element => {
               element.checked = true;
            })
         } else {
            document.querySelectorAll('.incluirDocum').forEach(element => {
               element.checked = false;
            })
         }
         calcularTotales()
      });
   }


   //*********************************************************************************************
   document.getElementById('consignaForm').addEventListener('submit', function(e) {
      e.preventDefault();
      e.stopPropagation();
      createAcounting(paramsList);
      createDocumConsig();
      //return;
      let isValid = "";
      let isRun = true;

      isValid = document.getElementById('BancoCodig').value !== '' && document.getElementById('BancoCodig').value !== null;
      document.getElementById('BancoCodig').nextElementSibling.classList.remove('is-invalid');
      document.getElementById('BancoCodig').nextElementSibling.classList.add(isValid ? 'is-valid' : 'is-invalid');
      document.getElementById('BancoCodig').nextElementSibling.classList.contains('is-invalid') ? document.getElementById('BancoCodig').focus() : "";
      !isValid ? isRun = false : "";

      if (document.getElementById('canConsig').value == 0) {
         isRun = false;
         alert("Debe seleccionar Cheques a Consignar");
      }
      if (!isRun) {
         document.getElementById('consignaForm').classList.remove('was-validated');
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


//*********************************************************************************************
function cargarDocumentos(data) {
   let tbody = document.getElementById("ConsigTable-body");
   tbody.innerHTML = "";
   let innerHtml = ``;
   if (data.length > 0) {
      data.forEach(element => {
         innerHtml += `
         <tr>
            <td class="text-start ps-0">${element.consecutivo}</td>
            <td>${element.TerNombr}</td>
            <td>${element.numero}</td>
            <td>${element.codigo}</td>
            <td>${element.fecha}</td>
            <td class="text-start">${element.UltVenci}</td>
            <td class="text-end">${formatCurrency(parseFloat(element.valor_cheque),0)}</td>
            <td class="text-start">
               <input type="checkbox" name="id_cheque[]" TerDocId="${element.TerDocId}" valor="${element.valor_cheque}" value="${element.id_cheque}" class="form-check-input incluirDocum" id="id_cheque${element.id_cheque}">
            </td>
         </tr>
         `; 
      });
   } else {
      innerHtml += `
         <tr>
            <td class="text-center ps-0" colspan="7">No hay Documentos para consignar el día de hoy</td>
         </tr>
      `; 
   }
   document.getElementById('ConsigTable-body').innerHTML = innerHtml;
   calcularTotales()
}


//*********************************************************************************************
function calcularTotales() {
   let total = 0;
   let canti = 0;
   let totalCan = 0;
   document.querySelectorAll('.incluirDocum').forEach(element => {
      totalCan++;
      if (element.checked) {
         total += parseFloat(element.getAttribute('valor'));
         canti++;
      }
   });
   if (totalCan == canti) {
      document.getElementById('incluirDocum').checked = true;
   }
   if (canti == 0 || totalCan != canti) {
      document.getElementById('incluirDocum').checked = false;
   }
   document.getElementById('totalesCondig').innerHTML = `Cantidad: ${canti} - Valor Total: $` + formatCurrency(total,0);
}


//*********************************************************************************************
function createDocumConsig() {
   const documList = [];
   let canDocum = 0;
   document.querySelectorAll('.incluirDocum').forEach(element => {
      if (element.checked) {
         canDocum++;
         documList.push({
            "id_cheque": element.value,
            "valor_cheque": element.getAttribute('valor')
         });
      }
   });
   document.getElementById('canConsig').value = canDocum;
   document.getElementById('documConsigList').value = JSON.stringify(documList);
}


//*********************************************************************************************
function createAcounting(paramsList) {
   const acountingList = [];
   let CompteParam = paramsList.find(param => param.ParCodig === "CO2");
   let Compte = CompteParam.ParValor;
   const CueClien = paramsList.find(param => param.ParCodig === "CU1").ParValor;
   const BancoCodig = document.getElementById("BancoCodig");
   const CueBanco = BancoCodig.options[BancoCodig.selectedIndex].getAttribute('data-cuecodig');
   const ConConsig = paramsList.find(param => param.ParCodig === "BA1").ParValor;
   document.getElementById('CompteBco').value = ConConsig;
   let AsiValor = 0;
   let TerDocId = 0;
   if (Compte != "") {
      document.querySelectorAll('.incluirDocum').forEach(element => {
         if (element.checked) {
            AsiValor = parseFloat(element.getAttribute('valor'));
            TerDocId = element.getAttribute('TerDocId');
            acountingList.push({
               "CueCodig": CueBanco,
               'TerDocId': 0,
               "CenCodig": "",
               "CenCodAu": "",
               "AsiDescr": "CONSIGNACION CHEQUES",
               "AsiNatur": 'D',
               "AsiValor": AsiValor,
               "AsiVBase": 0
            })
            acountingList.push({
               "CueCodig": CueClien,
               'TerDocId': TerDocId,
               "CenCodig": "",
               "CenCodAu": "",
               "AsiDescr": "CONSIGNACION CHEQUES",
               "AsiNatur": 'C',
               "AsiValor": AsiValor,
               "AsiVBase": 0
            })
         }
      });
      document.getElementById('compte').value = Compte;
      document.getElementById('acountingList').value = JSON.stringify(acountingList);
   }
}