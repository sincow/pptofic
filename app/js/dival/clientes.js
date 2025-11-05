//*********************************************************************************************
document.addEventListener("DOMContentLoaded", function() {
   cargarClientes();

   //*********************************************************************************************
   // $("#btnaddCliente").on('click', async function(e) {
   document.getElementById('btnaddCliente').addEventListener('click', async function(e) {
      e.preventDefault();
      document.getElementById('newCiuCodig').nextElementSibling.classList.remove('is-valid');
      document.getElementById('newCiuCodig').nextElementSibling.classList.remove('is-invalid');
      var listWhere = [];
      listWhere.push({
         "id": "TabCodig",
         "value": '01'
      })
      listWhere.push({
         "id": "TabEstad",
         "value": '1'
      })
      let selectTipDoc = document.getElementById('newTerTiDoc');
      await getSelects('admon', 'tablas', selectTipDoc, 'TabNive1', textOpt = ['TabNive1', 'TabNive2'], listWhere);

      var listWhere = [];
      listWhere.push({
         "id": "CiuEstad",
         "value": '1'
      })
      let selectCity = document.getElementById('newCiuCodig');
      await getSelects('admon', 'ciudades', selectCity, 'CiuCodig', textOpt = ['CiuCodig', 'CiuNombr'], listWhere);
      var listWhere = [];
      listWhere.push({
         "id": "status",
         "value": '1'
      })
      let selectActEco = document.getElementById('newTerAcEco');
      await getSelects('admon', 'activi', selectActEco, 'id_actividad', textOpt = ['codigo', 'nombre'], listWhere);
      $('#modalClienteAdd').modal('show');
      document.getElementById('formClienteAdd').classList.remove('was-validated');
      document.getElementById('formClienteAdd').reset();
   });

   // $('#formClienteAdd').on('submit', function(e) {
   document.getElementById('formClienteAdd').addEventListener('submit', function(e) {
      e.preventDefault();
      // var formData = $(this).serialize();
      const isValid = document.getElementById('newCiuCodig').value !== '' && document.getElementById('newCiuCodig').value !== null;
      document.getElementById('newCiuCodig').nextElementSibling.classList.remove('is-invalid');
      document.getElementById('newCiuCodig').nextElementSibling.classList.add(isValid ? 'is-valid' : 'is-invalid');
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarClientes', '#modalClienteAdd')) {
      }
   });

   /*
   var selTipDoc = document.getElementsByName('TerTiDoc')[0];
   selTipDoc.addEventListener('change', function() {
      selectedValue = this.value;
      if (selTipDoc.value == '31') {
         document.getElementsByName('TerNomb1')[0].disabled = true;
         document.getElementsByName('TerNomb2')[0].disabled = true;
         document.getElementsByName('TerApel1')[0].disabled = true;
         document.getElementsByName('TerApel2')[0].disabled = true;
         document.getElementsByName('TerRaSoc')[0].disabled = false;
         document.getElementsByName('TerRaSoc')[0].setAttribute('required', 'required');
         document.getElementsByName('TerNomb1')[0].removeAttribute('required');
         document.getElementsByName('TerNomb2')[0].removeAttribute('required');
         document.getElementsByName('TerApel1')[0].removeAttribute('required');
         document.getElementsByName('TerApel2')[0].removeAttribute('required');
         document.getElementsByName('TerRaSoc')[0].focus();
      } else {
         document.getElementsByName('TerNomb1')[0].disabled = false;
         document.getElementsByName('TerNomb2')[0].disabled = false;
         document.getElementsByName('TerApel1')[0].disabled = false;
         document.getElementsByName('TerApel2')[0].disabled = false;
         document.getElementsByName('TerRaSoc')[0].disabled = true;
         document.getElementsByName('TerRaSoc')[0].value = '';
         document.getElementsByName('TerRaSoc')[0].removeAttribute('required');
         document.getElementsByName('TerNomb1')[0].setAttribute('required', 'required');
         document.getElementsByName('TerNomb2')[0].setAttribute('required', 'required');
         document.getElementsByName('TerApel1')[0].setAttribute('required', 'required');
         document.getElementsByName('TerApel2')[0].setAttribute('required', 'required');
         document.getElementsByName('TerNomb1')[0].focus();
      }
   });
   */


   //******************************************************************************************
   var newTipDoc = document.getElementsByName('TerTiDoc');
   Array.from(newTipDoc).forEach((elemento, index) => {
      elemento.addEventListener('change', function(e) {
         console.log('Evento disparado por:', e.target);
         console.log('Valor seleccionado:', e.target.value);
         console.log('Índice:', index);
         console.log('Name:', e.target.name);
         changeInpusts(e.target.value, index);
      });
   });


   //******************************************************************************************
   function changeInpusts(selTipDoc, e) {
      if (selTipDoc == '31') {
         document.getElementsByName('TerNomb1')[e].disabled = true;
         document.getElementsByName('TerNomb2')[e].disabled = true;
         document.getElementsByName('TerApel1')[e].disabled = true;
         document.getElementsByName('TerApel2')[e].disabled = true;
         document.getElementsByName('TerRaSoc')[e].disabled = false;
         document.getElementsByName('TerRaSoc')[e].setAttribute('required', 'required');
         document.getElementsByName('TerNomb1')[e].removeAttribute('required');
         document.getElementsByName('TerNomb2')[e].removeAttribute('required');
         document.getElementsByName('TerApel1')[e].removeAttribute('required');
         document.getElementsByName('TerApel2')[e].removeAttribute('required');
         document.getElementsByName('TerRaSoc')[e].focus();
      } else {
         document.getElementsByName('TerNomb1')[e].disabled = false;
         document.getElementsByName('TerNomb2')[e].disabled = false;
         document.getElementsByName('TerApel1')[e].disabled = false;
         document.getElementsByName('TerApel2')[e].disabled = false;
         document.getElementsByName('TerRaSoc')[e].disabled = true;
         document.getElementsByName('TerRaSoc')[e].value = '';
         document.getElementsByName('TerRaSoc')[e].removeAttribute('required');
         document.getElementsByName('TerNomb1')[e].setAttribute('required', 'required');
         document.getElementsByName('TerNomb2')[e].setAttribute('required', 'required');
         document.getElementsByName('TerApel1')[e].setAttribute('required', 'required');
         document.getElementsByName('TerApel2')[e].setAttribute('required', 'required');
         document.getElementsByName('TerNomb1')[e].focus();
      }
   }


   // Manejar el evento de clic en el botón de editar
   //******************************************************************************************
   $('#clientesTable tbody').on('click', '.edit-btn', async function(e) {
      e.preventDefault();
      document.getElementById('formClienteEdit').reset();
      document.getElementById('editCiuCodig').nextElementSibling.classList.remove('is-valid');
      document.getElementById('editCiuCodig').nextElementSibling.classList.remove('is-invalid');
      var listWhere = [];
      listWhere.push({
         "id": "TabCodig",
         "value": '01'
      })
      listWhere.push({
         "id": "TabEstad",
         "value": '1'
      })
      let selectTipDoc = document.getElementById('editTerTiDoc');
      await getSelects('admon', 'tablas', selectTipDoc, 'TabNive1', textOpt = ['TabNive1', 'TabNive2'], listWhere);
      var listWhere = [];
      listWhere.push({
         "id": "CiuEstad",
         "value": '1'
      })
      let selectCity = document.getElementById('editCiuCodig');
      await getSelects('admon', 'ciudades', selectCity, 'CiuCodig', textOpt = ['CiuCodig', 'CiuNombr'], listWhere);

      var listWhere = [];
      listWhere.push({
         "id": "status",
         "value": '1'
      })
      let selectActEco = document.getElementById('editTerAcEco');
      await getSelects('admon', 'activi', selectActEco, 'id_actividad', textOpt = ['codigo', 'nombre'], listWhere);
      var data = $(this).parents().parents('tr');
      document.getElementById('editId').value = data.find('.id').attr('id');
      document.getElementById(`editTerTiDoc`).value = data.find('.id').attr('TerTiDoc');
      document.getElementById(`editTerDocId`).value = data.find('.id').text();
      document.getElementById(`editTerRaSoc`).value = data.find('.id').attr("TerRaSoc");
      document.getElementById(`editTerNomb1`).value = data.find('.id').attr("TerNomb1");
      document.getElementById(`editTerNomb2`).value = data.find('.id').attr("TerNomb2");
      document.getElementById(`editTerApel1`).value = data.find('.id').attr("TerApel1");
      document.getElementById(`editTerApel2`).value = data.find('.id').attr("TerApel2");
      document.getElementById(`editCiuCodig`).value = data.find('.address').attr("CiuCodig");
      document.getElementsByName('CiuCodig')[0].dispatchEvent(new Event('change'));
      document.getElementById(`editfecha_nacimiento`).value = data.find('.name').attr("fecha_nacimiento");
      document.getElementById(`editpep`).value = data.find('.name').attr("pep");
      // $('#editId').val(data.find('.id').attr('id'));
      // $('#editTerTiDoc').val(data.find('.id').attr('TerTiDoc'));
      // $('#editTerDocId').val(data.find('.id').text());
      // $('#editTerRaSoc').val(data.find('.id').attr("TerRaSoc"));
      // $('#editTerNomb1').val(data.find('.id').attr("TerNomb1"));
      // $('#editTerNomb2').val(data.find('.id').attr("TerNomb2"));
      // $('#editTerApel1').val(data.find('.id').attr("TerApel1"));
      // $('#editTerApel2').val(data.find('.id').attr("TerApel2"));
      // $('#editCiuCodig').val(data.find('.address').attr("CiuCodig")).trigger('change.select2');
      // $('#editfecha_nacimiento').val(data.find('.name').attr("fecha_nacimiento"));
      // $('#editpep').val(data.find('.name').attr("pep"));
      if (data.find('.name').attr("pep") == '1') {
         document.getElementById('editpep').checked = true;
         // $('#editpep').prop('checked', true);
      }
      document.getElementById('editTerDirec').value = data.find('.address').text();
      document.getElementById('editdireccion_residencia').value = data.find('.address').attr("direccion_residencia");
      document.getElementById('editorigen_recursos').value = data.find('.address').attr("origen_recursos");
      document.getElementById('editTerTele1').value = data.find('.phone').text();
      document.getElementById('editTerTele2').value = data.find('.phone').attr("TerTele2");
      // $('#editTerDirec').val(data.find('.address').text());
      // $('#editdireccion_residencia').val(data.find('.address').attr("direccion_residencia"));
      // $("#editorigen_recursos").val(data.find('.address').attr("origen_recursos"));
      // $('#editTerTele1').val(data.find('.phone').text());
      // $('#editTerTele2').val(data.find('.phone').attr("TerTele2"));
      if (data.find('.phone').attr("tipo_telefono") == '1') {
         document.querySelector('input[name="tipo_telefono"][value="1"]').checked = true;
      } else {
         document.querySelector('input[name="tipo_telefono"][value="2"]').checked = true;
      }
      document.getElementById('editpersona_responde').value = data.find('.phone').attr("persona_responde");
      document.getElementById('editTerEmail').value = data.find('.email').text();
      document.getElementById('editTerResAu').value = data.find('.name').attr("TerResAu");
      document.getElementById('editTerFreAu').value = data.find('.name').attr("TerFreAu");
      // $("#editpersona_responde").val(data.find('.phone').attr("persona_responde"));
      // $('#editTerEmail').val(data.find('.email').text());
      // $('#editTerResAu').val(data.find('.name').attr("TerResAu"));
      // $('#editTerFreAu').val(data.find('.name').attr("TerFreAu"));
      if (data.find('.name').attr("TerGrCon") == '1') {
         document.getElementById('editTerGrCon').checked = true;
      }
      if (data.find('.name').attr("TerAuRet") == '1') {
         document.getElementById('editTerAuRet').checked = true;
      }
      if (data.find('.name').attr("TerRetie") == '1') {
         document.getElementById('editTerRetie').checked = true;
      }
      if (data.find('.name').attr("TerRegim") == '1') {
         document.querySelector('input[name="TerRegim"][value="1"]').checked = true;
      } else {
         document.querySelector('input[name="TerRegim"][value="0"]').checked = true;
      }
      document.getElementById('editreferencia_comercial').value = data.find('.address').attr("referencia_comercial");
      document.getElementById('edittelefono_refcomercial').value = data.find('.address').attr("telefono_refcomercial");
      document.getElementById('editreferencia_personal').value = data.find('.address').attr("referencia_personal");
      document.getElementById('edittelefono_refpersonal').value = data.find('.address').attr("telefono_refpersonal");
      document.getElementById('editTerAcEco').value = data.find('.name').attr("id_actividad");
      document.getElementById('editvalor_cupo').value = data.find('.phone').attr("valor_cupo");
      document.getElementById('editvalor_cupotemporal').value = data.find('.phone').attr("valor_cupotemporal");
      document.getElementById('editnivel_riezgo').value = data.find('.name').attr("nivel_riezgo");
      // $('#editreferencia_comercial').val(data.find('.address').attr("referencia_comercial"));
      // $('#edittelefono_refcomercial').val(data.find('.address').attr("telefono_refcomercial"));
      // $('#editreferencia_personal').val(data.find('.address').attr("referencia_personal"));
      // $('#edittelefono_refpersonal').val(data.find('.address').attr("telefono_refpersonal"));
      // $("#editTerAcEco").val(data.find('.name').attr("id_actividad"));
      // $('#editvalor_cupo').val(data.find('.phone').attr("valor_cupo"));
      // $('#editvalor_cupotemporal').val(data.find('.phone').attr("valor_cupotemporal"));
      // $('#editnivel_riezgo').val(data.find('.name').attr("nivel_riezgo"));
      document.getElementsByName('TerTiDoc')[0].dispatchEvent(new Event('change'));
      $('#modalClienteEdit').modal('show');
      document.getElementById('formClienteEdit').classList.remove('was-validated');
   });


   // $('#modalClienteEdit').on('hidden.bs.modal', function () {
   document.getElementById('modalClienteEdit').addEventListener('hidden.bs.modal', function () {
      $(this).attr('aria-hidden', 'true');
   });


   // Manejar el evento de envío del formulario de edición
   //******************************************************************************************
   // $('#formClienteEdit').on('submit', function(e) {
   document.getElementById('formClienteEdit').addEventListener('submit', function(e) {
      e.preventDefault();
      // var formData = $(this).serialize();
      const formData = new FormData(this);
      if (execQueryUpd(formData, 'cargarClientes', "#modalClienteEdit")) {

      }
   })


   $('#newCiuCodig').on('select2:select', function(e) {
      const isValid = document.getElementById('newCiuCodig').value !== '' && document.getElementById('newCiuCodig').value !== null;
      document.getElementById('newCiuCodig').nextElementSibling.classList.remove('is-invalid');
      document.getElementById('newCiuCodig').nextElementSibling.classList.add(isValid ? 'is-valid' : 'is-invalid');
   });
   $('#editCiuCodig').on('select2:select', function(e) {
      const isValid = document.getElementById('editCiuCodig').value !== '' && document.getElementById('editCiuCodig').value !== null;
      document.getElementById('editCiuCodig').nextElementSibling.classList.remove('is-invalid');
      document.getElementById('editCiuCodig').nextElementSibling.classList.add(isValid ? 'is-valid' : 'is-invalid');
   });



   // Manejar el evento de búsqueda
   //**************************************************************************************
   $('#searchCliente').on('keyup', function() {
      // table.search(this.value).draw();
      if (window.clientesListInstance) {
         window.clientesListInstance.fuzzySearch(this.value);
         setTimeout(updatePaginationInfo, 50);
      }
   });


   //**************************************************************************************
   document.getElementById('newvalor_cupo').addEventListener('input', function() {
      this.value = this.value.replace(/[^\d]/g, '');
   });
   document.getElementById('newvalor_cupo').addEventListener('blur', function() {
      if (this.value) {
         const number = parseInt(this.value);
         this.value = number.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
         // document.getElementById('montoReal').value = number;
      }
   });
   document.getElementById('newvalor_cupo').addEventListener('focus', function() {
      this.value = this.value.replace(/\,/g, '');
   });

   document.getElementById('newvalor_cupotemporal').addEventListener('input', function() {
      this.value = this.value.replace(/[^\d]/g, '');
   });
   document.getElementById('newvalor_cupotemporal').addEventListener('blur', function() {
      if (this.value) {
         const number = parseInt(this.value);
         this.value = number.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
         // document.getElementById('montoReal').value = number;
      }
   });
   document.getElementById('newvalor_cupotemporal').addEventListener('focus', function() {
      this.value = this.value.replace(/\,/g, '');
   });


   document.getElementById('editvalor_cupo').addEventListener('input', function() {
      this.value = this.value.replace(/[^\d]/g, '');
   });
   document.getElementById('editvalor_cupo').addEventListener('blur', function() {
      if (this.value) {
         const number = parseInt(this.value);
         this.value = number.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
         // document.getElementById('montoReal').value = number;
      }
   });
   document.getElementById('editvalor_cupo').addEventListener('focus', function() {
      this.value = this.value.replace(/\,/g, '');
   });

   document.getElementById('editvalor_cupotemporal').addEventListener('input', function() {
      this.value = this.value.replace(/[^\d]/g, '');
   });
   document.getElementById('editvalor_cupotemporal').addEventListener('blur', function() {
      if (this.value) {
         const number = parseInt(this.value);
         this.value = number.toLocaleString('es-MX', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
         // document.getElementById('montoReal').value = number;
      }
   });
   document.getElementById('editvalor_cupotemporal').addEventListener('focus', function() {
      this.value = this.value.replace(/\,/g, '');
   });

})


//*********************************************************************************************
async function cargarClientes() {
   const formData = new FormData();
   formData.append("modulo", "dival");
   formData.append("option", "clientes");
   formData.append("action", "index");
   const response = await fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(resp => resp.json())
   .then( data => {
      const tbody = document.getElementById('clientes-table-body');
      tbody.innerHTML = '';
      if (data.length > 0) {
         data.forEach(cliente => {
            const row = document.createElement('tr');
            // Columna ID
            const tdId = document.createElement('td');
            tdId.className = 'align-middle text-660 ps-2 py-1 id';
            tdId.setAttribute('id', cliente.id_dvcliente);
            tdId.setAttribute('TerApel1', cliente.TerApel1);
            tdId.setAttribute('TerApel2', cliente.TerApel2);
            tdId.setAttribute('TerNomb1', cliente.TerNomb1);
            tdId.setAttribute('TerNomb2', cliente.TerNomb2);
            tdId.setAttribute('TerRaSoc', cliente.TerRaSoc);
            tdId.setAttribute('TerTiDoc', cliente.TerTiDoc);
            tdId.textContent = cliente.TerDocId;
            row.appendChild(tdId);
            
            const tdName = document.createElement('td');
            tdName.className = 'align-middle text-660 ps-2 py-1 name';
            tdName.setAttribute('fecha_nacimiento', cliente.fecha_nacimiento);
            tdName.setAttribute('pep', cliente.pep);
            tdName.setAttribute('id_actividad', cliente.id_actividad);
            tdName.setAttribute('nivel_riezgo', cliente.nivel_riezgo);
            tdName.setAttribute('TerGrCon', cliente.TerGrCon);
            tdName.setAttribute('TerAuRet', cliente.TerAuRet);
            tdName.setAttribute('TerRetie', cliente.TerRetie);
            tdName.setAttribute('TerResAu', cliente.TerResAu);
            tdName.setAttribute('TerFreAu', cliente.TerFreAu);
            tdName.setAttribute('TerRegim', cliente.TerRegim);
            tdName.textContent = cliente.TerNombr;
            row.appendChild(tdName);

            const tdaddress = document.createElement('td');
            tdaddress.className = 'align-middle text-660 ps-2 py-1 address';
            tdaddress.setAttribute('CiuCodig', cliente.CiuCodig);
            tdaddress.setAttribute('direccion_residencia', cliente.direccion_residencia);
            tdaddress.setAttribute('origen_recursos', cliente.origen_recursos);
            tdaddress.setAttribute('referencia_comercial', cliente.referencia_comercial);
            tdaddress.setAttribute('telefono_refcomercial', cliente.telefono_refcomercial);
            tdaddress.setAttribute('referencia_personal', cliente.referencia_personal);
            tdaddress.setAttribute('telefono_refpersonal', cliente.telefono_refpersonal);
            tdaddress.textContent = cliente.TerDirec;
            row.appendChild(tdaddress);
            
            const tdPhone = document.createElement('td');
            tdPhone.className = 'align-middle text-660 ps-2 py-1 phone';
            tdPhone.setAttribute('TerTele2', cliente.TerTele2);
            tdPhone.setAttribute('persona_responde', cliente.persona_responde);
            tdPhone.setAttribute('tipo_telefono', cliente.tipo_telefono);
            tdPhone.setAttribute('valor_cupo', formato.format(cliente.valor_cupo));
            tdPhone.setAttribute('valor_cupotemporal', formato.format(cliente.valor_cupotemporal));
            tdPhone.textContent = cliente.TerTele1;
            row.appendChild(tdPhone);

            const tdEmail = document.createElement('td');
            tdEmail.className = 'align-middle text-660 ps-2 py-1 email';
            tdEmail.textContent = cliente.TerEmail;
            row.appendChild(tdEmail);

            const tdNivRie = document.createElement('td');
            tdNivRie.className = 'align-middle text-660 p-2 py-1 nr';
            const nrBadge = document.createElement('span');
            nrBadge.className = `badge badge-phoenix badge-phoenix-${getRiskBadgeClass(cliente.nivel_riezgo)} p-2` 
            nrBadge.textContent = cliente.nivel_riezgo;
            tdNivRie.appendChild(nrBadge);
            row.appendChild(tdNivRie);

            const tdStatus = document.createElement('td');
            tdStatus.className = 'align-middle text-660 ps-2 py-1 fs--1 status';
            const statusBadge = document.createElement('span');
            statusBadge.className = cliente.status == 1 ? 
               'badge badge-phoenix badge-phoenix-success p-2' : 'badge badge-phoenix badge-phoenix-danger p-2';
            statusBadge.textContent = cliente.status == 1 ? 
               "Activo" : "Desactivado";
            tdStatus.appendChild(statusBadge);
            row.appendChild(tdStatus);
            
            const tdActions = document.createElement('td');
            tdActions.className = 'align-middle text-start p-2 py-1';
            if ($('#permiModsw').val() == "1") {
               const editBtn = document.createElement('a');
               editBtn.className = 'btn btn-outline-primary me-1 p-2 py-1 edit-btn';
               editBtn.innerHTML = '<i class="fas fa-edit"></i> Editar';
               editBtn.setAttribute('data-id', cliente.id_dvcliente);
               // editBtn.onclick = function() {
               //     editarcliente(this.getAttribute('data-id'));
               // };
               tdActions.appendChild(editBtn);
            }

            if ($('#permiDelsw').val() == "1") {
               const deleteBtn = document.createElement('a');
               deleteBtn.className = 'btn btn-sm btn-outline-danger p-2 py-1 delete-btn';
               deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Desactivar' ;
               if (cliente.status == 0) {
                  deleteBtn.innerHTML = '<i class="fas fa-arrow-rotate-left"></i>Activar';
                  // deleteBtn.style.pointerEvents = 'none';
               }
               deleteBtn.setAttribute('data-id', cliente.id_dvcliente);
               deleteBtn.setAttribute('data-status', cliente.status);
               deleteBtn.onclick = function() {
                   eliminarCliente(this.getAttribute('data-id'), this.getAttribute('data-status'));
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
        td.textContent = 'No se encontraron clientes';
        row.appendChild(td);
        tbody.appendChild(row);
      }
      updateListJS();
      setTimeout(() => {
         if (window.clientesListInstance) {
            window.clientesListInstance.update();
            window.clientesListInstance.reIndex();
            window.clientesListInstance.sort('name', { order: 'asc' });
            $('[data-list-info]').text(`${window.clientesListInstance.visibleItems.length} to ${window.clientesListInstance.items.length} Items`);
            window.clientesListInstance.fuzzySearch('');
         }
      }, 100);
   })
   .catch(error => {
      console.error('Error:', error);
      const tbody = document.getElementById('clientes-table-body');
      tbody.innerHTML = '';
      const row = document.createElement('tr');
      const td = document.createElement('td');
      td.colSpan = 4;
      td.className = 'text-center text-danger py-4';
      td.textContent = 'Error al cargar los clientes';
      row.appendChild(td);
      tbody.appendChild(row);
   });

}


/*************************************************************/
function eliminarCliente(id, status) {
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
         formData.append("option", "clientes");
         formData.append("action", "delete");
         formData.append("id", id);
         formData.append("status", stanew);
         if (execQueryUpd(formData, 'cargarClientes', null)) {
         }
      }
   })
}


//*********************************************************************************************
function updateListJS() {
   if (!window.clientesListInstance) {
      window.clientesListInstance = null;
   }
   window.clientesListInstance = new List('Clientes', {
      valueNames: [
         'id', 'name', 'address', 'phone', 'email', 'nr', 'status'
      ],
      page: 15,
      pagination: true,
      indexAsync: true
   });
   window.clientesListInstance.sort('name', { order: 'asc' });
   window.clientesListInstance.fuzzySearch('');
}


//*********************************************************************************************
// Función para actualizar la información del paginador
function updatePaginationInfo() {
   if (!window.clientesListInstance) return;
   const list = window.clientesListInstance;
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