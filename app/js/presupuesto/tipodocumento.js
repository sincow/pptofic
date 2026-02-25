//*********************************************************************************************
document.addEventListener("DOMContentLoaded", function() {
   cargarTipoDocumento();

   //*********************************************************************************************
   
   document.getElementById('btnaddTipoDocumento').addEventListener('click', function(e) {
      e.preventDefault();
      $('#modalTipoDocumentoAdd').modal('show');
      document.getElementById('formTipoDocumentoAdd').classList.remove('was-validated');
      document.getElementById('formTipoDocumentoAdd').reset();
   });

   
   document.getElementById('formTipoDocumentoAdd').addEventListener('submit', function(e) {
      e.preventDefault();
      // var formData = $(this).serialize();
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarTipoDocumento', '#modalTipoDocumentoAdd')) {
      }
   });


   // Manejar el evento de clic en el botón de editar
   //******************************************************************************************
   $('#tipodocumentoTable tbody').on('click', '.edit-btn', function(e) {
      e.preventDefault();
      
      document.getElementById('formTipoDocumentoEdit').reset();
      var data = $(this).parents().parents('tr');
      $('#editId').val(data.find('.id').attr('id'));
      $('#editCodigo').val(data.find('.id').text());
      $('#editNombre').val(data.find('.name').text());
      $('#editIniciales').val(data.find('.initials').text());
      $('#editStatus').val(data.find('.status').text());
      $('#modalTipoDocumentoEdit').modal('show');
      document.getElementById('modalTipoDocumentoEdit').classList.remove('was-validated');
   });


   // Manejar el evento de envío del formulario de edición
   //******************************************************************************************
   document.getElementById('formTipoDocumentoEdit').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarTipoDocumento', "#modalTipoDocumentoEdit")) {
      }
   })


   // Manejar el evento de búsqueda
   //**************************************************************************************
   $('#searchTipoDocumento').on('keyup', function() {
      // table.search(this.value).draw();
      if (window.tipodocumentoListInstance) {
         window.tipodocumentoListInstance.fuzzySearch(this.value);
         setTimeout(updatePaginationInfo, 50);
      }
   });

})


//*********************************************************************************************
async function cargarTipoDocumento() {
   
   const formData = new FormData();
   formData.append("modulo", "presupuesto");
   formData.append("option", "tipodocumento");
   formData.append("action", "index");
   const response = await fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(resp => resp.json())
   .then( data => {
      const tbody = document.getElementById('tipodocumento-table-body');
      tbody.innerHTML = '';
      if (data.length > 0) {
         data.forEach(tipodocumento => {
            const row = document.createElement('tr');
            // Columna ID
            const tdId = document.createElement('td');
            tdId.className = 'align-middle text-660 ps-2 py-1 id';
            tdId.setAttribute('id', tipodocumento.TipoDocumentoId);
            tdId.textContent = tipodocumento.TipoDocumentoId;
            row.appendChild(tdId);
            
            // Columna name
            const tdName = document.createElement('td');
            tdName.className = 'align-middle text-660 ps-2 py-1 name';
            tdName.textContent = tipodocumento.Nombre;
            row.appendChild(tdName);

            // Columna Descripción
            const tdDesc = document.createElement('td');
            // tdDesc.className = 'white-space-nowrap align-middle ps-2';
            tdDesc.className = 'align-middle text-660 ps-2 py-1 initials';
            tdDesc.textContent = tipodocumento.Iniciales;
            row.appendChild(tdDesc);
            
            // Columna Status
            const tdStatus = document.createElement('td');
            tdStatus.className = 'align-middle text-660 ps-2 py-1 fs-0 status';
            const statusBadge = document.createElement('span');
            statusBadge.className = tipodocumento.Estado == 1 ? 
               'badge badge-phoenix badge-phoenix-success p-2' : 'badge badge-phoenix badge-phoenix-danger p-2';
            statusBadge.textContent = tipodocumento.Estado == 1 ? 
               "Activo" : "Desactivado";
            tdStatus.appendChild(statusBadge);
            row.appendChild(tdStatus);
            
            const tdActions = document.createElement('td');
            tdActions.className = 'align-middle text-start p-2 py-1';
            if ($('#permiModsw').val() == "1") {
               const editBtn = document.createElement('a');
               editBtn.className = 'btn btn-outline-primary me-1 p-2 py-1 edit-btn';
               editBtn.innerHTML = '<i class="fas fa-edit"></i> Editar';
               editBtn.setAttribute('data-id', tipodocumento.TipoDocumentoId);
               
               tdActions.appendChild(editBtn);
            }

            if ($('#permiDelsw').val() == "1") {   
               const deleteBtn = document.createElement('a');
               deleteBtn.className = 'btn btn-sm btn-outline-danger p-2 py-1 delete-btn';
               deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Desactivar' ;
               if (tipodocumento.status == 0) {
                  deleteBtn.innerHTML = '<i class="fas fa-arrow-rotate-left"></i>Activar';
                  // deleteBtn.style.pointerEvents = 'none';
               }
               deleteBtn.setAttribute('data-id', tipodocumento.TipoDocumentoId);
               deleteBtn.setAttribute('data-status', tipodocumento.Estado);
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
        td.textContent = 'No se encontraron tipos de documento';
        row.appendChild(td);
        tbody.appendChild(row);

         
      }
      updateListJS();
      setTimeout(() => {
         if (window.tipoDocumentoListInstance) {
            window.tipoDocumentoListInstance.update();
            window.tipoDocumentoListInstance.reIndex();
            window.tipoDocumentoListInstance.sort('name', { order: 'asc' });
            $('[data-list-info]').text(`${window.tipoDocumentoListInstance.visibleItems.length} to ${window.tipoDocumentoListInstance.items.length} Items`);
            window.tipoDocumentoListInstance.fuzzySearch('');
         }
      }, 100);
   })
   .catch(error => {
      console.error('Error:', error);
      const tbody = document.getElementById('tipodocumento-table-body');
      tbody.innerHTML = '';
      const row = document.createElement('tr');
      const td = document.createElement('td');
      td.colSpan = 5;
      td.className = 'text-center text-danger py-4';
      td.textContent = 'Error al cargar los tipos de documento';
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
         formData.append("option", "tipodocumento");
         formData.append("action", "delete");
         formData.append("id", id);
         formData.append("status", stanew);
         if (execQueryUpd(formData, 'cargarTipoDocumento', null)) {
         }
      }
   })
}


//*********************************************************************************************
function updateListJS() {
   if (!window.tipoDocumentoListInstance) {
      window.tipoDocumentoListInstance = null;
   }
   window.tipoDocumentoListInstance = new List('TipoDocumento', {
      valueNames: ['id', 'name', 'initials', 'status'],
      page: 15,
      pagination: true,
      indexAsync: true
   });
   window.tipoDocumentoListInstance.sort('name', { order: 'asc' });
   window.tipoDocumentoListInstance.fuzzySearch('');

   setupPaginationEvents(window.tipoDocumentoListInstance, 15);
   window.tipoDocumentoListInstance.on('updated', function() {
      updatePaginationInfo(window.tipoDocumentoListInstance);
   });
   updatePaginationInfo(window.tipoDocumentoListInstance);
}