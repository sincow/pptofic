//*********************************************************************************************
document.addEventListener("DOMContentLoaded", function() {
   cargarNotifi();

   var listWhere = [];
   listWhere.push({
      "id": "status",
      "value": '1'
   });
   const selectCuenta = document.getElementById('empleadoSearch');
   getSelects('admon', 'users', selectCuenta, 'id_user', textOpt = ['name'], listWhere);
});


//*********************************************************************************************
//document.getElementById('fechaSearchTo').addEventListener('change', function() {
$('#fechaSearchFrom').on ('change', function() {
   let minDate = new Date(this.value.valueOf());
   minDate.setDate(minDate.getDate() + 1);
   $('#fechaSearchTo').datepicker('setStartDate', minDate);

   const fechaInicio = new Date('1900-01-01');
   $("#entregaSearchFrom").datepicker({
      startDate: fechaInicio
   });
});


//*********************************************************************************************
//document.getElementById('entregaSearchFrom').addEventListener('change', function() {
$('#entregaSearchFrom').on ('change', function() {
   let minDateEntrega = new Date(document.getElementById('entregaSearchFrom').value.valueOf());
   minDateEntrega.setDate(minDateEntrega.getDate() + 1);
   $('#entregaSearchTo').datepicker('setStartDate', minDateEntrega);

   let minDate = new Date(document.getElementById('fechaSearchFrom').value.valueOf());
   minDate.setDate(minDate.getDate() + 1);
   $('#fechaSearchTo').datepicker('setStartDate', minDate);

});


//*********************************************************************************************
async function cargarNotifi() {
   const formData = new FormData();
   formData.append("modulo", "admon");
   formData.append("option", "notificaciones");
   formData.append("action", "filter");
   const response = await fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(resp => resp.json())
   .then( data => {
      // const tbody = document.getElementById('notifiTable-body');
      // tbody.innerHTML = '';
      cargarNotifiDetail(data);
      let dataArray = [];
      dataArray.push({
         "data":  data,
         "applied_filters": []
      });

      if (data.length > 0) {

      }
   });
};


//*********************************************************************************************
function cargarNotifiDetail(data) {
   allCheques = data["data"];
   const tbody = document.getElementById('notifiTable-body');
   tbody.innerHTML = '';
   if (data["data"].length > 0) {
      let fechaInicio = new Date();
      let colorVencim = "";
      let colorCierre = "";
      fechaInicio.setHours(0, 0, 0, 0);
      data["data"].forEach(funcionForEach);
      function funcionForEach(item, index) {
         colorVencim = "green";
         colorCierre = "green";
         let permiRec = '<a class="dropdown-item poRectId" idpoRec=' + item["po_id"] + '>Receive</a>';
         permiRec = '';
         const fechaFin = new Date(item['UltEntrega']);
         fechaFin.setHours(0, 0, 0, 0);
         let fechaCierre = formatDate(item['fecha_cierre']);
         let fecha_cierre = item['fecha_cierre'];
         if (item['fecha_cierre'] == null) {
            fecha_cierre = "";
            fechaCierre = "";
         } else {
            fecha_cierre = formatDate(fecha_cierre);
            fechaCierre = new Date(item['fecha_cierre']);
            fechaCierre.setHours(0, 0, 0, 0);
         }
         let diasShow = "";
         const diferencia = fechaFin.getTime() - fechaInicio.getTime();
         const dias = Math.ceil(diferencia / (1000 * 60 * 60 * 24)) +1;
         if (dias < 0) {
            colorVencim = "red";
            diasShow = ' ->  '+ (dias * -1) + " Días";
         } else if (dias < 3) {
            colorVencim = "orange";
         }
         let status = 'PENDIENTE';
         let statusBadgeClass = "warning";
         switch (item["status"]) {
            case '1':
               break;
            case '9':
               status = 'CERRADA';
               statusBadgeClass = "success";
               break;
            case 'C':
               status = 'CONSIGNADO';
               break;
            default:
               break;
         }
         let situacion = "";
         if (item["status"] == "9") {
            colorVencim = "";
            if (fechaCierre > fechaFin) {
               colorCierre = "red";
               situacion = 'REALIZADA - ATRASADA';
            } else {
               colorCierre = "green";
               situacion = 'REALIZADA - A TIEMPO';
            }
         } else {
            if (fechaFin < fechaInicio) {
               colorCierre = "red";
               situacion = 'EN PROCESO - ATRASADA';
            } else {
               colorCierre = "green";
               situacion = 'EN PROCESO - A TIEMPO';
            }
         }
         let row = `
            <tr class="hover-actions-trigger btn-reveal-trigger position-static">
               <td class="id align-middle text-end white-space-nowrap py-1 pe-3">
                  <a class="fw-semi-bold documentId" name="idDocument" idDoc='${item["id_notifi"]}'>${item["numero"]}</a>
               </td>
               <td class="empleado  align-middle white-space-nowrap text-uppercase py-1 ps-0">${item["name"]}</td>
               <td class="fecha     align-middle white-space-nowrap text-start py-1 ps-0">${formatDate(item["fecha"])}</td>
               <td class="entrega   align-middle white-space-nowrap text-start py-1 ps-0">${formatDate(item["fecha_entrega"])}</td>
               <td class="reprogra  align-middle white-space-nowrap text-start py-1 ps-0" style="color:${colorVencim};" >${formatDate(item["UltEntrega"])}  ${diasShow}</td>
               <td class="cierre    align-middle white-space-nowrap text-start py-1 ps-0" style="color:${colorCierre};" >${(fecha_cierre)}</td>
               <td class="titulo    align-middle white-space-nowrap text-start py-1 ps-0">${item["titulo"]}</td>
               <td class="situacion align-middle white-space-nowrap text-start py-1 ps-0">${situacion}</td>
               <td class="cumplimi  align-middle white-space-nowrap text-end py-1 pe-3">${formatCurrency(item["cumplimiento"],0)}</td>
               <td class="status    align-middle white-space-nowrap text-start py-1 ps-1 fw-bold text-700"><span class="badge badge-phoenix fs--2 badge-phoenix-${statusBadgeClass}"><span class="badge-label">${status}</span><span class="ms-1" style="height:12.8px;width:12.8px;"></span></span></td>
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
         $('#notifiTable-body').append(row);
         //tbody.appendChild(row);
      }
   } else {
      $('#notifiTable-body').empty();
      $('#notifiTable-body').append(`
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
   window.docTableList = new List('notifiTable', {
      valueNames: ["id","empleado","fecha","entrega","reprogra","titulo","situacion","cumplimi","status"],
      page: 20,
      pagination: true,
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
         cargarNotifiDetail(data);
      });
   });
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
   //var clientesOptions = $('#empleadoSearch').find('option');
   $('#empleadoSearch').find('option').each(function() {
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
   $('#empleadoSearch').find('option').each(function() {
      vendirText = $(this).text();
      vendorNames.push(
         vendirText
      );
   });
   // Mapeo de nombres de campos a etiquetas legibles
   const fieldLabels = {
      'empresaSearch': 'Empresa',
      'numberSearch': 'Nro Tarea',
      'statusSearch': 'Estado',
      'fechaSearchFrom': 'Creada Desde',
      'fechaSearchTo': 'Creada Hasta',
      'entregaSearchFrom': 'Entrega Desde',
      'entregaSearchTo': 'Entrega Hasta',
      'empleadoSearch': 'Empleado',
      'tituloSearch': 'Titulo',
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
         } else if (field === 'empleadoSearch') {
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