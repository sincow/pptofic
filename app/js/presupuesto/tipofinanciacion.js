//*********************************************************************************************
document.addEventListener("DOMContentLoaded", function() {
   cargarTipoFinanciacion();

   //*********************************************************************************************
   const btn = document.getElementById("btnaddTipoFinanciacion");
   if (btn) {  // si existe el botón, agregar el event listener, puede no existir si el usuario no tiene permisos de agregar
      document.getElementById('btnaddTipoFinanciacion').addEventListener('click', function(e) {
         e.preventDefault();
         $('#modalTipoFinanciacionAdd').modal('show');
         document.getElementById('formTipoFinanciacionAdd').classList.remove('was-validated');
         document.getElementById('formTipoFinanciacionAdd').reset();
      });
   }
   // Manejar el evento de clic en el botón de agregar
   // $('#formTipoFinanciacionAdd').on('submit', function(e) {
   document.getElementById('formTipoFinanciacionAdd').addEventListener('submit', function(e) {
      e.preventDefault();
      // var formData = $(this).serialize();
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarTipoFinanciacion', '#modalTipoFinanciacionAdd')) {
      }
   });


   // Manejar el evento de clic en el botón de editar
   //******************************************************************************************
   $('#tipoFinanciacionTable tbody').on('click', '.edit-btn', function(e) {
      e.preventDefault();
      document.getElementById('formTipoFinanciacionEdit').reset();
      var data = $(this).parents().parents('tr');
      $('#editId').val(data.find('.id').attr('id'));
      $('#editCodigo').val(data.find('.id').text());
      $('#editNombre').val(data.find('.name').text());
      $('#editStatus').val(data.find('.status').text());
      $('#modalTipoFinanciacionEdit').modal('show');
      document.getElementById('modalTipoFinanciacionEdit').classList.remove('was-validated');
   });


   // Manejar el evento de envío del formulario de edición
   //******************************************************************************************
   document.getElementById('formTipoFinanciacionEdit').addEventListener('submit', function(e) {
      e.preventDefault();
      // var formData = $(this).serialize();
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarTipoFinanciacion', "#modalTipoFinanciacionEdit")) {
      }
   })


   // Manejar el evento de búsqueda
   //***********************************************************************  ***************
   $('#searchTipoFinanciacion').on('keyup', function() {
      // table.search(this.value).draw();
      if (window.tipoFinanciacionListInstance) {
         window.tipoFinanciacionListInstance.fuzzySearch(this.value);
         setTimeout(updatePaginationInfo, 50);
      }
   });

})


//*********************************************************************************************
async function cargarTipoFinanciacion() {
   const formData = new FormData();
   formData.append("modulo", "presupuesto");
   formData.append("option", "tipofinanciacion");
   formData.append("action", "index");
   const response = await fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData 
   }).then(resp => resp.json())
   .then( data => {
      const tbody = document.getElementById('tipoFinanciacion-table-body');
      tbody.innerHTML = '';
      if (data.length > 0) {
         data.forEach(tipofinanciacion => {
            const row = document.createElement('tr');
            // Columna ID
            const tdId = document.createElement('td');
            tdId.className = 'align-middle text-660 ps-2 py-1 id';
            tdId.setAttribute('id', tipofinanciacion.TipoFinanciacionId);   
            tdId.textContent = tipofinanciacion.TipoFinanciacionId;
            row.appendChild(tdId);
            
            // Columna name
            const tdName = document.createElement('td');
            tdName.className = 'align-middle text-660 ps-2 py-1 name';
            tdName.textContent = tipofinanciacion.Nombre;
            row.appendChild(tdName);

            
            // Columna Status
            const tdStatus = document.createElement('td');
            tdStatus.className = 'align-middle text-660 ps-2 py-1 fs-0 status';
            const statusBadge = document.createElement('span');
            statusBadge.className = tipofinanciacion.Estado == 1 ? 
               'badge badge-phoenix badge-phoenix-success p-2' : 'badge badge-phoenix badge-phoenix-danger p-2';
            statusBadge.textContent = tipofinanciacion.Estado == 1 ?     "Activo" : "Desactivado";
            tdStatus.appendChild(statusBadge);
            row.appendChild(tdStatus);
            
            const tdActions = document.createElement('td');
            tdActions.className = 'align-middle text-start p-2 py-1';
            if ($('#permiModsw').val() == "1") {
               const editBtn = document.createElement('a');
               editBtn.className = 'btn btn-outline-primary me-1 p-2 py-1 edit-btn';
               editBtn.innerHTML = '<i class="fas fa-edit"></i> Editar';
               editBtn.setAttribute('data-id', tipofinanciacion.TipoFinanciacionId);
               tdActions.appendChild(editBtn);
            }

            if ($('#permiDelsw').val() == "1") {
               const deleteBtn = document.createElement('a');
               deleteBtn.className = 'btn btn-sm btn-outline-danger p-2 py-1 delete-btn';
               deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Desactivar' ;
               if (tipofinanciacion.Estado == 0) {
                  deleteBtn.innerHTML = '<i class="fas fa-arrow-rotate-left"></i>Activar';
                  // deleteBtn.style.pointerEvents = 'none';
               }
               deleteBtn.setAttribute('data-id', tipofinanciacion.TipoFinanciacionId);
               deleteBtn.setAttribute('data-status', tipofinanciacion.Estado);
               deleteBtn.onclick = function() {
                   eliminarTipoFinanciacion(this.getAttribute('data-id'), this.getAttribute('data-status'));
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
        td.textContent = 'No se encontraron tipos de financiacion registrados';
        row.appendChild(td);
        tbody.appendChild(row);
         
      }
      updateListJS();
      setTimeout(() => {
         if (window.tipoFinanciacionListInstance) {
            window.tipoFinanciacionListInstance.update();
            window.tipoFinanciacionListInstance.reIndex();
            window.tipoFinanciacionListInstance.sort('name', { order: 'asc' });
            $('[data-list-info]').text(`${window.tipoFinanciacionListInstance.visibleItems.length} to ${window.tipoFinanciacionListInstance.items.length} Items`);
            window.tipoFinanciacionListInstance.fuzzySearch('');
         }
      }, 100);
   })
   .catch(error => {
      console.error('Error:', error);
      const tbody = document.getElementById('tipoFinanciacion-table-body');
      tbody.innerHTML = '';
      const row = document.createElement('tr');
      const td = document.createElement('td');
      td.colSpan = 5;
      td.className = 'text-center text-danger py-4';
      td.textContent = 'Error al cargar los tipos de financiacion registrados';
      row.appendChild(td);
      tbody.appendChild(row);
   });

}


/*************************************************************/
function eliminarTipoFinanciacion(id, status) {
console.log("eliminarTipoFinanciacion called with id:", id, "status:", status);

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
         formData.append("option", "tipofinanciacion");
         formData.append("action", "delete");
         formData.append("id", id);
         formData.append("codigo", id);
         formData.append("status", stanew);
         if (execQueryUpd(formData, 'cargarTipoFinanciacion', null)) {
         }
      }
   })
}


//*********************************************************************************************
function updateListJS() {
   if (!window.tipoFinanciacionListInstance) {
      window.tipoFinanciacionListInstance = null;
   }
   window.tipoFinanciacionListInstance = new List('TipoFinanciacion', {
      valueNames: ['id', 'name', 'initials', 'status'],
      page: 15,
      pagination: true,
      indexAsync: true
   });
   window.tipoFinanciacionListInstance.sort('name', { order: 'asc' });
   window.tipoFinanciacionListInstance.fuzzySearch('');

   setupPaginationEvents(window.tipoFinanciacionListInstance, 15);
   window.tipoFinanciacionListInstance.on('updated', function() {
      updatePaginationInfo(window.tipoFinanciacionListInstance);
   });
   updatePaginationInfo(window.tipoFinanciacionListInstance);
}