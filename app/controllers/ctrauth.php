<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/admon/mdlgeneral.php';
require_once __DIR__ . '/../../config/mdlpermission.php';

class AuthController {

   // Mostrar login
   //*****************************************************************************************
   public function loginForm(): void {
      require_once __DIR__ . '/../views/auth/login.php';
   }


	// Procesar login
   //*****************************************************************************************
   public function login(): void {
      if (!isset($_SESSION)) {
         session_start();
      }
      $email = $_POST['email'] ?? '';
      $password = $_POST['password'] ?? '';
      if (empty($email) || empty($password)) {
         $_SESSION['error'] = "Por favor ingrese usuario y contraseña.";
         header("Location: index.php?controller=auth&action=loginForm");
         exit;
      }
      $usuario = Usuario::login($email, $password);
      if ($usuario) {
         $_SESSION['usuario_id'] = $usuario->getId();
         $_SESSION['usuario_nombre'] = $usuario->getNombre();
         $_SESSION['usuario_role'] = $usuario->getRole();
         // Redirigir según role
         switch ($usuario->getRole()) {
            case 'admin':
               header("Location: index.php?controller=dashboard&action=admin");
               break;
            case 'medico':
               header("Location: index.php?controller=dashboard&action=medico");
               break;
            case 'asistente':
               header("Location: index.php?controller=dashboard&action=asistente");
               break;
            default:
               header("Location: index.php?controller=dashboard&action=index");
         }
         exit;
      } else {
         $_SESSION['error'] = "Credenciales incorrectas.";
         header("Location: index.php?controller=auth&action=loginForm");
         exit;
      }
   }


	//*****************************************************************************************
   static public function loginUser(){
		/*
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			echo '<div class="alert alert-soft-danger" role="alert">Invalid request method</div>';
			session_destroy();
			echo '<script>
				window.location = "#";
			</script>';
			return;
		}
		*/
		$email = "";
		$password = "";
		if (isset($_SESSION['user_id'])) {
			// header("Location: dashboard.php");
			echo '
			<script>
				window.location = "./";
			</script>';
			exit();
		}
		if (isset($_POST['username']) && $_POST['username'] !== "") {
			if (!validateCSRFToken($_POST['pyt'])) {
				echo '<div class="alert alert-soft-danger" role="alert">Método de solicitud inválido</div>';
				session_destroy();
				echo '<script>
					setTimeout(function() {
						console.log("Ejecutado después de 2 segundos");
						miFuncion();
					}, 4000);
					window.location = "";
				</script>';
				return;
			}
			$table = "users";
			$email = $_POST['username'];
			$password = $_POST['password'];
			$user = Usuario::login($email, $password);
			if ($user) {
				if ($user["status"] == 'inactivo') {
					echo '<div class="alert alert-soft-danger" role="alert">'.'Usuario '.'<strong>'.'Desactivado'.'</strong>, '.'contacte al Administrador'.'</div>';
					session_destroy();
					echo '<script>
						window.location = "#";
					</script>';
					return;
				}
				$_SESSION['user_id'] = $user['id_user'];
				$_SESSION['user_name'] = $user['name'];
				$_SESSION['user_email'] = $user['email'];
				$_SESSION["profile"] = $user['id_role'];
				$_SESSION["photo"] = $user['photo'];
				$_SESSION["login"] = "ok";
				$_SESSION["id_empresa"] = $user["id_empresa"];
				$_SESSION["empdef"] = $user["EmpCodig"];
				
				$_SESSION["empdb"]  = $user["host_user"];
				$_SESSION["empser"] = "localhost";
				$_SESSION["empusu"] = $user["user_user"];
				$_SESSION["empcla"] = $user["pass_user"];

				$_SESSION['companyname']    = "DEMOSTRACION S.A.S.";
				$_SESSION['companyid']      = "900,100,200";
				$_SESSION['companylogo']    = "";
				$_SESSION['companyaddress'] = "CRA 11 22 - 33";
				$_SESSION['companycity']    = "BARRANQUILLA";
				$_SESSION['companyphone']   = "3456789";
				$_SESSION['companyemail']   = "correo@correo.com";

				$_SESSION['currencies'] = ['USD', 'CAD', 'EUR', 'GBP', 'MXN'];
				$_SESSION['billingCycles'] = ['monthly', 'quarterly', 'yearly'];
				$_SESSION['statuses'] = ['active', 'suspended', 'past_due', 'canceled'];
				$_SESSION['countries'] = ['USA', 'Canada', 'Mexico', 'Colombia', 'Brazil', 'Chile', 'Argentina'];

				$table = "companies";
				$order = "name_company";
				$where = "status_company <> 'canceled'";
				$where = "id_empresa = ". $_SESSION['id_empresa'];
				$company = GeneralModel::getAll($table, $order, $where);
				if ($company != null) {
					$_SESSION['companyname']    = $company[0]["name_company"];
					$_SESSION['companyid']      = $company[0]["identification_company"];
					$_SESSION['companylogo']    = $company[0]["logo_company"];
					$_SESSION['companyaddress'] = json_decode($company[0]["address_company"], true);
					$_SESSION['companyemail']   = $company[0]["email_company"];
				}
				// $item = "id_user_seller";
				// $value = $_SESSION['user_id'];
				// $order = "name_seller";
				// $where = "";
				// $limit = "";
				// $seller = SellersController::getSellers($item, $value, $order, $where, $limit);
				// if ($seller != null) {
				// 	$_SESSION["idSeller"] = $seller[0]["id_seller"];
				// } else {
				$_SESSION["idSeller"] = 0;
				// }
				$item = null;
				$valor = null;
				$permusua = PermissionModel::ViewPermissions($item, $valor);
				$_SESSION['permissionssin'] = $permusua;
				$_SESSION['ivaIncluido'] = 2;

				$tabla = "GrParame";
				$order = "ParCodig";
				$where = "EmpCodig = '" . $_SESSION['empdef']."' AND ModCodig = '21'"." AND ParCodig = 'IVI'";
				$ivaIncluido = GeneralModel::getAll($tabla, $order, $where);
				if ($ivaIncluido != null) {
					$_SESSION['ivaIncluido'] = intval($ivaIncluido[0]["ParValor"]);
				}

				$tabla = "GrParame";
				$order = "ParCodig";
				$_SESSION['valorIva'] = 0;
				$where = "EmpCodig = '" . $_SESSION['empdef']."' AND ModCodig = '21'"." AND ParCodig = 'IVA'";
				$valorIva = GeneralModel::getAll($tabla, $order, $where);
				if ($valorIva != null) {
					$_SESSION['valorIva'] = floatval($valorIva[0]["ParValor"]);
				}

				date_default_timezone_set('America/Bogota');
				$fecha = date('Y-m-d H:i:s');
				$fechaHora = date('Y-m-d H:i:s');
				$UsuIp = $_SERVER['REMOTE_ADDR'];
				if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
					$UsuIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
				if (!empty($_SERVER['HTTP_CLIENT_IP']))
					$UsuIp = $_SERVER['HTTP_CLIENT_IP'];
				try {
					$conn = Database::getConnection();
					$conn->beginTransaction();
					$table = "users";
					$data = array(
						"last_login" => $fechaHora,
						"last_ip"    => $UsuIp
					);
					$where = array("id_user" => $_SESSION['user_id']);
					$userupdt = GeneralModel::update($table, $data, $where, $conn);
					if ($userupdt["success"] == false) {
						throw new PDOException($userupdt["message"], $userupdt["code"]);
					}
					$conn->commit();
				} catch (PDOException $ex) {
					if (ENVIRONMENT == 1) {
						$errorCod = "Código: " . $ex->getCode();
					} else {
						$errorCod = $ex->getMessage();
					}
					$conn->rollBack();
				}
				// header("Location: dashboard.php");
				echo '
				<script>
					window.location = "";
				</script>';
				exit();
			} else {
				echo '<div class="alert alert-soft-danger" role="alert">'.'Usuario o contraseña incorrectos'.'</div>';
				// echo '<div class="alert alert-soft-danger" role="alert">Correo o Contraseña <strong>Incorrectos</strong>, vuelve a intentarlo</div>';
				session_destroy();
				// echo '<script>
				// 	window.location = "";
				// </script>';
				// return;
			}
		}
	}


	// Cerrar sesión
   //*****************************************************************************************
   public function logout(): void {
      session_start();
      session_destroy();
      header("Location: index.php?controller=auth&action=loginForm");
      exit;
   }


	//*****************************************************************************************
	public function managePermissions($userId) {
		// var_dump($_POST);
      if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			$response = array("success" => false, "message" => 'Método no permitido');
         return $response;
      }
      // $required = ['id_user'];
      // foreach ($required as $field) {
		//    if (empty($_POST[$field]) || $_POST[$field] == "") {
		//       $response = array("success" => false, "message" => trans('general.required_fields'));
		//       return $response;
		//    }
		// }
		// $userId = $_POST['id_user'];
		$userId = $_POST['id_user'];
		$table = "users";
		$order = "name";
		$where = "status_company <> 'canceled'";
		$where = "id_user = ". $userId." AND status = '1'";
		$users = GeneralModel::getAll($table, $order, $where);
		$user = $users[0];
		unset($user['password']);
		unset($user['pass_user']);
		unset($user['user_user']);
		unset($user['host_user']);
		unset($user['id_empresa']);

		if (!$user) {
			$response = array("success" => false, "message" => 'Por favor, completa todos los campos obligatorios');
			return $response;
		}
		$item = 'id_user_permission';
		$valor = 0;
		$permissionsStructure = PermissionModel::ViewPermissions($item, $valor);
		$userPermissions = PermissionModel::ViewPermissions($item, $userId);

		// $permissionsStructure = $this->permissionModel->getPermissionsStructure();
		// $userPermissions = $this->permissionModel->getUserPermissions($userId);
		
		// Organizar la estructura de permisos
		$organizedPermissions = $this->organizePermissions($permissionsStructure);
		
		$data = [
			'title' => 'manage_permissions',
			'view' => 'permissions/manage',
			'user' => $user,
			'permissions' => $organizedPermissions,
			'userPermissions' => $userPermissions
		];
		return $data;
	}


   //*****************************************************************************************
	private function organizePermissions($permissions) {
		$organized = [];
		foreach ($permissions as $permission) {
			$moduleId = $permission["id_module"];
			$menuId = $permission["id_menu"];
			$optionId = $permission["id_option"];

			// Agrupar por módulo
			if (!isset($organized[$moduleId])) {
				$organized[$moduleId] = [
					'module' => [
						'id' => $permission["id_module"],
						'description' => $permission["description_module"],
						'image' => $permission["image_module"],
						'path' => $permission["path_module"]
					],
					'menus' => []
				];
			}
			
			// Agrupar por menú dentro del módulo
			if (!isset($organized[$moduleId]['menus'][$menuId])) {
				$organized[$moduleId]['menus'][$menuId] = [
					'menu' => [
						'id' => $permission["id_menu"],
						'description' => $permission["description_menu"]
					],
					'options' => []
				];
			}
			
			// Agregar opción al menú
			$organized[$moduleId]['menus'][$menuId]['options'][] = [
				'id' => $permission["id_option"],
				'description' => $permission["description_option"],
				'link' => $permission["link_option"],
				'order' => $permission["order_option"]
			];
		}
		return $organized;
	}


	public function savePermissions() {
      if ($_SERVER['REQUEST_METHOD'] != 'POST') {
         $response = array("success" => false, "message" => 'Método no permitido');
         return $response;
      }
      $required = ['id_user'];
      foreach ($required as $field) {
         if (empty($_POST[$field]) || $_POST[$field] == "") {
            $response = array("success" => false, "message" => 'Por favor, completa todos los campos obligatorios');
            return $response;
         }
      }
		$data = $_POST;
		// var_dump($data);
		try {
         $connection = Database::getConnection();
			$connection->beginTransaction();
			$response = PermissionModel::deletePermissions($data['id_user'], $connection);
			if (!$response['success']) {
				$response = array("success" => false, "message" => 'Proceso fallido');
				return $response;
			}
			$response = PermissionModel::addPermissions($data, $connection);
			if ($response['success'] == false) {
            throw new PDOException($response["message"], 400);
				// $response = array("success" => false, "message" => trans('general.error_permission'));
				// return $response;
			}
         $connection->commit();
		} catch (PDOException $e) {
         $connection->rollBack();
			$result = htmlspecialchars($e->getMessage());
         $response = array("success" => false, "message" => $result);
		}
      $connection = null;
      return $response;

	}


}