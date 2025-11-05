//*********************************************************************************************
document.addEventListener("DOMContentLoaded", function() {
   cargarCtaclien();

   //*********************************************************************************************
   // $("#btnaddCtaclien").on('click', function(e) {
   document.getElementById('btnaddCtaclien').addEventListener('click', function(e) {
      e.preventDefault();
      var listWhere = [];
      listWhere.push({
         "id": "status",
         "value": '1'
      })
      let selectBanco = document.getElementById('newBanCodNa');
      getSelects('dival', 'bancos', selectBanco, 'id_banco', textOpt = ['nombre'], listWhere);
      $('#modalCtaclienAdd').modal('show');
      document.getElementById('formCtaclienAdd').classList.remove('was-validated');
      document.getElementById('formCtaclienAdd').reset();
   });

   // Manejar el evento de clic en el botón de agregar
   //*********************************************************************************************
   // $('#formCtaclienAdd').on('submit', function(e) {
   document.getElementById('formCtaclienAdd').addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarCtaclien', '#modalCtaclienAdd')) {
      }
   });


   //*********************************************************************************************
   $('#ctaclienTable tbody').on('click', '.edit-btn', async function(e) {
      e.preventDefault();
      document.getElementById('formCtaclienEdit').reset();
      var listWhere = [];
      listWhere.push({
         "id": "status",
         "value": '1'
      })
      let selectBanco = document.getElementById('editBanCodNa');
      await getSelects('dival', 'bancos', selectBanco, 'id_banco', textOpt = ['nombre'], listWhere);
      var data = $(this).parents().parents('tr');
      document.getElementById('id_bancli').value = data.find('.id').attr('id_bancli');
      document.getElementById('id_dvcliente').value = data.find('.id').attr('id');
      document.getElementById(`editTerNombr`).value = data.find('.id').text()+' '+data.find('.name').text();
      document.getElementById(`editBanCodNa`).value = data.find('.banco').attr('id_banco');
      document.getElementById(`editsucursal`).value = data.find('.sucursal').text();
      document.getElementById(`editnumero_cuenta`).value = data.find('.cuenta').text();
      $('#modalCtaclienEdit').modal('show');
      document.getElementById('modalCtaclienEdit').classList.remove('was-validated');
   });

   // Manejar el evento de envío del formulario de edición
   //*********************************************************************************************
   // $('#formCtaclienEdit').on('submit', function(e) {
   document.getElementById('formCtaclienEdit').addEventListener('submit', function(e) {
      e.preventDefault();
      // var formData = $(this).serialize();
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarCtaclien', "#modalCtaclienEdit")) {
      }
   });


   //*********************************************************************************************
   document.querySelectorAll('.client-search').forEach(input => {
      initAutocomplete(input, 25, 150);
   });

});


//*********************************************************************************************
async function cargarCtaclien() {
   const formData = new FormData();
   formData.append("modulo", "dival");
   formData.append("option", "ctaclien");
   formData.append("action", "index");
   const response = await fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(resp => resp.json())
   .then( data => {
      const tbody = document.getElementById('ctaclien-table-body');
      tbody.innerHTML = '';
      if (data.length > 0) {
         data.forEach(ctaclien => {
            const row = document.createElement('tr');
            // Columna ID
            const tdId = document.createElement('td');
            tdId.className = 'align-middle text-660 ps-2 py-1 id';
            tdId.setAttribute('id', ctaclien.id_dvcliente);
            tdId.setAttribute('id_bancli', ctaclien.id_bancli);
            tdId.setAttribute('TerEmail', ctaclien.TerEmail);
            tdId.textContent = ctaclien.TerDocId;
            row.appendChild(tdId);

            const tdName = document.createElement('td');
            tdName.className = 'align-middle text-660 ps-2 py-1 name';
            tdName.textContent = ctaclien.TerNombr;
            row.appendChild(tdName);

            const tdDesc = document.createElement('td');
            tdDesc.className = 'align-middle text-660 ps-2 py-1 cuenta';
            tdDesc.textContent = ctaclien.numero_cuenta;
            row.appendChild(tdDesc);

            const tdbanco = document.createElement('td');
            tdbanco.className = 'align-middle text-660 ps-2 py-1 banco';
            tdbanco.setAttribute('id_banco', ctaclien.id_banco);
            tdbanco.textContent = ctaclien.BanNomNa;
            row.appendChild(tdbanco);

            const tdpuc = document.createElement('td');
            tdpuc.className = 'align-middle text-660 ps-2 py-1 sucursal';
            tdpuc.textContent = ctaclien.sucursal;
            row.appendChild(tdpuc);

            const tdStatus = document.createElement('td');
            tdStatus.className = 'align-middle text-660 ps-2 py-1 fs-0 status';
            const statusBadge = document.createElement('span');
            statusBadge.className = ctaclien.status == 1 ? 
               'badge badge-phoenix badge-phoenix-success p-2' : 'badge badge-phoenix badge-phoenix-danger p-2';
            statusBadge.textContent = ctaclien.status == 1 ? 
               "Activo" : "Desactivado";
            tdStatus.appendChild(statusBadge);
            row.appendChild(tdStatus);
            
            const tdActions = document.createElement('td');
            tdActions.className = 'align-middle text-start p-2 py-1';
            if ($('#permiModsw').val() == "1") {
               const editBtn = document.createElement('a');
               editBtn.className = 'btn btn-outline-primary me-1 p-2 py-1 edit-btn';
               editBtn.innerHTML = '<i class="fas fa-edit"></i> Editar';
               editBtn.setAttribute('data-id', ctaclien.BanCodig);
               // editBtn.onclick = function() {
               //     editarCtaclien(this.getAttribute('data-id'));
               // };
               tdActions.appendChild(editBtn);
            }

            if ($('#permiDelsw').val() == "1") {
               const deleteBtn = document.createElement('a');
               deleteBtn.className = 'btn btn-sm btn-outline-danger p-2 py-1 delete-btn';
               deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Desactivar' ;
               if (ctaclien.status == 0) {
                  deleteBtn.innerHTML = '<i class="fas fa-arrow-rotate-left"></i>Activar';
                  // deleteBtn.style.pointerEvents = 'none';
               }
               deleteBtn.setAttribute('data-id', ctaclien.BanCodig);
               deleteBtn.setAttribute('data-status', ctaclien.status);
               deleteBtn.onclick = function() {
                   eliminarCtaclien(this.getAttribute('data-id'), this.getAttribute('data-status'));
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
         if (window.ctaclienListInstance) {
            window.ctaclienListInstance.update();
            window.ctaclienListInstance.reIndex();
            window.ctaclienListInstance.sort('name', { order: 'asc' });
            $('[data-list-info]').text(`${window.ctaclienListInstance.visibleItems.length} to ${window.ctaclienListInstance.items.length} Items`);
            window.ctaclienListInstance.fuzzySearch('');
         }
      }, 100);
   })
   .catch(error => {
      console.error('Error:', error);
      const tbody = document.getElementById('ctaclien-table-body');
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


//***********************************************************************************************
function eliminarCtaclien(id, status) {
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
      cancelButtonText: "No",
      confirmButtonText: botcon
   }).then(function (result) {
      if (result.value) {
         const formData = new FormData();
         formData.append("modulo", "dival");
         formData.append("option", "ctaclien");
         formData.append("action", "delete");
         formData.append("id", id);
         formData.append("status", stanew);
         if (execQueryUpd(formData, 'cargarCtaclien', null)) {
            // $('#modalCtaclienEdit').modal('hide');
            // cargarCtaclien();
         }
      }
   })
}


//***********************************************************************************************
function updateListJS() {
   if (!window.ctaclienListInstance) {
      window.ctaclienListInstance = null;
   }
   window.ctaclienListInstance = new List('Ctaclien', {
      valueNames: [
         'id', 'name', 'cuenta', 'ctacontable', 'status'
      ],
      page: 20,
      pagination: true,
      indexAsync: true
   });
   window.ctaclienListInstance.sort('name', { order: 'asc' });
   window.ctaclienListInstance.fuzzySearch('');
}


//***********************************************************************************************
function selectClient(selectedItem, inputElement) {
   const clientData = JSON.parse(selectedItem.dataset.clientData);
   // Establecer dueño seleccionado
   document.getElementById('idCliente').value = clientData.id_dvcliente;
   document.getElementById('id_dvcliente').dispatchEvent(new Event('change', { bubbles: true }));
   inputElement.value = `${clientData.TerDocId} ${clientData.TerNombr} (${clientData.TerEmail})`;
   // Cargar mascotas del dueño
   // loadCtasByClient(clientData.id_dvcliente);
   hideSuggestions();
}

/*
//***********************************************************************************************
function initAutocomplete(inputElement) {
   if (!inputElement) return;
   let timeoutId;
   inputElement.addEventListener('input', function (e) {
      clearTimeout(timeoutId);
      const searchTerm = e.target.value.trim();
      if (searchTerm.length < 2) {
         hideSuggestions();
         resetOwnerSelection();
         return;
      }
      timeoutId = setTimeout(() => {
         searchClients(searchTerm, inputElement);
      }, 300);
   });

   // Manejar selección con teclado
   inputElement.addEventListener('keydown', function (e) {
      const suggestions = document.getElementById('clientSuggestions');
      if (!suggestions || suggestions.style.display === 'none') return;
      const items = suggestions.querySelectorAll('.list-group-item');
      let activeItem = suggestions.querySelector('.active');
      switch (e.key) {
         case 'ArrowDown':
            e.preventDefault();
            if (!activeItem) {
               items[0]?.classList.add('active');
            } else {
               activeItem.classList.remove('active');
               const next = activeItem.nextElementSibling || items[0];
               next.classList.add('active');
            }
            break;
         case 'ArrowUp':
            e.preventDefault();
            if (!activeItem) {
               items[items.length - 1]?.classList.add('active');
            } else {
               activeItem.classList.remove('active');
               const prev = activeItem.previousElementSibling || items[items.length - 1];
               prev.classList.add('active');
            }
            break;
         case 'Enter':
            e.preventDefault();
            if (activeItem) {
               selectClient(activeItem, inputElement);
            }
            break;
         case 'Escape':
            hideSuggestions();
            break;
      }
   });
}

//***********************************************************************************************
function searchClients(searchTerm, inputElement) {
   let listWhere = [];
   listWhere.push({
      "id": "status",
      "value": '1',
      "like": false
   })
   listWhere.push({
      "id": "name",
      "value": searchTerm,
      "like": true
   })
   listWhere = JSON.stringify(listWhere);
   const formData = new FormData();
   formData.append("modulo", "dival");
   formData.append("option", "clientes");
   formData.append("action", "searchClient");
   formData.append("searchTerm", searchTerm);
   fetch('helpers/ajaxRouter.php', {
         method: 'POST',
         body: formData
   }).then(response => response.json())
   .then(clients => {
      showClientSuggestions(clients, inputElement);
   })
   .catch(error => {
      console.error('Error:', error);
   });
}

//***********************************************************************************************
function showClientSuggestions(clients, inputElement) {
   hideSuggestions();
   if (clients.length === 0) return;
   const suggestionsDiv = document.createElement('div');
   suggestionsDiv.id = 'clientSuggestions';
   suggestionsDiv.className = 'list-group position-absolute';
   suggestionsDiv.style.zIndex = '1000';
   suggestionsDiv.style.width = inputElement.offsetWidth + 'px';
   suggestionsDiv.style.maxHeight = '200px';
   suggestionsDiv.style.overflowY = 'auto';
   clients.forEach(client => {
      const item = document.createElement('button');
      item.type = 'button';
      item.className = 'list-group-item list-group-item-action';
      item.innerHTML = `
      <div class="fw-bold">${client.TerDocId} ${client.TerNombr}</div>
      <small class="text-label">${client.numero_cuenta} | ${client.BanNomNa}</small>
      ${client.sucursal ? `<br><small class="text-label">Doc: ${client.sucursal}</small>` : ''}
      `;
      item.dataset.clientId = client.id_dvcliente;
      item.dataset.clientData = JSON.stringify(client);
      item.addEventListener('click', function () {
         selectClient(this, inputElement);
      });
      item.addEventListener('mouseenter', function () {
         suggestionsDiv.querySelectorAll('.list-group-item').forEach(i => i.classList.remove('active'));
         this.classList.add('active');
      });
      suggestionsDiv.appendChild(item);
   });

   // Posicionar debajo del input
   const rect = inputElement.getBoundingClientRect();
   // suggestionsDiv.style.top = (rect.bottom + window.scrollY) + 'px';
   // suggestionsDiv.style.left = (rect.left + window.scrollX) + 'px';
   suggestionsDiv.style.top = (150) + 'px';
   suggestionsDiv.style.left = (25) + 'px';
   //document.getElementById('formCtaclienAdd').appendChild(suggestionsDiv);
   document.getElementsByClassName('form-search')[0].appendChild(suggestionsDiv);
   // document.body.appendChild(suggestionsDiv);
}

//***********************************************************************************************
function selectClient(selectedItem, inputElement) {
   const clientData = JSON.parse(selectedItem.dataset.clientData);
   // Establecer dueño seleccionado
   document.getElementById('id_dvcliente').value = clientData.id_dvcliente;
   inputElement.value = `${clientData.TerDocId} ${clientData.TerNombr} (${clientData.TerEmail})`;
   // Cargar mascotas del dueño
   // loadPetsByOwner(clientData.id_dvcliente);
   hideSuggestions();
}

//***********************************************************************************************
function resetOwnerSelection() {
   document.getElementById('id_dvcliente').value = '';
   // const petSelect = document.getElementById('id_pet');
   // petSelect.innerHTML = '<option value="">Seleccionar Mascota</option>';
   // petSelect.disabled = true;
}

//***********************************************************************************************
function hideSuggestions() {
   const existing = document.getElementById('clientSuggestions');
   if (existing) {
      existing.remove();
   }
}
*/