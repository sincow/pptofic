<nav class="navbar navbar-top fixed-top navbar-expand ps-0" id="navbarDefault">
	<!-- <div class="collapse navbar-collapse justify-content-between"> -->
	<div class="collapse navbar-collapse">
		<div class="navbar-logo">
			<button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
			<a class="navbar-brand me-1 me-sm-3" href="./">
				<div class="d-flex align-items-center">
					<!-- <div class="d-flex align-items-center"><img src="views/img/icons/logo_gescomer2000.png" alt="WMS" width="50"/> -->
					<!-- <div class="d-flex align-items-center">WMS -->
					<div class="col-5 d-none d-sm-flex justify-content-center align-items-center ms-2 py-0 px-1">
						<!-- <p class="wms logo-text d-none d-sm-block">VET</p> -->
						<!-- <p class="wms logo-text d-none d-sm-block ps-2">- Clinic</p> -->
						<img class="ps-1" src=<?php echo APP_ICON?> alt="VET" width="60" />
					</div>
				</div>
			</a>
		</div>
		<!-- <div class="navbar-nav navbar-items d-none d-lg-block">
			<select class="form-select" style="color: #127473;" name="idCompanyDef" id="idCompanyDef">
				<?php
					// $itemCompany = null;
					// $data = array("origen" => "clients");
					// $itemCompany = GeneralController::getAll($data);
					// if ($itemCompany != null) {
					// 	foreach ($itemCompany as $key => $value) {
					// 		if ($value["client_id"] == $_SESSION["client_id"]) {
					// 			echo '<option value="'.$value["client_id"].'" selected>'.$value["name"].'</option>';
					// 		} else {
					// 			echo '<option value="'.$value["client_id"].'">'.$value["name"].'</option>';
					// 		}
					// 	}
					// }
				?>
			</select>
		</div> -->
		<div class="navbar-nav navbar-top-search-box d-none d-lg-block">
			<li class="nav-item d-none d-sm-inline-block" style="margin-top: 5px;">
				<span>
					<strong>
						<h4 style="color: #127473; margin: 0px;"><?php echo $_SESSION['companyname']; ?></h4>
					</strong>
				</span>
			</li>
			<li class="nav-item d-sm-none d-inline-block" style="margin-top: 5px;">
				<span>
					<strong>
						<h4 style="color: #127473; margin: 0px;">POLYLOGIK</h4>
					</strong>
				</span>
			</li>
		</div>

	<?php
		// include 'language_selector.php';
	?>


		<ul class="navbar-nav navbar-nav-icons flex-row ms-auto">
			<!--
			<li class="nav-item">
				<a class="card setting-toggle d-none d-lg-block pt-4" style="position:relative; transform:none; border:none;" href="#settings-offcanvas" data-bs-toggle="offcanvas">
					<div class="card-body d-flex align-items-center px-2 py-1">
						<div class="position-relative rounded-start" style="height:34px;width:28px">
							<div class="settings-popover">
								<span class="ripple">
									<span class="fa-spin position-absolute all-0 d-flex flex-center">
										<span class="icon-spin position-absolute all-0 d-flex flex-center">
											<svg width="20" height="20" viewBox="0 0 20 20" fill="#ffffff" xmlns="http://www.w3.org/2000/svg">
												<path d="M19.7369 12.3941L19.1989 12.1065C18.4459 11.7041 18.0843 10.8487 18.0843 9.99495C18.0843 9.14118 18.4459 8.28582 19.1989 7.88336L19.7369 7.59581C19.9474 7.47484 20.0316 7.23291 19.9474 7.03131C19.4842 5.57973 18.6843 4.28943 17.6738 3.20075C17.5053 3.03946 17.2527 2.99914 17.0422 3.12011L16.393 3.46714C15.6883 3.84379 14.8377 3.74529 14.1476 3.3427C14.0988 3.31422 14.0496 3.28621 14.0002 3.25868C13.2568 2.84453 12.7055 2.10629 12.7055 1.25525V0.70081C12.7055 0.499202 12.5371 0.297594 12.2845 0.257272C10.7266 -0.105622 9.16879 -0.0653007 7.69516 0.257272C7.44254 0.297594 7.31623 0.499202 7.31623 0.70081V1.23474C7.31623 2.09575 6.74999 2.8362 5.99824 3.25599C5.95774 3.27861 5.91747 3.30159 5.87744 3.32493C5.15643 3.74527 4.26453 3.85902 3.53534 3.45302L2.93743 3.12011C2.72691 2.99914 2.47429 3.03946 2.30587 3.20075C1.29538 4.28943 0.495411 5.57973 0.0322686 7.03131C-0.051939 7.23291 0.0322686 7.47484 0.242788 7.59581L0.784376 7.8853C1.54166 8.29007 1.92694 9.13627 1.92694 9.99495C1.92694 10.8536 1.54166 11.6998 0.784375 12.1046L0.242788 12.3941C0.0322686 12.515 -0.051939 12.757 0.0322686 12.9586C0.495411 14.4102 1.29538 15.7005 2.30587 16.7891C2.47429 16.9504 2.72691 16.9907 2.93743 16.8698L3.58669 16.5227C4.29133 16.1461 5.14131 16.2457 5.8331 16.6455C5.88713 16.6767 5.94159 16.7074 5.99648 16.7375C6.75162 17.1511 7.31623 17.8941 7.31623 18.7552V19.2891C7.31623 19.4425 7.41373 19.5959 7.55309 19.696C7.64066 19.7589 7.74815 19.7843 7.85406 19.8046C9.35884 20.0925 10.8609 20.0456 12.2845 19.7729C12.5371 19.6923 12.7055 19.4907 12.7055 19.2891V18.7346C12.7055 17.8836 13.2568 17.1454 14.0002 16.7312C14.0496 16.7037 14.0988 16.6757 14.1476 16.6472C14.8377 16.2446 15.6883 16.1461 16.393 16.5227L17.0422 16.8698C17.2527 16.9907 17.5053 16.9504 17.6738 16.7891C18.7264 15.7005 19.4842 14.4102 19.9895 12.9586C20.0316 12.757 19.9474 12.515 19.7369 12.3941ZM10.0109 13.2005C8.1162 13.2005 6.64257 11.7893 6.64257 9.97478C6.64257 8.20063 8.1162 6.74905 10.0109 6.74905C11.8634 6.74905 13.3792 8.20063 13.3792 9.97478C13.3792 11.7893 11.8634 13.2005 10.0109 13.2005Z" fill="#2A7BE4"></path>
											</svg>
										</span>
									</span>
								</span>
							</div>
						</div>
						<small class="text-uppercase text-700 fw-bold py-2 pe-2 ps-1 rounded-end d-none d-xl-block">Pers</small>
					</div>
				</a>
			</li>
			-->
			<li class="nav-item">
				<div class="theme-control-toggle fa-icon-wait px-2">
					<input class="form-check-input ms-0 theme-control-toggle-input" type="checkbox" data-theme-control="phoenixTheme" value="dark" id="themeControlToggle" />
					<label class="mb-0 theme-control-toggle-label theme-control-toggle-light" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Cambiar tema"><span class="icon" data-feather="moon"></span></label>
					<label class="mb-0 theme-control-toggle-label theme-control-toggle-dark"  for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Cambiar tema"><span class="icon" data-feather="sun"></span></label>
				</div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link" href="#" style="min-width: 2.5rem" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-auto-close="outside"><span data-feather="bell" style="height:20px;width:20px;"></span></a>
				<div class="dropdown-menu dropdown-menu-end notification-dropdown-menu py-0 shadow border border-300 navbar-dropdown-caret" id="navbarDropdownNotfication" aria-labelledby="navbarDropdownNotfication">
					<div class="card position-relative border-0">
						<div class="card-header p-2">
							<div class="d-flex justify-content-between">
								<h5 class="text-black mb-0">Notificaciones</h5>
								<button class="btn btn-link p-0 fs--1 fw-normal" type="button">Marcar todo como leído</button>
							</div>
						</div>
						<div class="card-body p-0">
							<div class="scrollbar-overlay" style="height: 27rem;">
								<div class="border-300">
									<div class="px-2 px-sm-3 py-3 border-300 notification-card position-relative read border-bottom">
										<div class="d-flex align-items-center justify-content-between position-relative">
											<div class="d-flex">
												<!-- <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="assets/img/team/40x40/30.webp" alt="" /> -->
												</div>
												<div class="flex-1 me-sm-3">
													<h4 class="fs--1 text-black">Jessie Samson</h4>
													<p class="fs--1 text-1000 mb-2 mb-sm-3 fw-normal"><span class='me-1 fs--2'>💬</span>Mentioned you in a comment.<span class="ms-2 text-400 fw-bold fs--2">10m</span></p>
													<p class="text-800 fs--1 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:41 AM </span>August 7,2021</p>
												</div>
											</div>
											<div class="font-sans-serif d-none d-sm-block">
												<button class="btn fs--2 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2 text-900"></span></button>
												<div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Marcar como no Leida</a></div>
											</div>
										</div>
									</div>
									<div class="px-2 px-sm-3 py-3 border-300 notification-card position-relative unread border-bottom">
										<div class="d-flex align-items-center justify-content-between position-relative">
											<div class="d-flex">
												<div class="avatar avatar-m status-online me-3">
													<div class="avatar-name rounded-circle"><span>J</span></div>
												</div>
												<div class="flex-1 me-sm-3">
													<h4 class="fs--1 text-black">Jane Foster</h4>
													<p class="fs--1 text-1000 mb-2 mb-sm-3 fw-normal"><span class='me-1 fs--2'>📅</span>Created an event.<span class="ms-2 text-400 fw-bold fs--2">20m</span></p>
													<p class="text-800 fs--1 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:20 AM </span>August 7,2021</p>
												</div>
											</div>
											<div class="font-sans-serif d-none d-sm-block">
												<button class="btn fs--2 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2 text-900"></span></button>
												<div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Marcar como no Leida</a></div>
											</div>
										</div>
									</div>
									<div class="px-2 px-sm-3 py-3 border-300 notification-card position-relative unread border-bottom">
										<div class="d-flex align-items-center justify-content-between position-relative">
											<div class="d-flex">
												<!-- <div class="avatar avatar-m status-online me-3"><img class="rounded-circle avatar-placeholder" src="assets/img/team/40x40/avatar.webp" alt="" /> -->
												</div>
												<div class="flex-1 me-sm-3">
													<h4 class="fs--1 text-black">Jessie Samson</h4>
													<p class="fs--1 text-1000 mb-2 mb-sm-3 fw-normal"><span class='me-1 fs--2'>👍</span>Liked your comment.<span class="ms-2 text-400 fw-bold fs--2">1h</span></p>
													<p class="text-800 fs--1 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">9:30 AM </span>August 7,2021</p>
												</div>
											</div>
											<div class="font-sans-serif d-none d-sm-block">
												<button class="btn fs--2 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2 text-900"></span></button>
												<div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Marcar como no Leida</a></div>
											</div>
										</div>
									</div>
								</div>
								<div class="border-300">
									<div class="px-2 px-sm-3 py-3 border-300 notification-card position-relative unread border-bottom">
										<div class="d-flex align-items-center justify-content-between position-relative">
											<div class="d-flex">
												<!-- <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="assets/img/team/40x40/57.webp" alt="" /> -->
												</div>
												<div class="flex-1 me-sm-3">
													<h4 class="fs--1 text-black">Kiera Anderson</h4>
													<p class="fs--1 text-1000 mb-2 mb-sm-3 fw-normal"><span class='me-1 fs--2'>💬</span>Mentioned you in a comment.<span class="ms-2 text-400 fw-bold fs--2"></span></p>
													<p class="text-800 fs--1 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">9:11 AM </span>August 7,2021</p>
												</div>
											</div>
											<div class="font-sans-serif d-none d-sm-block">
												<button class="btn fs--2 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2 text-900"></span></button>
												<div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Marcar como no Leida</a></div>
											</div>
										</div>
									</div>
									<div class="px-2 px-sm-3 py-3 border-300 notification-card position-relative unread border-bottom">
										<div class="d-flex align-items-center justify-content-between position-relative">
											<div class="d-flex">
												<!-- <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="assets/img/team/40x40/59.webp" alt="" /> -->
												</div>
												<div class="flex-1 me-sm-3">
													<h4 class="fs--1 text-black">Herman Carter</h4>
													<p class="fs--1 text-1000 mb-2 mb-sm-3 fw-normal"><span class='me-1 fs--2'>👤</span>Tagged you in a comment.<span class="ms-2 text-400 fw-bold fs--2"></span></p>
													<p class="text-800 fs--1 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:58 PM </span>August 7,2021</p>
												</div>
											</div>
											<div class="font-sans-serif d-none d-sm-block">
												<button class="btn fs--2 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2 text-900"></span></button>
												<div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Marcar como no Leida</a></div>
											</div>
										</div>
									</div>
									<div class="px-2 px-sm-3 py-3 border-300 notification-card position-relative read ">
										<div class="d-flex align-items-center justify-content-between position-relative">
											<div class="d-flex">
												<!-- <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="assets/img/team/40x40/58.webp" alt="" /> -->
												</div>
												<div class="flex-1 me-sm-3">
													<h4 class="fs--1 text-black">Benjamin Button</h4>
													<p class="fs--1 text-1000 mb-2 mb-sm-3 fw-normal"><span class='me-1 fs--2'>👍</span>Liked your comment.<span class="ms-2 text-400 fw-bold fs--2"></span></p>
													<p class="text-800 fs--1 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:18 AM </span>August 7,2021</p>
												</div>
											</div>
											<div class="font-sans-serif d-none d-sm-block">
												<button class="btn fs--2 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2 text-900"></span></button>
												<div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Marcar como no Leida</a></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer p-0 border-top border-0">
							<!-- <div class="my-2 text-center fw-bold fs--2 text-600"><a class="fw-bolder" href="pages/notifications.html">Historial de Notificationes</a></div> -->
						</div>
					</div>
				</div>
			</li>

			<li class="nav_item">
				<?php
					// include 'language_selector.php';
				?>
			</li>

			<li class="nav-item dropdown"><a class="nav-link lh-1 pe-0" id="navbarDropdownUser" href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
					<div class="avatar avatar-l ">
						<?php
							if ($_SESSION["photo"] != "") {
								echo '<img class="rounded-circle elevation-2" alt="assets/img/team/avatar.webp" src="' . $_SESSION["photo"] . '">';
							} else {
								echo '<img src="assets/img/team/avatar.webp" class="rounded-circle elevation-2" alt="Foto Usuario">';
							}
						?>
					</div>
				</a>
				<div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border border-300" aria-labelledby="navbarDropdownUser">
					<div class="card position-relative border-0">
						<div class="card-body p-0">
							<div class="text-center pt-4 pb-3">
								<div class="avatar avatar-xl ">
									<?php
										if ($_SESSION["photo"] != "") {
											echo '<img class="rounded-circle elevation-2" alt="assets/img/team/avatar.webp" src="' . $_SESSION["photo"] . '">';
										} else {
											echo '<img src="assets/img/team/avatar.webp" class="rounded-circle elevation-2" alt="Foto Usuario">';
										}
									?>
								</div>
								<h6 class="mt-2 text-black"><?php echo $_SESSION["user_name"]; ?></h6>
								<h7 class="mt-2 text-black"><?php echo $_SESSION["user_email"]; ?></h7>
							</div>
							<div class="mb-3 mx-3">
								<input class="form-control form-control-sm" id="statusUpdateInput" type="text" placeholder="Actualizar tu estado" />
							</div>
						</div>
						<div class="overflow-auto scrollbar" style="height: 10rem;">
							<ul class="nav d-flex flex-column mb-2 pb-1">
								<li class="nav-item"><a class="nav-link px-3" href="userprofile"> <span class="me-2 text-900" data-feather="user"></span><span>Perfil</span></a></li>
								<li class="nav-item"><a class="nav-link px-3" href="./"><span class="me-2 text-900" data-feather="pie-chart"></span>Dashboard</a></li>
								<li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-900" data-feather="settings"></span>Configuración y privacidad</a></li>
								<li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-900" data-feather="help-circle"></span>Centro de ayuda</a></li>
								<li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-900" data-feather="globe"></span>Idioma</a></li>
							</ul>
						</div>
						<div class="card-footer p-0 border-top">
							<div class="px-3 py-3"> <a class="btn btn-outline-danger d-flex flex-center w-100" href="app/views/auth/signout.php"> <span class="me-2" data-feather="log-out"> </span>Cerrar sesión</a></div>
							<!-- <div class="my-2 text-center fw-bold fs--2 text-600"><a class="text-600 me-1" href="#!">Privacy Policy</a>&bull;<a class="text-600 mx-1" href="#!">Terms</a>&bull;<a class="text-600 ms-1" href="#!">Cookies</a></div> -->
						</div>
					</div>
				</div>
			</li>
		</ul>
	</div>








</nav>

<nav class="navbar navbar-top fixed-top navbar-expand-lg" id="navbarTop" style="display:none;">
	<div class="navbar-logo">
		<button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTopCollapse" aria-controls="navbarTopCollapse" aria-expanded="false" aria-label="Toggle Navigation"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
		<a class="navbar-brand me-1 me-sm-3" href="./">
			<div class="d-flex align-items-center">
				<div class="d-flex align-items-center"><img src="" alt="VET-Clinic" width="50" />
					<p class="logo-text ms-2 d-none d-sm-block">VET-Clinic</p>
				</div>
			</div>
		</a>
	</div>
	<div class="collapse navbar-collapse navbar-top-collapse order-1 order-lg-0 justify-content-center" id="navbarTopCollapse">
		<ul class="navbar-nav navbar-nav-top" data-dropdown-on-hover="data-dropdown-on-hover">
			<?php
				/*
				$modulo = "**";
				$menu = "**";
				$opcion = "**";
				$menuact = '1';
				foreach ($_SESSION['permissionssin'] as $key => $value) {
					if ($value["UsuPermi"] == 0) {
						continue;
					}
					if ($modulo != $value["module_id"]) {
						if ($modulo != "**") {
							echo '</ul></li>';
							if ($menu != "**" && $menuact == '1') {
								echo '</ul></li>';
							}
						}
						if ($value["status_module"] == 1) {
							$modulo = $value["module_id"];
							$menu = "**";
							$opcion = "**";
							echo '
								<li class="nav-item dropdown">
									<a class="nav-link dropdown-toggle lh-1" href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
										<span class="uil fs-0 me-2 uil"  "' . $value["image_module"] . '"></span>' . $value["description_module"] . '
									</a>
										<ul class="dropdown-menu">
							';
						}
					}
					if ($value["status_module"] == 1) {
						if ($menu != $value["id_menu"]) {
							if ($menu != "**" && $menuact == "1") {
								echo '</ul></li>';
							}
							$menu = $value["id_menu"];
							if ($value["status_module"] == 1 && $value["status_option"] == 3) {
								echo '<ul style="nav nav-treeview"><a href="';
								if ($value["UsuPermi"] != 0) {
									echo $value["OpcLink"] . '">';
								} else {
									echo 'forbidden">';
								}
								echo '<span class="fs-6">' . $value["description_option"] . '</span></a>';
							} else {
								if ($value["status_menu"] == '1') {
									$menuact = '1';
									echo '
										<li class="nav-item my-1" style="font-size: 14px;">
										<a href="" class="nav-link">
											<p class="fs-6">' . $value["description_menu"] . '<i class="right fas fa-angle-left"></i>
											</p>
										</a>
											<ul class="nav nav-treeview">
									';
								} else {
									$menuact = '0';
								}
							}
						}
					}
					if ($value["status_module"] == 2 && $value["status_option"] == 3) {
						$menu = "**";
						$modulo = "**";
						echo '<li class="nav-item my-1" style="font-size: 14px;"><a href="';
						if ($value["UsuPermi"] != 0) {
							echo $value["OpcLink"] . '"';
						} else {
							echo 'forbidden" ';
						}
						echo ' class="nav-link" style="padding: 8px 5px;">';
						echo '<i class="nav-icon ' . $value["image_module"] . '"></i>';
						echo '<p class="fs-6">' . $value["description_option"] . '</p></a></li>';
					}
					if ($value["status_option"] == 1) {
						$opcion = "1";
						// <ul class="dropdown-menu
						echo '<li><a class="dropdown-item" href="';
						if ($value["UsuPermi"] != 0) {
							echo $value["OpcLink"] . '';
						} else {
							echo 'forbidden';
						}
						echo '"><div class="dropdown-item-wrapper"><span class="me-2 uil"></span>' . $value["description_option"] . '</div></a></li>';
					}
				}
				*/
			?>
		</ul>
	</div>

	<?php
		// include 'app/views/layouts/language_selector.php';
	?>


	<ul class="navbar-nav navbar-nav-icons flex-row">
		<li class="nav-item">
			<div class="theme-control-toggle fa-icon-wait px-2">
				<input class="form-check-input ms-0 theme-control-toggle-input" type="checkbox" data-theme-control="phoenixTheme" value="dark" id="themeControlToggle" />
				<label class="mb-0 theme-control-toggle-label theme-control-toggle-light" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch theme"><span class="icon" data-feather="moon"></span></label>
				<label class="mb-0 theme-control-toggle-label theme-control-toggle-dark" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch theme"><span class="icon" data-feather="sun"></span></label>
			</div>
		</li>
		<li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#searchBoxModal"><span data-feather="search" style="height:19px;width:19px;margin-bottom: 2px;"></span></a></li>
		<li class="nav-item dropdown">
			<a class="nav-link" href="#" style="min-width: 2.5rem" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-auto-close="outside"><span data-feather="bell" style="height:20px;width:20px;"></span></a>
			<div class="dropdown-menu dropdown-menu-end notification-dropdown-menu py-0 shadow border border-300 navbar-dropdown-caret" id="navbarDropdownNotfication" aria-labelledby="navbarDropdownNotfication">
				<div class="card position-relative border-0">
					<div class="card-header p-2">
						<div class="d-flex justify-content-between">
							<h5 class="text-black mb-0">Notificatons</h5>
							<button class="btn btn-link p-0 fs--1 fw-normal" type="button">Mark all as read</button>
						</div>
					</div>
					<div class="card-body p-0">
						<div class="scrollbar-overlay" style="height: 27rem;">
							<div class="border-300">
								<div class="px-2 px-sm-3 py-3 border-300 notification-card position-relative read border-bottom">
									<div class="d-flex align-items-center justify-content-between position-relative">
										<div class="d-flex">
											<!-- <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="assets/img/team/40x40/30.webp" alt="" /> -->
											</div>
											<div class="flex-1 me-sm-3">
												<h4 class="fs--1 text-black">Jessie Samson</h4>
												<p class="fs--1 text-1000 mb-2 mb-sm-3 fw-normal"><span class='me-1 fs--2'>💬</span>Mentioned you in a comment.<span class="ms-2 text-400 fw-bold fs--2">10m</span></p>
												<p class="text-800 fs--1 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:41 AM </span>August 7,2021</p>
											</div>
										</div>
										<div class="font-sans-serif d-none d-sm-block">
											<button class="btn fs--2 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2 text-900"></span></button>
											<div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
										</div>
									</div>
								</div>
								<div class="px-2 px-sm-3 py-3 border-300 notification-card position-relative unread border-bottom">
									<div class="d-flex align-items-center justify-content-between position-relative">
										<div class="d-flex">
											<div class="avatar avatar-m status-online me-3">
												<div class="avatar-name rounded-circle"><span>J</span></div>
											</div>
											<div class="flex-1 me-sm-3">
												<h4 class="fs--1 text-black">Jane Foster</h4>
												<p class="fs--1 text-1000 mb-2 mb-sm-3 fw-normal"><span class='me-1 fs--2'>📅</span>Created an event.<span class="ms-2 text-400 fw-bold fs--2">20m</span></p>
												<p class="text-800 fs--1 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:20 AM </span>August 7,2021</p>
											</div>
										</div>
										<div class="font-sans-serif d-none d-sm-block">
											<button class="btn fs--2 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2 text-900"></span></button>
											<div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
										</div>
									</div>
								</div>
								<div class="px-2 px-sm-3 py-3 border-300 notification-card position-relative unread border-bottom">
									<div class="d-flex align-items-center justify-content-between position-relative">
										<div class="d-flex">
											<!-- <div class="avatar avatar-m status-online me-3"><img class="rounded-circle avatar-placeholder" src="assets/img/team/40x40/avatar.webp" alt="" /> -->
											</div>
											<div class="flex-1 me-sm-3">
												<h4 class="fs--1 text-black">Jessie Samson</h4>
												<p class="fs--1 text-1000 mb-2 mb-sm-3 fw-normal"><span class='me-1 fs--2'>👍</span>Liked your comment.<span class="ms-2 text-400 fw-bold fs--2">1h</span></p>
												<p class="text-800 fs--1 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">9:30 AM </span>August 7,2021</p>
											</div>
										</div>
										<div class="font-sans-serif d-none d-sm-block">
											<button class="btn fs--2 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2 text-900"></span></button>
											<div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
										</div>
									</div>
								</div>
							</div>
							<div class="border-300">
								<div class="px-2 px-sm-3 py-3 border-300 notification-card position-relative unread border-bottom">
									<div class="d-flex align-items-center justify-content-between position-relative">
										<div class="d-flex">
											<!-- <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="assets/img/team/40x40/57.webp" alt="" /> -->
											</div>
											<div class="flex-1 me-sm-3">
												<h4 class="fs--1 text-black">Kiera Anderson</h4>
												<p class="fs--1 text-1000 mb-2 mb-sm-3 fw-normal"><span class='me-1 fs--2'>💬</span>Mentioned you in a comment.<span class="ms-2 text-400 fw-bold fs--2"></span></p>
												<p class="text-800 fs--1 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">9:11 AM </span>August 7,2021</p>
											</div>
										</div>
										<div class="font-sans-serif d-none d-sm-block">
											<button class="btn fs--2 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2 text-900"></span></button>
											<div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
										</div>
									</div>
								</div>
								<div class="px-2 px-sm-3 py-3 border-300 notification-card position-relative unread border-bottom">
									<div class="d-flex align-items-center justify-content-between position-relative">
										<div class="d-flex">
											<!-- <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="assets/img/team/40x40/59.webp" alt="" /> -->
											</div>
											<div class="flex-1 me-sm-3">
												<h4 class="fs--1 text-black">Herman Carter</h4>
												<p class="fs--1 text-1000 mb-2 mb-sm-3 fw-normal"><span class='me-1 fs--2'>👤</span>Tagged you in a comment.<span class="ms-2 text-400 fw-bold fs--2"></span></p>
												<p class="text-800 fs--1 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:58 PM </span>August 7,2021</p>
											</div>
										</div>
										<div class="font-sans-serif d-none d-sm-block">
											<button class="btn fs--2 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2 text-900"></span></button>
											<div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
										</div>
									</div>
								</div>
								<div class="px-2 px-sm-3 py-3 border-300 notification-card position-relative read ">
									<div class="d-flex align-items-center justify-content-between position-relative">
										<div class="d-flex">
											<!-- <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="assets/img/team/40x40/58.webp" alt="" /> -->
											</div>
											<div class="flex-1 me-sm-3">
												<h4 class="fs--1 text-black">Benjamin Button</h4>
												<p class="fs--1 text-1000 mb-2 mb-sm-3 fw-normal"><span class='me-1 fs--2'>👍</span>Liked your comment.<span class="ms-2 text-400 fw-bold fs--2"></span></p>
												<p class="text-800 fs--1 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:18 AM </span>August 7,2021</p>
											</div>
										</div>
										<div class="font-sans-serif d-none d-sm-block">
											<button class="btn fs--2 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2 text-900"></span></button>
											<div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer p-0 border-top border-0">
						<!-- <div class="my-2 text-center fw-bold fs--2 text-600"><a class="fw-bolder" href="pages/notifications.html">Notification history</a></div> -->
					</div>
				</div>
			</div>
		</li>
		<li class="nav-item dropdown"><a class="nav-link lh-1 pe-0" id="navbarDropdownUser" href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
				<div class="avatar avatar-l ">
					<?php
					if ($_SESSION["photo"] != "") {
						echo '<img class="rounded-circle elevation-2" alt="assets/img/team/avatar.webp" src="' . $_SESSION["photo"] . '">';
					} else {
						echo '<img src="assets/img/team/avatar.webp" class="rounded-circle elevation-2" alt="Foto Usuario">';
					}
					?>
				</div>
			</a>
			<div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border border-300" aria-labelledby="navbarDropdownUser">
				<div class="card position-relative border-0">
					<div class="card-body p-0">
						<div class="text-center pt-4 pb-3">
							<div class="avatar avatar-xl ">
								<?php
								if ($_SESSION["photo"] != "") {
									echo '<img class="rounded-circle elevation-2" alt="assets/img/team/avatar.webp" src="' . $_SESSION["photo"] . '">';
								} else {
									echo '<img src="assets/img/team/avatar.webp" class="rounded-circle elevation-2" alt="Foto Usuario">';
								}
								?>
							</div>
							<h6 class="mt-2 text-black"><?php echo $_SESSION["user_name"]; ?></h6>
							<h7 class="mt-2 text-black"><?php echo $_SESSION["user_email"]; ?></h7>
						</div>
						<div class="mb-3 mx-3">
							<input class="form-control form-control-sm" id="statusUpdateInput" type="text" placeholder="Actualiza tu Estatus" />
						</div>
					</div>
					<div class="overflow-auto scrollbar" style="height: 10rem;">
						<ul class="nav d-flex flex-column mb-2 pb-1">
							<li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-900" data-feather="user"></span><span>Perfil</span></a></li>
							<li class="nav-item"><a class="nav-link px-3" href="./"><span class="me-2 text-900" data-feather="pie-chart"></span>Dashboard</a></li>
							<li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-900" data-feather="settings"></span>Configuración &amp; Privacidad</a></li>
							<li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-900" data-feather="help-circle"></span>Centro de ayuda</a></li>
							<li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-900" data-feather="globe"></span>Idioma</a></li>
						</ul>
					</div>
					<div class="card-footer p-0 border-top">
						<div class="px-3 pt-3"> <a class="btn btn-outline-danger d-flex flex-center w-100" href="signout"> <span class="me-2" data-feather="log-out"> </span>Cerrar Sesión</a></div>
						<div class="my-2 text-center fw-bold fs--2 text-600"><a class="text-600 me-1" href="#!">Política de privacidad</a>&bull;<a class="text-600 mx-1" href="#!">Terminos</a>&bull;<a class="text-600 ms-1" href="#!">Cookies</a></div>
					</div>
				</div>
			</div>
		</li>
	</ul>
</nav>

<script>
	/*
var navbarTopShape = window.config.config.phoenixNavbarTopShape;
var navbarPosition = window.config.config.phoenixNavbarPosition;
var body = document.querySelector('body');
var navbarDefault = document.querySelector('#navbarDefault');
var navbarTop = document.querySelector('#navbarTop');
var topNavSlim = document.querySelector('#topNavSlim');
var navbarTopSlim = document.querySelector('#navbarTopSlim');
var navbarCombo = document.querySelector('#navbarCombo');
var navbarComboSlim = document.querySelector('#navbarComboSlim');
var dualNav = document.querySelector('#dualNav');
var documentElement = document.documentElement;
var navbarVertical = document.querySelector('.navbar-vertical');
if (navbarPosition === 'dual-nav') {
	topNavSlim.remove();
	navbarTop.remove();
	navbarVertical.remove();
	navbarTopSlim.remove();
	navbarCombo.remove();
	navbarComboSlim.remove();
	navbarDefault.remove();
	dualNav.removeAttribute('style');
	documentElement.classList.add('dual-nav');
} else if (navbarTopShape === 'slim' && navbarPosition === 'vertical') {
	navbarDefault.remove();
	navbarTop.remove();
	navbarTopSlim.remove();
	navbarCombo.remove();
	navbarComboSlim.remove();
	topNavSlim.style.display = 'block';
	navbarVertical.style.display = 'inline-block';
	body.classList.add('nav-slim');
} else if (navbarTopShape === 'slim' && navbarPosition === 'horizontal') {
	navbarDefault.remove();
	navbarVertical.remove();
	navbarTop.remove();
	topNavSlim.remove();
	navbarCombo.remove();
	navbarComboSlim.remove();
	navbarTopSlim.removeAttribute('style');
	body.classList.add('nav-slim');
} else if (navbarTopShape === 'slim' && navbarPosition === 'combo') {
	navbarDefault.remove();
	//- navbarVertical.remove();
	navbarTop.remove();
	topNavSlim.remove();
	navbarCombo.remove();
	navbarTopSlim.remove();
	navbarComboSlim.removeAttribute('style');
	navbarVertical.removeAttribute('style');
	body.classList.add('nav-slim');
} else if (navbarTopShape === 'default' && navbarPosition === 'horizontal') {
	alert("entro horiz");
	navbarDefault.remove();
	topNavSlim.remove();
	navbarVertical.remove();
	navbarTopSlim.remove();
	navbarCombo.remove();
	navbarComboSlim.remove();
	navbarTop.removeAttribute('style');
	documentElement.classList.add('navbar-horizontal');
} else if (navbarTopShape === 'default' && navbarPosition === 'combo') {
	topNavSlim.remove();
	navbarTop.remove();
	navbarTopSlim.remove();
	navbarDefault.remove();
	navbarComboSlim.remove();
	navbarCombo.removeAttribute('style');
	navbarVertical.removeAttribute('style');
	documentElement.classList.add('navbar-combo')
} else {
	topNavSlim.remove();
	navbarTop.remove();
	navbarTopSlim.remove();
	navbarCombo.remove();
	navbarComboSlim.remove();
	navbarDefault.removeAttribute('style');
	navbarVertical.removeAttribute('style');
}
var navbarTopStyle = window.config.config.phoenixNavbarTopStyle;
var navbarTop = document.querySelector('.navbar-top');
if (navbarTopStyle === 'darker') {
	navbarTop.classList.add('navbar-darker');
}
var navbarVerticalStyle = window.config.config.phoenixNavbarVerticalStyle;
var navbarVertical = document.querySelector('.navbar-vertical');
if (navbarVerticalStyle === 'darker') {
	navbarVertical.classList.add('navbar-darker');
}
*/
</script>


<!--**************************************-->
<div class="modal fade" id="searchBoxModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="true" data-phoenix-modal="data-phoenix-modal" style="--phoenix-backdrop-opacity: 1;">
	<div class="modal-dialog">
		<div class="modal-content mt-15 rounded-pill">
			<div class="modal-body p-0">
				<div class="search-box navbar-top-search-box" data-list='{"valueNames":["title"]}' style="width: auto;">
					<form class="position-relative" data-bs-toggle="search" data-bs-display="static">
						<input class="form-control search-input fuzzy-search rounded-pill form-control-lg" type="search" placeholder="Search..." aria-label="Search" />
						<span class="fas fa-search search-box-icon"></span>
					</form>
					<div class="btn-close position-absolute end-0 top-50 translate-middle cursor-pointer shadow-none" data-bs-dismiss="search">
						<button class="btn btn-link btn-close-falcon p-0" aria-label="Close"></button>
					</div>
					<div class="dropdown-menu border border-300 font-base start-0 py-0 overflow-hidden w-100">
						<div class="scrollbar-overlay" style="max-height: 30rem;">
							<div class="list pb-3">
								<h6 class="dropdown-header text-1000 fs--2 py-2">24 <span class="text-500">results</span></h6>
								<hr class="text-200 my-0" />
								<h6 class="dropdown-header text-1000 fs--1 border-bottom border-200 py-2 lh-sm">Recently Searched </h6>
								<div class="py-2">
									<a class="dropdown-item" href="apps/e-commerce/landing/product-details.html">
										<div class="d-flex align-items-center">
											<div class="fw-normal text-1000 title"><span class="fa-solid fa-clock-rotate-left" data-fa-transform="shrink-2"></span> Store Macbook</div>
										</div>
									</a>
									<a class="dropdown-item" href="apps/e-commerce/landing/product-details.html">
										<div class="d-flex align-items-center">
											<div class="fw-normal text-1000 title"> <span class="fa-solid fa-clock-rotate-left" data-fa-transform="shrink-2"></span> MacBook Air - 13″</div>
										</div>
									</a>
								</div>
								<hr class="text-200 my-0" />
								<h6 class="dropdown-header text-1000 fs--1 border-bottom border-200 py-2 lh-sm">Products</h6>
								<div class="py-2">
									<a class="dropdown-item py-2 d-flex align-items-center" href="apps/e-commerce/landing/product-details.html">
										<!-- <div class="file-thumbnail me-2"><img class="h-100 w-100 fit-cover rounded-3" src="assets/img/products/60x60/3.png" alt="" /></div> -->
										<div class="flex-1">
											<h6 class="mb-0 text-1000 title">MacBook Air - 13″</h6>
											<p class="fs--2 mb-0 d-flex text-700"><span class="fw-medium text-600">8GB Memory - 1.6GHz - 128GB Storage</span></p>
										</div>
									</a>
									<a class="dropdown-item py-2 d-flex align-items-center" href="apps/e-commerce/landing/product-details.html">
										<!-- <div class="file-thumbnail me-2"><img class="img-fluid" src="assets/img/products/60x60/3.png" alt="" /></div> -->
										<div class="flex-1">
											<h6 class="mb-0 text-1000 title">MacBook Pro - 13″</h6>
											<p class="fs--2 mb-0 d-flex text-700"><span class="fw-medium text-600 ms-2">30 Sep at 12:30 PM</span></p>
										</div>
									</a>
								</div>
								<hr class="text-200 my-0" />
								<h6 class="dropdown-header text-1000 fs--1 border-bottom border-200 py-2 lh-sm">Quick Links</h6>
								<div class="py-2">
									<a class="dropdown-item" href="apps/e-commerce/landing/product-details.html">
										<div class="d-flex align-items-center">
											<div class="fw-normal text-1000 title"><span class="fa-solid fa-link text-900" data-fa-transform="shrink-2"></span> Support MacBook House</div>
										</div>
									</a>
									<a class="dropdown-item" href="apps/e-commerce/landing/product-details.html">
										<div class="d-flex align-items-center">
											<div class="fw-normal text-1000 title"> <span class="fa-solid fa-link text-900" data-fa-transform="shrink-2"></span> Store MacBook″</div>
										</div>
									</a>
								</div>
								<hr class="text-200 my-0" />
								<h6 class="dropdown-header text-1000 fs--1 border-bottom border-200 py-2 lh-sm">Files</h6>
								<div class="py-2">
									<a class="dropdown-item" href="apps/e-commerce/landing/product-details.html">
										<div class="d-flex align-items-center">
											<div class="fw-normal text-1000 title"><span class="fa-solid fa-file-zipper text-900" data-fa-transform="shrink-2"></span> Library MacBook folder.rar</div>
										</div>
									</a>
									<a class="dropdown-item" href="apps/e-commerce/landing/product-details.html">
										<div class="d-flex align-items-center">
											<div class="fw-normal text-1000 title"> <span class="fa-solid fa-file-lines text-900" data-fa-transform="shrink-2"></span> Feature MacBook extensions.txt</div>
										</div>
									</a>
									<a class="dropdown-item" href="apps/e-commerce/landing/product-details.html">
										<div class="d-flex align-items-center">
											<div class="fw-normal text-1000 title"> <span class="fa-solid fa-image text-900" data-fa-transform="shrink-2"></span> MacBook Pro_13.jpg</div>
										</div>
									</a>
								</div>
								<hr class="text-200 my-0" />
								<h6 class="dropdown-header text-1000 fs--1 border-bottom border-200 py-2 lh-sm">Members</h6>
								<div class="py-2">
									<a class="dropdown-item py-2 d-flex align-items-center" href="pages/members.html">
										<div class="avatar avatar-l status-online  me-2 text-900">
											<!-- <img class="rounded-circle " src="assets/img/team/40x40/10.webp" alt="" /> -->
										</div>
										<div class="flex-1">
											<h6 class="mb-0 text-1000 title">Carry Anna</h6>
											<p class="fs--2 mb-0 d-flex text-700">anna@technext.it</p>
										</div>
									</a>
									<a class="dropdown-item py-2 d-flex align-items-center" href="pages/members.html">
										<div class="avatar avatar-l  me-2 text-900">
											<!-- <img class="rounded-circle " src="assets/img/team/40x40/12.webp" alt="" /> -->
										</div>
										<div class="flex-1">
											<h6 class="mb-0 text-1000 title">John Smith</h6>
											<p class="fs--2 mb-0 d-flex text-700">smith@technext.it</p>
										</div>
									</a>
								</div>
								<hr class="text-200 my-0" />
								<h6 class="dropdown-header text-1000 fs--1 border-bottom border-200 py-2 lh-sm">Related Searches</h6>
								<div class="py-2">
									<a class="dropdown-item" href="apps/e-commerce/landing/product-details.html">
										<div class="d-flex align-items-center">
											<div class="fw-normal text-1000 title"><span class="fa-brands fa-firefox-browser text-900" data-fa-transform="shrink-2"></span> Search in the Web MacBook</div>
										</div>
									</a>
									<a class="dropdown-item" href="apps/e-commerce/landing/product-details.html">
										<div class="d-flex align-items-center">
											<div class="fw-normal text-1000 title"> <span class="fa-brands fa-chrome text-900" data-fa-transform="shrink-2"></span> Store MacBook″</div>
										</div>
									</a>
								</div>
							</div>
							<div class="text-center">
								<p class="fallback fw-bold fs-1 d-none">No Result Found.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>