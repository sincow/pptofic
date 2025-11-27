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
   const form = document.getElementById('formChequeAnu');
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
         formData.append("option", "cheques");
         formData.append("action", "getByNum");
         formData.append("id_cheque", document.getElementById('numDocAnu').value);
         const response = fetch('helpers/ajaxRouter.php', {
            method: 'POST',
            body: formData
         }).then(resp => resp.json())
         .then( data => {
            if (data) {
               const idDocument = data.id_cheque
               getDocumentDetails(idDocument)
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
            title: '¿Está seguro de anular el cheque?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, anular',
            cancelButtonText: 'Cancelar'
         }).then((result) => {
            if (result.isConfirmed) {
               anularCheque(paramsList);
            }
         })
      })
   }

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
         if (data['cheque'].status == 'A') {
            swal.fire({
               title: 'Error',
               text: 'El documento ya se encuentra anulado',
               icon: 'error',
               confirmButtonText: 'Aceptar'
            })
            return;
         }
         if ((data['cheque'].status == '1' || data['cheque'].status == 'D') && (parseFloat(data['cheque'].valor_cheque) > parseFloat(data['cheque'].capital_pagado))) {
            document.getElementById('btnAnularDocum').classList.remove('d-none');
         }
         document.getElementById('id_cheque').value = data['cheque'].id_cheque;
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

            // <div class="fw-bold fs-0">Nro Documento: ${data['cheque'].numero}</div>
         const innerHtmlDoc = `
            <div class="row">
               <div class="col-6 text-label fs--0">
                  <p class="fw-bold mb-0 fs-0 badge badge-phoenix badge-phoenix-${colDocument}" >${tipDocument}</p>
               </div>
               <div class="col-6 fs--1 text-end">
                  <p class="fw-bold mb-0 fs--1 badge badge-phoenix badge-phoenix-${colStatus}" >${status}</p>
               </div>
            </div>

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
function anularCheque(paramsList) {
   const CompteParam = paramsList.find(param => param.ParCodig === "CO1");
   const Compte = CompteParam.ParValor;
   const formData = new FormData();
   formData.append("modulo", "dival");
   formData.append("option", "cheques");
   formData.append("action", "anular");
   formData.append("id_cheque", document.getElementById('id_cheque').value);
   formData.append("clase", "1");
   formData.append("compte", Compte);
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