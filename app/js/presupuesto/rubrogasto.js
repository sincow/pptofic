//*********************************************************************************************
document.addEventListener("DOMContentLoaded", function() {
   
   cargarRubroGasto();

   //*********************************************************************************************
   
   document.getElementById('btnaddRubroGasto').addEventListener('click', function(e) {

      document.getElementById('newTipoFinanciacion').nextElementSibling.classList.remove('is-valid');
      document.getElementById('newTipoFinanciacion').nextElementSibling.classList.remove('is-invalid');

      document.getElementById('newCtaPucId').nextElementSibling.classList.remove('is-valid');
      document.getElementById('newCtaPucId').nextElementSibling.classList.remove('is-invalid');

      e.preventDefault();
     
      //Combo tipo de financiacion  
      var listWhere = [];
      listWhere.push({
         "id": "Estado",
         "value": '1'
      })
      // para limpiar el select
      $('#newTipoFinanciacion').empty().append('<option value="">Seleccionar</option>').val(null).trigger('change');
      let selectTipoFinan = document.getElementById('newTipoFinanciacion');
      getSelects('presupuesto', 'tipofinanciacion', selectTipoFinan, 'TipoFinanciacionId', textOpt = ['TipoFinanciacionId', 'Nombre'], listWhere);
      //

     //Combo tipo de Cta x Recon  
      var listWhere = [{ id: "CueEstad", value: "1" },{ id: "CueMovim", value: "1" } ];
      // para limpiar el select
      $('#newCtaPucId').empty().append('<option value="">Seleccionar</option>').val(null).trigger('change');
      let selectCtaPucId = document.getElementById('newCtaPucId');
      getSelects('contabilidad', 'cuentas', selectCtaPucId, 'CueCodig', textOpt = ['CueCodig', 'CueNombr'], listWhere);
      //
      

      $('#modalRubroGastoAdd').modal('show');
      document.getElementById('formRubroGastoAdd').classList.remove('was-validated');
      document.getElementById('formRubroGastoAdd').reset();

   });

   // Manejar el evento de clic en el botón de agregar
   
   document.getElementById('formRubroGastoAdd').addEventListener('submit', function(e) {
      console.log('Rubro de Gasto agregado correctamente...');
      e.preventDefault();
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarRubroGasto', '#modalRubroGastoAdd')) {
         console.log('Rubro de Gasto agregado correctamente');
      } 
   });


   // Manejar el evento de clic en el botón de editar
   //******************************************************************************************
   $('#rubrogastoTable tbody').on('click', '.edit-btn', function(e) {
      e.preventDefault();

      var data = $(this).closest('tr');

      //combo financiacion
      var listWhere = [{ id: "Estado", value: "1" }];
      //var data = $(this).closest('tr');
      var tipofinanciacion = (data.find('.id').attr('tipofinanciacionid') || '').trim();
      $('#editTipoFinanciacion').empty().append('<option value="">Seleccionar</option>').val(null).trigger('change');
      let selectTipoFinanciacion = document.getElementById('editTipoFinanciacion');
      getSelects('presupuesto', 'tipofinanciacion', selectTipoFinanciacion, 'TipoFinanciacionId',['TipoFinanciacionId', 'Nombre'], listWhere);
      $("#editTipoFinanciacion").val(tipofinanciacion).trigger('change');
      // espera y luego selecciona
      setSelect2WhenOptionsExist('#editTipoFinanciacion', tipofinanciacion);

      //combo cta recon 
      var listWhere = [{ id: "CueEstad", value: "1" },{ id: "CueMovim", value: "1" } ];
      var ctapucid = (data.find('.id').attr('ctapucid') || '').trim();
      $('#editCtaPucId').empty().append('<option value="">Seleccionar</option>').val(null).trigger('change');
      let selectCtaPucId = document.getElementById('editCtaPucId');
      getSelects('contabilidad', 'cuentas', selectCtaPucId, 'CueCodig', ['CueCodig', 'CueNombr'], listWhere);
     $("#editCtaPucId").val(ctapucid).trigger('change');
      //espera y luego selecciona
     setSelect2WhenOptionsExist('#editCtaPucId', ctapucid);

      
      //Llenar los campos del formulario de edición con los datos de la fila seleccionada
      $('#editCodigo').val(data.find('.id').attr('id'));
      $('#editCodigo').val(data.find('.id').text());
      $('#editNombre').val(data.find('.name').text());
      $('#editStatus').val(data.find('.status').text());
      $('#editMovimiento').prop('checked', data.find('.movimiento').data('movimiento') == 1);

      $('#modalRubroGastoEdit').modal('show');
      document.getElementById('modalRubroGastoEdit').classList.remove('was-validated');
   });

   

function setSelect2WhenOptionsExist(selector, value, tries = 0) {
  const $sel = $(selector);

  // ya cargó opciones (más de 1 porque está "Seleccionar")
  if ($sel.find('option').length > 1) {

    // intenta exacto
    if ($sel.find("option[value='" + value + "']").length) {
      $sel.val(value).trigger('change');
      console.log("✅ Seleccionado exacto:", value);
      return;
    }

    // intenta sin ceros (0371 -> 371)
    const valueNum = String(parseInt(value, 10));
    if ($sel.find("option[value='" + valueNum + "']").length) {
      $sel.val(valueNum).trigger('change');
      console.log("✅ Seleccionado sin ceros:", valueNum);
      return;
    }

    console.warn("⚠️ No existe option para:", value, "ni", valueNum);
    console.log("Values disponibles:", $sel.find("option").map((i,o)=>o.value).get());
    return;
  }

  if (tries < 30) { // 3s
    setTimeout(() => setSelect2WhenOptionsExist(selector, value, tries + 1), 100);
  } else {
    console.warn("⚠️ Timeout esperando opciones en", selector);
  }
}



   // Manejar el evento de envío del formulario de edición
   //******************************************************************************************
   document.getElementById('formRubroGastoEdit').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarRubroGasto', "#modalRubroGastoEdit")) {
      }
   })


   // Manejar el evento de búsqueda
   //**************************************************************************************
   $('#searchRubroGasto').on('keyup', function() {
      // table.search(this.value).draw();
      if (window.rubroGastoListInstance) {
         window.rubroGastoListInstance.fuzzySearch(this.value);
         setTimeout(updatePaginationInfo, 50);
      }
   });

})


//*********************************************************************************************
async function cargarRubroGasto() {
   
   const formData = new FormData();
   formData.append("modulo", "presupuesto");
   formData.append("option", "rubrogasto");
   formData.append("action", "index");
   const response = await fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(resp => resp.json())
   .then( data => {
      const tbody = document.getElementById('rubrogasto-table-body');
      tbody.innerHTML = '';
      if (data.length > 0) {
         data.forEach(rubrogasto => {
            const row = document.createElement('tr');
            // Columna ID
            const tdId = document.createElement('td');
            tdId.className = 'align-middle text-660 ps-2 py-1 id';
            tdId.setAttribute('id', rubrogasto.RubroGastoId);
            tdId.setAttribute('tipofinanciacionid', rubrogasto.TipoFinanciacionId);   
            tdId.setAttribute('ctapucxcob', rubrogasto.CtaPucIdxCob);   
            tdId.setAttribute('ctapucxrecon', rubrogasto.CtaPucIdxRecon);   
            tdId.textContent = rubrogasto.RubroGastoId;
            row.appendChild(tdId);
            
            // Columna name
            const tdName = document.createElement('td');
            tdName.className = 'align-middle text-660 ps-2 py-1 name';
            tdName.textContent = rubrogasto.Nombre;
            row.appendChild(tdName);

            // Columna tipo de financiacion
            const tdDesc = document.createElement('td');
            tdDesc.className = 'align-middle text-660 ps-2 py-1 TipoFinanciacionNombre';
            tdDesc.textContent = rubrogasto.TipoFinanciacionNombre;
            row.appendChild(tdDesc);

            // es de movimiento 
            const tdceco = document.createElement('td');
            tdceco.className = 'align-middle text-660 ps-2 py-1 movimiento';
            tdceco.textContent = rubrogasto.Movimiento == 1 ? 'SI' : 'NO';
            tdceco.setAttribute('data-movimiento', rubrogasto.Movimiento);
            row.appendChild(tdceco);

            // Columna Status
            const tdStatus = document.createElement('td');
            tdStatus.className = 'align-middle text-660 ps-2 py-1 fs-0 status';
            const statusBadge = document.createElement('span');
            statusBadge.className = rubrogasto.Estado == 1 ? 
               'badge badge-phoenix badge-phoenix-success p-2' : 'badge badge-phoenix badge-phoenix-danger p-2';
            statusBadge.textContent = rubrogasto.Estado == 1 ? 
               "Activo" : "Desactivado";
            tdStatus.appendChild(statusBadge);
            row.appendChild(tdStatus);
            
            const tdActions = document.createElement('td');
            tdActions.className = 'align-middle text-start p-2 py-1';
            if ($('#permiModsw').val() == "1") {
               const editBtn = document.createElement('a');
               editBtn.className = 'btn btn-outline-primary me-1 p-2 py-1 edit-btn';
               editBtn.innerHTML = '<i class="fas fa-edit"></i> Editar';
               editBtn.setAttribute('data-id', rubrogasto.RubroGastoId);
               
               
               tdActions.appendChild(editBtn);
            }

            if ($('#permiDelsw').val() == "1") {   
               const deleteBtn = document.createElement('a');
               deleteBtn.className = 'btn btn-sm btn-outline-danger p-2 py-1 delete-btn';
               deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Desactivar' ;
               if (rubrogasto.Estado == 0) {
                  deleteBtn.innerHTML = '<i class="fas fa-arrow-rotate-left"></i>Activar';
                  
               }
               deleteBtn.setAttribute('data-id', rubrogasto.RubroGastoId);
               deleteBtn.setAttribute('data-status', rubrogasto.Estado);
               deleteBtn.onclick = function() {
                   eliminarRubroGasto(this.getAttribute('data-id'), this.getAttribute('data-status'));
               };
               tdActions.appendChild(deleteBtn);
            }
            row.appendChild(tdActions);
            tbody.appendChild(row);
         });
      } else {
        const row = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan = 5;
        td.className = 'text-center text-muted py-4';
        td.textContent = 'No se encontraron rubros de gasto';
        row.appendChild(td);
        tbody.appendChild(row);

         
      }
      updateListJS();
      setTimeout(() => {
         if (window.rubroGastoListInstance) {
            window.rubroGastoListInstance.update();
            window.rubroGastoListInstance.reIndex();
            window.rubroGastoListInstance.sort('name', { order: 'asc' });
            $('[data-list-info]').text(`${window.rubroGastoListInstance.visibleItems.length} to ${window.rubroGastoListInstance.items.length} Items`);
            window.rubroGastoListInstance.fuzzySearch('');
         }
      }, 100);
   })
   .catch(error => {
      console.error('Error:', error);
      const tbody = document.getElementById('rubrogasto-table-body');
      tbody.innerHTML = '';
      const row = document.createElement('tr');
      const td = document.createElement('td');
      td.colSpan = 5;
      td.className = 'text-center text-danger py-4';
      td.textContent = 'Error al cargar los rubros de gasto';
      row.appendChild(td);
      tbody.appendChild(row);
   });

}


/*************************************************************/
function eliminarRubroGasto(id, status) {
   let titulo = 'Estás seguro de que quieres desactivar este registro?';
   let botcon = 'Si, Desactivar ';
   let stanew = 0;
   if (status == 0) {
      titulo = '¿Estás seguro de que quieres activar este registro?'
      botcon = 'Si, Activar!';
      stanew = 1;
   }
   swal.fire({
      text: titulo,
      icon: 'question',
      showCancelButton: true,
      reverseButtons: true,
      focusCancel: true,
      // confirmButtonColor: '#3085d6',
      // cancelButtonColor: '#d33',
      confirmButtonColor: '#3086d6c7',
      cancelButtonColor: 'rgba(221, 51, 51, 0.73)',
      cancelButtonText: "No, Cancelar",
      confirmButtonText: botcon
   }).then(function (result) {
      if (result.value) {
         const formData = new FormData();
         formData.append("modulo", "presupuesto");
         formData.append("option", "rubrogasto");
         formData.append("action", "delete");
         formData.append("id", id);
         formData.append("status", stanew);
         if (execQueryUpd(formData, 'cargarRubroGasto', null)) {
         }
      }
   })
}


//*********************************************************************************************
function updateListJS() {
   if (!window.rubroGastoListInstance) {
      window.rubroGastoListInstance = null;
   }
   window.rubroGastoListInstance = new List('RubroGasto', {
      valueNames: ['id', 'name', 'initials', 'status'],
      page: 15,
      pagination: true,
      indexAsync: true
   });
   window.rubroGastoListInstance.sort('name', { order: 'asc' });
   window.rubroGastoListInstance.fuzzySearch('');

   setupPaginationEvents(window.rubroGastoListInstance, 15);
   window.rubroGastoListInstance.on('updated', function() {
      updatePaginationInfo(window.rubroGastoListInstance);
   });
   updatePaginationInfo(window.rubroGastoListInstance);
}