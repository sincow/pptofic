//*********************************************************************************************
document.addEventListener("DOMContentLoaded", function() {
   cargarOrdenadorGasto();

   //*********************************************************************************************
   
   document.getElementById('btnaddOrdenadorGasto').addEventListener('click', function(e) {
      e.preventDefault();
      $('#modalOrdenadorGastoAdd').modal('show');
      document.getElementById('formOrdenadorGastoAdd').classList.remove('was-validated');
      document.getElementById('formOrdenadorGastoAdd').reset();
   });

   
   document.getElementById('formOrdenadorGastoAdd').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarOrdenadorGasto', '#modalOrdenadorGastoAdd')) {
      }
   });


   // Manejar el evento de clic en el botón de editar
   //******************************************************************************************
   $('#ordenadorgastoTable tbody').on('click', '.edit-btn', function(e) {
      e.preventDefault();
      
      document.getElementById('formOrdenadorGastoEdit').reset();
      var data = $(this).parents().parents('tr');
      $('#editId').val(data.find('.id').attr('id'));
      $('#editNombre').val(data.find('.name').text());
      $('#editCargo').val(data.find('.cargo').text());
      $('#editDireccion').val(data.find('.id').attr('terdirec'));
      $('#editTelefono1').val(data.find('.id').attr('tertele1'));
      $('#editTelefono2').val(data.find('.id').attr('tertele2'));
      $('#editVigente').prop('checked', data.find('.vigente').data('vigente') == 1);
      $('#editStatus').val(data.find('.status').text());
      $('#modalOrdenadorGastoEdit').modal('show');
      document.getElementById('modalOrdenadorGastoEdit').classList.remove('was-validated');
   });


   // Manejar el evento de envío del formulario de edición
   //******************************************************************************************
   document.getElementById('formOrdenadorGastoEdit').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarOrdenadorGasto', "#modalOrdenadorGastoEdit")) {
      }
   })


   // Manejar el evento de búsqueda
   //**************************************************************************************
   $('#searchOrdenadorGasto').on('keyup', function() {
      // table.search(this.value).draw();
      if (window.ordenadorgastoListInstance) {
         window.ordenadorgastoListInstance.fuzzySearch(this.value);
         setTimeout(updatePaginationInfo, 50);
      }
   });

})


//*********************************************************************************************
async function cargarOrdenadorGasto() {
   
   const formData = new FormData();
   formData.append("modulo", "presupuesto");
   formData.append("option", "ordenadorgasto");
   formData.append("action", "index");
   const response = await fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(resp => resp.json())
   .then( data => {
      const tbody = document.getElementById('ordenadorgasto-table-body');
      tbody.innerHTML = '';
      if (data.length > 0) {
         
         data.forEach(ordenadorgasto => {

            const row = document.createElement('tr');
            // Columna ID
            const tdId = document.createElement('td');
            tdId.className = 'align-middle text-660 ps-2 py-1 id';
            tdId.setAttribute('terid', ordenadorgasto.TerceroId);
            tdId.setAttribute('terdirec', ordenadorgasto.TerDirec);
            tdId.setAttribute('tertele1', ordenadorgasto.TerTele1);
            tdId.setAttribute('tertele2', ordenadorgasto.TerTele2);
            tdId.textContent = ordenadorgasto.TerceroId;
            row.appendChild(tdId);
            
            // Columna name
            const tdName = document.createElement('td');
            tdName.className = 'align-middle text-660 ps-2 py-1 name';
            tdName.textContent = ordenadorgasto.TerNombr;
            row.appendChild(tdName);

            // Columna cargo
            const tdDesc = document.createElement('td');
            // tdDesc.className = 'white-space-nowrap align-middle ps-2';
            tdDesc.className = 'align-middle text-660 ps-2 py-1 cargo';
            tdDesc.textContent = ordenadorgasto.Cargo;
            row.appendChild(tdDesc);
            
            // Columna vigente
            const tdVig = document.createElement('td');
            // tdDesc.className = 'white-space-nowrap align-middle ps-2';
            tdVig.className = 'align-middle text-660 ps-2 py-1 vigente';
            tdVig.textContent = ordenadorgasto.Vigente == 1 ? 'SI' : 'NO';
            tdVig.setAttribute('data-vigente', ordenadorgasto.Vigente);
            row.appendChild(tdVig);

            
            // Columna Status
            const tdStatus = document.createElement('td');
            tdStatus.className = 'align-middle text-660 ps-2 py-1 fs-0 status';
            const statusBadge = document.createElement('span');
            statusBadge.className = ordenadorgasto.Estado == 1 ? 
               'badge badge-phoenix badge-phoenix-success p-2' : 'badge badge-phoenix badge-phoenix-danger p-2';
            statusBadge.textContent = ordenadorgasto.Estado == 1 ? 
               "Activo" : "Desactivado";
            tdStatus.appendChild(statusBadge);
            row.appendChild(tdStatus);


                  
            const tdActions = document.createElement('td');
            tdActions.className = 'align-middle text-start p-2 py-1';
            if ($('#permiModsw').val() == "1") {
               const editBtn = document.createElement('a');
               editBtn.className = 'btn btn-outline-primary me-1 p-2 py-1 edit-btn';
               editBtn.innerHTML = '<i class="fas fa-edit"></i> Editar';
               editBtn.setAttribute('data-id', ordenadorgasto.TerceroId);
               
               tdActions.appendChild(editBtn);
            }

            if ($('#permiDelsw').val() == "1") {   
               const deleteBtn = document.createElement('a');
               deleteBtn.className = 'btn btn-sm btn-outline-danger p-2 py-1 delete-btn';
               deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Desactivar' ;
               if (ordenadorgasto.Estado == 0) {
                  deleteBtn.innerHTML = '<i class="fas fa-arrow-rotate-left"></i>Activar';
                  // deleteBtn.style.pointerEvents = 'none';
               }
               deleteBtn.setAttribute('data-id', ordenadorgasto.TerceroId);
               deleteBtn.setAttribute('data-status', ordenadorgasto.Estado);
               deleteBtn.onclick = function() {
                   eliminarTipoDocumento(this.getAttribute('data-id'), this.getAttribute('data-status'));
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
        td.textContent = 'No se encontraron ordenadores de gasto';
        row.appendChild(td);
        tbody.appendChild(row);

         
      }
      updateListJS();
      setTimeout(() => {
         if (window.ordenadorGastoListInstance) {
            window.ordenadorGastoListInstance.update();
            window.ordenadorGastoListInstance.reIndex();
            window.ordenadorGastoListInstance.sort('name', { order: 'asc' });
            $('[data-list-info]').text(`${window.ordenadorGastoListInstance.visibleItems.length} to ${window.ordenadorGastoListInstance.items.length} Items`);
            window.ordenadorGastoListInstance.fuzzySearch('');
         }
      }, 100);
   })
   .catch(error => {
      console.error('Error:', error);
      const tbody = document.getElementById('ordenadorgasto-table-body');
      tbody.innerHTML = '';
      const row = document.createElement('tr');
      const td = document.createElement('td');
      td.colSpan = 5;
      td.className = 'text-center text-danger py-4';
      td.textContent = 'Error al cargar los ordenadores del gasto';
      row.appendChild(td);
      tbody.appendChild(row);
   });

}


/*************************************************************/
function eliminarTipoDocumento(id, status) {
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
         formData.append("option", "ordenadorgasto");
         formData.append("action", "delete");
         formData.append("id", id);
         formData.append("status", stanew);
         if (execQueryUpd(formData, 'cargarOrdenadorGasto', null)) {
         }
      }
   })
}


//*********************************************************************************************
function updateListJS() {
   if (!window.ordenadorGastoListInstance) {
      window.ordenadorGastoListInstance = null;
   }
   window.ordenadorGastoListInstance = new List('OrdenadorGasto', {
      valueNames: ['id', 'name', 'initials', 'status'],
      page: 15,
      pagination: true,
      indexAsync: true
   });
   window.ordenadorGastoListInstance.sort('name', { order: 'asc' });
   window.ordenadorGastoListInstance.fuzzySearch('');

   setupPaginationEvents(window.ordenadorGastoListInstance, 15);
   window.ordenadorGastoListInstance.on('updated', function() {
      updatePaginationInfo(window.ordenadorGastoListInstance);
   });
   updatePaginationInfo(window.ordenadorGastoListInstance);
}

//Validacion de tercero
const inputId = document.getElementById('newid');
if (inputId) {
   inputId.addEventListener('blur', function() {
   
   var listWhere = [];
   listWhere.push({"id": "TerDocId","value": inputId.value })

   const formData = new FormData();
   formData.append("modulo", "contabilidad");
   formData.append("option", "terceros");
   formData.append("action", "getWhere");
   formData.append("listWhere", JSON.stringify(listWhere));
   
   const response = fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(resp => resp.json())
   .then( data => {
      if (data.length > 0) {
          document.getElementById('newNombre').value = data[0].TerNombr ;
          document.getElementById('newDireccion').value = data[0].TerDirec ;
          document.getElementById('newTelefono1').value = data[0].TerTele1 ;
          document.getElementById('newTelefono2').value = data[0].TerTele2 ;
      }
      else
      { 
          document.getElementById('newNombre').value = '';
          document.getElementById('newDireccion').value = '';
          document.getElementById('newTelefono1').value = '';
          document.getElementById('newTelefono2').value = '';

          Swal.fire({
            icon: 'warning',
            title: 'Aviso',
            text: 'El tercero debe estar creado en contabilidad.',
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#3086d6c7'
         });
      }
    })

   });

}
