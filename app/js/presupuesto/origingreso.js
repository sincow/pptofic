//*********************************************************************************************
document.addEventListener("DOMContentLoaded", function() {
   cargarOrigIngreso();

   //*********************************************************************************************
   const btn = document.getElementById("btnaddOrigIngreso");
   if (btn) {  // si existe el botón, agregar el event listener, puede no existir si el usuario no tiene permisos de agregar
      document.getElementById('btnaddOrigIngreso').addEventListener('click', function(e) {
         e.preventDefault();
         $('#modalOrigIngresoAdd').modal('show');
         document.getElementById('formOrigIngresoAdd').classList.remove('was-validated');
         document.getElementById('formOrigIngresoAdd').reset();
      });
   }
   // Manejar el evento de clic en el botón de agregar
   // $('#formOrigIngresoAdd').on('submit', function(e) {
   document.getElementById('formOrigIngresoAdd').addEventListener('submit', function(e) {
      e.preventDefault();
      // var formData = $(this).serialize();
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarOrigIngreso', '#modalOrigIngresoAdd')) {
      }
   });


   // Manejar el evento de clic en el botón de editar
   //******************************************************************************************
   $('#origingresoTable tbody').on('click', '.edit-btn', function(e) {
      e.preventDefault();
      document.getElementById('formOrigIngresoEdit').reset();
      var data = $(this).parents().parents('tr');
      $('#editId').val(data.find('.id').attr('id'));
      $('#editCodigo').val(data.find('.id').text());
      $('#editNombre').val(data.find('.name').text());
      $('#editStatus').val(data.find('.status').text());
      $('#modalOrigIngresoEdit').modal('show');
      document.getElementById('modalOrigIngresoEdit').classList.remove('was-validated');
   });


   // Manejar el evento de envío del formulario de edición
   //******************************************************************************************
   document.getElementById('formOrigIngresoEdit').addEventListener('submit', function(e) {
      e.preventDefault();
      // var formData = $(this).serialize();
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarOrigIngreso', "#modalOrigIngresoEdit")) {
      }
   })


   // Manejar el evento de búsqueda
   //***********************************************************************  ***************
   $('#searchOrigIngreso').on('keyup', function() {
      // table.search(this.value).draw();
      if (window.origingresoListInstance) {
         window.origingresoListInstance.fuzzySearch(this.value);
         setTimeout(updatePaginationInfo, 50);
      }
   });

})


//*********************************************************************************************
async function cargarOrigIngreso() {
   const formData = new FormData();
   formData.append("modulo", "presupuesto");
   formData.append("option", "origingreso");
   formData.append("action", "index");
   const response = await fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData 
   }).then(resp => resp.json())
   .then( data => {
      const tbody = document.getElementById('origingreso-table-body');
      tbody.innerHTML = '';
      if (data.length > 0) {
         data.forEach(origingreso => {
            const row = document.createElement('tr');
            // Columna ID
            const tdId = document.createElement('td');
            tdId.className = 'align-middle text-660 ps-2 py-1 id';
            tdId.setAttribute('id', origingreso.OrigIngresoId);   
            tdId.textContent = origingreso.OrigIngresoId;
            row.appendChild(tdId);
            
            // Columna name
            const tdName = document.createElement('td');
            tdName.className = 'align-middle text-660 ps-2 py-1 name';
            tdName.textContent = origingreso.Nombre;
            row.appendChild(tdName);

            
            // Columna Status
            const tdStatus = document.createElement('td');
            tdStatus.className = 'align-middle text-660 ps-2 py-1 fs-0 status';
            const statusBadge = document.createElement('span');
            statusBadge.className = origingreso.Estado == 1 ? 
               'badge badge-phoenix badge-phoenix-success p-2' : 'badge badge-phoenix badge-phoenix-danger p-2';
            statusBadge.textContent = origingreso.Estado == 1 ?     "Activo" : "Desactivado";
            tdStatus.appendChild(statusBadge);
            row.appendChild(tdStatus);
            
            const tdActions = document.createElement('td');
            tdActions.className = 'align-middle text-start p-2 py-1';
            if ($('#permiModsw').val() == "1") {
               const editBtn = document.createElement('a');
               editBtn.className = 'btn btn-outline-primary me-1 p-2 py-1 edit-btn';
               editBtn.innerHTML = '<i class="fas fa-edit"></i> Editar';
               editBtn.setAttribute('data-id', origingreso.OrigIngresoId);
               tdActions.appendChild(editBtn);
            }

            if ($('#permiDelsw').val() == "1") {
               const deleteBtn = document.createElement('a');
               deleteBtn.className = 'btn btn-sm btn-outline-danger p-2 py-1 delete-btn';
               deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Desactivar' ;
               if (origingreso.Estado == 0) {
                  deleteBtn.innerHTML = '<i class="fas fa-arrow-rotate-left"></i>Activar';
                  // deleteBtn.style.pointerEvents = 'none';
               }
               deleteBtn.setAttribute('data-id', origingreso.OrigIngresoId);
               deleteBtn.setAttribute('data-status', origingreso.Estado);
               deleteBtn.onclick = function() {
                   eliminarOrigIngreso(this.getAttribute('data-id'), this.getAttribute('data-status'));
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
        td.textContent = 'No se encontraron origenes especificos de ingreso';
        row.appendChild(td);
        tbody.appendChild(row);
         
      }
      updateListJS();
      setTimeout(() => {
         if (window.origingresoListInstance) {
            window.origingresoListInstance.update();
            window.origingresoListInstance.reIndex();
            window.origingresoListInstance.sort('name', { order: 'asc' });
            $('[data-list-info]').text(`${window.origingresoListInstance.visibleItems.length} to ${window.origingresoListInstance.items.length} Items`);
            window.origingresoListInstance.fuzzySearch('');
         }
      }, 100);
   })
   .catch(error => {
      console.error('Error:', error);
      const tbody = document.getElementById('origingreso-table-body');
      tbody.innerHTML = '';
      const row = document.createElement('tr');
      const td = document.createElement('td');
      td.colSpan = 5;
      td.className = 'text-center text-danger py-4';
      td.textContent = 'Error al cargar los origenes especificos de ingreso';
      row.appendChild(td);
      tbody.appendChild(row);
   });

}


/*************************************************************/
function eliminarOrigIngreso(id, status) {
console.log("eliminarOrigIngreso called with id:", id, "status:", status);

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
         formData.append("option", "origingreso");
         formData.append("action", "delete");
         formData.append("id", id);
         formData.append("codigo", id);
         formData.append("status", stanew);
         if (execQueryUpd(formData, 'cargarOrigIngreso', null)) {
         }
      }
   })
}


//*********************************************************************************************
function updateListJS() {
   if (!window.origingresoListInstance) {
      window.origingresoListInstance = null;
   }
   window.origingresoListInstance = new List('OrigIngreso', {
      valueNames: ['id', 'name', 'initials', 'status'],
      page: 15,
      pagination: true,
      indexAsync: true
   });
   window.origingresoListInstance.sort('name', { order: 'asc' });
   window.origingresoListInstance.fuzzySearch('');

   setupPaginationEvents(window.origingresoListInstance, 15);
   window.origingresoListInstance.on('updated', function() {
      updatePaginationInfo(window.origingresoListInstance);
   });
   updatePaginationInfo(window.origingresoListInstance);
}