//*********************************************************************************************
document.addEventListener("DOMContentLoaded", function() {
   
   cargarDependencia();

   //*********************************************************************************************
   
   document.getElementById('btnaddDependencia').addEventListener('click', function(e) {

      document.getElementById('newCentroCto').nextElementSibling.classList.remove('is-valid');
      document.getElementById('newCentroCto').nextElementSibling.classList.remove('is-invalid');
      e.preventDefault();
     
      var listWhere = [];
      listWhere.push({
         "id": "CenEstad",
         "value": '1'
      })
      // para limpiar el selectCentrocto cada vez que se abra el modal de agregar dependencia, para evitar que se acumulen opciones duplicadas
      $('#newCentroCto').empty().append('<option value="">Seleccionar</option>').val(null).trigger('change');

      let selectCentro = document.getElementById('newCentroCto');
      
      getSelects('contabilidad', 'centrocto', selectCentro, 'CenCodig', textOpt = ['CenCodig', 'CenDescr'], listWhere);

      $('#modalDependenciaAdd').modal('show');
      document.getElementById('formDependenciaAdd').classList.remove('was-validated');
      document.getElementById('formDependenciaAdd').reset();
   });

   // Manejar el evento de clic en el botón de agregar
   // $('#formBancoAdd').on('submit', function(e) {
   document.getElementById('formDependenciaAdd').addEventListener('submit', function(e) {
      console.log('Dependencia agregada correctamente...');
      e.preventDefault();
      // var formData = $(this).serialize();
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarDependencia', '#modalDependenciaAdd')) {
         console.log('Dependencia agregada correctamente');
      } 
   });


   // Manejar el evento de clic en el botón de editar
   //******************************************************************************************
   $('#dependenciaTable tbody').on('click', '.edit-btn', function(e) {
      e.preventDefault();

      var listWhere = [{ id: "CenEstad", value: "1" }];
      var data = $(this).closest('tr');
      var ceco = (data.find('.id').attr('ceco') || '').trim();

      $('#editCentroCto').empty().append('<option value="">Seleccionar</option>').val(null).trigger('change');
      let selectCentro = document.getElementById('editCentroCto');

      getSelects('contabilidad', 'centrocto', selectCentro, 'CenCodig', ['CenCodig', 'CenDescr'], listWhere);
      $("#editCentroCto").val(ceco).trigger('change');

      // espera y luego selecciona
      setSelect2WhenOptionsExist('#editCentroCto', ceco);

      //Llenar los campos del formulario de edición con los datos de la fila seleccionada
      $('#editId').val(data.find('.id').attr('id'));
      $('#editCodigo').val(data.find('.id').text());
      $('#editNombre').val(data.find('.name').text());
      $('#editIniciales').val(data.find('.initials').text());
      $('#editStatus').val(data.find('.status').text());
 
      $('#modalDependenciaEdit').modal('show');
      document.getElementById('modalDependenciaEdit').classList.remove('was-validated');
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
   document.getElementById('formDependenciaEdit').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarDependencia', "#modalDependenciaEdit")) {
      }
   })


   // Manejar el evento de búsqueda
   //**************************************************************************************
   $('#searchDependencia').on('keyup', function() {
      // table.search(this.value).draw();
      if (window.dependenciaListInstance) {
         window.dependenciaListInstance.fuzzySearch(this.value);
         setTimeout(updatePaginationInfo, 50);
      }
   });

})


//*********************************************************************************************
async function cargarDependencia() {
   
   const formData = new FormData();
   formData.append("modulo", "presupuesto");
   formData.append("option", "dependencia");
   formData.append("action", "index");
   const response = await fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(resp => resp.json())
   .then( data => {
      const tbody = document.getElementById('dependencia-table-body');
      tbody.innerHTML = '';
      if (data.length > 0) {
         data.forEach(dependencia => {
            const row = document.createElement('tr');
            // Columna ID
            const tdId = document.createElement('td');
            tdId.className = 'align-middle text-660 ps-2 py-1 id';
            tdId.setAttribute('id', dependencia.DependenciaId);
            tdId.setAttribute('ceco', dependencia.CentroCtoId);   
            tdId.textContent = dependencia.DependenciaId;
            row.appendChild(tdId);
            
            // Columna name
            const tdName = document.createElement('td');
            tdName.className = 'align-middle text-660 ps-2 py-1 name';
            tdName.textContent = dependencia.Nombre;
            row.appendChild(tdName);

            // Columna Descripción
            const tdDesc = document.createElement('td');
            tdDesc.className = 'align-middle text-660 ps-2 py-1 initials';
            tdDesc.textContent = dependencia.Iniciales;
            row.appendChild(tdDesc);

            // Centro de Costo
            const tdceco = document.createElement('td');
            tdceco.className = 'align-middle text-660 ps-2 py-1 centrocto';
            tdceco.textContent = dependencia.CentroCtoId ;
            tdceco.setAttribute('data-id', dependencia.CentroCtoId);
            row.appendChild(tdceco);

            // Columna Status
            const tdStatus = document.createElement('td');
            tdStatus.className = 'align-middle text-660 ps-2 py-1 fs-0 status';
            const statusBadge = document.createElement('span');
            statusBadge.className = dependencia.Estado == 1 ? 
               'badge badge-phoenix badge-phoenix-success p-2' : 'badge badge-phoenix badge-phoenix-danger p-2';
            statusBadge.textContent = dependencia.Estado == 1 ? 
               "Activo" : "Desactivado";
            tdStatus.appendChild(statusBadge);
            row.appendChild(tdStatus);
            
            const tdActions = document.createElement('td');
            tdActions.className = 'align-middle text-start p-2 py-1';
            if ($('#permiModsw').val() == "1") {
               const editBtn = document.createElement('a');
               editBtn.className = 'btn btn-outline-primary me-1 p-2 py-1 edit-btn';
               editBtn.innerHTML = '<i class="fas fa-edit"></i> Editar';
               editBtn.setAttribute('data-id', dependencia.DependenciaId);
               
               
               tdActions.appendChild(editBtn);
            }

            if ($('#permiDelsw').val() == "1") {   
               const deleteBtn = document.createElement('a');
               deleteBtn.className = 'btn btn-sm btn-outline-danger p-2 py-1 delete-btn';
               deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Desactivar' ;
               if (dependencia.Estado == 0) {
                  deleteBtn.innerHTML = '<i class="fas fa-arrow-rotate-left"></i>Activar';
                  
               }
               deleteBtn.setAttribute('data-id', dependencia.DependenciaId);
               deleteBtn.setAttribute('data-status', dependencia.Estado);
               deleteBtn.onclick = function() {
                   eliminarDependencia(this.getAttribute('data-id'), this.getAttribute('data-status'));
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
        td.textContent = 'No se encontraron dependencias';
        row.appendChild(td);
        tbody.appendChild(row);

         
      }
      updateListJS();
      setTimeout(() => {
         if (window.dependenciaListInstance) {
            window.dependenciaListInstance.update();
            window.dependenciaListInstance.reIndex();
            window.dependenciaListInstance.sort('name', { order: 'asc' });
            $('[data-list-info]').text(`${window.dependenciaListInstance.visibleItems.length} to ${window.dependenciaListInstance.items.length} Items`);
            window.dependenciaListInstance.fuzzySearch('');
         }
      }, 100);
   })
   .catch(error => {
      console.error('Error:', error);
      const tbody = document.getElementById('dependencia-table-body');
      tbody.innerHTML = '';
      const row = document.createElement('tr');
      const td = document.createElement('td');
      td.colSpan = 5;
      td.className = 'text-center text-danger py-4';
      td.textContent = 'Error al cargar las dependencias';
      row.appendChild(td);
      tbody.appendChild(row);
   });

}


/*************************************************************/
function eliminarDependencia(id, status) {
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
         formData.append("option", "dependencia");
         formData.append("action", "delete");
         formData.append("id", id);
         formData.append("status", stanew);
         if (execQueryUpd(formData, 'cargarDependencia', null)) {
         }
      }
   })
}


//*********************************************************************************************
function updateListJS() {
   if (!window.dependenciaListInstance) {
      window.dependenciaListInstance = null;
   }
   window.dependenciaListInstance = new List('Dependencia', {
      valueNames: ['id', 'name', 'initials', 'status'],
      page: 15,
      pagination: true,
      indexAsync: true
   });
   window.dependenciaListInstance.sort('name', { order: 'asc' });
   window.dependenciaListInstance.fuzzySearch('');

   setupPaginationEvents(window.dependenciaListInstance, 15);
   window.dependenciaListInstance.on('updated', function() {
      updatePaginationInfo(window.dependenciaListInstance);
   });
   updatePaginationInfo(window.dependenciaListInstance);
}