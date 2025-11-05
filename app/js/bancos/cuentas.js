//*********************************************************************************************
document.addEventListener("DOMContentLoaded", function() {
   cargarCuentas();

   //*********************************************************************************************
   // $("#btnaddCuenta").on('click', function(e) {
   document.getElementById('btnaddCuenta').addEventListener('click', function(e) {
      document.getElementById('newCueCodig').nextElementSibling.classList.remove('is-valid');
      document.getElementById('newCueCodig').nextElementSibling.classList.remove('is-invalid');
      e.preventDefault();
      var listWhere = [];
      listWhere.push({
         "id": "status",
         "value": '1'
      })
      let selectBanco = document.getElementById('newBanCodNa');
      getSelects('dival', 'bancos', selectBanco, 'id_banco', textOpt = ['nombre'], listWhere);
      var listWhere = [];
      listWhere.push({
         "id": "CueMovim",
         "value": '1'
      })
      listWhere.push({
         "id": "CueEstad",
         "value": '1'
      })
      let selectCuenta = document.getElementById('newCueCodig');
      getSelects('contabilidad', 'cuentas', selectCuenta, 'CueCodig', textOpt = ['CueCodig', 'CueNombr'], listWhere);
      $('#modalCuentaAdd').modal('show');
      document.getElementById('formCuentaAdd').classList.remove('was-validated');
      document.getElementById('formCuentaAdd').reset();
   });

   // Manejar el evento de clic en el botón de agregar
   document.getElementById('formCuentaAdd').addEventListener('submit', function(e) {
      e.preventDefault();
      e.stopPropagation();
      const isValid = document.getElementById('newCueCodig').value !== '' && document.getElementById('newCueCodig').value !== null;
      document.getElementById('newCueCodig').nextElementSibling.classList.remove('is-invalid');
      document.getElementById('newCueCodig').nextElementSibling.classList.add(isValid ? 'is-valid' : 'is-invalid');
      // this.classList.add('was-validated');
      if (!isValid) {
         return;
      }
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarCuentas', '#modalCuentaAdd')) {
      }
   });


   //*********************************************************************************************
   // document.getElementById('newCueCodig').addEventListener('change', function(e) {
   $('#newCueCodig').on('select2:select', function(e) {
      const isValid = document.getElementById('newCueCodig').value !== '' && document.getElementById('newCueCodig').value !== null;
      document.getElementById('newCueCodig').nextElementSibling.classList.remove('is-invalid');
      document.getElementById('newCueCodig').nextElementSibling.classList.add(isValid ? 'is-valid' : 'is-invalid');
   });
   $('#editCueCodig').on('select2:select', function(e) {
      const isValid = document.getElementById('editCueCodig').value !== '' && document.getElementById('editCueCodig').value !== null;
      document.getElementById('editCueCodig').nextElementSibling.classList.remove('is-invalid');
      document.getElementById('editCueCodig').nextElementSibling.classList.add(isValid ? 'is-valid' : 'is-invalid');
   });


   // Manejar el evento de clic en el botón de editar
   //******************************************************************************************
   $('#cuentasTable tbody').on('click', '.edit-btn', async function(e) {
      e.preventDefault();
      document.getElementById('formCuentaEdit').reset();
      document.getElementById('editCueCodig').nextElementSibling.classList.remove('is-valid');
      document.getElementById('editCueCodig').nextElementSibling.classList.remove('is-invalid');
      var listWhere = [];
      listWhere.push({
         "id": "status",
         "value": '1'
      })
      let selectBanco = document.getElementById('editBanCodNa');
      await getSelects('dival', 'bancos', selectBanco, 'id_banco', textOpt = ['nombre'], listWhere);
      var listWhere = [];
      listWhere.push({
         "id": "CueMovim",
         "value": '1'
      })
      listWhere.push({
         "id": "CueEstad",
         "value": '1'
      })
      let selectCuenta = document.getElementById('editCueCodig');
      await getSelects('contabilidad', 'cuentas', selectCuenta, 'CueCodig', textOpt = ['CueCodig', 'CueNombr'], listWhere);
      var data = $(this).parents().parents('tr');
      // const cueCodigValue = data.find('.id').attr('CueCodig');
      // $("#editCueCodig").val(cueCodigValue).trigger('change.select2');
      $('#editId').val(data.find('.id').attr('id'));
      $('#editCodigo').val(data.find('.id').text());
      $('#editBanNombr').val(data.find('.name').text());
      $('#editBanCuent').val(data.find('.cuenta').text());
      $('#editBanFeApe').val(data.find('.id').attr('BanFeApe'));
      $("#editCueCodig").val(data.find('.id').attr('CueCodig')).trigger('change.select2');
      $("#editBanCodNa").val(data.find('.id').attr('BanCodNa'));
      $('#editStatus').val(data.find('.status').text());
      // const optionSelect = data.find('.id').attr('BanCodNa');
      // document.getElementById('editBanCodNa').value = optionSelect;
      // const value = document.getElementById('editBanCodNa').value;
      // console.log(document.getElementById('editBanCodNa'));
      // document.getElementById('editBanCodNa').dispatchEvent(new Event('change', { bubbles: true }));
      // document.getElementById('editCueCodig').dispatchEvent(new Event('change'));
      $('#modalCuentaEdit').modal('show');
      document.getElementById('formCuentaEdit').classList.remove('was-validated');
   });

   // Manejar el evento de envío del formulario de edición
   // $('#formCuentaEdit').on('submit', function(e) {
   document.getElementById('formCuentaEdit').addEventListener('submit', function(e) {
      e.preventDefault();
      // var formData = $(this).serialize();
      const isValid = document.getElementById('editCueCodig').value !== '' && document.getElementById('editCueCodig').value !== null;
      document.getElementById('editCueCodig').nextElementSibling.classList.remove('is-invalid');
      document.getElementById('editCueCodig').nextElementSibling.classList.add(isValid ? 'is-valid' : 'is-invalid');
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarCuentas', "#modalCuentaEdit")) {
      }
   })


   // Manejar el evento de búsqueda
   //**************************************************************************************
   $('#searchCuenta').on('keyup', function() {
      // table.search(this.value).draw();
      if (window.cuentasListInstance) {
         window.cuentasListInstance.fuzzySearch(this.value);
         setTimeout(updatePaginationInfo, 50);
      }
   });

})


//*********************************************************************************************
async function cargarCuentas() {
   //$('#cuentas-table-body').empty();
   const formData = new FormData();
   formData.append("modulo", "bancos");
   formData.append("option", "cuentas");
   formData.append("action", "index");
   const response = await fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(resp => resp.json())
   .then( data => {
      const tbody = document.getElementById('cuentas-table-body');
      tbody.innerHTML = '';
      if (data.length > 0) {
         data.forEach(cuenta => {
            const row = document.createElement('tr');
            // Columna ID
            const tdId = document.createElement('td');
            tdId.className = 'align-middle text-660 ps-2 py-1 id';
            tdId.setAttribute('id', cuenta.BanCodig);
            tdId.setAttribute('CueCodig', cuenta.CueCodig);
            tdId.setAttribute('BanFeApe', cuenta.BanFeApe);
            tdId.setAttribute('BanCodNa', cuenta.BanCodNa);
            tdId.textContent = cuenta.BanCodig;
            row.appendChild(tdId);

            const tdName = document.createElement('td');
            tdName.className = 'align-middle text-660 ps-2 py-1 name';
            tdName.textContent = cuenta.BanNombr;
            row.appendChild(tdName);

            const tdDesc = document.createElement('td');
            tdDesc.className = 'align-middle text-660 ps-2 py-1 cuenta';
            tdDesc.textContent = cuenta.BanCuent;
            row.appendChild(tdDesc);

            const tdpuc = document.createElement('td');
            tdpuc.className = 'align-middle text-660 ps-2 py-1 puc';
            tdpuc.textContent = cuenta.CueCodig;
            row.appendChild(tdpuc);

            const tdStatus = document.createElement('td');
            tdStatus.className = 'align-middle text-660 ps-2 py-1 fs-0 status';
            const statusBadge = document.createElement('span');
            statusBadge.className = cuenta.BanEstad == 1 ? 
               'badge badge-phoenix badge-phoenix-success p-2' : 'badge badge-phoenix badge-phoenix-danger p-2';
            statusBadge.textContent = cuenta.BanEstad == 1 ? 
               "Activo" : "Desactivado";
            tdStatus.appendChild(statusBadge);
            row.appendChild(tdStatus);
            
            const tdActions = document.createElement('td');
            tdActions.className = 'align-middle text-start p-2 py-1';
            if ($('#permiModsw').val() == "1") {
               const editBtn = document.createElement('a');
               editBtn.className = 'btn btn-outline-primary me-1 p-2 py-1 edit-btn';
               editBtn.innerHTML = '<i class="fas fa-edit"></i> Editar';
               editBtn.setAttribute('data-id', cuenta.BanCodig);
               // editBtn.onclick = function() {
               //     editarCuenta(this.getAttribute('data-id'));
               // };
               tdActions.appendChild(editBtn);
            }

            if ($('#permiDelsw').val() == "1") {
               const deleteBtn = document.createElement('a');
               deleteBtn.className = 'btn btn-sm btn-outline-danger p-2 py-1 delete-btn';
               deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Desactivar' ;
               if (cuenta.BanEstad == 0) {
                  deleteBtn.innerHTML = '<i class="fas fa-arrow-rotate-left"></i>Activar';
                  // deleteBtn.style.pointerEvents = 'none';
               }
               deleteBtn.setAttribute('data-id', cuenta.BanCodig);
               deleteBtn.setAttribute('data-status', cuenta.BanEstad);
               deleteBtn.onclick = function() {
                  eliminarCuenta(this.getAttribute('data-id'), this.getAttribute('data-status'));
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
        td.textContent = 'No se encontraron cuentas';
        row.appendChild(td);
        tbody.appendChild(row);
      }
      updateListJS();
      setTimeout(() => {
         if (window.cuentasListInstance) {
            window.cuentasListInstance.update();
            window.cuentasListInstance.reIndex();
            window.cuentasListInstance.sort('name', { order: 'asc' });
            $('[data-list-info]').text(`${window.cuentasListInstance.visibleItems.length} to ${window.cuentasListInstance.items.length} Items`);
            window.cuentasListInstance.fuzzySearch('');
         }
      }, 100);
   })
   .catch(error => {
      console.error('Error:', error);
      const tbody = document.getElementById('cuentas-table-body');
      tbody.innerHTML = '';
      const row = document.createElement('tr');
      const td = document.createElement('td');
      td.colSpan = 4;
      td.className = 'text-center text-danger py-4';
      td.textContent = 'Error al cargar las Cuentas';
      row.appendChild(td);
      tbody.appendChild(row);
   });

}


/*************************************************************/
function eliminarCuenta(id, status) {
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
         formData.append("modulo", "bancos");
         formData.append("option", "cuentas");
         formData.append("action", "delete");
         formData.append("id", id);
         formData.append("status", stanew);
         if (execQueryUpd(formData, 'cargarCuentas', null)) {
            // $('#modalCuentaEdit').modal('hide');
            // cargarCuentas();
         }
      }
   })
}


//*********************************************************************************************
function updateListJS() {
   if (!window.cuentasListInstance) {
      window.cuentasListInstance = null;
   }
   window.cuentasListInstance = new List('Cuentas', {
      valueNames: [
         'id', 'name', 'cuenta', 'ctacontable', 'status'
      ],
      page: 15,
      pagination: true,
      indexAsync: true
   });
   window.cuentasListInstance.sort('name', { order: 'asc' });
   window.cuentasListInstance.fuzzySearch('');
}


//*********************************************************************************************
// Función para actualizar la información del paginador
function updatePaginationInfo() {
   if (!window.cuentasListInstance) return;
   const list = window.cuentasListInstance;
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
