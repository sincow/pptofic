<?php
$user = [];
if (isset($_SESSION['user_id'])) {
	$item = "id_user";
	$value = $_SESSION['user_id'];
	$order = "name_user";
	$where = "";
	$limit = "";
	// $user = UsersController::getUsers($item, $value, $order, $where, $limit);
	// $user = UsersController::getOne($value);
	date_default_timezone_set('America/Bogota');
	$fecha = date('Y-m-d');
}
$profile = "";
if ($user != []) {
	switch ($user[0]["profile_user"]) {
		case 'A':
			$profile = "Administrador";
			break;
		case 'C':
			$profile = "Cliente";
			break;
		case 'E':
			$profile = "Editor";
			break;
		case 'V':
			$profile = "Vendedor";
			break;
		default:
			break;
	}
}
?>

<div class="content pt-10">
	<section class="pt-2 pb-3">
		<div class="container-small">
			<div class="row mb-2">
				<div class="col-lg-8">
					<h2 class="mb-0">Perfil del usuario</h2>
				</div>
				<div class="col-lg-4">
					<nav class="mb-2" aria-label="breadcrumb">
						<ol class="breadcrumb mb-0 float-sm-end">
							<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
							<li class="breadcrumb-item active">Perfil usuario</li>
						</ol>
					</nav>
				</div>
			</div>
			<div class="row g-3 mb-3">
				<div class="col-12 col-lg-8">
					<div class="card">
						<div class="card-body p-3 pb-0">
							<form class="needs-validation mb-1" id="frmUserUptEmail" method="post" enctype="multipart/form-data" novalidate>
								<input class="form-control" type="hidden" id="userUpdtId" name="userUpdtId" value="<?php if ($user == []) {echo '';} else {echo $user[0]["id_user"];} ?>" />
								<input class="form-control" type="hidden" id="userUpdtCode" name="userUpdtCode" value="<?php if ($user == []) {echo 'code';} else {echo $user[0]["code_user"];} ?>" />
								<div class="border-bottom border-dashed border-300 pb-2">
									<div class="row align-items-center g-3 g-sm-5 text-center text-sm-start">
										<div class="col-12 col-sm-auto ps-5">
											<input type="file" class="d-none" id="userUpdatePhoto" name="userUpdatePhoto" accept="image/png, image/jpg, image/webp" />
											<div class="hoverbox" style="width: 150px; height: 150px">
												<div class="hoverbox-content bg-black rounded-circle d-flex flex-center z-index-1" style="--phoenix-bg-opacity: .56;"><span class="fa-solid fa-camera fs-7 text-300"></span></div>
												<div class="position-relative bg-400 rounded-circle cursor-pointer d-flex flex-center mb-xxl-7">
													<!-- <div class="avatar avatar-5xl"><img class="rounded-circle userUpdateFilePhoto" src="<?php // if ($user == []) {echo 'assets/img/team/avatar.webp';} else {echo $user[0]["photo_user"];} ?>" alt="" /></div> -->
													<div class="avatar avatar-5xl"><img class="rounded-circle userUpdateFilePhoto" src="assets/img/team/avatar.webp" alt="" /></div>
													<label class="cursor-pointer w-100 h-100 position-absolute z-index-1" for="userUpdatePhoto"></label>
												</div>
											</div>
											<!-- <label class="cursor-pointer avatar avatar-5xl" for="userUpdatePhoto"><img class="rounded-circle" src="<?php //if ($user == []) { echo 'views/img/users/avatar.webp';} else { echo $user[0]["photo_user"];} ?>" alt="" /></label> -->
										</div>
										<div class="col-12 col-sm-auto flex-1">
											<h3 id="userName"><?php //if ($user == []) {echo '';} else {echo $user[0]["name_user"];} ?></h3>
											<p class="text-800 mb-1" id="vinculacion"></p>
											<p class="text-800" id="lastLogin">Fecha Última entrada <?php if ($user == []) {echo '';} else {echo substr($user[0]["last_login_user"], 0, 10);} ?></p>
										</div>
									</div>
								</div>
								<div class="row align-items-center g-3 g-sm-15 text-start pt-2 ps-sm-5">
									<div class="col-12 py-2">
										<h4 class="mb-2 text-800">Información del Usuario</h4>
									</div>
									<div class="col-12 col-sm-auto mt-1 pb-2">
										<label for="userUpdateCode">Código del Usuario</label>
										<input class="form-control-plaintext outline-none py-0 text-700 fw-bold" id="userUpdateCode" type="text" readonly value="<?php if ($user == []) {echo '';} else {echo $user[0]["code_user"];} ?>" />
									</div>
									<div class="col-12 col-sm-auto mt-1 pb-2">
										<label for="userUpdateProfile">Perfil del Usuario</label>
										<input class="form-control-plaintext outline-none py-0 text-700 fw-bold" id="userUpdateProfile" type="text" readonly value="<?php echo $profile; ?>" />
									</div>
									<div class="col-12 mt-1 pb-2">
										<label for="userUpdateName">Nombre del Usuario</label>
										<input class="form-control-plaintext lh-sm py-0 text-700 fw-bold" id="userUpdateName" type="text" readonly value="<?php if ($user == []) {echo '';} else {echo $user[0]["name_user"];} ?>" />
									</div>
									<div class="col-12 col-sm-7 mt-1 pb-2">
										<label for="userUpdateEmail">Correo electrónico</label>
										<input class="form-control" type="email" id="userUpdateEmail" name="userUpdateEmail" value="<?php if ($user == []) {echo '';} else {echo $user[0]["email_user"];} ?>" minlength="10" maxlength="100" required />
									</div>
									<div class="col-12 mt-2 mb-3 pb-2">
										<button class="btn btn-phoenix-primary" type="button" id="btnUserUpdtData"><span class="fas fa-edit me-2"></span>Modificar datos</button>
									</div>
								</div>
							</form>
							<?php
							// $customer = new UsersController();
							// $customer->UserUpdtData("userprofile");
							?>
						</div>
					</div>
				</div>
				<div class="col-12 col-lg-4">
					<form class="needs-validation mb-4 bg-white" id="frmUserUpdtPassword" method="post" novalidate>
						<input class="form-control" type="hidden" id="userUpdtEmail" name="userUpdtEmail" value="<?php if ($user == []) {echo '';} else {echo $user[0]["email_user"];} ?>">
						<input class="form-control" type="hidden" id="userUpdateId" name="userUpdateId" value="">
						<div class="card">
							<div class="card-body p-3">
								<div class="border-bottom border-dashed border-300">
									<h4 class="mb-3 lh-sm lh-xl-1">Cambiar Contraseña
									</h4>
								</div>
								<div class="pt-4 mb-3">
									<div class="row justify-content-between">
										<div class="col-12 mt-1 pb-3">
											<label for="userUpdatePassAct">Contraseña actual</label>
											<input class="form-control" type="password" id="userUpdatePassAct" name="userUpdatePassAct" value="" required />
										</div>
										<div class="col-12 mt-1 pb-3">
											<label for="userUpdatePassword">Nueva contraseña</label>
											<input class="form-control" type="password" id="userUpdatePassword" name="userUpdatePassword" value="" minlength="8" required />
										</div>
										<div class="col-12 mt-1 pb-1">
											<label for="userUpdatePassRep">Repetir contraseña</label>
											<input class="form-control" type="password" id="userUpdatePassRep" name="userUpdatePassRep" value="" minlength="8" required />
										</div>
									</div>
								</div>
								<div class="border-top border-dashed border-300 pt-2">
									<div class="row flex-between-center mb-2">
										<div class="col-12 mt-2 pb-1 text-center">
											<button class="btn btn-phoenix-primary" type="button" id="btnUserUpdtPass"><span class="fas fa-key me-2"></span>Cambiar contraseña</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
					<?php
					// $customer = new UsersController();
					// $customer->UserUpdtPass("userprofile");
					?>
				</div>
			</div>
		</div>
	</section>
	<?php
	include APP_PATH . '/views/layouts/footer.php';
	?>
</div>
<script src="app/js/admon/users.js"></script>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const formData = new FormData();
		formData.append("modulo", "admon");
		formData.append("option", "users");
		formData.append("action", "getOne");
		formData.append("id", "*");
		const response = fetch('helpers/ajaxRouter.php', {
			method: 'POST',
			body: formData
		}).then(resp => resp.json())
		.then(data => {
			document.getElementById('userUpdtId').value = data["id_user"];
			document.getElementById('userUpdateId').value = data["id_user"];
			document.querySelector('.userUpdateFilePhoto').src = data["photo"];
			document.getElementById('userName').textContent = data["name"];
			document.getElementById('vinculacion').textContent = "Fecha vinculación "+ data["created_at"];
			document.getElementById('lastLogin').textContent = "Fecha Última entrada "+ data["last_login"];
			document.getElementById('userUpdateCode').value = data["id_user"];
			document.getElementById('userUpdateProfile').value = data["role"];
			document.getElementById('userUpdateName').value = data["name"];
			document.getElementById('userUpdateEmail').value = data["email"];
			console.log(data);
		});


		//**************************************************************************************
		document.getElementById('btnUserUpdtPass').addEventListener('click', function(e) {
			e.preventDefault();
			const formData = new FormData();
			formData.append("modulo", "admon");
			formData.append("option", "users");
			formData.append("action", "updtPass");
			formData.append("id", document.getElementById('userUpdateId').value);
			formData.append("passAct", document.getElementById('userUpdatePassAct').value);
			formData.append("pass", document.getElementById('userUpdatePassword').value);
			formData.append("passRep", document.getElementById('userUpdatePassRep').value);
			const response = fetch('helpers/ajaxRouter.php', {
				method: 'POST',
				body: formData
			}).then(resp => resp.json())
			.then(data => {
				if (data["success"]) {
					const notify = swal.fire({
						title: 'Éxito',
						text: data["message"],
						icon: 'success',
						confirmButtonText: 'Aceptar'
					});
				} else {
					const notify = swal.fire({
						title: 'Error',
						text: data["message"],
						icon: 'error',
						confirmButtonText: 'Aceptar'
					});
				}
			});
		})


		//**************************************************************************************
		document.getElementById('btnUserUpdtData').addEventListener('click', function(e) {
			e.preventDefault();
			const formData = new FormData();
			formData.append("modulo", "admon");
			formData.append("option", "users");
			formData.append("action", "updtData");
			formData.append("id", document.getElementById('userUpdateId').value);
			formData.append("name", document.getElementById('userUpdateName').value);
			formData.append("email", document.getElementById('userUpdateEmail').value);
			const response = fetch('helpers/ajaxRouter.php', {
				method: 'POST',
				body: formData
			}).then(resp => resp.json())
			.then(data => {
				console.log(data);
				if (data["success"]) {
					const notify = swal.fire({
						title: 'Éxito',
						text: data["message"],
						icon: 'success',
						confirmButtonText: 'Aceptar'
					});
				} else {
					const notify = swal.fire({
						title: 'Error',
						text: data["message"],
						icon: 'error',
						confirmButtonText: 'Aceptar'
					});
				}
			});
		})

	});
</script>