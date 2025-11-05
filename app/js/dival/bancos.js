//*********************************************************************************************
document.addEventListener("DOMContentLoaded", function() {
   cargarBancos();

   //*********************************************************************************************
   // $("#btnaddBanco").on('click', function(e) {
   document.getElementById('btnaddBanco').addEventListener('click', function(e) {
      e.preventDefault();
      $('#modalBancoAdd').modal('show');
      document.getElementById('formBancoAdd').classList.remove('was-validated');
      document.getElementById('formBancoAdd').reset();
   });

   // Manejar el evento de clic en el botón de agregar
   // $('#formBancoAdd').on('submit', function(e) {
   document.getElementById('formBancoAdd').addEventListener('submit', function(e) {
      e.preventDefault();
      // var formData = $(this).serialize();
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarBancos', '#modalBancoAdd')) {
      }
   });


   // Manejar el evento de clic en el botón de editar
   //******************************************************************************************
   $('#bancosTable tbody').on('click', '.edit-btn', function(e) {
      e.preventDefault();
      document.getElementById('formBancoEdit').reset();
      var data = $(this).parents().parents('tr');
      $('#editId').val(data.find('.id').attr('id'));
      $('#editCodigo').val(data.find('.id').text());
      $('#editNombre').val(data.find('.name').text());
      $('#editIniciales').val(data.find('.iniciales').text());
      $('#editStatus').val(data.find('.status').text());
      $('#modalBancoEdit').modal('show');
      document.getElementById('modalBancoEdit').classList.remove('was-validated');
   });

   // Manejar el evento de envío del formulario de edición
   // $('#formBancoEdit').on('submit', function(e) {
   document.getElementById('formBancoEdit').addEventListener('submit', function(e) {
      e.preventDefault();
      // var formData = $(this).serialize();
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarBancos', "#modalBancoEdit")) {
      }
   })


   // Manejar el evento de búsqueda
   //**************************************************************************************
   $('#searchBanco').on('keyup', function() {
      // table.search(this.value).draw();
      if (window.bancosListInstance) {
         window.bancosListInstance.fuzzySearch(this.value);
         setTimeout(updatePaginationInfo, 50);
      }
   });

})


//*********************************************************************************************
async function cargarBancos() {
   //$('#bancos-table-body').empty();
   const formData = new FormData();
   formData.append("modulo", "dival");
   formData.append("option", "bancos");
   formData.append("action", "index");
   const response = await fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(resp => resp.json())
   .then( data => {
      const tbody = document.getElementById('bancos-table-body');
      tbody.innerHTML = '';
      if (data.length > 0) {
         data.forEach(banco => {
            const row = document.createElement('tr');
            // Columna ID
            const tdId = document.createElement('td');
            tdId.className = 'align-middle text-660 ps-2 py-1 id';
            tdId.setAttribute('id', banco.id_banco);
            tdId.textContent = banco.codigo;
            row.appendChild(tdId);
            
            // Columna name
            const tdName = document.createElement('td');
            tdName.className = 'align-middle text-660 ps-2 py-1 name';
            tdName.textContent = banco.nombre;
            row.appendChild(tdName);

            // Columna Descripción
            const tdDesc = document.createElement('td');
            // tdDesc.className = 'white-space-nowrap align-middle ps-2';
            tdDesc.className = 'align-middle text-660 ps-2 py-1 iniciales';
            tdDesc.textContent = banco.iniciales;
            row.appendChild(tdDesc);
            
            // Columna Status
            const tdStatus = document.createElement('td');
            tdStatus.className = 'align-middle text-660 ps-2 py-1 fs-0 status';
            const statusBadge = document.createElement('span');
            statusBadge.className = banco.status == 1 ? 
               'badge badge-phoenix badge-phoenix-success p-2' : 'badge badge-phoenix badge-phoenix-danger p-2';
            statusBadge.textContent = banco.status == 1 ? 
               "Activo" : "Desactivado";
            tdStatus.appendChild(statusBadge);
            row.appendChild(tdStatus);
            
            const tdActions = document.createElement('td');
            tdActions.className = 'align-middle text-start p-2 py-1';
            if ($('#permiModsw').val() == "1") {
               const editBtn = document.createElement('a');
               editBtn.className = 'btn btn-outline-primary me-1 p-2 py-1 edit-btn';
               editBtn.innerHTML = '<i class="fas fa-edit"></i> Editar';
               editBtn.setAttribute('data-id', banco.id_banco);
               // editBtn.onclick = function() {
               //     editarBanco(this.getAttribute('data-id'));
               // };
               tdActions.appendChild(editBtn);
            }

            if ($('#permiDelsw').val() == "1") {
               const deleteBtn = document.createElement('a');
               deleteBtn.className = 'btn btn-sm btn-outline-danger p-2 py-1 delete-btn';
               deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Desactivar' ;
               if (banco.status == 0) {
                  deleteBtn.innerHTML = '<i class="fas fa-arrow-rotate-left"></i>Activar';
                  // deleteBtn.style.pointerEvents = 'none';
               }
               deleteBtn.setAttribute('data-id', banco.id_banco);
               deleteBtn.setAttribute('data-status', banco.status);
               deleteBtn.onclick = function() {
                   eliminarBanco(this.getAttribute('data-id'), this.getAttribute('data-status'));
               };
               tdActions.appendChild(deleteBtn);
            }
            row.appendChild(tdActions);
            tbody.appendChild(row);
         });
      } else {
        const row = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan = 4;
        td.className = 'text-center text-muted py-4';
        td.textContent = 'No se encontraron bancos';
        row.appendChild(td);
        tbody.appendChild(row);

         // let vendorSelect = document.getElementById('vendorSelect');
         // data.forEach(vendor => {
         //    let option = document.createElement('option');
         //    option.value = vendor.id;
         //    option.text = vendor.name;
         //    vendorSelect.appendChild(option);
         // });
      }
      updateListJS();
      setTimeout(() => {
         if (window.bancosListInstance) {
            window.bancosListInstance.update();
            window.bancosListInstance.reIndex();
            window.bancosListInstance.sort('name', { order: 'asc' });
            $('[data-list-info]').text(`${window.bancosListInstance.visibleItems.length} to ${window.bancosListInstance.items.length} Items`);
            window.bancosListInstance.fuzzySearch('');
         }
      }, 100);
   })
   .catch(error => {
      console.error('Error:', error);
      const tbody = document.getElementById('bancos-table-body');
      tbody.innerHTML = '';
      const row = document.createElement('tr');
      const td = document.createElement('td');
      td.colSpan = 4;
      td.className = 'text-center text-danger py-4';
      td.textContent = 'Error al cargar los bancos';
      row.appendChild(td);
      tbody.appendChild(row);
   });

}


/*************************************************************/
function eliminarBanco(id, status) {
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
         formData.append("modulo", "dival");
         formData.append("option", "bancos");
         formData.append("action", "delete");
         formData.append("id", id);
         formData.append("status", stanew);
         if (execQueryUpd(formData, 'cargarBancos', null)) {
         }
      }
   })
}


//*********************************************************************************************
function updateListJS() {
   if (!window.bancosListInstance) {
      window.bancosListInstance = null;
   }
   window.bancosListInstance = new List('Bancos', {
      valueNames: [
         'id', 'name', 'initials', 'status'
      ],
      page: 15,
      pagination: true,
      indexAsync: true
   });
   window.bancosListInstance.sort('name', { order: 'asc' });
   window.bancosListInstance.fuzzySearch('');
}


//*********************************************************************************************
// Función para actualizar la información del paginador
function updatePaginationInfo() {
   if (!window.bancosListInstance) return;
   const list = window.bancosListInstance;
   const visibleItems = list.visibleItems.length;
   const currentPage = list.i;  // Página actual (comienza en 1)
   const itemsPerPage = list.page;  // Items por página (20)
   let start = 0;
   let end = 0;
   if (visibleItems > 0) {
      start = (currentPage - 1) * itemsPerPage + 1;
      end = Math.min(currentPage * itemsPerPage, visibleItems);
      start = Math.min(start, end); // Asegurar que start <= end
   }
   const infoText = `${start} to ${end} of ${visibleItems}`;
   $('[data-list-info]').text(infoText);
}


