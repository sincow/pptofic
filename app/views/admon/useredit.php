<div id="modalUserEdit" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title fw-bolder" id="modalUserEditTit">Modificar Usuario</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" ara-label="Close"></button>
			</div>
			<form role="form" id="formUserEdit" method="post">
            <input type="hidden" id="modulo" name="modulo" value="admon">
            <input type="hidden" id="option" name="option" value="users">
            <input type="hidden" id="action" name="action" value="update">
            <input type="hidden" id="editId" name="id">
            <input type="hidden" id="photoPrev" name="photoPrev">
            <input type="hidden" id="photoCurrent" name="photoCurrent">
				<div class="modal-body h-100 p-0">
               <div class="card mb-0">
                  <div class="card-body p-4">
                     <div class="row">

                        <div class="col-md-3">
                           <label for="editPhoto" class="text-label ps-3" >Seleccione Foto</label>
                           <div class="d-flex align-items-end position-relative mb-5">
                              <input class="d-none photoUser" type="file" id="editPhoto" name="photo" accept="image/png, image/jpg, image/webp" />
                              <div class="hoverbox" style="width: 150px; height: 150px">
                                 <div class="hoverbox-content bg-black rounded-circle d-flex flex-center z-index-1" style="--phoenix-bg-opacity: .56;"><span class="fa-solid fa-camera fs-7 text-300"></span></div>
                                 <div class="position-relative bg-400 rounded-circle cursor-pointer d-flex flex-center mb-xxl-7">
                                    <div class="avatar avatar-5xl"><img class="rounded-circle photoUserFile" src="" alt="" /></div>
                                    <label class="cursor-pointer w-100 h-100 position-absolute z-index-1" for="editPhoto"></label>
                                 </div>
                              </div>
                           </div>
                        </div>

                        <div class="row col-md-9 pe-0">
                           <div class="col-md-12 mb-3 pe-0">
                              <label for="editname" class="text-label fs-0 ps-1">Nombre</label>
                              <input type="text" class="form-control" id="editname" name="nombre">
                           </div>
                           <div class="col-md-12 mb-3 pe-0">
                              <label for="editemail" class="text-label fs-0 ps-1">Correo Electrónico</label>
                              <input type="text" class="form-control" id="editemail" name="email">
                           </div>
                           <div class="col-md-12 mb-3 pe-0">
                              <label for="editrole" class="text-label fs-0 ps-1">Perfil</label>
                              <select class="select2 form-control" style="width: 100%;" id="editrole" name="role">
                                 <option value="0">Seleccione un Perfil</option>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="row g-3">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-8">
                        </div>
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-8">
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-phoenix-primary" id="btnUserEdit">Modificar</button>
               <button type="button" class="btn btn-phoenix-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
         </form>
      </div>
   </div>
</div>
<script>
   $(document).ready(function() {
      $('#editrole').select2({
         dropdownParent: $('#modalUserEdit'),
         width: '100%'
      });
   });
</script>