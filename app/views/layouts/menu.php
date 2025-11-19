<nav class="navbar navbar-vertical navbar-expand-lg">
	<script>
		var navbarStyle = window.config.config.phoenixNavbarStyle;
		if (navbarStyle && navbarStyle !== 'transparent') {
			document.querySelector('body').classList.add(`navbar-${navbarStyle}`);
		}
	</script>
	<div class="collapse navbar-collapse" id="navbarVerticalCollapse">
		<div class="navbar-vertical-content">
			<!-- <ul class="navbar-nav flex-column" id="navbarVerticalNav"> -->
            <?php
               $structured_menu = [];
               foreach ($_SESSION['permissionssin'] as $item) {
                  $module = $item['description_module'];
                  $menu = $item['description_menu'];
                  $option = ['name' => $item['description_option'], 'url' => $item['OpcLink']];
                  // Agrupar en niveles: módulo → menú → opciones
                  $structured_menu[$module][$menu][] = $option;
               }
            ?>

            <?php
               $currentModule = null;
               $currentMenu = null;
               echo '<ul class="navbar-nav flex-column" id="navbarVerticalNav">';
               foreach ($_SESSION['permissionssin'] as $item) {
                  if ((int)$item["UsuPermi"] !== 1 || empty($item["description_option"])) continue;
                  if ((int)$item["status_option"] > 3 ) continue;
                  if ($currentModule !== $item["id_module"]) {        // Nuevo módulo
                     if ($currentModule !== null) {
                        if ($currentMenu !== null) echo '</ul></li>'; // cerrar submenú
                        echo '</ul></div></div></li>'; // cerrar módulo
                     }
                     $currentModule = $item["id_module"];             // Reset menú
                     $currentMenu = null;
                     // Abrir módulo
                     echo '
                     <li class="nav-item">
                        <div class="nav-item-wrapper">
                           <a class="nav-link dropdown-indicator label-1 collapsed" href="#mod_'.$item["id_module"].'" data-bs-toggle="collapse" aria-expanded="false" aria-controls="mod_'.$item["id_module"].'">
                              <div class="d-flex align-items-center">
                                 <div class="dropdown-indicator-icon">
                                    <span class="fas fa-caret-right"></span>
                                 </div>
                                 <span class="nav-link-icon"><i class="fs-0 '.$item["image_module"].'"></i></span>
                                 <span class="nav-link-text fs-0">'.$item["description_module"].'</span>
                              </div>
                           </a>
                           <div class="parent-wrapper label-1">
                              <ul class="nav collapse parent" data-bs-parent="#navbarVerticalNav" id="mod_'.$item["id_module"].'">
											<li class="collapsed-nav-item-title d-none fs-0">'.$item["description_module"].'</li>
                     ';
                  }
                  if ($currentMenu !== $item["id_menu"]) {                // Nuevo menú dentro del módulo
                     if ($currentMenu !== null) echo '</ul></li>';        // Cerrar menú anterior
                     $currentMenu = $item["id_menu"];
                     if ($item["description_menu"] === $item["description_module"] || $item["description_menu"] === null) {
                        // Opción directa
                        echo '
                        <li class="nav-item">
                           <a class="nav-link py-2 ps-4" href="'.$item["OpcLink"].'">
                              <span class="nav-link-text">'.$item["description_option"].'</span>
                           </a>
                        </li>';
                        $currentMenu = null; // No abrir submenú
                     } else {
                        // Comienza submenú
                        echo '
                        <li class="nav-item">
                           <a class="nav-link collapsed ps-4" href="#menu_'.$item["id_menu"].'" data-bs-toggle="collapse" aria-expanded="false">
                              <div class="d-flex align-items-center">
                                 <span class="nav-link-icon ps-3 me-0"><i class="fs-0 fas fa-plus-circle"></i></span>
                                 <span class="nav-link-text ps-2 fs-0">'.$item["description_menu"].'</span>
                              </div>
                           </a>
                           <ul class="nav collapse" id="menu_'.$item["id_menu"].'">
                        ';
                     }
                  }

                  // Mostrar opción si estamos en un submenú
                  // if ($currentMenu !== null && $item["description_menu"] !== $item["description_option"]) {
                  if ($currentMenu !== null) {
                     echo '
                     <li class="nav-item">
                        <a class="nav-link py-2 ps-4" href="'.$item["OpcLink"].'">
                              <span class="nav-link-text">'.$item["description_option"].'</span>
                        </a>
                     </li>';
                  }
                  
               }
               // Cierre final
               if ($currentMenu !== null) echo '</ul></li>';
               if ($currentModule !== null) echo '</ul></div></div></li>';
               echo '</ul>';
            ?>
                  <!--
                  </li>
			      </ul> -->
		      </div>
	      </div>
	   </div>
	</div>
	<div class="navbar-vertical-footer">
		<button class="btn navbar-vertical-toggle border-0 fw-semi-bold w-100 white-space-nowrap d-flex align-items-center">
         <span class="uil uil-left-arrow-to-left fs-0"></span>
         <span class="uil uil-arrow-from-right fs-0"></span>
         <span class="navbar-vertical-footer-text ms-2">Collapsed view</span>
      </button>
	</div>
</nav>
<!--
<script>
  //feather.replace();
</script>
-->