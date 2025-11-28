<!--
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Veterinaria - Ingreso</title>
  <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/vendor/phoenix/css/theme.min.css" rel="stylesheet">
  <link href="/assets/vendor/sweetalert2/sweetalert2.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

  <div class="card shadow-lg rounded-4 p-4" style="max-width: 420px; width: 100%;">
    <div class="text-center mb-4">
      <img src="/assets/img/logo.png" alt="Logo" width="80">
      <h4 class="mt-2">Clínica Veterinaria</h4>
      <p class="text-muted">Ingreso al sistema</p>
    </div>

    <form id="loginForm" method="POST" autocomplete="off">
      <div class="mb-3">
        <label for="email" class="form-label">Usuario (Email)</label>
        <input type="email" class="form-control" id="email" name="email" 
               placeholder="ejemplo@correo.com" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="password" name="password" 
               placeholder="********" required>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary rounded-pill">Ingresar</button>
      </div>
    </form>
  </div>

  <script src="/assets/vendor/jquery/jquery.min.js"></script>
  <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
  <script src="/assets/js/auth.js"></script>
</body>
</html>
-->

<?php
	require_once CONFIG_PATH.'/config.php';
?>
<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= APP_NAME ?></title>
	<meta name="Description" content="<?= APP_DESCRIPTION ?> Autor: Tincolsas">
	<link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon-16x16.png">
	<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicons/favicon.ico">
	<link rel="manifest" href="assets/img/favicons/manifest.json">
	<meta name="msapplication-TileImage" content="assets/img/favicons/mstile-150x150.png">

	<meta name="theme-color" content="#ffffff">
	<!-- <script src="vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
	<script src="vendor/simplebar/simplebar.min.js"></script> -->
	<script src="assets/js/config.js"></script>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
	<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap" rel="stylesheet">
	<!-- <link href="vendor/simplebar/simplebar.min.css" rel="stylesheet"> -->
	<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
	<link href="assets/css/theme-rtl.min.css" type="text/css" rel="stylesheet" id="style-rtl">
	<link href="assets/css/theme.min.css" type="text/css" rel="stylesheet" id="style-default">
	<link href="assets/css/user-rtl.min.css" type="text/css" rel="stylesheet" id="user-style-rtl">
	<link href="assets/css/user.min.css" type="text/css" rel="stylesheet" id="user-style-default">
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
</head>

<body>
	<main class="main" id="top">
		<div class="row vh-60 container-fluid g-0 bg-300 dark__bg-1200">
			<div class="bg-holder vh-100" id="bgSignin"></div>
			<form method="post">
				<input type="hidden" name="pyt" id="pyt" value="<?= getCSRFToken() ?>">
				<div class="row d-flex flex-center align-content-between position-relative min-vh-95 min-vh-xl-100 g-0 py-10 py-xxl-15">
					<div class="col-11 col-md-6 col-lg-5 col-xl-4" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
						<div class="card border border-200 auth-card">
							<div class="card-body p-0">
								<div class="row gx-0 gy-1">
									<div class="col mx-auto pe-md-0">
										<div class="py-3 mx-3 mx-lg-6">
											<div class="text-center mb-7">
												<a class="d-flex flex-center text-decoration-none mb-4" href="index.php">
													<div class="d-flex flex-center align-items-center fw-bolder fs-5 d-inline-block">
														<img class="ps-1 " src=<?php echo APP_ICON?> alt="VET-Clinic" height="150" width="250" />
														<!-- <p class="wms logo-text d-none d-sm-block ps-2" style="font-size: 35px; font-weight: 800; color: #042940;">VET-Clinic</p> -->
													</div>
												</a>
											</div>
											<div class="mb-3 text-start">
												<label class="text-label" for="username"><?= 'Email' ?></label>
												<div class="form-icon-container">
													<input class="form-control form-icon-input fs-1" id="username" name="username" type="email" placeholder="name@example.com" required autofocus /><span class="fas fa-user text-900 fs--1 form-icon"></span>
												</div>
											</div>
											<div class="mb-3 text-start">
												<label class="text-label" for="password"><?= 'Password' ?></label>
												<div class="form-icon-container">
													<input class="form-control form-icon-input fs-1" id="password" name="password" type="password" placeholder="Password" required /><span class="fas fa-key text-900 fs--1 form-icon"></span>
												</div>
											</div>
											<div class="row flex-between-center mb-5">
												<div class="text-center"><a class="fs--1 fw-semi-bold" href="pages/authentication/card/forgot-password.html"><?= 'Olvidaste tu contraseña?' ?></a></div>
											</div>
											<button type="submit" class="btn btn-primary w-100 mb-3"><?= 'Ingresar' ?></button>
										</div>
										<?php
                                 $login = new AuthController();
                                 $login->loginUser();
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</main>
	<script type="text/javascript">
		document.onload = function() {
			console.log(document.querySelector('meta[name="csrf-token"]').content);
		}
		var imgArray = ['url(assets/img/bg/bg_350.png)', 'url(assets/img/bg/10.png)', 'url(assets/img/bg/17.png)', 'url(assets/img/bg/20.png)', 'url(assets/img/bg/24.png)', 'url(assets/img/bg/34.png)', 'url(assets/img/bg/bg_278.jpeg)', 'url(assets/img/bg/bg_327.jpeg)', 'url(assets/img/bg/bg_383.jpeg)', 'url(assets/img/bg/bg_557.jpeg)'];
		let element = Math.floor(Math.random() * 6);
		var imgBg = imgArray[element];
		document.getElementById("bgSignin").style.backgroundImage = imgBg;
		csrfToken = document.querySelector('meta[name="csrf-token"]');
		if (!csrfToken) {
			console.log("CSRF token not found!");
		} else {
			console.log("CSRF token found: " + csrfToken.getAttribute('content'));
			document.getElementById("pyt").value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
			// $("#pyt").val(document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
		}
	</script>

	<script src="vendor/fontawesome/all.min.js"></script>
	<!-- ===============================================
	<script src="vendors/popper/popper.min.js"></script>
	<script src="vendors/bootstrap/bootstrap.min.js"></script>
	<script src="vendors/anchorjs/anchor.min.js"></script>
	<script src="vendors/is/is.min.js"></script>
	<script src="vendors/lodash/lodash.min.js"></script>
	<script src="vendors/list.js/list.min.js"></script>
	<script src="vendors/feather-icons/feather.min.js"></script>
	<script src="vendors/dayjs/dayjs.min.js"></script>
	<script src="assets/js/phoenix.js"></script>
	-->
</body>

</html>