//*********************************************************************************************
document.addEventListener("DOMContentLoaded", function() {
   cargarCheques();

   var listWhere = [];
   listWhere.push({
      "id": "status",
      "value": '1'
   })
   let selectQuery = 'clienteSearch';
   let paramsQuery = [];
   paramsQuery.push({
      "modulo": "dival",
      "option": "clientes",
      "action": "getByQuery",
      "listWhere": JSON.stringify(listWhere)
   })
   // initialSelect(selectQuery, paramsQuery);


   //*********************************************************************************************
   const selectCuenta = document.getElementById('clienteSearch');
   getSelects('dival', 'clientes', selectCuenta, 'TerDocId', textOpt = ['TerDocId', 'TerNombr'], listWhere);
    $('#clienteSearch').select2({
      placeholder: "Seleccionar cliente",
      dropdownParent: $('#filterOffcanvas'),
      allowClear: true,
      width: '100%'
    });


   //*********************************************************************************************
   document.querySelector('#documentsTable tbody').addEventListener('click', async function(e) {
      e.preventDefault();
      const target = e.target.closest('.documentId');
      if (target) {
         const idDocument = target.getAttribute('iddoc');
         const modal =  new bootstrap.Modal(document.getElementById('modalChequeDetails'));
         getDocumentDetails(idDocument);
         /*
         const formData = new FormData();
         formData.append("modulo", "dival");
         formData.append("option", "cheques");
         formData.append("action", "getDetails");
         formData.append("idDocument", idDocument);
         const response = await fetch('helpers/ajaxRouter.php', {
            method: 'POST',
            body: formData
         }).then(resp => resp.json())
         .then( data => {
            let valCheque = 0;
            let aboCapital = 0;
            let sdoCapital = 0;

            let valInteres = 0;
            let aboInteres = 0;
            let sdoInteres = 0;

            let diasVencim = 0;
            let fechaInicio = new Date();
            let colorVencim = "";
            fechaInicio.setHours(0, 0, 0, 0);
            if (data['cheque']) {
               const datCliente = document.getElementById('datCliente');
               datCliente.innerHTML = '';
               const innerHtml = `
                  <div class="fw-bold fs--1">${data['cheque'].TerDocId} ${data['cheque'].TerNombr}</div>
                  <div class="fw-600 fs--1">${data['cheque'].TerDirec} Teléfono: ${data['cheque'].TerTele1}</div>
                  <small class="text-label fs-0">${data['cheque'].TerEmail}</small>
                  ${data['cheque'].nivel_riezgo ? `<br><small class="text-label fs--1 badge badge-phoenix badge-phoenix-${getRiskBadgeClass(data['cheque'].nivel_riezgo)}">N.R. ${data['cheque'].nivel_riezgo}</small>` : ''}
               `;
               document.getElementById('datCliente').innerHTML = innerHtml;

               const innerHtmlCta = `
                  <div class="fw-bold fs--1">Numero Cuenta: ${data['cheque'].numero_cuenta}</div>
                  <div class="fw-600 fs--1">${data['cheque'].banco_nombre}</div>
                  <small class="text-label">Sucursal: ${data['cheque'].sucursal}</small>
               `;
               document.getElementById('datCuenta').innerHTML = innerHtmlCta;
               const valDocument = data['cheque'].valor_cheque * 1;
               let tipDocument = data['cheque'].clase;
               let colDocument = 'success';
               switch (tipDocument) {
                  case '1':
                     tipDocument = 'CHEQUE';
                     break;
                  case '3':
                     tipDocument = 'PAGARÉ';
                     colDocument = 'warning';
                     break;
                  case '5':
                     tipDocument = 'LETRA';
                     colDocument = 'info';
                     break;
                  default:
                     break;
               }
               valCheque  = parseFloat(data['cheque'].valor_cheque);
               aboCapital = parseFloat(data['cheque'].capital_pagado);
               sdoCapital = data['cheque'].valor_cheque - data['cheque'].capital_pagado;

               valInteres = parseFloat(data['cheque'].intereses_cobrados);
               aboInteres = parseFloat(data['cheque'].intereses_pagados);

               valImpBco = data['cheque'].valor_cheque * data['cheque'].impuesto_banco / 100;

               sdoInteres = data['cheque'].intereses_cobrados - data['cheque'].intereses_pagados;
               const fechaFin = new Date(data['cheque'].UltVenci);
               let diasShow = "";
               fechaFin.setHours(0, 0, 0, 0);
               const diferencia = fechaFin.getTime() - fechaInicio.getTime();
               const dias = Math.ceil(diferencia / (1000 * 60 * 60 * 24)) +1;
               if (dias < 0) {
                  colorVencim = "red";
                  diasShow = ' ->  '+ (dias * -1) + " Días";
               } else if (dias < 2) {
                  colorVencim = "orange";
               }

               let status = 'PENDIENTE';
               let colStatus = 'info';
               switch (data['cheque'].status) {
                  case '1':
                     break;
                  case 'D':
                     status = 'DEVUELTO';
                     colStatus = 'danger';
                     break;
                  case 'C':
                     status = 'CONSIGNADO';
                     colStatus = 'success';
                     break;
                  default:
                     break;
               }
               if (parseFloat(data['cheque'].valor_cheque) <= parseFloat(data['cheque'].capital_pagado)) {
                  colStatus = 'secondary';
                  status = 'LIQUIDADO';                  
               }
                  // <small class="text-label fs-0">Fec. Documento:   ${data['cheque'].fecha} </small><br>
                  // <small class="text-label fs-0" style="color:${colorVencim};">Fec. Vencimiento: ${data['cheque'].UltVenci}   ${diasShow}</small><br>

                  // <div class="fw-bold fs--1">
                  //    <p class="fw-bold mb-0 fs-0 badge badge-phoenix badge-phoenix-${colDocument}" >${tipDocument}</p>
                  // </div>

               const innerHtmlDoc = `
                  <div class="row">
                     <div class="col-6 text-label fs--0">
                        <p class="fw-bold mb-0 fs-0 badge badge-phoenix badge-phoenix-${colDocument}" >${tipDocument}</p>
                     </div>
                     <div class="col-6 fs--1 text-end">
                        <p class="fw-bold mb-0 fs--1 badge badge-phoenix badge-phoenix-${colStatus}" >${status}</p>
                     </div>
                  </div>

                  <div class="fw-bold fs-0">Nro Documento: ${data['cheque'].numero}</div>
                  <div class="row">
                     <div class="col-5 text-label fs--1">Fecha Documento:</div>
                     <div class="col-7 fs--1 text-start">${data['cheque'].fecha}</div>
                  </div>
                  <div class="row">
                     <div class="col-5 text-label fs--1">Fecha Vencimiento:</div>
                     <div class="col-7 fs--1 text-start">${data['cheque'].vencimiento}</div>
                  </div>
                  <div class="row mb-2">
                     <div class="col-5 text-label fs--1">Ultimo Vencimiento:</div>
                     <div class="col-7 fs--1 text-start" style="color:${colorVencim};">${data['cheque'].UltVenci} ${diasShow}</div>
                  </div>

                  <div class="row pt-2 border-top">
                     <div class="col-5 text-label fs--1">Valor Documento:</div>
                     <div class="col-4 col-lg-3 p-0 fs--1 text-end">${formatCurrency(valDocument,0)}</div>
                  </div>
                  <div class="row">
                     <div class="col-5 text-label fs--1">Aboono Capital:</div>
                     <div class="col-4 col-lg-3 p-0 fs--1 text-end">${formatCurrency(aboCapital,0)}</div>
                  </div>
                  <div class="row mb-2">
                     <div class="col-5 text-label fs--1">Saldo Capital:</div>
                     <div class="col-4 col-lg-3 p-0 fs--1 text-end">${formatCurrency(sdoCapital,0)}</div>
                  </div>

                  <div class="row pt-2 border-top">
                     <div class="col-5 text-label fs--1">Interes Cobrado:</div>
                     <div class="col-4 col-lg-3 p-0 fs--1 text-end">${formatCurrency(valInteres,0)}</div>
                  </div>
                  <div class="row">
                     <div class="col-5 text-label fs--1">Interes Pagado:</div>
                     <div class="col-4 col-lg-3 p-0 fs--1 text-end">${formatCurrency(aboInteres,0)}</div>
                  </div>
                  <div class="row mb-2">
                     <div class="col-5 text-label fs--1">Saldo Interes:</div>
                     <div class="col-4 col-lg-3 p-0 fs--1 text-end">${formatCurrency(sdoInteres,0)}</div>
                  </div>

                  <div class="row pt-2 border-top">
                     <div class="col-5 text-label fs--1">Valor Comisión:</div>
                     <div class="col-4 col-lg-3 p-0 fs--1 text-end">${formatCurrency(parseFloat(data['cheque'].comision),0)}</div>
                     <div class="col-9 col-lg-4 ps-0 fs--1 text-end">${data['cheque'].porcentaje_comision} %</div>
                  </div>
                  <div class="row">
                     <div class="col-5 text-label fs--1">Valor IVA:</div>
                     <div class="col-4 col-lg-3 p-0 fs--1 text-end">${formatCurrency(parseFloat(data['cheque'].valor_iva),0)}</div>
                  </div>
                  <div class="row">
                     <div class="col-5 text-label fs--1">Valor Imp Banco:</div>
                     <div class="col-4 col-lg-3 p-0 fs--1 text-end">${formatCurrency(valImpBco,0)}</div>
                  </div>

               `;

               document.getElementById('datDocument').innerHTML = innerHtmlDoc;

               const innerHtmlObs = `
                  <div class="fw-600 fs--1">${data['cheque'].observacion == null ? 'Sin Observaciones' : data['cheque'].observacion}</div>
               `;
               document.getElementById('observacion').innerHTML = innerHtmlObs;

               if (data["aplaza"].length > 0 ) {
                  let innerHtml = ``;
                  data["aplaza"].forEach(aplaza => {
                     var status = "ACTIVO";
                     if (aplaza.status != '1') {
                        status = "ANULADO";
                     }
                     innerHtml += `
                     <tr>
                        <td class="text-end pe-2">${aplaza.id_aplaza}</td>
                        <td>${aplaza.fecha}</td>
                        <td class="text-end">${aplaza.dias_cobrar}</td>
                        <td class="text-end">${formatCurrency(parseFloat(aplaza.valor_aplaza),0)}</td>
                        <td class="text-end">${aplaza.intereses}</td>
                        <td class="text-end">${formatCurrency(parseFloat(aplaza.valor_interes),0)}</td>
                        <td>${aplaza.motivo}</td>
                        <td>${status}</td>
                     </tr>
                     `;
                  });
                  document.getElementById('aplazaTable-body').innerHTML = innerHtml;
               }

               if (data["devolucion"].length > 0 ) {
                  let innerHtml = ``;
                  data["devolucion"].forEach(devolucion => {
                     var status = "ACTIVO";
                     if (devolucion.status != '1') {
                        status = "ANULADO";
                     }
                     innerHtml += `
                        <tr>
                           <td class="text-end pe-2">${devolucion.id_devolu}</td>
                           <td>${devolucion.fecha}</td>
                           <td>${devolucion.motivo}</td>
                           <td>${status}</td>
                        </tr>
                     `;
                  });
                  document.getElementById('devolTable-body').innerHTML = innerHtml;
               }
            } else {
               swal.fire({
                  title: 'Error',
                  text: 'No se encontraron detalles',
                  icon: 'error',
                  confirmButtonText: 'Aceptar'
               })
            }
         });
         */

         modal.show();
         // const bsModal = new bootstrap.Modal(modal);
      }
   });


   //*********************************************************************************************
   let fechaMinima = new Date();
   const dateFormat = document.getElementById('dateFormat').value || 'yyyy-mm-dd';
   $('.fecVencimSearch').datepicker({
      format: dateFormat,
      // format: 'yyyy-mm-dd',
      // minDate: new Date(),
      // startDate: '-0m',
      minDate: 0,
      // min: '2025-11-20',
      autoclose: true,
      todayBtn: true,
      language: 'es',
      todayHighlight: true,
      orientation: "bottom auto"
   });
   document.getElementById('fecVencimSearch').setAttribute('min', fechaMinima.toISOString().split('T')[0]);

});


//*********************************************************************************************
async function getDocumentDetails(idDocument) {
   const formData = new FormData();
   formData.append("modulo", "dival");
   formData.append("option", "cheques");
   formData.append("action", "getDetails");
   formData.append("idDocument", idDocument);
   const response = await fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(resp => resp.json())
   .then( data => {
      let valCheque = 0;
      let aboCapital = 0;
      let sdoCapital = 0;

      let valInteres = 0;
      let aboInteres = 0;
      let sdoInteres = 0;

      let diasVencim = 0;
      let fechaInicio = new Date();
      let colorVencim = "";
      fechaInicio.setHours(0, 0, 0, 0);
      if (data['cheque']) {
         const datCliente = document.getElementById('datCliente');
         datCliente.innerHTML = '';
         const innerHtml = `
            <div class="fw-bold fs--1">${data['cheque'].TerDocId} ${data['cheque'].TerNombr}</div>
            <div class="fw-600 fs--1">${data['cheque'].TerDirec} Teléfono: ${data['cheque'].TerTele1}</div>
            <small class="text-label fs-0">${data['cheque'].TerEmail}</small>
            ${data['cheque'].nivel_riezgo ? `<br><small class="text-label fs--1 badge badge-phoenix badge-phoenix-${getRiskBadgeClass(data['cheque'].nivel_riezgo)}">N.R. ${data['cheque'].nivel_riezgo}</small>` : ''}
         `;
         document.getElementById('datCliente').innerHTML = innerHtml;

         const innerHtmlCta = `
            <div class="fw-bold fs--1">Numero Cuenta: ${data['cheque'].numero_cuenta}</div>
            <div class="fw-600 fs--1">${data['cheque'].banco_nombre}</div>
            <small class="text-label">Sucursal: ${data['cheque'].sucursal}</small>
         `;
         document.getElementById('datCuenta').innerHTML = innerHtmlCta;
         const valDocument = data['cheque'].valor_cheque * 1;
         let tipDocument = data['cheque'].clase;
         let colDocument = 'success';
         switch (tipDocument) {
            case '1':
               tipDocument = 'CHEQUE';
               break;
            case '3':
               tipDocument = 'PAGARÉ';
               colDocument = 'warning';
               break;
            case '5':
               tipDocument = 'LETRA';
               colDocument = 'info';
               break;
            default:
               break;
         }
         valCheque  = parseFloat(data['cheque'].valor_cheque);
         aboCapital = parseFloat(data['cheque'].capital_pagado);
         sdoCapital = data['cheque'].valor_cheque - data['cheque'].capital_pagado;

         valInteres = parseFloat(data['cheque'].intereses_cobrados);
         aboInteres = parseFloat(data['cheque'].intereses_pagados);

         valImpBco = data['cheque'].valor_cheque * data['cheque'].impuesto_banco / 100;

         sdoInteres = data['cheque'].intereses_cobrados - data['cheque'].intereses_pagados;
         const fechaFin = new Date(data['cheque'].UltVenci);
         let diasShow = "";
         fechaFin.setHours(0, 0, 0, 0);
         const diferencia = fechaFin.getTime() - fechaInicio.getTime();
         const dias = Math.ceil(diferencia / (1000 * 60 * 60 * 24)) +1;
         if (dias < 0) {
            colorVencim = "red";
            diasShow = ' ->  '+ (dias * -1) + " Días";
         } else if (dias < 2) {
            colorVencim = "orange";
         }

         let status = 'PENDIENTE';
         let colStatus = 'info';
         switch (data['cheque'].status) {
            case '1':
               break;
            case 'D':
               status = 'DEVUELTO';
               colStatus = 'danger';
               break;
            case 'C':
               status = 'CONSIGNADO';
               colStatus = 'success';
               break;
            default:
               break;
         }
         if (parseFloat(data['cheque'].valor_cheque) <= parseFloat(data['cheque'].capital_pagado)) {
            colStatus = 'secondary';
            status = 'LIQUIDADO';                  
         }
            // <small class="text-label fs-0">Fec. Documento:   ${data['cheque'].fecha} </small><br>
            // <small class="text-label fs-0" style="color:${colorVencim};">Fec. Vencimiento: ${data['cheque'].UltVenci}   ${diasShow}</small><br>

            // <div class="fw-bold fs--1">
            //    <p class="fw-bold mb-0 fs-0 badge badge-phoenix badge-phoenix-${colDocument}" >${tipDocument}</p>
            // </div>

         const innerHtmlDoc = `
            <div class="row">
               <div class="col-6 text-label fs--0">
                  <p class="fw-bold mb-0 fs-0 badge badge-phoenix badge-phoenix-${colDocument}" >${tipDocument}</p>
               </div>
               <div class="col-6 fs--1 text-end">
                  <p class="fw-bold mb-0 fs--1 badge badge-phoenix badge-phoenix-${colStatus}" >${status}</p>
               </div>
            </div>

            <div class="fw-bold fs-0">Nro Documento: ${data['cheque'].numero}</div>
            <div class="row">
               <div class="col-5 text-label fs--1">Fecha Documento:</div>
               <div class="col-7 fs--1 text-start">${data['cheque'].fecha}</div>
            </div>
            <div class="row">
               <div class="col-5 text-label fs--1">Fecha Vencimiento:</div>
               <div class="col-7 fs--1 text-start">${data['cheque'].vencimiento}</div>
            </div>
            <div class="row mb-2">
               <div class="col-5 text-label fs--1">Ultimo Vencimiento:</div>
               <div class="col-7 fs--1 text-start" style="color:${colorVencim};">${data['cheque'].UltVenci} ${diasShow}</div>
            </div>

            <div class="row pt-2 border-top">
               <div class="col-5 text-label fs--1">Valor Documento:</div>
               <div class="col-4 col-lg-3 p-0 fs--1 text-end">${formatCurrency(valDocument,0)}</div>
            </div>
            <div class="row">
               <div class="col-5 text-label fs--1">Aboono Capital:</div>
               <div class="col-4 col-lg-3 p-0 fs--1 text-end">${formatCurrency(aboCapital,0)}</div>
            </div>
            <div class="row mb-2">
               <div class="col-5 text-label fs--1">Saldo Capital:</div>
               <div class="col-4 col-lg-3 p-0 fs--1 text-end">${formatCurrency(sdoCapital,0)}</div>
            </div>

            <div class="row pt-2 border-top">
               <div class="col-5 text-label fs--1">Interes Cobrado:</div>
               <div class="col-4 col-lg-3 p-0 fs--1 text-end">${formatCurrency(valInteres,0)}</div>
            </div>
            <div class="row">
               <div class="col-5 text-label fs--1">Interes Pagado:</div>
               <div class="col-4 col-lg-3 p-0 fs--1 text-end">${formatCurrency(aboInteres,0)}</div>
            </div>
            <div class="row mb-2">
               <div class="col-5 text-label fs--1">Saldo Interes:</div>
               <div class="col-4 col-lg-3 p-0 fs--1 text-end">${formatCurrency(sdoInteres,0)}</div>
            </div>

            <div class="row pt-2 border-top">
               <div class="col-5 text-label fs--1">Valor Comisión:</div>
               <div class="col-4 col-lg-3 p-0 fs--1 text-end">${formatCurrency(parseFloat(data['cheque'].comision),0)}</div>
               <div class="col-9 col-lg-4 ps-0 fs--1 text-end">${data['cheque'].porcentaje_comision} %</div>
            </div>
            <div class="row">
               <div class="col-5 text-label fs--1">Valor IVA:</div>
               <div class="col-4 col-lg-3 p-0 fs--1 text-end">${formatCurrency(parseFloat(data['cheque'].valor_iva),0)}</div>
            </div>
            <div class="row">
               <div class="col-5 text-label fs--1">Valor Imp Banco:</div>
               <div class="col-4 col-lg-3 p-0 fs--1 text-end">${formatCurrency(valImpBco,0)}</div>
            </div>

         `;

         document.getElementById('datDocument').innerHTML = innerHtmlDoc;

         const innerHtmlObs = `
            <div class="fw-600 fs--1">${data['cheque'].observacion == null ? 'Sin Observaciones' : data['cheque'].observacion}</div>
         `;
         document.getElementById('observacion').innerHTML = innerHtmlObs;

         if (data["aplaza"].length > 0 ) {
            let innerHtml = ``;
            data["aplaza"].forEach(aplaza => {
               var status = "ACTIVO";
               if (aplaza.status != '1') {
                  status = "ANULADO";
               }
               innerHtml += `
               <tr>
                  <td class="text-end pe-2">${aplaza.id_aplaza}</td>
                  <td>${aplaza.fecha}</td>
                  <td class="text-end">${aplaza.dias_cobrar}</td>
                  <td class="text-end">${formatCurrency(parseFloat(aplaza.valor_aplaza),0)}</td>
                  <td class="text-end">${aplaza.intereses}</td>
                  <td class="text-end">${formatCurrency(parseFloat(aplaza.valor_interes),0)}</td>
                  <td>${aplaza.motivo}</td>
                  <td>${status}</td>
               </tr>
               `;
            });
            document.getElementById('aplazaTable-body').innerHTML = innerHtml;
         }

         if (data["devolucion"].length > 0 ) {
            let innerHtml = ``;
            data["devolucion"].forEach(devolucion => {
               var status = "ACTIVO";
               if (devolucion.status != '1') {
                  status = "ANULADO";
               }
               innerHtml += `
                  <tr>
                     <td class="text-end pe-2">${devolucion.id_devolu}</td>
                     <td>${devolucion.fecha}</td>
                     <td>${devolucion.motivo}</td>
                     <td>${status}</td>
                  </tr>
               `;
            });
            document.getElementById('devolTable-body').innerHTML = innerHtml;
         }
      } else {
         swal.fire({
            title: 'Error',
            text: 'No se encontraron detalles',
            icon: 'error',
            confirmButtonText: 'Aceptar'
         })
      }

   })
   .catch(error => {
      console.error('Error:', error);
   });
}


//*********************************************************************************************
async function cargarCheques() {
   const formData = new FormData();
   formData.append("modulo", "dival");
   formData.append("option", "cheques");
   formData.append("action", "filter");
   const response = await fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(resp => resp.json())
   .then( data => {
      // const tbody = document.getElementById('chequesTable-body');
      // tbody.innerHTML = '';
      cargarChequesDetall(data);
      let dataArray = [];
      dataArray.push({
         "data":  data,
         "applied_filters": []
      });

      if (data.length > 0) {
         /*
         data.forEach(banco => {
            const row = document.createElement('tr');
            // Columna ID
            const tdId = document.createElement('td');
            tdId.className = 'align-middle text-660 ps-2 py-1 id';
            tdId.setAttribute('id', banco.id_banco);
            tdId.textContent = banco.codigo;
            row.appendChild(tdId);
            
            // Columna name
            const tdName = document.createElement('td');
            tdName.className = 'align-middle text-660 ps-2 py-1 name';
            tdName.textContent = banco.nombre;
            row.appendChild(tdName);
            
            tbody.appendChild(row);
         });
         */
      }
   });

}


//*********************************************************************************************
function cargarChequesDetall(data) {
   allCheques = data["data"];
   const tbody = document.getElementById('chequesTable-body');
   tbody.innerHTML = '';
   if (data["data"].length > 0) {
      let valCheque = 0;
      let valInteres = 0;
      let sdoCapital = 0;
      let sdoInteres = 0;
      let diasVencim = 0;
      let fechaInicio = new Date();
      let colorVencim = "";
      fechaInicio.setHours(0, 0, 0, 0);
      data["data"].forEach(funcionForEach);
      function funcionForEach(item, index) {
         colorVencim = "green";
         let permiRec = '<a class="dropdown-item poRectId" idpoRec=' + item["po_id"] + '>Receive</a>';
         permiRec = '';
         valCheque = parseFloat(item["valor_cheque"]);
         valInteres = parseFloat(item["intereses_cobrados"]);
         sdoCapital = parseFloat(item["valor_cheque"] - item["capital_pagado"]);
         sdoInteres = parseFloat(item["intereses_cobrados"] - item["intereses_pagados"]);
         const fechaFin = new Date(item['UltVenci']);
         let diasShow = "";
         fechaFin.setHours(0, 0, 0, 0);
         const diferencia = fechaFin.getTime() - fechaInicio.getTime();
         const dias = Math.ceil(diferencia / (1000 * 60 * 60 * 24)) +1;
         if (dias < 0) {
            colorVencim = "red";
            diasShow = ' ->  '+ (dias * -1) + " Días";
         } else if (dias < 2) {
            colorVencim = "orange";
         }
         let status = 'PENDIENTE';
         switch (item["status"]) {
            case '1':
               break;
            case 'D':
               status = 'DEVUELTO';
               break;
            case 'C':
               status = 'CONSIGNADO';
               break;
            default:
               break;
         }
         let row = `
            <tr class="hover-actions-trigger btn-reveal-trigger position-static">
               <td class="id align-middle text-end white-space-nowrap py-1 pe-3">
                  <a class="fw-semi-bold documentId" name="idDocument" idDoc='${item["id_cheque"]}'>${item["id_cheque"]}</a>
               </td>
               <td class="numero  align-middle white-space-nowrap text-uppercase py-1 ps-0">${item["numero"]}</td>
               <td class="cliente align-middle white-space-nowrap text-uppercase py-1 ps-0">${item["TerNombr"]}</td>
               <td class="fecha   align-middle white-space-nowrap text-start py-1 ps-0">${formatDate(item["fecha"])}</td>
               <td class="vencin  align-middle white-space-nowrap text-start py-1 ps-0" style="color:${colorVencim};" >${formatDate(item["UltVenci"])}  ${diasShow}</td>
               <td class="valor   align-middle white-space-nowrap text-end py-1 pe-2">${formatCurrency(valCheque,0)}</td>
               <td class="saldo   align-middle white-space-nowrap text-end py-1 pe-2">${formatCurrency(sdoCapital,0)}</td>
               <td class="interes align-middle text-end fw-semi-bold text-1000 py-1 pe-2">${formatCurrency(valInteres,0)}</td>
               <td class="sdoint  align-middle text-end fw-semi-bold text-1000 py-1 pe-2">${formatCurrency(sdoInteres,0)}</td>
               <td class="status  align-middle white-space-nowrap text-start py-1 ps-1 fw-bold text-700"><span class="badge badge-phoenix fs--2 badge-phoenix-${getStatusBadgeClass(item["status"])}"><span class="badge-label">${status}</span><span class="ms-1" data-feather="${getStatusBadgeIcon(item["status"])}" style="height:12.8px;width:12.8px;"></span></span></td>
               <td>`;
         if (permiRec != "") {
            row += `
               <div class="font-sans-serif btn-reveal-trigger position-static">
                  <button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2 py-0" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent">
                     <span class="fas fa-ellipsis-h fs--2"></span>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end py-2">${permiRec}</div>
               </div>`;
         }
         row += `
               </td>
            </tr>
         `;
         $('#chequesTable-body').append(row);
         //tbody.appendChild(row);
      }
   } else {
      $('#chequesTable-body').empty();
      $('#chequesTable-body').append(`
         <tr>
            <td class="align-middle text-center" colspan="10">
               <div class="empty">
                  <div class="empty-img"><span class="fas fa-file-invoice-dollar"></span></div>
                  <p class="mb-0">No hay registros para mostrar</p>
               </div>
            </td>
         </tr>
      `);
   }
   updateListJS();
   // setTimeout(() => {
   //    if (window.docTableList) {
   //       window.docTableList.update();
   //       window.docTableList.reIndex();
   //       window.docTableList.sort('expected', { order: 'asc' });
   //       $('[data-list-info]').text(`${window.docTableList.visibleItems.length} to ${window.docTableList.items.length} Items`);
   //       window.docTableList.fuzzySearch('');
   //    }
   // }, 100);
   displayActiveFilters(data["applied_filters"]);
}


//*********************************************************************************************
function updateListJS() {
   if (!window.docTableList) {
      window.docTableList = null;
   }
   window.docTableList = new List('chequesTable', {
      valueNames: ["id","numero","cliente","fecha","vencim","valor","saldo","interes","sdoint","status"],
      page: 20,
      pagination: true,
      // pagination: {
      //    innerWindow: 1,
      //    outerWindow: 1,
      //    left: 0,
      //    right: 0
      // },
      indexAsync: true
   });
   window.docTableList.sort('vencim', { order: 'asc' });
   window.docTableList.fuzzySearch('');
   setupPaginationEvents(window.docTableList, 20);
   window.docTableList.on('updated', function() {
      updatePaginationInfo(window.docTableList);
   });
   updatePaginationInfo(window.docTableList);
}


//*********************************************************************************************
const formulario = document.getElementById("frmDocumentsFilter");
if (formulario) {
   formulario.addEventListener("submit", async function (e) {
      e.preventDefault();
      const csrfToken = document.querySelector('meta[name="pyt"]').getAttribute('content');
      //$("#poExpectedSearchLabel").text($("#poToDateSearch").val());
      const form = $(this).closest('form')[0];
      const formData = new FormData(form);
      const formValues = Object.fromEntries(formData.entries());
      let allDocuments = [];
      $('#po-table-body').empty();
      const response = await fetch('helpers/ajaxRouter.php', {
         method: 'POST',
         headers: {
            // 'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken  // Envía el token en el header
         },
         body: formData
      }).then(resp => { 
         return resp.json();
      }).then( data => {
         if (data.length > 0) {
            allDocuments = data;
         }
         cargarChequesDetall(data);
      });
   });
}


//*********************************************************************************************
function formatDate(dateString) {
   if (!dateString) return 'N/A';
   const [year, month, day] = dateString.split('-');
   if (!year || !month || !day) return 'Formato inválido';
   const dateFormat = $("#dateFormat").val() || 'mm-dd-yyyy';
   switch(dateFormat) {
      case 'dd-mm-yyyy': return `${day}-${month}-${year}`;
      case 'mm-dd-yyyy': return `${month}-${day}-${year}`;
      case 'yyyy-mm-dd': return `${year}-${month}-${day}`;
      default: return `${month}-${day}-${year}`;
   }
}


//*********************************************************************************************
function displayActiveFilters(filters) {
   const activeFiltersContainer = $('#activeFilters');
   activeFiltersContainer.empty();
   // var statusNames = [{
   //    id: '',
   //    name: 'Todos'
   // }];
   var statusNames = [];
   //var statusOptions = $('#statusSearch').find('option');
   $('#statusSearch').find('option').each(function() {
      statusText = $(this).text();
      statusNames.push({
         id: $(this).val(),
         name: statusText
      });
   });
   var clientesNames = [];
   //var clientesOptions = $('#clienteSearch').find('option');
   $('#clienteSearch').find('option').each(function() {
      clientesText = $(this).text();
      clientesNames.push({
         id: $(this).val(),
         name: clientesText
      });
   });
   var companyName = [];
   //var companyOptions = $('#poCompanySearch').find('option');
   $('#poCompanySearch').find('option').each(function() {
      companyText = $(this).text();
      companyName.push(
         companyText
      );
   });
   var vendorNames = [];
   //var vendorOptions = $('#poVendorSearch').find('option');
   $('#clienteSearch').find('option').each(function() {
      vendirText = $(this).text();
      vendorNames.push(
         vendirText
      );
   });
   // Mapeo de nombres de campos a etiquetas legibles
   const fieldLabels = {
      'empresaSearch': 'Empresa',
      'numberSearch': 'Nro Docum',
      'statusSearch': 'Estado',
      'fecCambioSearchFrom': 'Creado Desde',
      'fecCambioSearchTo': 'Creado Hasta',
      'fecVencimSearch': 'Por Vencerse',
      'clienteSearch': 'Cliente',
      'poVendorSearch': 'Vendor',
      'poFromExpectedSearch': 'Expected From',
      'poToExpectedSearch': 'Expected To',
      'minCostSearch': 'Saldo Desde',
      'maxCostSearch': 'Saldo Hasta'
   };
   const fieldLabels2 = {
      'po_number': 'PO Number',
      'client_id': 'Company',
      'vendor_id': 'Vendor',
      'date_from': 'Order From',
      'date_to': 'Order To',
      'expected_from': 'Expected From',
      'expected_to': 'Expected To',
      'status': 'Status',
      'min_amount': 'Min Cost',
      'max_amount': 'Max Cost'
   };
   for (const [field, value] of Object.entries(filters)) {
      // Solo mostrar filtros con valores
      if (value && value !== '' && value !== '0' && value != 'purchases' && value != 'filter' && field != 'empresaSearch') {
         let displayValue = value;
         // Formatear valores especiales
         if (field === 'poCompanySearch') {
            displayValue = companyName[value] || `Company #${value}`;
         } else if (field === 'clienteSearch') {
            // displayValue = vendorNames[value] || `${value}`;
            // displayValue = clientesNames.find(status => status.id === value).name;
         } else if (field == 'minCostSearch' || field == 'maxCostSearch') {
            displayValue = `$${formatCurrency(parseFloat(value),0)}`;
         } else if (field == 'statusSearch') {
            displayValue = statusNames.find(status => status.id === value).name;
         }
         const badge = $(`
            <span class="badge badge-phoenix badge-phoenix-info border me-1 mb-0">
               <span class="fw-bold">${fieldLabels[field] || field}:</span>
               ${displayValue}
               <button class="btn-close btn-close-black btn-sm ms-1 remove-filter" 
                  data-field="${field}" 
                  aria-label="Remove filter">
               </button>
            </span>
         `);
         activeFiltersContainer.append(badge);
      }
   }
   // Agregar evento para remover filtros
   $('body').on('click', '.remove-filter', function(e) {
      // 1. Prevenir el comportamiento por defecto y la propagación
      e.preventDefault();
      e.stopPropagation();
      e.stopImmediatePropagation();
      // 2. Obtener el campo a limpiar
      const field = $(this).data('field');      
      // 3. Limpiar el campo en el formulario CORRECTO (#frmDocumentsFilter)
      $(`#frmDocumentsFilter #${field}`).val('').trigger('change');
      // 4. Si es un Select2, resetearlo correctamente
      if ($(`#frmDocumentsFilter #${field}`).hasClass('select2-hidden-accessible')) {
         $(`#frmDocumentsFilter #${field}`).val(null).trigger('change');
      }
      // 5. Disparar el evento de filtrado en el botón CORRECTO
      $('#btnFilterPurchases').trigger('click');
      // 6. Opcional: Eliminar el badge del filtro
      $(this).closest('.badge').fadeOut(300, function() {
         $(this).remove();
      });
   });
}


//*********************************************************************************************
function getStatusBadgeClass(status) {
   const classes = {
      'pending': 'secondary',
      'saved': 'primary',
      'ordered': 'info',
      '1': 'info',
      'c': 'success',
      'd': 'danger'
   };
   return classes[status.toLowerCase()] || 'secondary';
}


//*********************************************************************************************
function getStatusBadgeIcon(status) {
   const icons = {
      '1': 'clock',
      'saved': 'check',
      'ordered': 'check',
      'received': 'check',
      'completed': 'check',
      'D': 'x'
   };
   return icons[status.toLowerCase()] || 'clock';
}


//*********************************************************************************************
function setupPaginationEvents2() {
   const prevBtn = document.querySelector('[data-list-pagination="prev"]');
   const nextBtn = document.querySelector('[data-list-pagination="next"]');
   const viewAllLink = document.querySelector('[data-list-view="*"]');
   const viewLessLink = document.querySelector('[data-list-view="less"]');

   if (prevBtn) {
      prevBtn.addEventListener('click', (e) => {
         e.preventDefault();
         if (window.docTableList) {
            let currentPage = 1;
            const activePage = document.querySelector('.pagination .active');
            if (activePage) {
               currentPage = parseInt(activePage.textContent) || 1;
            }
            console.log('currentPage PREV: ', activePage);
            if (currentPage > 1) {
               const pageSize = window.docTableList.page;
               const startIndex = (currentPage - 2) * pageSize + 1;
               window.docTableList.show(startIndex, pageSize);
            }
         }
      });
   }

   if (nextBtn) {
      nextBtn.addEventListener('click', (e) => {
         e.preventDefault();
         if (window.docTableList) {
            let currentPage = 1;
            const activePage = document.querySelector('.pagination .active');
            if (activePage) {
               currentPage = parseInt(activePage.textContent) || 1;
            }
            const totalPages = Math.ceil(window.docTableList.items.length / window.docTableList.page);
            if (currentPage < totalPages) {
               const pageSize = window.docTableList.page;
               const startIndex = currentPage * pageSize + 1;
               window.docTableList.show(startIndex, pageSize);
            }

         }
      });
   }

   if (viewAllLink) {
      viewAllLink.addEventListener('click', (e) => {
         e.preventDefault();
         if (window.docTableList) {
            window.docTableList.page = 10000;
            window.docTableList.update();
            viewAllLink.classList.add('d-none');
            if (viewLessLink) viewLessLink.classList.remove('d-none');
            // viewLessLink.classList.remove('d-none');
         }
      });
   }
   if (viewLessLink) {
      viewLessLink.addEventListener('click', (e) => {
         e.preventDefault();
         if (window.docTableList) {
            window.docTableList.page = 5;
            window.docTableList.update();
            viewLessLink.classList.add('d-none');
            // viewAllLink.classList.remove('d-none');
            if (viewAllLink) viewAllLink.classList.remove('d-none');
         }
      });
   }
}


//*********************************************************************************************
// Función para actualizar la información del paginador
function updatePaginationInfo2() {
   if (!window.docTableList) return;
   const listInfo = document.querySelector('[data-list-info]');
   const list = window.docTableList;
   const visibleItems = list.visibleItems.length;
   const totalItems = list.items.length;
   // const visibleItems = list.items.length;
   const currentPage = list.i;  // Página actual (comienza en 1)
   const itemsPerPage = list.page;  // Items por página (20)
   let start = 0;
   let end = 0;
   if (visibleItems > 0) {
      start = (currentPage - 1) * itemsPerPage + 1;
      start = (currentPage);
      if (currentPage == 1) {
         end = Math.min(currentPage * itemsPerPage, totalItems);
      } else {
         end = Number(currentPage) + Number(itemsPerPage) - 1;
         console.log('end antes: ', end);
         end = Math.min(end, totalItems);
      }
      start = Math.min(start, end);
   }
   const infoText = `${start} hasta ${end} de ${totalItems}`;

   console.log('list: ', list);
   console.log('visibleItems: ', visibleItems);
   console.log('currentPage: ', currentPage);
   console.log('itemsPerPage: ', itemsPerPage);
   console.log('start: ', start);
   console.log('end: ', end);
   console.log('start: ', start);
   console.log('infoText: ', infoText);
   listInfo.textContent = `${infoText}`;
   //$('[data-list-info]').text(infoText);
}