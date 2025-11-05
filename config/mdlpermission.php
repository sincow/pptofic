<?php
//require_once "conection.php";
require_once __DIR__ . '/../config/Database.php';

if(!isset($_SESSION))
{
	session_start();
}
// $lang = Language::getInstance("../app/languages/");
// $lang->loadTranslations();

class PermissionModel{

	/**************************** GET PERMISS ************************/
	/*
	static public function getPermissionsAsig($table, $item, $value, $order, $connum="", $empcod="", $conn){
		// $stmt = Connection::getConnection()->prepare("SELECT a.*, ifnull(b.MenDescr,'') as MenDescr,
		$stmt = $conn->prepare("SELECT a.*, ifnull(b.MenDescr,'') as MenDescr,
		ifnull(c.ModCodig,'') as ModCodig, ifnull(c.ModDescr,'') as ModDescr,
		ifnull((SELECT c.PerEstad FROM gr_Permisos c WHERE c.EmpCodig = :empcod and a.OpcCodig = c.OpcCodig and c.UsuCodig = :usucod),0) as PerEstad
		FROM $table a
		LEFT JOIN gr_Menus   b ON a.EmpCodig = b.EmpCodig AND a.MenCodig = b.MenCodig
		LEFT JOIN gr_Modulos c ON b.EmpCodig = c.EmpCodig AND b.ModCodig = c.ModCodig
		WHERE a.EmpCodig = :connum AND a.OpcEstad >= 1 ORDER BY MenCodig, OpcOrden");
		if ($connum != "") {
			$stmt -> bindParam(":connum", $connum, PDO::PARAM_STR);
			$stmt -> bindParam(":empcod", $empcod, PDO::PARAM_STR);
		}else {
			$stmt -> bindParam(":connum", $_SESSION["connum"], PDO::PARAM_STR);
			$stmt -> bindParam(":empcod", $_SESSION["empdef"], PDO::PARAM_STR);
		}
		$stmt -> bindParam(":usucod", $value, PDO::PARAM_STR);
		$stmt -> execute();
		$resp = $stmt -> fetchAll(PDO::FETCH_ASSOC);
		$stmt = null;
		return $resp;
	}
	*/


	//*********************** ADD BACK END ***********************
   //*****************************************************************************************
	static public function addPermissions($data, $connection = null){
		$con = false;
		if ($connection == null) {
			$connection = Database::getConnection();
			$con = true;
		}
		date_default_timezone_set('America/Bogota');
		$fecha = date('Y-m-d');
		$fechaHora = date('Y-m-d H:i:s');
		$uno = 1;
		// $permissList = json_decode($data["permissions"], true);
		$stmt = $connection->prepare("INSERT INTO grpermissions (id_empresa, id_user_permission, 
			id_option_permission, status_permission, date_created_permission)
			VALUES (:id_empresa, :id_user_permission, :id_option_permission, :status_permission, 
			:date_created_permission)"
		);
		$stmt->bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
		$stmt->bindParam(":id_user_permission", $data["id_user"], PDO::PARAM_INT);
		$stmt->bindParam(":date_created_permission", $fechaHora);
		foreach ($data["permissions"] as $key => $value) {
			$stmt->bindParam(":id_option_permission", $value, PDO::PARAM_INT);
			$stmt->bindParam(":status_permission", $uno, PDO::PARAM_INT);
			$stmt->execute();
		}
		$response = array("success" => true, "message" => 'Proceso completado correctamente');
		$stmt = null;
		if ($con) {
			$connection = null;
		}
		return $response;
	}


	//********************* GET *****************
   //*****************************************************************************************
	static public function ViewPermissions($item, $valor){
		if($item != null){
			$stmt = Database::getConnection()->prepare("SELECT a.id_module, a.description_module, a.image_module, 
				a.path_module, b.id_menu, b.description_menu, c.id_option, c.description_option, c.link_option, 
				IFNULL(c.link_option,'***') AS OpcLink, c.order_option, 
				(SELECT COUNT(*) FROM grpermissions d WHERE d.id_option_permission = c.id_option AND 
				d.id_user_permission = :id_user_permission AND c.status_option >= 1 AND d.status_permission = 1) AS UsuPermi, 
				c.status_option, a.status_module, b.status_menu 
				FROM grmodules a
				LEFT JOIN grmenus b ON a.id_module = b.id_module_menu
				LEFT JOIN groptions c ON b.id_menu = c.id_menu_option
				WHERE a.status_module >= 1 AND c.status_option >= 1 
				ORDER BY a.order_module, b.order_menu, c.order_option"
			);
			// SELECT * FROM grpermissions WHERE id_user_permission = :usucod AND $item = :$item");
			// $stmt -> bindParam(":usucod", $_SESSION["user_id"], PDO::PARAM_STR);
			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> execute();
			$resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}else{
			$conn = Database::getConnection();
			if ($conn != null) {
				$stmt = Database::getConnection()->prepare("SELECT a.id_module, a.description_module, a.image_module, 
					a.path_module, b.id_menu, b.description_menu, c.id_option, c.description_option, c.link_option, 
					IFNULL(c.link_option,'***') AS OpcLink, c.order_option, 
					(SELECT COUNT(*) FROM grpermissions d WHERE d.id_option_permission = c.id_option AND 
					d.id_user_permission = :usucod AND c.status_option >= 1 AND d.status_permission = 1) AS UsuPermi, 
					c.status_option, a.status_module, b.status_menu 
					FROM grmodules a
					LEFT JOIN grmenus b ON a.id_module = b.id_module_menu
					LEFT JOIN groptions c ON b.id_menu = c.id_menu_option
					WHERE a.status_module >= 1 
					ORDER BY a.order_module, b.order_menu, c.order_option"
				);
				$stmt -> bindParam(":usucod", $_SESSION["user_id"], PDO::PARAM_STR);
				$stmt -> execute();
				$resp = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}else {
				$resp = null;
			}
		}
		$stmt = null;
		return $resp;
	}

	//************************ DELETE **********************
   //*****************************************************************************************
	static public function deletePermissions($id_user, $connection = null){
		$con = false;
		if ($connection == null) {
			$connection = Database::getConnection();
			$con = true;
		}
		$stmt = $connection->prepare("DELETE FROM grpermissions 
		WHERE id_empresa = :id_empresa AND id_user_permission  = :id_user_permission ");
		$stmt -> bindParam(":id_empresa", $_SESSION["id_empresa"], PDO::PARAM_INT);
		$stmt -> bindParam(":id_user_permission", $id_user, PDO::PARAM_INT);
		$stmt -> execute();
		// $resp="ok";
		$response = array("success" => true, "message" => 'Proceso completado correctamente');
		$stmt = null;
		if ($con) {
			$connection = null;
		}
		return $response;
	}


}