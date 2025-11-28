
document.addEventListener("DOMContentLoaded", function() {
   cargarUsers();

   //********************************************************************************
   $("#btnaddUser").on('click', function(e) {
      e.preventDefault();
      $(".photoUserFile").attr("src", "assets/img/team/avatar.webp");
      const formDataRoles = new FormData();
      formDataRoles.append("modulo", "admon");
      formDataRoles.append("option", "roles");
      formDataRoles.append("action", "index");
      fetch('helpers/ajaxRouter.php', {
         method: 'POST',
         body: formDataRoles
      }).then(resp => resp.json())
      .then( data => {
         let roleSelect = document.getElementById('newrole');
         data.forEach(role => {
            var option = document.createElement('option');
            option.value = role.id_role;
            option.text = role.description;
            roleSelect.appendChild(option);
         });
      })
      .catch(error => {
         console.error('Error:', error);
      });
      $('#modalUserAdd').modal('show');
   });
   // Manejar el evento de clic en el botón de agregar
   $('#formUserAdd').on('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      execQueryUpd(formData, 'cargarUsers', '#modalUserAdd')
   });


   // Manejar el evento de clic en el botón de editar
   //********************************************************************************
   $('#usersTable tbody').on('click', '.edit-btn', function() {
      var data = $(this).parents().parents('tr');
      $('#editId').val(data.find('.photo').attr('id'));
      $('#editIdentificacion').val(data.find('.id').text());
      $('#editname').val(data.find('.nombre').text());
      $('#editrole').val(data.find('.role').text());
      $('#edittarjeta').val(data.find('.nombre').attr('tarjeta'));
      $('#photoPrev').val(data.find('.nombre').attr('photo'));
      $('#photoCurrent').val(data.find('.nombre').attr('photo'));
      $('.photoUserFile').attr('src', data.find('.nombre').attr('photo'));
      $('#editemail').val(data.find('.email').text());
      let idrole = parseInt(data.find('.role').attr('idrole'));
      let listWhere = [];
      listWhere.push({
			"id": "status",
         "value": '1'
      })
	   listWhere = JSON.stringify(listWhere);
      const formDataRoles = new FormData();
      formDataRoles.append("modulo", "admon");
      formDataRoles.append("option", "roles");
      formDataRoles.append("action", "index");
      formDataRoles.append("listWhere", listWhere);
      fetch('helpers/ajaxRouter.php', {
         method: 'POST',
         body: formDataRoles
      }).then(resp => resp.json())
      .then( data => {
         let roleSelect = document.getElementById('editrole');
         data.forEach(role => {
            var option = document.createElement('option');
            option.value = role.id_role;
            option.text = role.description;
            if (role.id_role == idrole) {
               option.selected = true;
            }
            roleSelect.appendChild(option);
         });
      })
      .catch(error => {
         console.error('Error:', error);
      });
      $('#modalUserEdit').modal('show');
   });

   // Manejar el evento de envío del formulario de edición
   $('#formUserEdit').on('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      execQueryUpd(formData, 'cargarUsers', "#modalUserEdit")
   })


   //********************************************************************************
   $('#usersTable tbody').on('click', '.perm-btn', function() {
      var idUserPermi = $(this).attr("data-id");
      $(".idUserPermi").val(idUserPermi);
      const form = document.createElement('form');
      // form.target = '_blank';
      form.method = 'POST';
      form.action = 'permissions';
      form.style.display = 'none';
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'id_user';
      input.value = idUserPermi;
      form.appendChild(input);
      const params = {
         id_user: idUserPermi,
      };
      document.body.appendChild(form);
      form.submit();

   });


   // Manejar el evento de búsqueda
   //********************************************************************************
   $('#searchUser').on('keyup', function() {
      table.search(this.value).draw();
   });


   //********************************************************************************
   $(".photoUser").on("change", function() {
      var imagen = this.files[0];
      console.log(imagen);
      var datosImagen = new FileReader;
      datosImagen.readAsDataURL(imagen);
      $(datosImagen).on("load", function (event) {
         var rutaImagen = event.target.result;
         $(".photoUserFile").attr("src", rutaImagen);
      })

      /*
      var input = this;
      if (input.files && input.files[0]) {
         var imagen = this.files[0];
         console.log(input.files);
         var reader = new FileReader();
         reader.readAsDataURL(input.files[0]);
         reader.onload = function(e) {
            $('.newPhotoFile').attr('src', e.target.result);
         }
      }
      */
   });
})

//********************************************************************************
async function cargarUsers() {
   const formData = new FormData();
   formData.append("modulo", "admon");
   formData.append("option", "users");
   formData.append("action", "index");
   const response = await fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(resp => resp.json())
   .then( data => {
      const tbody = document.getElementById('users-table-body');
      tbody.innerHTML = '';
      if (data.length > 0) {
         data.forEach(user => {
            const row = document.createElement('tr');

            // Columna ID
            const tdId = document.createElement('td');
            tdId.className = 'd-none d-sm-table-cell align-middle white-space-nowrap py-0 id';
            const photoa = document.createElement('a');
            photoa.className = 'd-block rounded-2';
            const photoimg = document.createElement('img');
            photoimg.className = 'rounded-circle';
            photoimg.style.width = '50px';
            photoimg.style.height = '50px';
            photoimg.style.objectFit = 'cover';
            if (user.photo && user.photo.trim() !== '') {
               photoimg.src = user.photo;
               photoimg.alt = `Foto de ${user.name}`;
            } else {
               // Foto por defecto si no hay imagen
               photoimg.src = 'assets/img/team/avatar.webp';
               photoimg.alt = 'Foto por defecto';
            }
            photoa.appendChild(photoimg);
            tdId.appendChild(photoa);
            tdId.setAttribute('id', user.id_user);
            row.appendChild(tdId);

            // Columna nombre
            const tdDesc = document.createElement('td');
            tdDesc.className = 'align-middle text-660 ps-2 py-1 name';
            tdDesc.textContent = user.name;
            tdDesc.setAttribute('photo', user.photo);
            row.appendChild(tdDesc);

            // Columna email
            const tdemail = document.createElement('td');
            tdemail.className = 'align-middle text-660 ps-2 py-1 email';
            tdemail.textContent = user.email;
            row.appendChild(tdemail);

            // Columna role
            const tdrole = document.createElement('td');
            tdrole.className = 'align-middle text-660 ps-2 py-1 role';
            tdrole.textContent = user.role;
            tdrole.setAttribute('idrole', user.id_role);
            row.appendChild(tdrole);

            // Columna last login
            const tdlast = document.createElement('td');
            tdlast.className = 'align-middle text-660 ps-2 py-1 last';
            tdlast.textContent = user.last_login;
            row.appendChild(tdlast);

            // Columna Status
            const tdStatus = document.createElement('td');
            tdStatus.className = 'align-middle text-660 ps-2 py-1 fs-0 status';
            const statusBadge = document.createElement('span');
            statusBadge.className = user.status == 1 ? 
               'badge badge-phoenix badge-phoenix-success' : 'badge badge-phoenix badge-phoenix-danger';
            statusBadge.textContent = user.status == 1 ? 
               'Activo' : 'Inactivo';
            tdStatus.appendChild(statusBadge);
            row.appendChild(tdStatus);
            
            const tdActions = document.createElement('td');
            tdActions.className = 'align-middle text-start p-2 py-1';

            if ($('#permiPersw').val() == "1") {
               const editBtn = document.createElement('a');
               editBtn.className = 'btn btn-outline-success me-1 p-2 py-1 perm-btn';
               editBtn.innerHTML = '<i class="fas fa-edit"></i> Permissions';
               editBtn.setAttribute('data-id', user.id_user);
               tdActions.appendChild(editBtn);
            }

            if ($('#permiModsw').val() == "1") {
               const PermBtn = document.createElement('a');
               PermBtn.className = 'btn btn-outline-primary me-1 p-2 py-1 edit-btn';
               PermBtn.innerHTML = '<i class="fas fa-edit"></i> Editar';
               PermBtn.setAttribute('data-id', user.id_user);
               tdActions.appendChild(PermBtn);
            }

            if ($('#permiDelsw').val() == "1") {
               const deleteBtn = document.createElement('a');
               deleteBtn.className = 'btn btn-sm btn-outline-danger p-2 py-1 delete-btn';
               deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Inactivar';
               if (user.status == 0) {
                  deleteBtn.innerHTML = '<i class="fas fa-arrow-rotate-left"></i> Activar';
               }
               deleteBtn.setAttribute('data-id', user.id_user);
               deleteBtn.setAttribute('data-status', user.status);
               deleteBtn.onclick = function() {
                   eliminarUser(this.getAttribute('data-id'), this.getAttribute('data-status'));
               };
               tdActions.appendChild(deleteBtn);
            }
            row.appendChild(tdActions);
            tbody.appendChild(row);
         });
      } else {
        const row = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan = 7;
        td.className = 'text-center text-muted py-4';
        td.textContent = 'No se encontraron usuarios registrados';
        row.appendChild(td);
        tbody.appendChild(row);
      }
      updateListJS();
      // setTimeout(() => {
      //    if (window.usersListInstance) {
      //       window.usersListInstance.update();
      //       window.usersListInstance.reIndex();
      //       window.usersListInstance.sort('nombre', { order: 'asc' });
      //       $('[data-list-info]').text(`${window.usersListInstance.visibleItems.length} to ${window.usersListInstance.items.length} Items`);
      //       window.usersListInstance.fuzzySearch('');
      //    }
      // }, 100);
   })
   .catch(error => {
      console.error('Error:', error);
      const tbody = document.getElementById('users-table-body');
      tbody.innerHTML = '';
      const row = document.createElement('tr');
      const td = document.createElement('td');
      td.colSpan = 7;
      td.className = 'text-center text-danger py-4';
      td.textContent = 'Error al cargar los datos. Por favor, intente nuevamente.';
      row.appendChild(td);
      tbody.appendChild(row);
   });
}


//********************************************************************************
function eliminarUser(id, status) {
   let titulo = "Está seguro de inactivar este Usuario?";
   let botcon = "Si, Inactivar!";
   let stanew = 0;
   if (status == 0) {
      titulo = "Está seguro de activar este Usuario?";
      botcon = "Si, Activar!";
      stanew = 1;
   }
   swal.fire({
      title: titulo,
      text: "¡Si no lo está puede cancelar la accíón!",
      icon: 'question',
      showCancelButton: true,
      reverseButtons: true,
      focusCancel: true,
      confirmButtonColor: '#3086d6c7',
      cancelButtonColor: 'rgba(221, 51, 51, 0.73)',
      cancelButtonText: 'No, Cancelar',
      confirmButtonText: botcon
   }).then(function (result) {
      if (result.value) {
         const formData = new FormData();
         formData.append("modulo", "admon");
         formData.append("option", "users");
         formData.append("action", "delete");
         formData.append("id", id);
         formData.append("status", stanew);
         if (execQueryUpd(formData, 'cargarUsers', null)) {
         }
      }
   })
}


//********************************************************************************
function updateListJS() {
   if (!window.usersListInstance) {
      window.usersListInstance = null;
   }
   window.usersListInstance = new List('Users', {
      valueNames: ["id","name","email","role","last","status"],
      page: 10,
      pagination: true,
      indexAsync: true
   });
   window.usersListInstance.sort('name', { order: 'asc' });
   window.usersListInstance.fuzzySearch('');

   setupPaginationEvents(window.usersListInstance, 10);
   window.usersListInstance.on('updated', function() {
      updatePaginationInfo(window.usersListInstance);
   });
   updatePaginationInfo(window.usersListInstance);
}

