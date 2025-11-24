<?php
   echo '<input type="hidden" name="id_user" value="1">';
   // $_POST["id_user"] = 1;
   $login = new AuthController();
   $data = $login->managePermissions(1);
   //var_dump($data);
   if (isset($data['success']) && $data['success'] == false) {
      echo '<script>alert("' . $data['message'] . '");</script>';
      echo '<script>window.location.href = ".";</script>';
      exit;
   };
   $permissions = $data['permissions'];
   $permissionsUser = $data['userPermissions'];
   $userPermissions = [];
   foreach ($permissionsUser as $row) {
      if ($row["UsuPermi"] == 1) {
         $userPermissions[] = $row["id_option"];
      }
   }
   $user = $data['user'];
?>
<div class="content p-2 pt-10">
   <div class="row mb-1">
		<div class="col-md-7">
         <div class="d-flex justify-content-between align-items-center">
            <h4 class="h4 text-900">Gestionar Permisos</h4>
         </div>
         <p class="text-muted mb-0" id="userName">
            <?= $user["name"] ?>
            - <?= 'permissions.roles.' . $user["id_rol"] ?>
         </p>
      </div>
		<div class="col-md-5">
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb float-sm-end mb-0 pe-2 pt-1">
               <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
               <li class="breadcrumb-item"><a href="users">Usuarios</a></li>
               <li class="breadcrumb-item active">Gestionar Permisos</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="row mb-1">
      <div class="d-flex justify-content-between align-items-center">
         <div class="d-flex gap-2">
            <button type="button" class="btn btn-phoenix-success" id="selectAllBtn">
               <i class="fas fa-check-double me-2"></i>Seleccionar Todos
            </button>
            <button type="button" class="btn btn-phoenix-warning" id="deselectAllBtn">
               <i class="fas fa-times-circle me-2"></i>Borrar Todos
            </button>
            <a href="users" class="btn btn-phoenix-secondary">
               <i class="fas fa-arrow-left me-2"></i>Regresar
            </a>
         </div>
      </div>
   </div>

   <form id="permissionsForm">
      <input type="hidden" name="id_user" id="id_user" value="<?= !empty($_POST["id_user"]) ? $_POST["id_user"] : "" ?>" >
      <!-- <input type="hidden" name="user_id" value="<?= $user["id_user"] ?>"> -->
      <div class="table-responsive scrollbar" style="max-height: 600px;" id="ownersContainer">
         <div class="row px-0 mx-1">
            <?php foreach ($permissions as $module): ?>
               <div class="col-lg-6 col-xl-4 mb-2 p-1">
                  <div class="card h-100">
                     <!-- Header del Módulo -->
                     <div class="card-header bg-100 py-3">
                        <div class="d-flex align-items-center">
                           <i class="<?= $module['module']['image'] ?> me-2 text-primary"></i>
                           <h4 class="mb-0 text-800 flex-grow-1"><?= $module['module']['description'] ?></h4>
                           <div class="form-check">
                              <input class="form-check-input module-checkbox"
                                 type="checkbox"
                                 data-module="<?= $module['module']['id'] ?>">
                           </div>
                        </div>
                     </div>

                     <div class="card-body p-2">
                        <?php foreach ($module['menus'] as $menu): ?>
                           <!-- Grupo de Menú -->
                           <div class="mb-2">
                              <h5 class="text-700 border-bottom pb-2 mb-1">
                                 <i class="fas fa-folder me-2 text-600"></i>
                                 <?= $menu['menu']['description'] ?>
                              </h5>

                              <!-- Tabla de Opciones -->
                              <div class="table-responsive">
                                 <table class="table table-sm table-borderless fs--1">
                                    <thead class="bg-200">
                                       <tr>
                                          <th class="ps-2" width="35%">Opción</th>
                                          <th class="text-center" width="10%">Todos</th>
                                          <th class="text-center" width="10%">Ejecutar</th>
                                          <th class="text-center" width="15%">Adicionar</th>
                                          <th class="text-center" width="15%">Editar</th>
                                          <th class="text-center" width="15%">Eliminar</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php
                                          $groupedOptions = groupOptionsByParent($menu['options']);
                                          foreach ($groupedOptions as $parentOption => $childOptions):
                                       ?>
                                       <tr class="border-top">
                                          <td class="ps-2 fw-bold text-800">
                                             <?= $parentOption ?>
                                          </td>
                                          <td colspan="1" class="text-center">
                                             <div class="form-check form-check-inline mb-0">
                                                <input class="form-check-input parent-checkbox"
                                                   type="checkbox"
                                                   data-parent="<?= sanitizeForId($parentOption) ?>">
                                             </div>
                                          </td>

                                          <td class="text-center">
                                             <div class="form-check form-check-inline mb-0">
                                                <input class="form-check-input permission-checkbox"
                                                type="checkbox"
                                                name="permissions[]"
                                                value="<?= $childOptions[0]['id'] ?>"
                                                data-parent="<?= sanitizeForId($parentOption) ?>"
                                                data-descriptiom="<?= $childOptions[0]['description'] ?>"
                                                data-module="<?= $module['module']['id'] ?>"
                                                <?=in_array($childOptions[0]['id'], $userPermissions) ? 'checked' : '' ?>>
                                             </div>
                                          </td>
                                          <?php foreach ($childOptions as $option): ?>
                                             <?php if (hasAction($option['description'], 'adicionar')): ?>
                                                <td class="text-center">
                                                   <div class="form-check form-check-inline mb-0">
                                                      <input class="form-check-input permission-checkbox"
                                                         type="checkbox"
                                                         name="permissions[]"
                                                         value="<?= $option['id'] ?>"
                                                         data-parent="<?= sanitizeForId($parentOption) ?>"
                                                         data-descriptiom="<?= $option['description'] ?>"
                                                         data-module="<?= $module['module']['id'] ?>"
                                                         <?= in_array($option['id'], $userPermissions) ? 'checked' : '' ?>>
                                                   </div>
                                                </td>
                                             <?php endif; ?>
                                             <?php if (hasAction($option['description'], 'modificar')): ?>
                                                <td class="text-center">
                                                   <div class="form-check form-check-inline mb-0">
                                                      <input class="form-check-input permission-checkbox"
                                                         type="checkbox"
                                                         name="permissions[]"
                                                         value="<?= $option['id'] ?>"
                                                         data-parent="<?= sanitizeForId($parentOption) ?>"
                                                         data-module="<?= $module['module']['id'] ?>"
                                                         <?= in_array($option['id'], $userPermissions) ? 'checked' : '' ?>>
                                                   </div>
                                                </td>
                                             <?php endif; ?>
                                             <?php if (hasAction($option['description'], 'desactivar') || hasAction($option['description'], 'anular')): ?>
                                                <td class="text-center">
                                                   <div class="form-check form-check-inline mb-0">
                                                      <input class="form-check-input permission-checkbox"
                                                         type="checkbox"
                                                         name="permissions[]"
                                                         value="<?= $option['id'] ?>"
                                                         data-parent="<?= sanitizeForId($parentOption) ?>"
                                                         data-module="<?= $module['module']['id'] ?>"
                                                         <?= in_array($option['id'], $userPermissions) ? 'checked' : '' ?>>
                                                   </div>
                                                </td>
                                             <?php endif; ?>
                                          <?php endforeach; ?>
                                          </tr>
                                       <?php endforeach; ?>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        <?php endforeach; ?>
                     </div>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
      </div>

      <!-- Botones de Acción -->
      <div class="row mt-2">
         <div class="col-12">
            <div class="card">
               <div class="card-body text-center p-3">
                  <button type="submit" class="btn btn-phoenix-primary btn-lg px-5">
                     <i class="fas fa-save me-2"></i>Guardar
                  </button>
                  <a href="users" class="btn btn-phoenix-secondary btn-lg px-5 ms-2">
                     <i class="fas fa-times me-2"></i>Cancelar
                  </a>
               </div>
            </div>
         </div>
      </div>
   </form>
   <?php
      include APP_PATH.'/views/layouts/footer.php';
   ?>


</div>

<script>
   document.addEventListener('DOMContentLoaded', function() {
      //  const userId = <?php //$user->id 
                           ?>;
      const userId = 1;

      /*      
      const formData = new FormData();
      // formData.append("modulo", "dival");
      formData.append('option', 'auth');
      formData.append('action', 'managePermissions');
      formData.append('id_user', userId);
      fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
      })
      .then(response => response.json())
      .then(data => {
      const user = data.user;
      const permissions = data.permissions;
      const userPermissions = data.userPermissions;
      console.log(user);
      console.log(permissions);
      console.log(userPermissions);
      document.getElementById('userName').textContent = user.name;
      });
      */
      

      // Seleccionar/Deseleccionar Todo
      document.getElementById('selectAllBtn').addEventListener('click', function() {
         document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = true;
         });
         updateParentCheckboxes();
         updateModuleCheckboxes();
      });

      document.getElementById('deselectAllBtn').addEventListener('click', function() {
         document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = false;
         });
         updateParentCheckboxes();
         updateModuleCheckboxes();
      });

      // Lógica para checkboxes padres
      document.querySelectorAll('.parent-checkbox').forEach(checkbox => {
         checkbox.addEventListener('change', function() {
            const parentId = this.dataset.parent;
            const childCheckboxes = document.querySelectorAll(`.permission-checkbox[data-parent="${parentId}"]`);
            childCheckboxes.forEach(child => {
               child.checked = this.checked;
            });
            updateModuleCheckboxes();
         });
      });

      // Lógica para checkboxes de módulo
      document.querySelectorAll('.module-checkbox').forEach(checkbox => {
         checkbox.addEventListener('change', function() {
            const moduleId = this.dataset.module;
            const moduleCheckboxes = document.querySelectorAll(`.permission-checkbox[data-module="${moduleId}"]`);
            moduleCheckboxes.forEach(child => {
               child.checked = this.checked;
            });
            // Actualizar checkboxes padres dentro del módulo
            const parentCheckboxes = document.querySelectorAll(`.parent-checkbox`);
            parentCheckboxes.forEach(parent => {
               updateParentCheckbox(parent.dataset.parent);
            });
         });
      });

      // Actualizar checkboxes padres cuando cambian los hijos
      document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
         checkbox.addEventListener('change', function() {
            const parentId = this.dataset.parent;
            updateParentCheckbox(parentId);
            updateModuleCheckboxes();
         });
      });

      // Envío del formulario
      document.getElementById('permissionsForm').addEventListener('submit', function(e) {
         e.preventDefault();
         savePermissions();
      });

      function updateParentCheckbox(parentId) {
         const parentCheckbox = document.querySelector(`.parent-checkbox[data-parent="${parentId}"]`);
         const childCheckboxes = document.querySelectorAll(`.permission-checkbox[data-parent="${parentId}"]`);
         const checkedCount = Array.from(childCheckboxes).filter(cb => cb.checked).length;
         if (parentCheckbox) {
            parentCheckbox.checked = checkedCount > 0;
            parentCheckbox.indeterminate = checkedCount > 0 && checkedCount < childCheckboxes.length;
         }
      }

      function updateParentCheckboxes() {
         document.querySelectorAll('.parent-checkbox').forEach(parent => {
            updateParentCheckbox(parent.dataset.parent);
         });
      }

      function updateModuleCheckboxes() {
         document.querySelectorAll('.module-checkbox').forEach(module => {
            const moduleId = module.dataset.module;
            const moduleCheckboxes = document.querySelectorAll(`.permission-checkbox[data-module="${moduleId}"]`);
            const checkedCount = Array.from(moduleCheckboxes).filter(cb => cb.checked).length;
            module.checked = checkedCount > 0;
            module.indeterminate = checkedCount > 0 && checkedCount < moduleCheckboxes.length;
         });
      }

      function savePermissions() {
         const formData = new FormData(document.getElementById('permissionsForm'));
         // Mostrar loading
         const submitBtn = document.querySelector('button[type="submit"]');
         const originalText = submitBtn.innerHTML;
         submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
         submitBtn.disabled = true;
         // fetch('/permissions/update/<?php // $user->id 
                                       ?>', {

         formData.append('option', 'auth');
         formData.append('action', 'savePermissions');

         fetch('helpers/ajaxRouter.php', {
            method: 'POST',
            body: formData
         })
         .then(response => response.json())
         .then(data => {
            if (data.success) {
               Swal.fire({
                  title: '¡Éxito!',
                  text: data.message,
                  icon: 'success',
                  confirmButtonColor: '#25a0e2'
               }).then(() => {
                  if (data.redirect) {
                     window.location.href = data.redirect;
                  }
               });
            } else {
               Swal.fire('Error', data.message, 'error');
            }
         })
         .catch(error => {
            Swal.fire('Error', 'Error de conexión', 'error');
         })
         .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
         });
      }

      // Inicializar estados
      updateParentCheckboxes();
      updateModuleCheckboxes();
   });
</script>

<?php
   // Funciones helper para organizar las opciones
   function groupOptionsByParent($options) {
      $grouped = [];
      // $firstOption = $options[0];
      // $firstparent = $firstOption['description'];
      // $grouped[$firstparent][] = $firstOption;

      foreach ($options as $option) {
         $description = $option['description'];

         // Identificar opciones padre (las que no tienen "Adicionar", "Modificar", etc.)
         if (!preg_match('/(Adicionar|Modificar|Desactivar|Anular|Consultar)\s+/', $description)) {
            $parent = $description;
            // var_dump($option);
            // $grouped[$parent] = [];
            $grouped[$parent] = [];
            $grouped[$parent][] = $option;
         } else {
            // Es una opción hija, encontrar su padre
            $parent = findParentOption($description, array_keys($grouped));
            if (!$parent) {
               $parent = 'General';
               if (!isset($grouped[$parent])) {
                  $grouped[$parent] = [];
               }
            }
            $grouped[$parent][] = $option;
         }
      }
      //var_dump($grouped);
      return $grouped;
   }


   function findParentOption($childDescription, $parentOptions) {
      foreach ($parentOptions as $parent) {
         if (strpos($childDescription, $parent) !== false) {
            return $parent;
         }
      }
      return null;
   }

   function hasAction($description, $action) {
      $actionMap = [
         'adicionar' => 'Adicionar',
         'modificar' => 'Modificar',
         'desactivar' => 'Desactivar',
         'anular' => 'Anular',
         'consultar' => 'Consultar'
      ];

      return strpos($description, $actionMap[$action] ?? $action) !== false;
   }

   function sanitizeForId($text) {
      return preg_replace('/[^a-zA-Z0-9]/', '_', $text);
   }
?>