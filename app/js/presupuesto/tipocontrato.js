//*********************************************************************************************
document.addEventListener("DOMContentLoaded", function() {
   cargarTipoContrato();

   //*********************************************************************************************
   
   document.getElementById('btnaddTipoContrato').addEventListener('click', function(e) {
      e.preventDefault();
      $('#modalTipoContratoAdd').modal('show');
      document.getElementById('formTipoContratoAdd').classList.remove('was-validated');
      document.getElementById('formTipoContratoAdd').reset();
   });

   // Manejar el evento de clic en el botón de agregar
   // $('#formBancoAdd').on('submit', function(e) {
   document.getElementById('formTipoContratoAdd').addEventListener('submit', function(e) {
      e.preventDefault();
      // var formData = $(this).serialize();
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarTipoContrato', '#modalTipoContratoAdd')) {
      }
   });


   // Manejar el evento de clic en el botón de editar
   //******************************************************************************************
   $('#tipocontratoTable tbody').on('click', '.edit-btn', function(e) {
      e.preventDefault();
      
      document.getElementById('formTipoContratoEdit').reset();
      var data = $(this).parents().parents('tr');
      
           
      $('#editId').val(data.find('.id').attr('id'));
      $('#editCodigo').val(data.find('.id').text());
      $('#editNombre').val(data.find('.name').text());
      $('#editIniciales').val(data.find('.initials').text());
      $('#editStatus').val(data.find('.status').text());
      $('#modalTipoContratoEdit').modal('show');
      document.getElementById('modalTipoContratoEdit').classList.remove('was-validated');
   });


   // Manejar el evento de envío del formulario de edición
   //******************************************************************************************
   document.getElementById('formTipoContratoEdit').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarTipoContrato', "#modalTipoContratoEdit")) {
      }
   })


   // Manejar el evento de búsqueda
   //**************************************************************************************
   $('#searchTipoContrato').on('keyup', function() {
      // table.search(this.value).draw();
      if (window.tipocontratoListInstance) {
         window.tipocontratoListInstance.fuzzySearch(this.value);
         setTimeout(updatePaginationInfo, 50);
      }
   });

})


//*********************************************************************************************
async function cargarTipoContrato() {
   
   const formData = new FormData();
   formData.append("modulo", "presupuesto");
   formData.append("option", "tipocontrato");
   formData.append("action", "index");
   const response = await fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(resp => resp.json())
   .then( data => {
      const tbody = document.getElementById('tipocontrato-table-body');
      tbody.innerHTML = '';
      if (data.length > 0) {
         data.forEach(tipocontrato => {
            const row = document.createElement('tr');
            // Columna ID
            const tdId = document.createElement('td');
            tdId.className = 'align-middle text-660 ps-2 py-1 id';
            tdId.setAttribute('id', tipocontrato.TipoContratoId);
            tdId.textContent = tipocontrato.TipoContratoId;
            row.appendChild(tdId);
            
            // Columna name
            const tdName = document.createElement('td');
            tdName.className = 'align-middle text-660 ps-2 py-1 name';
            tdName.textContent = tipocontrato.Nombre;
            row.appendChild(tdName);

            // Columna Descripción
            const tdDesc = document.createElement('td');
            tdDesc.className = 'align-middle text-660 ps-2 py-1 initials';
            tdDesc.textContent = tipocontrato.Iniciales;
            row.appendChild(tdDesc);
            
            // Columna Status
            const tdStatus = document.createElement('td');
            tdStatus.className = 'align-middle text-660 ps-2 py-1 fs-0 status';
            const statusBadge = document.createElement('span');
            statusBadge.className = tipocontrato.Estado == 1 ? 
               'badge badge-phoenix badge-phoenix-success p-2' : 'badge badge-phoenix badge-phoenix-danger p-2';
            statusBadge.textContent = tipocontrato.Estado == 1 ? 
               "Activo" : "Desactivado";
            tdStatus.appendChild(statusBadge);
            row.appendChild(tdStatus);
            
            const tdActions = document.createElement('td');
            tdActions.className = 'align-middle text-start p-2 py-1';
            if ($('#permiModsw').val() == "1") {
               const editBtn = document.createElement('a');
               editBtn.className = 'btn btn-outline-primary me-1 p-2 py-1 edit-btn';
               editBtn.innerHTML = '<i class="fas fa-edit"></i> Editar';
               editBtn.setAttribute('data-id', tipocontrato.TipoContratoId);
               
               tdActions.appendChild(editBtn);
            }

            if ($('#permiDelsw').val() == "1") {   
               const deleteBtn = document.createElement('a');
               deleteBtn.className = 'btn btn-sm btn-outline-danger p-2 py-1 delete-btn';
               deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Desactivar' ;
               if (tipocontrato.Estado == 0) {
                  deleteBtn.innerHTML = '<i class="fas fa-arrow-rotate-left"></i>Activar';
                  
               }
               deleteBtn.setAttribute('data-id', tipocontrato.TipoContratoId);
               deleteBtn.setAttribute('data-status', tipocontrato.Estado);
               deleteBtn.onclick = function() {
                   eliminarTipoContrato(this.getAttribute('data-id'), this.getAttribute('data-status'));
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
        td.textContent = 'No se encontraron tipos de contrato';
        row.appendChild(td);
        tbody.appendChild(row);

         
      }
      updateListJS();
      setTimeout(() => {
         if (window.tipocontratoListInstance) {
            window.tipocontratoListInstance.update();
            window.tipocontratoListInstance.reIndex();
            window.tipocontratoListInstance.sort('name', { order: 'asc' });
            $('[data-list-info]').text(`${window.tipocontratoListInstance.visibleItems.length} to ${window.tipocontratoListInstance.items.length} Items`);
            window.tipocontratoListInstance.fuzzySearch('');
         }
      }, 100);
   })
   .catch(error => {
      console.error('Error:', error);
      const tbody = document.getElementById('tipocontrato-table-body');
      tbody.innerHTML = '';
      const row = document.createElement('tr');
      const td = document.createElement('td');
      td.colSpan = 5;
      td.className = 'text-center text-danger py-4';
      td.textContent = 'Error al cargar los tipos de contrato';
      row.appendChild(td);
      tbody.appendChild(row);
   });

}


/*************************************************************/
function eliminarTipoContrato(id, status) {
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
         formData.append("option", "tipocontrato");
         formData.append("action", "delete");
         formData.append("id", id);
         formData.append("status", stanew);
         if (execQueryUpd(formData, 'cargarTipoContrato', null)) {
         }
      }
   })
}


//*********************************************************************************************
function updateListJS() {
   if (!window.tipocontratoListInstance) {
      window.tipocontratoListInstance = null;
   }
   window.tipocontratoListInstance = new List('TipoContrato', {
      valueNames: ['id', 'name', 'initials', 'status'],
      page: 15,
      pagination: true,
      indexAsync: true
   });
   window.tipocontratoListInstance.sort('name', { order: 'asc' });
   window.tipocontratoListInstance.fuzzySearch('');

   setupPaginationEvents(window.tipocontratoListInstance, 15);
   window.tipocontratoListInstance.on('updated', function() {
      updatePaginationInfo(window.tipocontratoListInstance);
   });
   updatePaginationInfo(window.tipocontratoListInstance);
}