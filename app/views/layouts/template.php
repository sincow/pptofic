<!DOCTYPE html>
<?php
if (!isset($_SESSION)) {
	session_start();
}
// $lang = Language::getInstance();
// $len = Language::getSupportedLanguages();
?>

<!-- <html lang="en-US" dir="ltr"> -->
<html lang="es" dir="ltr">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= APP_NAME ?></title>
	<meta name="Description" content="<?= APP_DESCRIPTION ?> Autor: Tincolsas">
	<link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-touch-icon.png">
	<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicons/favicon.ico">
	<link rel="manifest" href="assets/img/favicons/manifest.json">
	<meta name="msapplication-TileImage" content="assets/img/favicons/mstile-150x150.png">
	<meta name="theme-color" content="#ffffff">
	<meta name="pyt" content="<?= $_SESSION['csrf_token'] ?>">
	<script src="vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
	<script src="vendor/simplebar/simplebar.min.js"></script>
	<script src="assets/js/config.js"></script>
	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/jqueryNumber/jquerynumber.min.js"></script>

	<!-- ===============================================-->
	<!--    Stylesheets-->
	<!-- ===============================================-->
	<link href="vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
	<link href="vendor/choices/choices.min.css" rel="stylesheet">
	<link href="vendor/flatpickr/flatpickr.min.css" rel="stylesheet">
	<link href="vendor/dropzone/dropzone.min.css" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
	<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap" rel="stylesheet">
	<link href="vendor/simplebar/simplebar.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
	<link href="assets/css/theme-rtl.min.css" type="text/css" rel="stylesheet" id="style-rtl">
	<link href="assets/css/theme.min.css" type="text/css" rel="stylesheet" id="style-default">
	<link href="assets/css/user-rtl.min.css" type="text/css" rel="stylesheet" id="user-style-rtl">
	<link href="assets/css/user.min.css" type="text/css" rel="stylesheet" id="user-style-default">
	<link href="vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

	<!-- Datepicker CSS -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">


   <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">


	<script>
		var phoenixIsRTL = window.config.config.phoenixIsRTL;
		if (phoenixIsRTL) {
			var linkDefault = document.getElementById('style-default');
			var userLinkDefault = document.getElementById('user-style-default');
			linkDefault.setAttribute('disabled', true);
			userLinkDefault.setAttribute('disabled', true);
			document.querySelector('html').setAttribute('dir', 'rtl');
		} else {
			var linkRTL = document.getElementById('style-rtl');
			var userLinkRTL = document.getElementById('user-style-rtl');
			linkRTL.setAttribute('disabled', true);
			userLinkRTL.setAttribute('disabled', true);
		}
	</script>
	<!-- <link href="vendor/leaflet/leaflet.css" rel="stylesheet">
	<link href="vendor/leaflet.markercluster/MarkerCluster.css" rel="stylesheet">
	<link href="vendor/leaflet.markercluster/MarkerCluster.Default.css" rel="stylesheet"> -->
	<link href="vendor/sweetalert2/sweetalert2.min.css" rel="stylesheet">

	<link href="vendor/waitme/waitMe.min.css" rel="stylesheet">

	<script src="vendor/sweetalert2/sweetalert2.all.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<!-- <script src="vendor/leaflet/leaflet.js"></script>
	<script src="vendor/leaflet.markercluster/leaflet.markercluster.js"></script>
	<script src="vendor/leaflet.tilelayer.colorfilter/leaflet-tilelayer-colorfilter.min.js"></script> -->

	<!-- <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
		integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
		crossorigin="">
	</script> -->

</head>

<body>

   <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>

	<?php
		if (isset($_SESSION["user_id"])) {
			$pagina = $_SERVER["REQUEST_URI"];
			$pagina = str_replace('/vetclinic/', '', $pagina);
			$pagina = str_replace('/', '', $pagina);
			$dashborad = "";
			// $pagina = $_GET["ruta"]; // se adicionó para que pudieran aliminar registros (slia error de permiso)
			if (!isset($_GET["ruta"])) {
				if ($_SESSION["profile"] == "1") {
					$pagina = "dashboard";
					$dashborad = "app/views/dashboard/dashboard.php";
				} else {
					$pagina = "asisdashboard";
					$dashborad = "app/views/dashboard/asisdashboard.php";
				}
			} else {
				$pagina = $_GET["ruta"]; // se adicionó para que pudieran aliminar registros (slia error de permiso)
			}

			$permi = array_search($pagina, array_column($_SESSION['permissionssin'], 'OpcLink'));
			// if($pagina != "dashboard" && $pagina != "signout" && $pagina != "sinpermiso" && $pagina != "cerrarsesion"){
			if ($_SESSION['permissionssin'][$permi]["UsuPermi"] != 1 && $pagina != "dashboard" && $pagina != "asisdashboard" && $pagina != "signout" && $pagina != "forbidden" && $pagina != "cerrarsesion") {
				echo '<main class="main" id="top">';
				include "menu.php";
				include "header.php";
				include "forbidden.php";
				echo '</main>';
				return;
			}
		}

		if (isset($_SESSION["login"]) && $_SESSION["login"] == "ok") {
			echo '<main class="main" id="top">';
			echo '<input type="hidden" id="idSellerView" value="'.$_SESSION["user_id"].'">';
			echo '<input type="hidden" id="profile" value="'.$_SESSION["profile"].'">';
			include "menu.php";
			include "header.php";
			// include "messages.php";
			if (isset($_GET["ruta"])) {
				$opcion = array_search($_GET["ruta"], array_column($_SESSION['permissionssin'], 'OpcLink'));
				if ($opcion !== NULL && $opcion !== FALSE) {
					$permi = $_SESSION['permissionssin'][$opcion]["UsuPermi"];
				} else {
					$permi = 0;
				}
				if ($permi == 1 || $_GET["ruta"] == "dashboard" || $_GET["ruta"] == "sellersdashboard" || $_GET["ruta"] == "signout" || $_GET["ruta"] == "forbidden" || $_GET["ruta"] == "cerrarsesion") {
					$ruta = $_GET["ruta"] ?? '';
					$path = $_SESSION['permissionssin'][$opcion]["path_module"];
					$view = APP_PATH."/views/".$path."/" . $_GET["ruta"] . ".php";
					echo "<script>
						var navbarTopStyle = window.config.config.phoenixNavbarTopStyle;
						var navbarTop = document.querySelector('.navbar-top');
						if (navbarTopStyle === 'darker') {
							navbarTop.classList.add('navbar-darker');
						}
						var navbarVerticalStyle = window.config.config.phoenixNavbarVerticalStyle;
						var navbarVertical = document.querySelector('.navbar-vertical');
						if (navbarVertical && navbarVerticalStyle === 'darker') {
							navbarVertical.classList.add('navbar-darker');
						}

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
							if (topNavSlim != null) {
								topNavSlim.remove();
							}
							navbarTop.remove();
							navbarVertical.remove();
							if (navbarTopSlim != null) {
								navbarTopSlim.remove();
							}
							if (navbarCombo != null) {
								navbarCombo.remove();
							}
							if (navbarComboSlim != null) {
								navbarComboSlim.remove();
							}
							navbarDefault.remove();navbarTop
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
							navbarDefault.remove();
							if (topNavSlim != null) {
								topNavSlim.remove();
							}
							navbarVertical.remove();
							if (navbarTopSlim != null) {
								navbarTopSlim.remove();
							}
							if (navbarCombo != null) {
								navbarCombo.remove();
							}
							if (navbarComboSlim != null) {
								navbarComboSlim.remove();
							}
							navbarTop.removeAttribute('style');
							documentElement.classList.add('navbar-horizontal');
						} else if (navbarTopShape === 'default' && navbarPosition === 'combo') {
							if (topNavSlim != null) {
								topNavSlim.remove();
							}
							navbarTop.remove();
							if (navbarTopSlim != null) {
								navbarTopSlim.remove();
							}
							navbarDefault.remove();
							if (navbarComboSlim != null) {
								navbarComboSlim.remove();
							}
							navbarCombo.removeAttribute('style');
							navbarVertical.removeAttribute('style');
							documentElement.classList.add('navbar-combo')
						} else {
							if (topNavSlim != null) {
								topNavSlim.remove();
							}
							navbarTop.remove();
							if (navbarTopSlim != null) {
								navbarTopSlim.remove();
							}
							if (navbarCombo != null) {
								navbarCombo.remove();
							}
							if (navbarComboSlim != null) {
								navbarComboSlim.remove();
							}
							navbarDefault.removeAttribute('style');
							navbarVertical.removeAttribute('style');
						}
					</script>";
					// echo '<p>'. $_GET["ruta"] .'</p>';
					// echo '<p>'. $path .'</p>';
					if (file_exists($view)) {
						include APP_PATH."/views/".$path."/" . $_GET["ruta"] . ".php";
					} else {
						include APP_PATH."/views/auth/404.php";
					}
				} else {
					include APP_PATH."/views/auth/404.php";
				}
			} else {
				include $dashborad;
			}
		} else {
			include APP_PATH."/views/auth/login.php";
			return;
		}
	?>

	<script>
		/*
		var navbarTopStyle = window.config.config.phoenixNavbarTopStyle;
		var navbarTop = document.querySelector('.navbar-top');
		if (navbarTopStyle === 'darker') {
			navbarTop.classList.add('navbar-darker');
		}
		var navbarVerticalStyle = window.config.config.phoenixNavbarVerticalStyle;
		var navbarVertical = document.querySelector('.navbar-vertical');
		if (navbarVertical && navbarVerticalStyle === 'darker') {
			navbarVertical.classList.add('navbar-darker');
		}
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
			if (topNavSlim != null) {
				topNavSlim.remove();
			}
			navbarTop.remove();
			navbarVertical.remove();
			if (navbarTopSlim != null) {
				navbarTopSlim.remove();
			}
			if (navbarCombo != null) {
				navbarCombo.remove();
			}
			if (navbarComboSlim != null) {
				navbarComboSlim.remove();
			}
			navbarDefault.remove();navbarTop
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
			navbarDefault.remove();
			if (topNavSlim != null) {
				topNavSlim.remove();
			}
			navbarVertical.remove();
			if (navbarTopSlim != null) {
				navbarTopSlim.remove();
			}
			if (navbarCombo != null) {
				navbarCombo.remove();
			}
			if (navbarComboSlim != null) {
				navbarComboSlim.remove();
			}
			navbarTop.removeAttribute('style');
			documentElement.classList.add('navbar-horizontal');
		} else if (navbarTopShape === 'default' && navbarPosition === 'combo') {
			if (topNavSlim != null) {
				topNavSlim.remove();
			}
			navbarTop.remove();
			if (navbarTopSlim != null) {
				navbarTopSlim.remove();
			}
			navbarDefault.remove();
			if (navbarComboSlim != null) {
				navbarComboSlim.remove();
			}
			navbarCombo.removeAttribute('style');
			navbarVertical.removeAttribute('style');
			documentElement.classList.add('navbar-combo')
		} else {
			if (topNavSlim != null) {
				topNavSlim.remove();
			}
			navbarTop.remove();
			if (navbarTopSlim != null) {
				navbarTopSlim.remove();
			}
			if (navbarCombo != null) {
				navbarCombo.remove();
			}
			if (navbarComboSlim != null) {
				navbarComboSlim.remove();
			}
			navbarDefault.removeAttribute('style');
			navbarVertical.removeAttribute('style');
		}
		*/

		/*
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

	</main>

	<!--
	<a class="card setting-toggle" href="#settings-offcanvas" data-bs-toggle="offcanvas">
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
			</div><small class="text-uppercase text-700 fw-bold py-2 pe-2 ps-1 rounded-end">Personalizar</small>
		</div>
	</a>
	-->

	<div class="offcanvas offcanvas-end settings-panel border-0" id="settings-offcanvas" tabindex="-1" aria-labelledby="settings-offcanvas">
		<div class="offcanvas-header align-items-start border-bottom flex-column">
			<div class="pt-1 w-100 mb-6 d-flex justify-content-between align-items-start">
				<div>
					<h5 class="mb-2 me-2 lh-sm"><span class="fas fa-palette me-2 fs-0"></span>Personalizador de temas</h5>
					<p class="mb-0 fs--1">Explora diferentes estilos según tus preferencias</p>
				</div>
				<button class="btn p-1 fw-bolder" type="button" data-bs-dismiss="offcanvas" aria-label="Close"><span class="fas fa-times fs-0"> </span></button>
			</div>
			<button class="btn btn-phoenix-secondary w-100" data-theme-control="reset"><span class="fas fa-arrows-rotate me-2 fs--2"></span>Restablecer a los predeterminados</button>
		</div>
		<div class="offcanvas-body scrollbar px-card" id="themeController">
			<div class="setting-panel-item mt-0">
				<h5 class="setting-panel-item-title">Esquema de colores</h5>
				<div class="row gx-2">
					<div class="col-6">
						<input class="btn-check" id="themeSwitcherLight" name="theme-color" type="radio" value="light" data-theme-control="phoenixTheme" />
						<label class="btn d-inline-block btn-navbar-style fs--1" for="themeSwitcherLight"> <span class="mb-2 rounded d-block"><img class="img-fluid img-prototype mb-0" src="assets/img/generic/default-light.png" alt="" /></span><span class="label-text">Claro</span></label>
					</div>
					<div class="col-6">
						<input class="btn-check" id="themeSwitcherDark" name="theme-color" type="radio" value="dark" data-theme-control="phoenixTheme" />
						<label class="btn d-inline-block btn-navbar-style fs--1" for="themeSwitcherDark"> <span class="mb-2 rounded d-block"><img class="img-fluid img-prototype mb-0" src="assets/img/generic/default-dark.png" alt="" /></span><span class="label-text"> Oscuro</span></label>
					</div>
				</div>
			</div>
			<div class="border rounded-3 p-4 setting-panel-item bg-white">
				<div class="d-flex justify-content-between align-items-center">
					<h5 class="setting-panel-item-title mb-1">RTL </h5>
					<div class="form-check form-switch mb-0">
						<input class="form-check-input ms-auto" type="checkbox" name="phoenixIsRTL" data-theme-control="phoenixIsRTL" />
					</div>
				</div>
				<p class="mb-0 text-700">Cambiar dirección del texto</p>
			</div>
			<div class="border rounded-3 p-4 setting-panel-item bg-white">
				<div class="d-flex justify-content-between align-items-center">
					<h5 class="setting-panel-item-title mb-1">Chat de soporte </h5>
					<div class="form-check form-switch mb-0">
						<input class="form-check-input ms-auto" type="checkbox" name="phoenixSupportChat" data-theme-control="phoenixSupportChat" />
					</div>
				</div>
				<p class="mb-0 text-700">Alternar chat de soporte</p>
			</div>
			<div class="setting-panel-item">
				<h5 class="setting-panel-item-title">Tipo de Navegación</h5>
				<div class="row gx-2">
					<div class="col-6">
						<input class="btn-check" id="navbarPositionVertical" name="navigation-type" type="radio" value="vertical" data-theme-control="phoenixNavbarPosition" data-page-url="./" />
						<label class="btn d-inline-block btn-navbar-style fs--1" for="navbarPositionVertical"> <span class="mb-2 rounded d-block"><img class="img-fluid img-prototype d-dark-none" src="assets/img/generic/default-light.png" alt="" /><img class="img-fluid img-prototype d-light-none" src="assets/img/generic/default-dark.png" alt="" /></span><span class="label-text">Vertical</span></label>
					</div>
					<div class="col-6">
						<input class="btn-check" id="navbarPositionHorizontal" name="navigation-type" type="radio" value="horizontal" data-theme-control="phoenixNavbarPosition" data-page-url="./" />
						<label class="btn d-inline-block btn-navbar-style fs--1" for="navbarPositionHorizontal"> <span class="mb-2 rounded d-block"><img class="img-fluid img-prototype d-dark-none" src="assets/img/generic/top-default.png" alt="" /><img class="img-fluid img-prototype d-light-none" src="assets/img/generic/top-default-dark.png" alt="" /></span><span class="label-text"> Horizontal</span></label>
					</div>
					<!--
					<div class="col-6">
						<input class="btn-check" id="navbarPositionCombo" name="navigation-type" type="radio" value="combo" data-theme-control="phoenixNavbarPosition" data-page-url="documentation/layouts/combo-navbar.html" />
						<label class="btn d-inline-block btn-navbar-style fs--1" for="navbarPositionCombo"> <span class="mb-2 rounded d-block"><img class="img-fluid img-prototype d-dark-none" src="assets/img/generic/nav-combo-light.png" alt=""/><img class="img-fluid img-prototype d-light-none" src="assets/img/generic/nav-combo-dark.png" alt=""/></span><span class="label-text"> Combo</span></label>
					</div>
					<div class="col-6">
						<input class="btn-check" id="navbarPositionTopDouble" name="navigation-type" type="radio" value="dual-nav" data-theme-control="phoenixNavbarPosition" data-page-url="documentation/layouts/dual-nav.html" />
						<label class="btn d-inline-block btn-navbar-style fs--1" for="navbarPositionTopDouble"> <span class="mb-2 rounded d-block"><img class="img-fluid img-prototype d-dark-none" src="assets/img/generic/dual-light.png" alt=""/><img class="img-fluid img-prototype d-light-none" src="assets/img/generic/dual-dark.png" alt=""/></span><span class="label-text"> Dual nav</span></label>
					</div>
					-->
				</div>
			</div>
			<div class="setting-panel-item">
				<h5 class="setting-panel-item-title">Apariencia de la barra de navegación vertical</h5>
				<div class="row gx-2">
					<div class="col-6">
						<input class="btn-check" id="navbar-style-default" type="radio" name="config.name" value="default" data-theme-control="phoenixNavbarVerticalStyle" />
						<label class="btn d-block w-100 btn-navbar-style fs--1" for="navbar-style-default"> <img class="img-fluid img-prototype d-dark-none" src="assets/img/generic/default-light.png" alt="" /><img class="img-fluid img-prototype d-light-none" src="assets/img/generic/default-dark.png" alt="" /><span class="label-text d-dark-none"> Default</span><span class="label-text d-light-none">Default</span></label>
					</div>
					<div class="col-6">
						<input class="btn-check" id="navbar-style-dark" type="radio" name="config.name" value="darker" data-theme-control="phoenixNavbarVerticalStyle" />
						<label class="btn d-block w-100 btn-navbar-style fs--1" for="navbar-style-dark"> <img class="img-fluid img-prototype d-dark-none" src="assets/img/generic/vertical-darker.png" alt="" /><img class="img-fluid img-prototype d-light-none" src="assets/img/generic/vertical-lighter.png" alt="" /><span class="label-text d-dark-none"> Más Oscuro</span><span class="label-text d-light-none">Más Claro</span></label>
					</div>
				</div>
			</div>
			<!--
			<div class="setting-panel-item">
				<h5 class="setting-panel-item-title">Forma de barra de navegación horizontal</h5>
				<div class="row gx-2">
					<div class="col-6">
						<input class="btn-check" id="navbarShapeDefault" name="navbar-shape" type="radio" value="default" data-theme-control="phoenixNavbarTopShape" data-page-url="documentation/layouts/horizontal-navbar.html" />
						<label class="btn d-inline-block btn-navbar-style fs--1" for="navbarShapeDefault"> <span class="mb-2 rounded d-block"><img class="img-fluid img-prototype d-dark-none mb-0" src="assets/img/generic/top-default.png" alt=""/><img class="img-fluid img-prototype d-light-none mb-0" src="assets/img/generic/top-default-dark.png" alt=""/></span><span class="label-text">Default</span></label>
					</div>
					<div class="col-6">
						<input class="btn-check" id="navbarShapeSlim" name="navbar-shape" type="radio" value="slim" data-theme-control="phoenixNavbarTopShape" data-page-url="documentation/layouts/horizontal-navbar.html#horizontal-navbar-slim" />
						<label class="btn d-inline-block btn-navbar-style fs--1" for="navbarShapeSlim"> <span class="mb-2 rounded d-block"><img class="img-fluid img-prototype d-dark-none mb-0" src="assets/img/generic/top-slim.png" alt=""/><img class="img-fluid img-prototype d-light-none mb-0" src="assets/img/generic/top-slim-dark.png" alt=""/></span><span class="label-text"> Delgado</span></label>
					</div>
				</div>
			</div>
			-->
			<div class="setting-panel-item">
				<h5 class="setting-panel-item-title">Apariencia de la barra de navegación horizontal</h5>
				<div class="row gx-2">
					<div class="col-6">
						<input class="btn-check" id="navbarTopDefault" name="navbar-top-style" type="radio" value="default" data-theme-control="phoenixNavbarTopStyle" />
						<label class="btn d-inline-block btn-navbar-style fs--1" for="navbarTopDefault"> <span class="mb-2 rounded d-block"><img class="img-fluid img-prototype d-dark-none mb-0" src="assets/img/generic/top-default.png" alt="" /><img class="img-fluid img-prototype d-light-none mb-0" src="assets/img/generic/top-style-darker.png" alt="" /></span><span class="label-text">Default</span></label>
					</div>
					<div class="col-6">
						<input class="btn-check" id="navbarTopDarker" name="navbar-top-style" type="radio" value="darker" data-theme-control="phoenixNavbarTopStyle" />
						<label class="btn d-inline-block btn-navbar-style fs--1" for="navbarTopDarker"> <span class="mb-2 rounded d-block"><img class="img-fluid img-prototype d-dark-none mb-0" src="assets/img/generic/navbar-top-style-light.png" alt="" /><img class="img-fluid img-prototype d-light-none mb-0" src="assets/img/generic/top-style-lighter.png" alt="" /></span><span class="label-text d-dark-none">Más Oscuro</span><span class="label-text d-light-none">Más Claro</span></label>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!-- ========JavaScripts =========-->
	<script>
		$('.select2').select2();
	</script>


	<style>
		/*
		.select2-container--default .select2-selection,
		.select2-container--default .select2-dropdown {
			background-color: var(--phoenix-body-bg) !important;
			color: var(--phoenix-body-color) !important;
		}
		.select2-container--default .select2-results__option {
			background-color: var(--phoenix-body-bg);
			color: var(--phoenix-body-color);
		}
		.select2-container--default .select2-results__option--highlighted {
			background-color: var(--phoenix-primary) !important;
			color: white !important;
		}
		.select2-search__field {
			color: var(--phoenix-body-color) !important;
		}
		.select2-container--default .select2-search__field {
			background-color: var(--phoenix-body-bg) !important;
			color: var(--phoenix-body-color) !important;
			border-color: var(--phoenix-border-color) !important;
		}
		.select2-container--default .select2-search__field:focus {
			box-shadow: 0 0 0 0.25rem rgba(var(--phoenix-primary-rgb), 0.25);
		}
		.select2-container--default .select2-search__field::placeholder {
			color: var(--phoenix-secondary-color) !important;
			opacity: 0.7;
		}
		*/

		input[type=number]::-webkit-inner-spin-button,
		input[type=number]::-webkit-outer-spin-button {
			-webkit-appearance: none;
			margin: 0;
		}

		.select2-container--default .select2-selection--single {
			height: 35px;
			width: 100%;
		}

		.select2-container .select2-results__option--selectable {
			white-space: nowrap;
			word-wrap: break-word;
			min-height: 30px;
			line-height: 1.5;
			overflow: hidden;
			text-overflow: ellipsis;
			max-width: 100%;
			/* overflow-y: auto;
			overflow-x: hidden; */
		}

		.select2-container {
			width: 100% !important;
		}

		.select2-container--default .select2-selection--single.is-invalid {
        border-color: #dc3545;
      }

		.select2-container.is-invalid {
			border: 1px solid #dc3545 !important;
			border-radius: 0.25rem;
			padding: 0.0rem 0.0rem;
			position: relative;
			padding-right: calc(1.49em + 1rem);
			background: none;
			background: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 13 13' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e") no-repeat right calc(1.205em + 0.1875rem) center/calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
		}
		.select2-container.is-invalid .select2-selection {
			border: none !important;
		}
		.select2-container.is-invalid.select2-container--focus .select2-selection {
			box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
			outline: none;
		}

		.select2-container--default .select2-selection--single .select2-selection__rendered {
			line-height: 1.25;
			padding-top: 4px;
			padding-left: 2px;
			font-size: 0.8rem;
			font-weight: 600;
		}

		.select2-results__option--selectable {
			/* .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable { */
			height: 28px;
			font-size: 0.8rem;
			font-weight: 600;
		}

		.select2-container--default .select2-selection--single .select2-selection__arrow {
			top: 5px;
		}

		.map {
			height: 500px;
		}

		.input-error {
			border: 2px solid red;
		}

		.wms {
			font-family: Verdana, Geneva, Tahoma, sans-serif;
			margin-left: 5px;
			font-weight: 560;
			font-size: 1.5rem;
			color: #042940;
			text-decoration: none;
		}

		.form-label {
			color: #6e707e;
			text-transform: lowercase;
		}

	</style>

	<style>
		/* SELECT POLYLOGIK
		.select2-polylogik-option {
			padding: 5px 10px;
			line-height: 1.4;
		}
		.select2-results__option .select2-polylogik-item {
			line-height: 1.5;
			min-height: 60px;
			padding: 8px 12px;
		}
		.select2-results__option .product-name {
			font-weight: bold;
			margin-bottom: 4px;
			display: block;
		}
		.select2-results__option .polylogik-details {
			display: block !important;
			font-size: 0.85em;
			color: #6c757d;
		}
		.select2-polylogik-option .strong {
			display: block;
			margin-bottom: 2px;
			font-weight: 600;
		}
		.select2-polylogik-option .polyitalic {
			font-style: italic;
		}
		.select2-polylogik-option .polygreen {
			color: #28a745;
			font-weight: 500;
		}
		.selected-polylogik {
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		.selected-polylogik strong {
			color: var(--phoenix-gray-900);
			font-weight: bold;
		}
		.selected-polylogik small {
			opacity: 0.8;
			font-size: 90%;
			color: var(--phoenix-gray-900);
		}
		*/
		.select2-container--default .select2-results__option {
			padding: 8;
			white-space: normal;
		}
		.select2-container--default .select2-selection--single {
			height: auto;
			min-height: 38px;
			padding: 6px 24px 6px 12px;
		}
		.select2-results__option {
			padding: 8px 12px;
			height: auto !important;
		}
	</style>


	<script src="vendor/popper/popper.min.js"></script>
	<script src="vendor/bootstrap/bootstrap.min.js"></script>
	<script src="vendor/anchorjs/anchor.min.js"></script>
	<script src="vendor/is/is.min.js"></script>
	<script src="vendor/fontawesome/all.min.js"></script>
	<script src="vendor/lodash/lodash.min.js"></script>
	<script src="vendor/list.js/list.min.js"></script>
	<script src="vendor/feather-icons/feather.min.js"></script>
	<!-- <script src="vendor/dayjs/dayjs.min.js"></script> -->
	<script src="assets/js/phoenix.js"></script>
	<script src="vendor/echarts/echarts.min.js"></script>
	<script src="vendor/dropzone/dropzone.min.js"></script>
	<!-- <script src="vendor/leaflet/leaflet.js"></script>
	<script src="vendor/leaflet.markercluster/leaflet.markercluster.js"></script>
	<script src="vendor/leaflet.tilelayer.colorfilter/leaflet-tilelayer-colorfilter.min.js"></script> -->
	<!-- <script src="vendor/tinymce/tinymce.min.js"></script>
	<script src="vendor/choices/choices.min.js"></script>
	<script src="vendor/swiper/swiper-bundle.min.js"></script>
	<script src="vendor/rater-js/index.js"></script> -->

	<!-- <script src="vendor/jqueryNumber/jquerynumber.min.js"></script> -->

	<script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
	<script src="vendor/waitme/waitMe.min.js"></script>
	<script src="app/js/general.js?v=1.0.0"></script>

	<!-- <script src="views/js/reports.js?v=1.0.1"></script>
	<script src="views/js/queries.js?v=1.0.5"></script> -->
	<!-- <script src="views/js/general.js?v=1.0.0"></script> -->

	<!-- Datepicker JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.es.min.js"></script>

	<script>
		$(document).ready(function () {
			$('.dp_fecha_ini').datepicker({
				format: '<?php echo DATE_DISPLAY ?>',
				// format: 'yyyy-mm-dd',
				// minDate: new Date(),
				// startDate: '-0m',
				minDate: 0,
				autoclose: true,
				todayBtn: true,
				language: 'es',
				todayHighlight: true,
				orientation: "bottom auto"
			}).on('changeDate', function(selected) {
				var minDate = new Date(selected.date.valueOf());
				$('.dp_fecha_fin').datepicker('setStartDate', minDate);
			});

			$(".dp_fecha_fin").datepicker({
				format: '<?php echo DATE_DISPLAY ?>',
				// format: 'yyyy-mm-dd',
				autoclose: true,
				todayBtn: true,
				language: 'es',
				todayHighlight: true,
				// startDate: '+1d',
				startDate: '-0m',
				minDate: 0,
				orientation: "bottom auto"
			}).on('changeDate', function(selected) {
				var minDate = new Date(selected.date.valueOf());
				$('.dp_fecha_ini').datepicker('setEndDate', minDate);
			});
		});

	</script>
</body>

</html>