//*********************************************************************************************
document.addEventListener("DOMContentLoaded", function() {
   cargarChip();

   //*********************************************************************************************
   const btn = document.getElementById("btnaddChip");
   if (btn) {  // si existe el botón, agregar el event listener, puede no existir si el usuario no tiene permisos de agregar
      document.getElementById('btnaddChip').addEventListener('click', function(e) {
         e.preventDefault();
         $('#modalChipAdd').modal('show');
         document.getElementById('formChipAdd').classList.remove('was-validated');
         document.getElementById('formChipAdd').reset();
      });
   }

   // Manejar el evento de clic en el botón de agregar
   document.getElementById('formChipAdd').addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarChip', '#modalChipAdd')) {
      }
   });

   // Manejar el evento de clic en el botón de editar
   //******************************************************************************************
   $('#chipTable tbody').on('click', '.edit-btn', function(e) {
      e.preventDefault();
      document.getElementById('formChipEdit').reset();
      var data = $(this).parents().parents('tr');
      $('#editId').val(data.find('.id').attr('id'));
      $('#editCodigo').val(data.find('.id').text());
      $('#editNombre').val(data.find('.name').text());
      $('#modalChipEdit').modal('show');
      document.getElementById('modalChipEdit').classList.remove('was-validated');
   });


   // Manejar el evento de envío del formulario de edición
   //******************************************************************************************
   document.getElementById('formChipEdit').addEventListener('submit', function(e) {
      e.preventDefault();
      // var formData = $(this).serialize();
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarChip', "#modalChipEdit")) {
      }
   })


   // Manejar el evento de búsqueda
   //**************************************************************************************
   $('#searchChip').on('keyup', function() {
      // table.search(this.value).draw();
      if (window.chipsListInstance) {
         window.chipsListInstance.fuzzySearch(this.value);
         setTimeout(updatePaginationInfo, 50);
      }
   });

})


//*********************************************************************************************
async function cargarChip() {
   const formData = new FormData();
   formData.append("modulo", "presupuesto");
   formData.append("option", "chip");
   formData.append("action", "index");
   const response = await fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(resp => resp.json())
   .then( data => {
      const tbody = document.getElementById('chip-table-body');
      tbody.innerHTML = '';
      if (data.length > 0) {
         data.forEach(chip => {
            const row = document.createElement('tr');
            // Columna ID
            const tdId = document.createElement('td');
            tdId.className = 'align-middle text-660 ps-2 py-1 id';
            tdId.setAttribute('id', chip.id_chip);
            tdId.textContent = chip.ChipId;
            row.appendChild(tdId);
            
            // Columna name
            const tdName = document.createElement('td');
            tdName.className = 'align-middle text-660 ps-2 py-1 name';
            tdName.textContent = chip.Nombre;
            row.appendChild(tdName);

                        
            
            const tdActions = document.createElement('td');
            tdActions.className = 'align-middle text-start p-2 py-1';
            if ($('#permiModsw').val() == "1") {
               const editBtn = document.createElement('a');
               editBtn.className = 'btn btn-outline-primary me-1 p-2 py-1 edit-btn';
               editBtn.innerHTML = '<i class="fas fa-edit"></i> Editar';
               editBtn.setAttribute('data-id', chip.ChipId);
               tdActions.appendChild(editBtn);
            }

            if ($('#permiDelsw').val() == "1") {
               const deleteBtn = document.createElement('a');
               deleteBtn.className = 'btn btn-sm btn-outline-danger p-2 py-1 delete-btn';
               deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Desactivar' ;
               if (chip.status == 0) {
                  deleteBtn.innerHTML = '<i class="fas fa-arrow-rotate-left"></i>Activar';
                  // deleteBtn.style.pointerEvents = 'none';
               }
               deleteBtn.setAttribute('data-id', chip.ChipId);
               deleteBtn.onclick = function() {
                   eliminarChip(this.getAttribute('data-id'), this.getAttribute('data-status'));
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
        td.textContent = 'No se encontraron codigos chip';
        row.appendChild(td);
        tbody.appendChild(row);

         
      }
      updateListJS();
      setTimeout(() => {
         if (window.chipListInstance) {
            window.chipsListInstance.update();
            window.chipsListInstance.reIndex();
            window.chipsListInstance.sort('name', { order: 'asc' });
            $('[data-list-info]').text(`${window.chipsListInstance.visibleItems.length} to ${window.chipsListInstance.items.length} Items`);
            window.chipsListInstance.fuzzySearch('');
         }
      }, 100);
   })
   .catch(error => {
      console.error('Error:', error);
      const tbody = document.getElementById('chip-table-body');
      tbody.innerHTML = '';
      const row = document.createElement('tr');
      const td = document.createElement('td');
      td.colSpan = 5;
      td.className = 'text-center text-danger py-4';
      td.textContent = 'Error al cargar los codigos chip';
      row.appendChild(td);
      tbody.appendChild(row);
   });

}


/*************************************************************/
function eliminarChip(id, status) {
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
         formData.append("option", "chip");
         formData.append("action", "delete");
         formData.append("id", id);
         formData.append("status", stanew);
         if (execQueryUpd(formData, 'cargarChip', null)) {
         }
      }
   })
}


//*********************************************************************************************
function updateListJS() {
   if (!window.chipsListInstance) {
      window.chipsListInstance = null;
   }
   window.chipsListInstance = new List('Chip', {
      valueNames: ['id', 'name'],
      page: 15,
      pagination: true,
      indexAsync: true
   });
   window.chipsListInstance.sort('name', { order: 'asc' });
   window.chipsListInstance.fuzzySearch('');

   setupPaginationEvents(window.chipsListInstance, 15);
   window.chipsListInstance.on('updated', function() {
      updatePaginationInfo(window.chipsListInstance);
   });
   updatePaginationInfo(window.chipsListInstance);
}